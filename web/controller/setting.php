<?php

include_once('controller/accessLimitedToSignInPeople.php');

// Define variables
$actualLetter = '';
$style = '';
$orderByKeyword = '';
$orderBySource  = '';
$orderByType    = '';

if (empty($_SESSION['cptScienceQuery']))
{
	$_SESSION['cptScienceQuery'] = 3;
}

if (empty($_SESSION['cptPatentQuery']))
{
	$_SESSION['cptPatentQuery'] = 3;
}

if (isset($_SESSION['ERROR_MESSAGE']))
{
	$ERROR_MESSAGE = $_SESSION['ERROR_MESSAGE'];
	$_SESSION['ERROR_MESSAGE'] = '';
}
else
{
	$ERROR_MESSAGE = '';
}

/*include_once('model/get_text.php');*/

# Nav activation for this page
$result  = '';
$wiki    = '';
$setting = "active";

# Scroll position
if (isset($_POST['scrollPos']))
{
	$_SESSION['scrollPos'] = htmlspecialchars($_POST['scrollPos']);
}
elseif (!isset($_SESSION['scrollPos']) OR $_SESSION['scrollPos'] == '')
{
	$_SESSION['scrollPos'] = 0;
}

# Save folding state
if (isset($_POST['sourceType']))
{
	foreach($_SESSION as $key => $val)
	{
		if (preg_match("/radio-s./", $key))
		{
			$_SESSION[$key] = '';
		}
		elseif (preg_match("/radio-ks[0-9]+/", $key))
		{
			$_SESSION[$key] = '';
		}
	}
	foreach($_POST as $key => $val)
	{
		$key = htmlspecialchars($key);
		$val = htmlspecialchars($val);
		if (preg_match("/radio-s./", $key))
		{
			$_SESSION[$key] = $val;
		}
		elseif (preg_match("/radio-ks[0-9]+/", $key))
		{
			$_SESSION[$key] = $val;
		}
	}
}

# Read background list
include_once('model/readBackgroundList.php');
$type           = 'result';
$backgroundList = readBackgroundList($type, $bdd);

# Read owner sources
include_once('model/readOwnerSources.php');
include_once('model/readOwnerSourcesKeyword.php');

# Read user settings
include_once('model/readUserSettings.php');

if (htmlspecialchars($_POST['settings']) == 'ChangeSettings')
{
	# Change email
	if (isset($_POST['email']))
	{
		if (htmlspecialchars($_POST['email']) != '')
		{
			$newEmail = htmlspecialchars($_POST['email']);
			include_once('model/addNewEmail.php');
		}
	}

	# Change result backgroundList
	if (isset($_POST['backgroundResult']))
	{
		if (htmlspecialchars($_POST['backgroundResult']) != '')
		{
			$backgroundResult = htmlspecialchars($_POST['backgroundResult']);
			include_once('model/changeBackgroundResult.php');
		}
	}

	# change sending condition
	if (isset($_POST['cond']))
	{
		if (htmlspecialchars($_POST['cond']) != '')
		{
			$cond         = htmlspecialchars($_POST['cond']);
			$linkLimit    = htmlspecialchars($_POST['numberLinks']);
			$frequency    = htmlspecialchars($_POST['freq']);
			$selectedDays = htmlspecialchars($_POST['days']);
			$selectedHour = htmlspecialchars($_POST['hours']);
			$secondDay    = htmlspecialchars($_POST['secondDay']);

			if (!preg_match("/$secondDay/", $selectedDays))
			{
				$selectedDays = $selectedDays . $secondDay;
			}

			$linkLimit    = ($linkLimit == '' ? NULL : $linkLimit);
			$frequency    = ($frequency == '' ? NULL : $frequency);
			$selectedDays = ($selectedDays == '' ? NULL : $selectedDays);
			$selectedHour = ($selectedHour == '' ? NULL : $selectedHour);

			include_once('model/changeSendCondition.php');
		}

	}

	# Change sorting for link in email
	if (isset($_POST['orderBy']))
	{
		if (htmlspecialchars($_POST['orderBy']) != '')
		{
			$orderBy = htmlspecialchars($_POST['orderBy']);
			include_once('model/changeSortEmail.php');
		}
	}

	# Change privacy settings
	if (isset($_POST['recordRead']))
	{
		if (htmlspecialchars($_POST['recordRead']) == 'active')
		{
			$recordRead = 1;
			include_once('model/changeRecordRead.php');
		}
	}
	elseif (!isset($_POST['recordRead']))
	{
		$recordRead = 0;
		include_once('model/changeRecordRead.php');
	}

	if (isset($_POST['historyLifetime']))
	{
		if (htmlspecialchars($_POST['historyLifetime']) != '')
		{
			$historyLifetime = htmlspecialchars($_POST['historyLifetime']);
			include_once('model/changeHistoryLifetime.php');
		}
	}

	header('Location: setting');
}

# Delete history button
if (!empty($_POST['buttonDeleteHistory']))
{
	if (htmlspecialchars($_POST['buttonDeleteHistory']) == 'deleteHistory')
	{
		include_once('model/delResult.php');
		$deleteHistoryValue = htmlspecialchars($_POST['deleteHistoryValue']);
		$deleteHistoryUnit  = htmlspecialchars($_POST['deleteHistoryUnit']);

		if ($deleteHistoryUnit == 'hour')
		{
			$deleteTimeIntervale = $deleteHistoryValue * 3600;
		}
		elseif ($deleteHistoryUnit == 'day')
		{
			$deleteTimeIntervale = $deleteHistoryValue * 3600 * 24;
		}
		elseif ($deleteHistoryUnit == 'week')
		{
			$deleteTimeIntervale = $deleteHistoryValue * 3600 * 24 * 7;
		}
		elseif ($deleteHistoryUnit == 'month')
		{
			$deleteTimeIntervale = $deleteHistoryValue * 3600 * 24 * 7 * 30;
		}
		elseif ($deleteHistoryUnit == 'year')
		{
			$deleteTimeIntervale = $deleteHistoryValue * 3600 * 24 * 7 * 30 * 12;
		}

		$now                = time();
		$deleteTime         = $now - $deleteTimeIntervale;

		$owner = '%,' . $_SESSION['id'] . ',%';

		include_once('model/readOwnerResultByTimeInterval.php');

		foreach ($readIdResutlToDel as $idResultToDel)
		{
				deleteLink($bdd, $idResultToDel['id']);
		}
	}
}

# Adding new source
if (isset($_POST['sourceType']) AND isset($_POST['newSource']))
{
	if (htmlspecialchars($_POST['sourceType']) == 'inputSource' AND htmlspecialchars($_POST['newSource']) != '')
	{
		$source       = htmlspecialchars($_POST['newSource']);
		$sourceToTest = escapeshellarg($source);
		$cmd          = '/usr/bin/python /var/www/Serge/checkfeed.py ' . $sourceToTest;

		# Check if the link is valid
		exec($cmd, $linkValidation, $errorInCheckfeed);

		if ($linkValidation[0] == 'valid link' AND $errorInCheckfeed == 0)
		{
			include_once('model/addNewSource.php');
			if (isset($linkValidation[1]))
			{
				$_SESSION['ERROR_MESSAGE'] = $linkValidation[1];
			}
			header('Location: setting');
		}
		else
		{
			$ERROR_MESSAGE = 'Your link ' . 'return ' . $linkValidation[0] . ',' . $linkValidation[1] . ', please correct your link';
		}
	}
}

# Adding new keyword
if (isset($_POST['sourceKeyword']) AND isset($_POST['newKeyword']))
{
	include_once('model/addNewKeyword.php');
	$sourceId    = preg_replace('/[^0-9]/', '', htmlspecialchars($_POST['sourceKeyword']));
	$newKeyword  = ucfirst(htmlspecialchars($_POST['newKeyword']));

	if ($newKeyword != '' AND $sourceId != '')
	{
		$_SESSION['lastSourceUse'] = $sourceId;
		preg_match_all("/,?[^,]*,?/", $newKeyword, $newKeyword_array);
		array_pop($newKeyword_array[0]);
		foreach ($newKeyword_array[0] as $keyword)
		{
			$newKeyword = preg_replace("/^ | *, *| $/", "", $keyword);
			# Special keyword :all
			if (preg_match("/^:all$/i", $newKeyword) AND $sourceId != '00')
			{
				$newKeyword = ':all@' . $sourceId;
				$ERROR_MESSAGE = addNewKeyword($sourceId, $newKeyword, $ERROR_MESSAGE, $reqReadOwnerSourcestmp, $bdd);
			}
			elseif (preg_match("/^:all$/i", $newKeyword) AND $sourceId == '00')
			{
				$updateBDD = FALSE;
				foreach ($reqReadOwnerSourcestmp as $ownerSourcesList)
				{
					$newKeyword = ':all@' . $ownerSourcesList['id'];
					$sourceId = $ownerSourcesList['id'];
					$ERROR_MESSAGE = addNewKeyword($sourceId, $newKeyword, $ERROR_MESSAGE, $reqReadOwnerSourcestmp, $bdd);
				}
			}
			elseif (preg_match("/^alert:.+/i", $newKeyword) AND $sourceId != '00')
			{
				$newKeyword = preg_replace("/alert:/i", "", $newKeyword);
				$newKeyword = '[!ALERT!]' . $newKeyword;
				$ERROR_MESSAGE = addNewKeyword($sourceId, $newKeyword, $ERROR_MESSAGE, $reqReadOwnerSourcestmp, $bdd);
			}
			elseif (preg_match("/^alert:.+/i", $newKeyword) AND $sourceId == '00')
			{
				$updateBDD = FALSE;
				$newKeyword = preg_replace("/alert:/i", "", $newKeyword);
				$newKeyword = '[!ALERT!]' . $newKeyword;
				foreach ($reqReadOwnerSourcestmp as $ownerSourcesList)
				{
					$sourceId = $ownerSourcesList['id'];
					$ERROR_MESSAGE = addNewKeyword($sourceId, $newKeyword, $ERROR_MESSAGE, $reqReadOwnerSourcestmp, $bdd);
				}
			}
			elseif ($newKeyword != '')
			{
				$ERROR_MESSAGE = addNewKeyword($sourceId, $newKeyword, $ERROR_MESSAGE, $reqReadOwnerSourcestmp, $bdd);
			}
		}
		$_SESSION['ERROR_MESSAGE'] = $ERROR_MESSAGE;
		header('Location: setting');
	}
}

if (isset($_POST['delKeyword']) OR isset($_POST['disableKeyword']) OR isset($_POST['activateKeyword']))
{
	# Delete, disable, active keyword
	if (isset($_POST['delKeyword']))
	{
		preg_match_all("/[0-9]*&/", htmlspecialchars($_POST['delKeyword']), $matchKeywordAndSource);
		$sourceIdAction  = preg_replace("/[^0-9]/", "", $matchKeywordAndSource[0][0]);
		$keywordIdAction = preg_replace("/[^0-9]/", "", $matchKeywordAndSource[0][1]);
		$action          = 'delKeyword';
	}
	elseif (isset($_POST['disableKeyword']))
	{
		preg_match_all("/[0-9]*&/", htmlspecialchars($_POST['disableKeyword']), $matchKeywordAndSource);
		$sourceIdAction  = preg_replace("/[^0-9]/", "", $matchKeywordAndSource[0][0]);
		$keywordIdAction = preg_replace("/[^0-9]/", "", $matchKeywordAndSource[0][1]);
		$action          = 'disableKeyword';
	}
	elseif (isset($_POST['activateKeyword']))
	{
		preg_match_all("/[0-9]*&/", htmlspecialchars($_POST['activateKeyword']), $matchKeywordAndSource);
		$sourceIdAction  = preg_replace("/[^0-9]/", "", $matchKeywordAndSource[0][0]);
		$keywordIdAction = preg_replace("/[^0-9]/", "", $matchKeywordAndSource[0][1]);
		$action          = 'activateKeyword';
	}

	if (isset($sourceIdAction) AND isset($keywordIdAction) AND isset($action))
	{
		# Check if keyword exist for this ownerSourcesList
		$keywordExist = FALSE;
		foreach ($reqReadOwnerSourcesKeywordtmp as $ownerKeywordList)
		{
			# Source of current keyword for current user
			$applicable_owners_sourcestmp = $ownerKeywordList['applicable_owners_sources'];

			# Search for source in applicable_owners_sources
			$sourceInKeyword = preg_match("/\|" . $_SESSION['id'] . ":[,!0-9,]*,!*" . $sourceIdAction . ",[,!0-9,]*\|/", $applicable_owners_sourcestmp, $applicable_owners_sourceForCurrentUser);

			if ($ownerKeywordList['id'] == $keywordIdAction AND $sourceInKeyword)
			{
				$applicable_owners_sourcesCurrentKeywordAndUser = $applicable_owners_sourceForCurrentUser[0];
				$applicable_owners_sources = $ownerKeywordList['applicable_owners_sources'];
				$activeForCurrentKeyword   = $ownerKeywordList['active'];
				$keywordExist = TRUE;
			}
		}

		# Delete an existing keyword
		if ($keywordExist AND $action == 'delKeyword')
		{
			include_once('model/delKeyword.php');
			header('Location: setting');
		}
		elseif ($keywordExist AND $action == 'disableKeyword')
		{
			include_once('model/disableKeyword.php');
			header('Location: setting');
		}
		elseif ($keywordExist AND $action == 'activateKeyword')
		{
			include_once('model/activateKeyword.php');
			header('Location: setting');
		}
		else
		{
			$ERROR_MESSAGE = 'Keyword doesn\'t exist or invalid action';
		}
	}
}

if (isset($_POST['delSource']) OR isset($_POST['disableSource']) OR isset($_POST['activateSource']))
{
	# Delete, disable, active sources
	if (isset($_POST['delSource']))
	{
		preg_match("/[0-9]*&/", htmlspecialchars($_POST['delSource']), $matchSourceId);
		$sourceIdAction  = preg_replace("/[^0-9]/", "", $matchSourceId[0]);
		$action          = 'delSource';
	}
	elseif (isset($_POST['disableSource']))
	{
		preg_match("/[0-9]*&/", htmlspecialchars($_POST['disableSource']), $matchSourceId);
		$sourceIdAction  = preg_replace("/[^0-9]/", "", $matchSourceId[0]);
		$action          = 'disableSource';
	}
	elseif (isset($_POST['activateSource']))
	{
		preg_match("/[0-9]*&/", htmlspecialchars($_POST['activateSource']), $matchSourceId);
		$sourceIdAction  = preg_replace("/[^0-9]/", "", $matchSourceId[0]);
		$action          = 'activateSource';
	}

	if (isset($sourceIdAction) AND isset($action))
	{
		# Check if source exist for this owner
		$sourceExist = FALSE;
		foreach ($reqReadOwnerSourcestmp as $sourceList)
		{
			if ($sourceList['id'] == $sourceIdAction)
			{
				$owners                 = $sourceList['owners'];
				$activeForCurrentSource = $sourceList['active'];
				$sourceExist            = TRUE;
			}
		}

		# Delete an existing sources
		if ($sourceExist AND $action == 'delSource')
		{
			include_once('model/delSource.php');
			header('Location: setting');
		}
		elseif ($sourceExist AND $action == 'disableSource')
		{
			include_once('model/disableSource.php');
			header('Location: setting');
		}
		elseif ($sourceExist AND $action == 'activateSource')
		{
			include_once('model/activateSource.php');
			header('Location: setting');
		}
		else
		{
			$ERROR_MESSAGE = 'Source doesn\'t exist or invalid action';
		}
	}
}

# Sending condition
	if ($userSettings['send_condition'] == 'link_limit')
	{
		$condNbLink = 'checked';
	}
	elseif ($userSettings['send_condition'] == 'freq')
	{
		$condFreq = 'checked';
	}
	elseif ($userSettings['send_condition'] == 'deadline')
	{
		$condDate = 'checked';
	}

	preg_match_all("/[1-7]/", $userSettings['selected_days'], $selected_days);
	foreach ($selected_days[0] as $value)
	{
		$day[$value] = 'selected';
	}

	$day2 = $day;

	if ($day[1] == 'selected' AND $day[2] == 'selected' AND $day[3] == 'selected' AND $day[4] == 'selected' AND $day[5] == 'selected' AND $day[6] == 'selected' AND $day[7] == 'selected')
	{
		$day[1] = '';
		$day[2] = '';
		$day[3] = '';
		$day[4] = '';
		$day[5] = '';
		$day[6] = '';
		$day[7] = '';
		$day2[1] = '';
		$day2[2] = '';
		$day2[3] = '';
		$day2[4] = '';
		$day2[5] = '';
		$day2[6] = '';
		$day2[7] = '';
		$day[9] = 'selected';
	}
	elseif ($day[1] == 'selected' AND $day[2] == 'selected' AND $day[3] == 'selected' AND $day[4] == 'selected' AND $day[5] == 'selected')
	{
		$day[1] = '';
		$day[2] = '';
		$day[3] = '';
		$day[4] = '';
		$day[5] = '';
		$day[6] = '';
		$day[7] = '';
		$day2[1] = '';
		$day2[2] = '';
		$day2[3] = '';
		$day2[4] = '';
		$day2[5] = '';
		$day[0] = 'selected';
	}
	elseif($day[1] == 'selected' AND $day[3] == 'selected' AND $day[5] == 'selected')
	{
		$day[1]  = '';
		$day[2]  = '';
		$day[3]  = '';
		$day[4]  = '';
		$day[5]  = '';
		$day[6]  = '';
		$day[7]  = '';
		$day2[1] = '';
		$day2[3] = '';
		$day2[5] = '';
		$day[8]  = 'selected';
	}

	$firstEntry = FALSE;
	$cpt = 1;
	while ($cpt <= 7)
	{
		if ($day[$cpt] == 'selected' AND $day2[$cpt] == 'selected' AND !$firstEntry)
		{
			$day2[$cpt] = '';
			$firstEntry = TRUE;
		}
		elseif ($day[$cpt] == 'selected' AND $day2[$cpt] == 'selected' AND $firstEntry)
		{
			$day[$cpt] = '';
			$cpt = 8;
		}
		$cpt++;
	}

# Sorting links in email
if ($userSettings['mail_design'] == 'masterword')
{
	$orderByKeyword = 'checked';
}
elseif ($userSettings['mail_design'] == 'origin')
{
	$orderBySource = 'checked';
}
elseif ($userSettings['mail_design'] == 'type')
{
	$orderByType = 'checked';
}

# Privacy
if ($userSettings['record_read'] == 0)
{
	$recordRead = '';
}
elseif ($userSettings['record_read'] == 1)
{
	$recordRead = 'checked';
}

# Edit science query
if (!empty($_GET['action']) AND !empty($_GET['query']) AND $_GET['action'] == 'editQueryScience')
{
	preg_match("/[0-9]+/", $_GET['query'], $queryId);
	$delEditingScienceQuery = $queryId[0];

	$req = $bdd->prepare('SELECT query_arxiv FROM queries_science_serge WHERE id = :queryId AND  owners LIKE :userId');
	$req->execute(array(
		'queryId' => $queryId[0],
		'userId' => '%,' . $_SESSION['id'] . ',%'));
		$queriesEdit = $req->fetch();
		$req->closeCursor();

		$query = urldecode($queriesEdit['query_arxiv']);
		$query = preg_replace("/\"/", "", $query);
		$query = preg_replace("/(\(|\)|[^: ]+:| AND | NOTAND | OR )/", "|$1", $query);
		$query = preg_replace("/:/", "|", $query);
		$queryArray = explode("|", $query);

		$cpt = 0;
		$typeQuery = '';
		foreach ($queryArray as $queryPart)
		{
			$cptQuery = ceil($cpt/6) - 1;
			if (preg_match("/(^ AND $|^ NOTAND $|^ OR $)/",$queryPart, $value))
			{
				if (($cpt / 6) != intval($cpt / 6))
				{
					$cpt = (intval($cpt / 6) + 1) * 6;
					$cptQuery = ceil($cpt/6);
				}
				$value = preg_replace("/ /", "", $value[0]);
				$_POST['andOrAndnot' . $cptQuery] = $value;
			}
			elseif (preg_match("/^\($/",$queryPart))
			{
				$_POST['openParenthesis' . $cptQuery] = 'active';
			}
			elseif (preg_match("/^\)$/",$queryPart))
			{
				$_POST['closeParenthesis' . $cptQuery] = 'active';
			}
			elseif (!empty($queryPart) AND $typeQuery != 'displayed')
			{
				$_POST['scienceType' . $cptQuery] = $queryPart;
				$typeQuery = 'displayed';
			}
			elseif (!empty($queryPart))
			{
				$_POST['scienceQuery' . $cptQuery] = $queryPart;
				$typeQuery = '';
			}
			$cpt++;
		}

		$_SESSION['cptScienceQuery'] = ceil(($cptQuery+1)/3) * 3;
}


# Delete editing query
if (!empty($_POST['delEditingScienceQuery']) AND empty($_POST['extendScience']))
{
	preg_match("/[0-9]+/", $_POST['delEditingScienceQuery'], $idQueryToDel);

	$_POST['delEditingScienceQuery'] = '';

	$req = $bdd->prepare('SELECT owners, active FROM queries_science_serge WHERE id = :queryId AND  owners LIKE :userId');
	$req->execute(array(
		'queryId' => $idQueryToDel[0],
		'userId' => '%,' . $_SESSION['id'] . ',%'));
		$queriesEditOwners = $req->fetch();
		$req->closeCursor();

		if (!empty($queriesEditOwners))
		{
			$userId = $_SESSION['id'];
			$queryOwnerNEW = preg_replace("/,!*$userId,/", ',', $queriesEditOwners['owners']);
			$active = $queriesEditOwners['active'] - 1;

			$req = $bdd->prepare('UPDATE queries_science_serge SET owners = :owners, active = :active WHERE id = :id');
			$req->execute(array(
				'owners' => $queryOwnerNEW,
				'active' => $active,
				'id' => $idQueryToDel[0]));
				$req->closeCursor();
		}
}

# Add new science query
include_once('model/addNewScienceQuery.php');
if (!empty($_POST['scienceQuerySubmit']) AND $_POST['scienceQuerySubmit'] == 'add')
{
	$cpt = 0;
	$open = 0;
	$close = 0;
	$nbscienceType = 'scienceType0';
	$queryFieldsDoaj['ti']  = 'bibjson.title';
	$queryFieldsDoaj['au']  = 'bibjson.author.name';
	$queryFieldsDoaj['abs'] = 'bibjson.abstract';
	$queryFieldsDoaj['cat'] = 'bibjson.subject.term';
	$queryFieldsDoaj['all'] = '';
	$queryBoundDoaj['OR']   = 'OR';
	$queryBoundDoaj['AND']  = 'AND';
	$queryBoundDoaj['NOTAND'] = 'NOT';
	$queryScience_Arxiv = '';
	$queryScience_Doaj  = '';
	$_SESSION['cptScienceQuery'] = 3;

	while(!empty($_POST[$nbscienceType]) AND !empty($_POST['scienceQuery' . $cpt]))
	{
		if (!empty($_POST['andOrAndnot' . $cpt])
				AND preg_match("/(^AND$|^OR$|^NOTAND$)/", $_POST['andOrAndnot' . $cpt]))
		{
			$queryScience_Arxiv = $queryScience_Arxiv . '+' . $_POST['andOrAndnot' . $cpt] . '+';
			$queryScience_Doaj = $queryScience_Doaj . ' ' . $queryBoundDoaj[$_POST['andOrAndnot' . $cpt]] . ' ';
		}
		elseif (!empty($_POST['andOrAndnot' . $cpt])
						AND !preg_match("/(^AND$|^OR$|^NOTAND$)/", $_POST['andOrAndnot' . $cpt]))
		{
			$queryScience_Arxiv = $queryScience_Arxiv . '+OR+';
			$queryScience_Doaj = $queryScience_Doaj . ' OR ';
		}

		if (preg_match("/(^ti$|^au$|^abs$|^jr$|^cat$|^all$)/", $_POST['scienceType' . $cpt]))
		{
			$openParenthesis = '';
			$closeParenthesis = '';
			if ($_POST['openParenthesis' . $cpt] == 'active')
			{
				$openParenthesis = '%28';
				$open ++;
			}

			if ($_POST['closeParenthesis' . $cpt] == 'active')
			{
				$closeParenthesis = '%29';
				$close ++;
			}

			$queryScience_Arxiv = $queryScience_Arxiv . $openParenthesis . $_POST['scienceType' . $cpt] . ':';
			$queryScience_Doaj = $queryScience_Doaj . $openParenthesis;

			if (!empty($queryFieldsDoaj[$_POST['scienceType' . $cpt]]))
			{
				$queryScience_Doaj = $queryScience_Doaj . $queryFieldsDoaj[$_POST['scienceType' . $cpt]] . ':';
			}

			$scienceQuery = htmlspecialchars($_POST['scienceQuery' . $cpt]);
			$scienceQuery = urlencode($scienceQuery);
			$scienceQuery = preg_replace("/( |:|`|%22|%28|%29)/", "+", $scienceQuery);
			$queryScience_Arxiv = $queryScience_Arxiv . '%22' . $scienceQuery . '%22' . $closeParenthesis;
			$queryScience_Doaj = $queryScience_Doaj . '%22' . $scienceQuery . '%22' . $closeParenthesis;
		}

		# Cleaning
		$_POST['andOrAndnot' . $cpt] = '';
		$_POST['openParenthesis' . $cpt] = '';
		$_POST['scienceType' . $cpt] = '';
		$_POST['scienceQuery' . $cpt] = '';
		$_POST['closeParenthesis' . $cpt] = '';

		$cpt ++;
		$nbscienceType = 'scienceType' . $cpt;
	}

	if ($open != $close)
	{
		$ERROR_SCIENCEQUERY = 'Invalid query : parenthesis does not match';
	}

	if (empty($ERROR_SCIENCEQUERY) AND !empty($queryScience_Arxiv) AND !empty($queryScience_Doaj))
	{
		$ERROR_SCIENCEQUERY = addNewScienceQuery($queryScience_Arxiv, $queryScience_Doaj, $bdd);
	}
}

#Delete science query
if (!empty($_POST['delQueryScience']))
{
	preg_match("/[0-9]+/", $_POST['delQueryScience'], $idQueryToDel);

	// Read owner science query
	$req = $bdd->prepare('SELECT owners, active FROM queries_science_serge WHERE id = :queryId AND (owners LIKE :userIdDisable OR owners LIKE :userIdActivate)');
	$req->execute(array(
		'queryId' => $idQueryToDel[0],
		'userIdDisable' => '%,!' . $_SESSION['id'] . ',%',
		'userIdActivate' => '%,' . $_SESSION['id'] . ',%',));
		$result = $req->fetch();
		$req->closeCursor();

	if (!empty($result))
	{
		$userId = $_SESSION['id'];
		$queryOwnerNEW = preg_replace("/,!*$userId,/", ',', $result['owners']);

		$active = $result['active'] - 1;

		$req = $bdd->prepare('UPDATE queries_science_serge SET owners = :owners, active = :active WHERE id = :id');
		$req->execute(array(
			'owners' => $queryOwnerNEW,
			'active' => $active,
			'id' => $idQueryToDel[0]));
			$req->closeCursor();
	}
}

#Disable science query
if (!empty($_POST['disableQueryScience']))
{
	preg_match("/[0-9]+/", $_POST['disableQueryScience'], $idQueryToDisable);
	// Read owner science query
	$req = $bdd->prepare('SELECT owners, active FROM queries_science_serge WHERE id =  :queryId AND owners LIKE :userId');
	$req->execute(array(
		'queryId' => $idQueryToDisable[0],
		'userId' => '%,' . $_SESSION['id'] . ',%'));
		$result = $req->fetch();
		$req->closeCursor();

	if (!empty($result))
	{
		$userId = $_SESSION['id'];
		$queryOwnerNEW = preg_replace("/,$userId,/", ",!$userId,", $result['owners']);

		$active = $result['active'] - 1;
		$req = $bdd->prepare('UPDATE queries_science_serge SET owners = :owners, active = :active WHERE id = :id');
		$req->execute(array(
			'owners' => $queryOwnerNEW,
			'active' => $active,
			'id' => $idQueryToDisable[0]));
			$req->closeCursor();
	}
}

#Activate science query
if (!empty($_POST['activateQueryScience']))
{
	preg_match("/[0-9]+/", $_POST['activateQueryScience'], $idQueryToActivate);
	// Read owner science query
	$req = $bdd->prepare('SELECT owners, active FROM queries_science_serge WHERE id =  :queryId AND owners LIKE :userId');
	$req->execute(array(
		'queryId' => $idQueryToActivate[0],
		'userId' => '%,!' . $_SESSION['id'] . ',%'));
		$result = $req->fetch();
		$req->closeCursor();

	if (!empty($result))
	{
		$userId = $_SESSION['id'];
		$queryOwnerNEW = preg_replace("/,!$userId,/", ",$userId,", $result['owners']);

		$active = $result['active'] + 1;
		$req = $bdd->prepare('UPDATE queries_science_serge SET owners = :owners, active = :active WHERE id = :id');
		$req->execute(array(
			'owners' => $queryOwnerNEW,
			'active' => $active,
			'id' => $idQueryToActivate[0]));
			$req->closeCursor();
	}
}

# Edit patent query
if (!empty($_GET['action']) AND !empty($_GET['query']) AND $_GET['action'] == 'editQueryPatent')
{
	preg_match("/[0-9]+/", $_GET['query'], $queryId);
	$delEditingPatentQuery = $queryId[0];

	$req = $bdd->prepare('SELECT query FROM queries_wipo_serge WHERE id = :queryId AND  owners LIKE :userId');
	$req->execute(array(
		'queryId' => $queryId[0],
		'userId' => '%,' . $_SESSION['id'] . ',%'));
		$queriesEdit = $req->fetch();
		$req->closeCursor();

		$query = urldecode($queriesEdit['query']);
		$query = preg_replace("/\"/", "", $query);
		$query = preg_replace("/(\(|\)|[^: ]+:| AND | OR )/", "|$1", $query);
		$query = preg_replace("/:/", "|", $query);
		$queryArray = explode("|", $query);

		$cpt = 0;
		$typeQuery = '';
		foreach ($queryArray as $queryPart)
		{
			$cptQuery = ceil($cpt/6) - 1;
			if (preg_match("/(^ AND $|^ OR $)/",$queryPart, $value))
			{
				if (($cpt / 6) != intval($cpt / 6))
				{
					$cpt = (intval($cpt / 6) + 1) * 6;
					$cptQuery = ceil($cpt/6);
				}
				$value = preg_replace("/ /", "", $value[0]);
				$_POST['andOrPatent' . $cptQuery] = $value;
			}
			elseif (!empty($queryPart) AND $typeQuery != 'displayed')
			{
				$_POST['patentType' . $cptQuery] = $queryPart;
				$typeQuery = 'displayed';
			}
			elseif (!empty($queryPart))
			{
				$_POST['patentQuery' . $cptQuery] = $queryPart;
				$typeQuery = '';
			}
			$cpt++;
		}

		$_SESSION['cptPatentQuery'] = ceil(($cptQuery+1)/3) * 3;
}


# Delete editing query
if (!empty($_POST['delEditingPatentQuery']) AND empty($_POST['extendPatent']))
{
	preg_match("/[0-9]+/", $_POST['delEditingPatentQuery'], $idQueryToDel);

	$_POST['delEditingPatentQuery'] = '';

	$req = $bdd->prepare('SELECT owners, active FROM queries_wipo_serge WHERE id = :queryId AND  owners LIKE :userId');
	$req->execute(array(
		'queryId' => $idQueryToDel[0],
		'userId' => '%,' . $_SESSION['id'] . ',%'));
		$queriesEditOwners = $req->fetch();
		$req->closeCursor();

		if (!empty($queriesEditOwners))
		{
			$userId = $_SESSION['id'];
			$queryOwnerNEW = preg_replace("/,!*$userId,/", ',', $queriesEditOwners['owners']);
			$active = $queriesEditOwners['active'] - 1;

			$req = $bdd->prepare('UPDATE queries_wipo_serge SET owners = :owners, active = :active WHERE id = :id');
			$req->execute(array(
				'owners' => $queryOwnerNEW,
				'active' => $active,
				'id' => $idQueryToDel[0]));
				$req->closeCursor();
		}
}

# Add new patents query
if (!empty($_POST['patentQuerySubmit']) AND $_POST['patentQuerySubmit'] == 'add')
{
	include_once('model/addNewPatentQuery.php');
	$cpt = 0;
	$andOrPatent = '';
	$queryPatent = '';
	$_SESSION['cptPatentQuery'] = 3;

	while(!empty($_POST['patentType' . $cpt]) AND !empty($_POST['patentQuery' . $cpt]))
	{
		if (!preg_match("/^[A-Z_]+$/", $_POST['patentType' . $cpt]))
		{
			$_POST['patentType' . $cpt] = 'ALLNAMES';
		}

		$patentQueryInput = urlencode(preg_replace("/(:| $)/", "", $_POST['patentQuery' . $cpt]));

		$queryPatent = $queryPatent . $andOrPatent . $_POST['patentType' . $cpt] . '%3A' . $patentQueryInput . '+';

		# Cleaning
		$_POST['patentType' . $cpt ] = '';
		$_POST['patentQuery' . $cpt ] = '';
		$_POST['andOrPatent' . $cpt ] = '';

		$cpt++;

		if(empty($_POST['andOrPatent' . $cpt]))
		{
			$andOrPatent = 'AND+';
		}
		else
		{
			$andOrPatent = 'OR+';
		}
	}

	if (!empty($queryPatent))
	{
		$ERROR_PATENTQUERY = addNewPatentQuery($queryPatent, $bdd);
	}
}

#Delete patent query
if (!empty($_POST['delQueryPatent']))
{
	preg_match("/[0-9]+/", $_POST['delQueryPatent'], $idQueryToDel);

	// Read owner patent query
	$req = $bdd->prepare('SELECT owners, active FROM queries_wipo_serge WHERE id =  :queryId AND (owners LIKE :userIdDisable OR owners LIKE :userIdActivate)');
	$req->execute(array(
		'queryId' => $idQueryToDel[0],
		'userIdDisable' => '%,!' . $_SESSION['id'] . ',%',
		'userIdActivate' => '%,' . $_SESSION['id'] . ',%',));
		$result = $req->fetch();
		$req->closeCursor();

	if (!empty($result))
	{
		$userId = $_SESSION['id'];
		$queryOwnerNEW = preg_replace("/,!*$userId,/", ',', $result['owners']);

		$active = $result['active'] - 1;

		$req = $bdd->prepare('UPDATE queries_wipo_serge SET owners = :owners, active = :active WHERE id = :id');
		$req->execute(array(
			'owners' => $queryOwnerNEW,
			'active' => $active,
			'id' => $idQueryToDel[0]));
			$req->closeCursor();
	}
}

#Disable patent query
if (!empty($_POST['disableQueryPatent']))
{
	preg_match("/[0-9]+/", $_POST['disableQueryPatent'], $idQueryToDisable);
	// Read owner patent query
	$req = $bdd->prepare('SELECT owners, active FROM queries_wipo_serge WHERE id =  :queryId AND owners LIKE :userId');
	$req->execute(array(
		'queryId' => $idQueryToDisable[0],
		'userId' => '%,' . $_SESSION['id'] . ',%'));
		$result = $req->fetch();
		$req->closeCursor();

	if (!empty($result))
	{
		$userId = $_SESSION['id'];
		$queryOwnerNEW = preg_replace("/,$userId,/", ",!$userId,", $result['owners']);

		$active = $result['active'] - 1;
		$req = $bdd->prepare('UPDATE queries_wipo_serge SET owners = :owners, active = :active WHERE id = :id');
		$req->execute(array(
			'owners' => $queryOwnerNEW,
			'active' => $active,
			'id' => $idQueryToDisable[0]));
			$req->closeCursor();
	}
}

#Activate patent query
if (!empty($_POST['activateQueryPatent']))
{
	preg_match("/[0-9]+/", $_POST['activateQueryPatent'], $idQueryToActivate);
	// Read owner patent query
	$req = $bdd->prepare('SELECT owners, active FROM queries_wipo_serge WHERE id =  :queryId AND owners LIKE :userId');
	$req->execute(array(
		'queryId' => $idQueryToActivate[0],
		'userId' => '%,!' . $_SESSION['id'] . ',%'));
		$result = $req->fetch();
		$req->closeCursor();

	if (!empty($result))
	{
		$userId = $_SESSION['id'];
		$queryOwnerNEW = preg_replace("/,!$userId,/", ",$userId,", $result['owners']);

		$active = $result['active'] + 1;
		$req = $bdd->prepare('UPDATE queries_wipo_serge SET owners = :owners, active = :active WHERE id = :id');
		$req->execute(array(
			'owners' => $queryOwnerNEW,
			'active' => $active,
			'id' => $idQueryToActivate[0]));
			$req->closeCursor();
	}
}

# Extend science query
if (!empty($_POST['extendScience']))
{
	$_SESSION['cptScienceQuery'] += 3;
	if (!empty($_POST['delEditingScienceQuery']))
	{
		preg_match("/[0-9]+/", $_POST['delEditingScienceQuery'], $idQueryToDel);
		$delEditingScienceQuery = $idQueryToDel[0];
	}
}

# Extend patent query
if (!empty($_POST['extendPatent']))
{
	$_SESSION['cptPatentQuery'] += 3;
	if (!empty($_POST['delEditingScienceQuery']))
	{
		preg_match("/[0-9]+/", $_POST['delEditingPatentQuery'], $idQueryToDel);
		$delEditingPatentQuery = $idQueryToDel[0];
	}
}

include_once('view/nav/nav.php');

include_once('view/body/setting.php');

include_once('view/footer/footer.php');

?>
