<?php

include('controller/accessLimitedToSignInPeople.php');
include('model/get_text.php');
include('model/read.php');
include('model/update.php');
include('model/insert.php');
include('controller/generateNonce.php');

// Define variables
$actualLetter           = '';
$style                  = '';
$orderByKeyword         = '';
$orderBySource          = '';
$orderByType            = '';
$delEditingScienceQuery = '';
$delEditingPatentQuery  = '';
$userId                 = $_SESSION['id'];
$classNoPremium         = '';

# Data processing
$unsafeData = array();
$unsafeData = array_merge($unsafeData, array(array('action', 'action', 'GET', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('query', 'query', 'GET', '09')));

$unsafeData = array_merge($unsafeData, array(array('scrollPos', 'scrollPos', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('sourceType', 'sourceType', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('settings', 'settings', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('email', 'email', 'POST', 'email')));
$unsafeData = array_merge($unsafeData, array(array('backgroundResult', 'backgroundResult', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('cond', 'cond', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('numberLinks', 'numberLinks', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('freq', 'freq', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('days', 'days', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('hours', 'hours', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('secondDay', 'secondDay', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('orderBy', 'orderBy', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('recordRead', 'recordRead', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('historyLifetime', 'historyLifetime', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('buttonDeleteHistory', 'buttonDeleteHistory', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('deleteHistoryValue', 'deleteHistoryValue', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('deleteHistoryUnit', 'deleteHistoryUnit', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('newSource', 'newSource', 'POST', 'url')));
$unsafeData = array_merge($unsafeData, array(array('sourceKeyword', 'sourceKeyword', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('newKeyword', 'newKeyword', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('delKeyword', 'delKeyword', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('disableKeyword', 'disableKeyword', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('activateKeyword', 'activateKeyword', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('delSource', 'delSource', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('disableSource', 'disableSource', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('activateSource', 'activateSource', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('delEditingScienceQuery', 'delEditingScienceQuery', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('extendScience', 'extendScience', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('scienceQuerySubmit', 'scienceQuerySubmit', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('delQueryScience', 'delQueryScience', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('disableQueryScience', 'disableQueryScience', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('activateQueryScience', 'activateQueryScience', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('delEditingPatentQuery', 'delEditingPatentQuery', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('extendPatent', 'extendPatent', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('patentQuerySubmit', 'scienceQuerySubmit', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('delQueryPatent', 'delQueryScience', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('disableQueryPatent', 'disableQueryScience', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('activateQueryPatent', 'activateQueryScience', 'POST', '09')));

foreach($_POST as $key => $val)
{
		$key = htmlspecialchars($key);
		if (preg_match("/radio-s./", $key, $name) ||
		preg_match("/radio-ks[0-9]+/", $key, $name) ||
		preg_match("/andOrAndnot[0-9]+/", $key, $name) ||
		preg_match("/openParenthesis[0-9]+/", $key, $name) ||
		preg_match("/closeParenthesis[0-9]+/", $key, $name) ||
		preg_match("/scienceType[0-9]+/", $key, $name) ||
		preg_match("/scienceQuery[0-9]+/", $key, $name) ||
		preg_match("/andOrPatent[0-9]+/", $key, $name) ||
		preg_match("/patentType[0-9]+/", $key, $name) ||
		preg_match("/patentQuery[0-9]+/", $key, $name))
		{
			$unsafeData = array_merge($unsafeData, array(array($name[0], $name[0], 'POST', 'str')));
		}
}

include('controller/dataProcessing.php');

# Nonce
$nonceTime = $_SERVER['REQUEST_TIME'];
$nonce     = getNonce($nonceTime);

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


# Nav activation for this page
$resultTab  = '';
$wikiTab    = '';
$settingTab = 'active';

# Scroll position
if (!empty($data['scrollPos']))
{
	$_SESSION['scrollPos'] = $data['scrollPos'];
}
elseif (empty($_SESSION['scrollPos']))
{
	$_SESSION['scrollPos'] = 0;
}

# Save folding state
if (!empty($data['sourceType']))
{
	foreach($_SESSION as $key => $val)
	{
		if (preg_match("/radio-s./", $key) || preg_match("/radio-ks[0-9]+/", $key))
		{
			$_SESSION[$key] = '';
		}
	}
	foreach($data as $key => $val)
	{
		$key = htmlspecialchars($key);
		$val = htmlspecialchars($val);
		if (preg_match("/radio-s./", $key) || preg_match("/radio-ks[0-9]+/", $key))
		{
			$_SESSION[$key] = $val;
		}
	}
}

# Read watchPack used
$checkCol          = array(array('users', 'l', '%,' . $_SESSION['id'] . ',%', ''));
$watchPackUsedList = read('watch_pack_serge', 'id, name', $checkCol, 'ORDER BY name', $bdd);

# Read background list
$type           = 'result';
$checkCol       = array(array('type', '=', $type, ''));
$backgroundList = read('background_serge', 'id, name, filename', $checkCol, 'ORDER BY name', $bdd);

# Read token
$checkCol = array(array('id', '=', $_SESSION['id'], ''));
$token    = read('users_table_serge', 'token', $checkCol, '', $bdd);

# Read if user is premium
$checkCol      = array(array('id', '=', $_SESSION['id'], 'AND'),
											array('premium_expiration_date', '>', $_SERVER['REQUEST_TIME'], ''));
$userIsPremium = read('users_table_serge', '', $checkCol, '', $bdd);

if (!$userIsPremium)
{
	$classNoPremium = 'class="noPremium"';
}

# Read owner sources
$checkCol  = array(array('owners', 'l', '%,' . $_SESSION['id'] . ',%', 'OR'),
									array('owners', 'l', '%,!' . $_SESSION['id'] . ',%', ''));
$reqReadOwnerSourcestmp = read('rss_serge', 'id, link, name, owners, active', $checkCol, 'ORDER BY name', $bdd);

$checkCol                      = array(array('applicable_owners_sources', 'l', '%|' . $_SESSION['id'] . ':%', ''));
$reqReadOwnerSourcesKeywordtmp = read('keyword_news_serge', 'id, keyword, applicable_owners_sources, active', $checkCol, 'ORDER BY keyword', $bdd);

# Read user settings
$checkCol     = array(array('users', ' =', $_SESSION['pseudo'], ''));
$userSettings = read('users_table_serge', 'id, email, password, send_condition, frequency, link_limit, selected_days, selected_hour, mail_design, language, record_read, history_lifetime, background_result, record_read', $checkCol, '', $bdd);
$userSettings = $userSettings[0];

if (!empty($data['settings']) && $data['settings'] === 'ChangeSettings')
{
	# Change email
	if (!empty($data['email']))
	{
		$newEmail  = $data['email'];
		$updateCol = array(array('email', $newEmail));
		$checkCol  = array(array('id', '=', $_SESSION['id'], ''));
		$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);
	}

	# Change result backgroundList
	if (!empty($data['backgroundResult']))
	{
		$backgroundResult = $data['backgroundResult'];
		// Update background result
		$updateCol = array(array('background_result', $backgroundResult));
		$checkCol  = array(array('id', '=', $_SESSION['id'], ''));
		$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);
	}

	# change sending condition
	if (!empty($data['cond']))
	{
		$secondDay = $data['secondDay'];
		if (!preg_match("/$secondDay/", $data['days']))
		{
			$data['days'] = $data['days'] . $data['secondDay'];
		}

		$data['numberLinks'] = ($data['numberLinks'] == '' ? NULL : $data['numberLinks']);
		$data['freq']        = ($data['freq'] == '' ? NULL : $data['freq']);
		$data['days']        = ($data['days'] == '' ? NULL : $data['days']);
		$data['hours']       = ($data['hours'] == '' ? NULL : $data['hours']);

		$updateCol = array(array('send_condition', $data['cond']),
											array('link_limit', $data['numberLinks']),
											array('frequency', $data['freq']),
											array('selected_days', $data['days']),
											array('selected_hour', $data['hours']));
		$checkCol  = array(array('id', '=', $_SESSION['id'], ''));
		$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);
	}

	# Change sorting for link in email
	$orderBy = 'masterword';
	if (!empty($data['orderBy']) && ($data['orderBy'] === 'origin' || $data['orderBy'] === 'type'))
	{
		$orderBy = $data['orderBy'];
	}

	// Update background result
	$updateCol = array(array('mail_design', $orderBy));
	$checkCol  = array(array('id', '=', $_SESSION['id'], ''));
	$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);

	# Change privacy settings
	$recordRead = 0;
	if (!empty($data['recordRead']) && $data['recordRead'] === 'active')
	{
		$recordRead = 1;
	}

	// Change record read
	$updateCol = array(array('record_read', $recordRead));
	$checkCol  = array(array('id', '=', $_SESSION['id'], ''));
	$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);

	// TODO implement in serge AND in the UI
	if (!empty($data['historyLifetime']))
	{
		$updateCol = array(array('history_lifetime', $data['historyLifetime']));
		$checkCol  = array(array('id', '=', $_SESSION['id'], ''));
		$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);
	}

	header('Location: setting');
	die();
}

# Delete history button
if (!empty($data['buttonDeleteHistory']) && $data['buttonDeleteHistory'] === 'deleteHistory')
{
	$deleteHistoryValue = $data['deleteHistoryValue'];
	$deleteHistoryUnit  = $data['deleteHistoryUnit'];

	if ($deleteHistoryUnit === 'hour')
	{
		$deleteTimeIntervale = $deleteHistoryValue * 3600;
	}
	elseif ($deleteHistoryUnit === 'day')
	{
		$deleteTimeIntervale = $deleteHistoryValue * 3600 * 24;
	}
	elseif ($deleteHistoryUnit === 'week')
	{
		$deleteTimeIntervale = $deleteHistoryValue * 3600 * 24 * 7;
	}
	elseif ($deleteHistoryUnit === 'month')
	{
		$deleteTimeIntervale = $deleteHistoryValue * 3600 * 24 * 7 * 30;
	}
	elseif ($deleteHistoryUnit === 'year')
	{
		$deleteTimeIntervale = $deleteHistoryValue * 3600 * 24 * 7 * 30 * 12;
	}

	$now        = $_SERVER['REQUEST_TIME'];
	$deleteTime = $now - $deleteTimeIntervale;
	$owner      = '%,' . $_SESSION['id'] . ',%';

	$checkCol          = array(array('owners', 'l', $owner, 'AND'),
														array('date', '>=', $deleteTime, ''));
	$readIdResutlToDel = read('result_news_serge', 'id', $checkCol, '', $bdd);

	foreach ($readIdResutlToDel as $idResultToDel)
	{
			$checkCol = array(array('id', ' =', $idResultToDel['id'], ''));
			$result   = read('result_news_serge', 'owners', $checkCol, '', $bdd);
			$result   = $result[0];

			if (!empty($result))
			{
				$updateCol = array(array('owners', preg_replace("/,$userId,/", ',', $ownersResult['owners'])));
				$checkCol  = array(array('id', '=', $idResultToDel['id'], ''));
				$execution = update('result_news_serge', $updateCol, $checkCol, '', $bdd);
			}
	}
}

# Adding new source
if (!empty($data['sourceType']) && !empty($data['newSource']) && $data['sourceType'] === 'inputSource')
{
	$sourceToTest = escapeshellarg($data['newSource']);
	$cmd          = '/usr/bin/python /var/www/Serge/checkfeed.py ' . $sourceToTest;

	# Check if the link is valid
	exec($cmd, $linkValidation, $errorInCheckfeed);

	if ($linkValidation[0] === 'valid link' && $errorInCheckfeed === 0)
	{
		// Check if source is already in bdd
		$checkCol = array(array('link', ' =', $source, ''));
		$result   = read('rss_serge', 'owners', $checkCol, '', $bdd);
		$result   = $result[0];

		if (!$result)
		{
			// Adding new source
			preg_match('@^(?:http.*://[www.]*)?([^/]+)@i', $source, $matches);

			$insertCol = array(array('link', $source),
												array('owners', ',' . $_SESSION['id'] . ','),
												array('name', ucfirst($matches[1] . '[!NEW!]')),
												array('active', 1));
			$execution = insert('rss_serge', $insertCol, '', 'setting', $bdd);
		}
		else
		{
				$checkCol = array(array('owners', 'l', '%,' . $_SESSION['id'] . ',%', 'AND'),
													array('link', '=', $source, ''));
				$result   = read('rss_serge', 'owners, active', $checkCol, '', $bdd);
				$resultActualOwner = $result[0];

				if (!$resultActualOwner)
				{
					// Update owners of existing source with the new onwer
					$updateCol = array(array('owners', $resultActualOwner['owners'] . $_SESSION['id'] . ','),
														array('active', $resultActualOwner['active'] + 1));
					$checkCol  = array(array('link', '=', $source, ''));
					$execution = update('rss_serge', $updateCol, $checkCol, '', $bdd);
				}
				else
				{
					$_SESSION['ERROR_MESSAGE'] = 'This source is already in the database';
				}
		}

		if (isset($linkValidation[1]))
		{
			$_SESSION['ERROR_MESSAGE'] = $linkValidation[1];
		}
		header('Location: setting');
		die();
	}
	else
	{
		$ERROR_MESSAGE = 'Your link ' . 'return ' . $linkValidation[0] . ',' . $linkValidation[1] . ', please correct your link';
	}
}

# Adding new keyword
if (!empty($data['sourceKeyword']) && !empty($data['newKeyword']))
{
	include('model/addNewKeyword.php');
	$sourceId    = $data['sourceKeyword'];
	$newKeyword  = ucfirst($data['newKeyword']);

	$_SESSION['lastSourceUse'] = $sourceId;
	preg_match_all("/,?[^,]*,?/", $newKeyword, $newKeyword_array);
	array_pop($newKeyword_array[0]);
	foreach ($newKeyword_array[0] as $keyword)
	{
		$newKeyword = preg_replace("/^ | *, *| $/", '', $keyword);
		# Special keyword :all
		if (preg_match("/^:all$/i", $newKeyword) && $sourceId != '00')
		{
			$newKeyword    = ':all@' . $sourceId;
			$ERROR_MESSAGE = addNewKeyword($sourceId, $newKeyword, $ERROR_MESSAGE, $reqReadOwnerSourcestmp, $bdd);
		}
		elseif (preg_match("/^:all$/i", $newKeyword) && $sourceId === '00')
		{
			$updateBDD = FALSE;
			foreach ($reqReadOwnerSourcestmp as $ownerSourcesList)
			{
				$newKeyword    = ':all@' . $ownerSourcesList['id'];
				$sourceId      = $ownerSourcesList['id'];
				$ERROR_MESSAGE = addNewKeyword($sourceId, $newKeyword, $ERROR_MESSAGE, $reqReadOwnerSourcestmp, $bdd);
			}
		}
		elseif (preg_match("/^alert:.+/i", $newKeyword) && $sourceId != '00')
		{
			$newKeyword    = preg_replace("/alert:/i", '', $newKeyword);
			$newKeyword    = '[!ALERT!]' . $newKeyword;
			$ERROR_MESSAGE = addNewKeyword($sourceId, $newKeyword, $ERROR_MESSAGE, $reqReadOwnerSourcestmp, $bdd);
		}
		elseif (preg_match("/^alert:.+/i", $newKeyword) && $sourceId === '00')
		{
			$updateBDD  = FALSE;
			$newKeyword = preg_replace("/alert:/i", '', $newKeyword);
			$newKeyword = '[!ALERT!]' . $newKeyword;
			foreach ($reqReadOwnerSourcestmp as $ownerSourcesList)
			{
				$sourceId      = $ownerSourcesList['id'];
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
	die();
}

# Delete, disable, active keyword
if (!empty($data['delKeyword']))
{
	preg_match_all("/[0-9]*&/", $data['delKeyword'], $matchKeywordAndSource);
	$sourceIdAction  = preg_replace("/[^0-9]/", '', $matchKeywordAndSource[0][0]);
	$keywordIdAction = preg_replace("/[^0-9]/", '', $matchKeywordAndSource[0][1]);
	$action          = 'delKeyword';
}
elseif (!empty($data['disableKeyword']))
{
	preg_match_all("/[0-9]*&/", $data['disableKeyword'], $matchKeywordAndSource);
	$sourceIdAction  = preg_replace("/[^0-9]/", '', $matchKeywordAndSource[0][0]);
	$keywordIdAction = preg_replace("/[^0-9]/", '', $matchKeywordAndSource[0][1]);
	$action          = 'disableKeyword';
}
elseif (!empty($data['activateKeyword']))
{
	preg_match_all("/[0-9]*&/", $data['activateKeyword'], $matchKeywordAndSource);
	$sourceIdAction  = preg_replace("/[^0-9]/", '', $matchKeywordAndSource[0][0]);
	$keywordIdAction = preg_replace("/[^0-9]/", '', $matchKeywordAndSource[0][1]);
	$action          = 'activateKeyword';
}

if (!empty($sourceIdAction) && !empty($keywordIdAction) && !empty($action))
{
	# Check if keyword exist for this ownerSourcesList
	$keywordExist = FALSE;
	foreach ($reqReadOwnerSourcesKeywordtmp as $ownerKeywordList)
	{
		# Source of current keyword for current user
		$applicableOwnerstmp = $ownerKeywordList['applicable_owners_sources'];

		# Search for source in applicable_owners_sources
		$sourceInKeyword = preg_match("/\|" . $_SESSION['id'] . ':[,!0-9,]*,!*' . $sourceIdAction . ",[,!0-9,]*\|/", $applicableOwnerstmp, $applicable_owners_sourceForCurrentUser);

		if ($ownerKeywordList['id'] === $keywordIdAction && $sourceInKeyword)
		{
			$actualOwners            = $applicable_owners_sourceForCurrentUser[0];
			$applicableOwners        = $ownerKeywordList['applicable_owners_sources'];
			$activeForCurrentKeyword = $ownerKeywordList['active'];
			$keywordExist            = TRUE;
		}
	}

	# Delete an existing keyword
	if ($keywordExist && $action === 'delKeyword')
	{
		$newOwners        = preg_replace("/,!*$sourceIdAction,/", ',', $actualOwners);
		$newOwners        = preg_replace("/\|/", '', $newOwners);
		$applicableOwners = preg_replace($actualOwners, $newOwners, $applicableOwners);

		$updateCol = array(array('applicable_owners_sources', $applicableOwners),
											array('active', $activeForCurrentKeyword - 1));
		$checkCol  = array(array('id', '=', $keywordIdAction, ''));
		$execution = update('keyword_news_serge', $updateCol, $checkCol, '', $bdd);

		header('Location: setting');
		die();
	}
	elseif ($keywordExist && $action === 'disableKeyword')
	{
		$newOwners        = preg_replace("/,$sourceIdAction,/', ',!$sourceIdAction,", $actualOwners);
		$newOwners        = preg_replace("/\|/", '', $newOwners);
		$applicableOwners = preg_replace($actualOwners, $newOwners, $applicableOwners);

		$updateCol = array(array('applicable_owners_sources', $applicableOwners),
											array('active', $activeForCurrentKeyword - 1));
		$checkCol  = array(array('id', '=', $keywordIdAction, ''));
		$execution = update('keyword_news_serge', $updateCol, $checkCol, '', $bdd);

		header('Location: setting');
		die();
	}
	elseif ($keywordExist && $action === 'activateKeyword')
	{
		$newOwners        = preg_replace("/,!$sourceIdAction,/', ',$sourceIdAction,", $actualOwners);
		$newOwners        = preg_replace("/\|/", '', $newOwners);
		$applicableOwners = preg_replace($actualOwners, $newOwners, $applicableOwners);

		$updateCol = array(array('applicable_owners_sources', $applicableOwners),
												array('active', $activeForCurrentKeyword + 1));
		$checkCol  = array(array('id', '=', $keywordIdAction, ''));
		$execution = update('keyword_news_serge', $updateCol, $checkCol, '', $bdd);

		header('Location: setting');
		die();
	}
	else
	{
		$ERROR_MESSAGE = 'Keyword doesn\'t exist or invalid action';
	}
}

# Delete, disable, active sources
if (!empty($data['delSource']))
{
	preg_match("/[0-9]*&/", $data['delSource'], $matchSourceId);
	$sourceIdAction  = preg_replace("/[^0-9]/", '', $matchSourceId[0]);
	$action          = 'delSource';
}
elseif (!empty($data['disableSource']))
{
	preg_match("/[0-9]*&/", $data['disableSource'], $matchSourceId);
	$sourceIdAction  = preg_replace("/[^0-9]/", '', $matchSourceId[0]);
	$action          = 'disableSource';
}
elseif (!empty($data['activateSource']))
{
	preg_match("/[0-9]*&/", $data['activateSource'], $matchSourceId);
	$sourceIdAction  = preg_replace("/[^0-9]/", '', $matchSourceId[0]);
	$action          = 'activateSource';
}

if (!empty($sourceIdAction) && !empty($action))
{
	# Check if source exist for this owner
	$sourceExist = FALSE;
	foreach ($reqReadOwnerSourcestmp as $sourceList)
	{
		if ($sourceList['id'] === $sourceIdAction)
		{
			$owners                 = $sourceList['owners'];
			$activeForCurrentSource = $sourceList['active'];
			$sourceExist            = TRUE;
		}
	}

	# Delete an existing sources
	if ($sourceExist && $action === 'delSource')
	{
		$userId    = $_SESSION['id'];
		$updateCol = array(array('owners', preg_replace("/,!*$userId,/", ',', $owners)),
											array('active', $activeForCurrentSource - 1));
		$checkCol  = array(array('id', '=', $sourceIdAction, ''));
		$execution = update('rss_serge', $updateCol, $checkCol, '', $bdd);

		header('Location: setting');
		die();
	}
	elseif ($sourceExist && $action === 'disableSource')
	{
		$userId    = $_SESSION['id'];
		$updateCol = array(array('owners', preg_replace("/,$userId,/', ',!$userId,", $owners)),
											array('active', $activeForCurrentSource - 1));
		$checkCol  = array(array('id', '=', $sourceIdAction, ''));
		$execution = update('rss_serge', $updateCol, $checkCol, '', $bdd);

		header('Location: setting');
		die();
	}
	elseif ($sourceExist && $action === 'activateSource')
	{
		$userId    = $_SESSION['id'];
		$updateCol = array(array('owners', preg_replace("/,!$userId,/', ',$userId,", $owners)),
											array('active', $activeForCurrentSource + 1));
		$checkCol  = array(array('id', '=', $sourceIdAction, ''));
		$execution = update('rss_serge', $updateCol, $checkCol, '', $bdd);

		header('Location: setting');
		die();
	}
	else
	{
		$ERROR_MESSAGE = 'Source doesn\'t exist or invalid action';
	}
}

	# Sending condition
	if ($userSettings['send_condition'] === 'link_limit')
	{
		$condNbLink = 'checked';
		$condFreq   = '';
		$condDate   = '';
	}
	elseif ($userSettings['send_condition'] === 'freq')
	{
		$condNbLink = '';
		$condFreq   = 'checked';
		$condDate   = '';
	}
	elseif ($userSettings['send_condition'] === 'deadline')
	{
		$condNbLink = '';
		$condFreq   = '';
		$condDate   = 'checked';
	}

	preg_match_all("/[1-7]/", $userSettings['selected_days'], $selected_days);
	foreach ($selected_days[0] as $value)
	{
		$day[$value] = 'selected';
	}

	$day2 = $day;

	if (isset($day[1]) && isset($day[2]) && isset($day[3]) && isset($day[4]) && isset($day[5]) && isset($day[6]) && isset($day[7]))
	{
		$day[1]  = '';
		$day[2]  = '';
		$day[3]  = '';
		$day[4]  = '';
		$day[5]  = '';
		$day[6]  = '';
		$day[7]  = '';
		$day2[1] = '';
		$day2[2] = '';
		$day2[3] = '';
		$day2[4] = '';
		$day2[5] = '';
		$day2[6] = '';
		$day2[7] = '';
		$day[9]  = 'selected';
	}
	elseif (isset($day[1]) && isset($day[2]) && isset($day[3]) && isset($day[4]) && isset($day[5]))
	{
		$day[1]  = '';
		$day[2]  = '';
		$day[3]  = '';
		$day[4]  = '';
		$day[5]  = '';
		$day[6]  = '';
		$day[7]  = '';
		$day2[1] = '';
		$day2[2] = '';
		$day2[3] = '';
		$day2[4] = '';
		$day2[5] = '';
		$day[0]  = 'selected';
	}
	elseif(isset($day[1]) && isset($day[3]) && isset($day[5]))
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
		if ($day[$cpt] === 'selected' && $day2[$cpt] === 'selected' && !$firstEntry)
		{
			$day2[$cpt] = '';
			$firstEntry = TRUE;
		}
		elseif ($day[$cpt] === 'selected' && $day2[$cpt] === 'selected' && $firstEntry)
		{
			$day[$cpt] = '';
			$cpt       = 8;
		}
		$cpt++;
	}

# Sorting links in email
if ($userSettings['mail_design'] === 'masterword')
{
	$orderByKeyword = 'checked';
}
elseif ($userSettings['mail_design'] === 'origin')
{
	$orderBySource = 'checked';
}
elseif ($userSettings['mail_design'] === 'type')
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
if (!empty($data['action']) && !empty($data['query']) && $data['action'] === 'editQueryScience')
{
	$checkCol = array(array('id', '=', $data['query'], 'AND'),
										array('owners', 'l', '%,' . $_SESSION['id'] . ',%', ''));
	$queriesEdit = read('queries_science_serge', 'query_arxiv', $checkCol, '', $bdd);
	$queriesEdit = $queriesEdit[0];

		$query = urldecode($queriesEdit['query_arxiv']);
		$query = preg_replace("/\"/", '', $query);
		$query = preg_replace("/(\(|\)|[^: ]+:| AND | NOTAND | OR )/', '|$1", $query);
		$query = preg_replace("/:/', '|", $query);
		$queryArray = explode('|', $query);

		$cpt       = 0;
		$typeQuery = '';
		foreach ($queryArray as $queryPart)
		{
			$cptQuery = ceil($cpt/6) - 1;
			if (preg_match("/(^ AND $|^ NOTAND $|^ OR $)/",$queryPart, $value))
			{
				if (($cpt / 6) != intval($cpt / 6))
				{
					$cpt      = (intval($cpt / 6) + 1) * 6;
					$cptQuery = ceil($cpt/6);
				}
				$value = preg_replace("/ /", '', $value[0]);
				$data['andOrAndnot' . $cptQuery] = $value;
			}
			elseif (preg_match("/^\($/",$queryPart))
			{
				$data['openParenthesis' . $cptQuery] = 'active';
			}
			elseif (preg_match("/^\)$/",$queryPart))
			{
				$data['closeParenthesis' . $cptQuery] = 'active';
			}
			elseif (!empty($queryPart) && $typeQuery != 'displayed')
			{
				$data['scienceType' . $cptQuery] = $queryPart;
				$typeQuery = 'displayed';
			}
			elseif (!empty($queryPart))
			{
				$data['scienceQuery' . $cptQuery] = $queryPart;
				$typeQuery = '';
			}
			$cpt++;
		}

		$_SESSION['cptScienceQuery'] = ceil(($cptQuery+1)/3) * 3;
}


# Delete editing query
if (!empty($data['delEditingScienceQuery']) && empty($data['extendScience']))
{
	$checkCol = array(array('id', '=', $data['delEditingScienceQuery'], ''),
										array('owners', 'l', '%,' . $_SESSION['id'] . ',%', ''));
	$queriesEditOwners = read('queries_science_serge', 'owners, active', $checkCol, '', $bdd);
	$queriesEditOwners = $queriesEditOwners[0];

		if (!empty($queriesEditOwners))
		{
			$userId    = $_SESSION['id'];
			$updateCol = array(array('owners', preg_replace("/,!*$userId,/", ',', $queriesEditOwners['owners'])),
													array('active', $queriesEditOwners['active'] - 1));
			$checkCol  = array(array('id', ' =', $data['delEditingScienceQuery'], ''));
			$execution = update('queries_science_serge', $updateCol, $checkCol, '', $bdd);
		}
}

# Add new science query
include('model/addNewScienceQuery.php');
if (!empty($data['scienceQuerySubmit']) && $data['scienceQuerySubmit'] === 'add')
{
	$cpt                         = 0;
	$open                        = 0;
	$close                       = 0;
	$nbscienceType               = 'scienceType0';
	$queryFieldsDoaj['ti']       = 'bibjson.title';
	$queryFieldsDoaj['au']       = 'bibjson.author.name';
	$queryFieldsDoaj['abs']      = 'bibjson.abstract';
	$queryFieldsDoaj['cat']      = 'bibjson.subject.term';
	$queryFieldsDoaj['all']      = '';
	$queryBoundDoaj['OR']        = 'OR';
	$queryBoundDoaj['AND']       = 'AND';
	$queryBoundDoaj['NOTAND']    = 'NOT';
	$queryScience_Arxiv          = '';
	$queryScience_Doaj           = '';
	$_SESSION['cptScienceQuery'] = 3;

	while(!empty($data[$nbscienceType]) && !empty($data['scienceQuery' . $cpt]))
	{
		if (!empty($data['andOrAndnot' . $cpt])
				&& preg_match("/(^AND$|^OR$|^NOTAND$)/", $data['andOrAndnot' . $cpt]))
		{
			$queryScience_Arxiv = $queryScience_Arxiv . '+' . $data['andOrAndnot' . $cpt] . '+';
			$queryScience_Doaj  = $queryScience_Doaj . ' ' . $queryBoundDoaj[$data['andOrAndnot' . $cpt]] . ' ';
		}
		elseif (!empty($data['andOrAndnot' . $cpt])
						&& !preg_match("/(^AND$|^OR$|^NOTAND$)/", $data['andOrAndnot' . $cpt]))
		{
			$queryScience_Arxiv = $queryScience_Arxiv . '+OR+';
			$queryScience_Doaj  = $queryScience_Doaj . ' OR ';
		}

		if (preg_match("/(^ti$|^au$|^abs$|^jr$|^cat$|^all$)/", $data['scienceType' . $cpt]))
		{
			$openParenthesis  = '';
			$closeParenthesis = '';
			if ($data['openParenthesis' . $cpt] === 'active')
			{
				$openParenthesis = '%28';
				$open ++;
			}

			if ($data['closeParenthesis' . $cpt] === 'active')
			{
				$closeParenthesis = '%29';
				$close ++;
			}

			$queryScience_Arxiv = $queryScience_Arxiv . $openParenthesis . $data['scienceType' . $cpt] . ':';
			$queryScience_Doaj  = $queryScience_Doaj . $openParenthesis;

			if (!empty($queryFieldsDoaj[$data['scienceType' . $cpt]]))
			{
				$queryScience_Doaj = $queryScience_Doaj . $queryFieldsDoaj[$data['scienceType' . $cpt]] . ':';
			}

			$scienceQuery       = $data['scienceQuery' . $cpt];
			$scienceQuery       = urlencode($scienceQuery);
			$scienceQuery       = preg_replace("/( |:|`|%22|%28|%29)/", '+', $scienceQuery);
			$queryScience_Arxiv = $queryScience_Arxiv . '%22' . $scienceQuery . '%22' . $closeParenthesis;
			$queryScience_Doaj  = $queryScience_Doaj . '%22' . $scienceQuery . '%22' . $closeParenthesis;
		}

		# Cleaning
		$data['andOrAndnot' . $cpt]      = '';
		$data['openParenthesis' . $cpt]  = '';
		$data['scienceType' . $cpt]      = '';
		$data['scienceQuery' . $cpt]     = '';
		$data['closeParenthesis' . $cpt] = '';

		$cpt ++;
		$nbscienceType = 'scienceType' . $cpt;
	}

	if ($open != $close)
	{
		$ERROR_SCIENCEQUERY = 'Invalid query : parenthesis does not match';
	}

	if (empty($ERROR_SCIENCEQUERY) && !empty($queryScience_Arxiv) && !empty($queryScience_Doaj))
	{
		$ERROR_SCIENCEQUERY = addNewScienceQuery($queryScience_Arxiv, $queryScience_Doaj, $bdd);
	}
}

#Delete science query
if (!empty($data['delQueryScience']))
{
	// Read owner science query
	$checkCol = array(array('id', '=', $data['delQueryScience'], 'AND'),
										array('owners', 'l', '%,' . $_SESSION['id'] . ',%', 'OR'),
										array('id', '=', $data['delQueryScience'], 'AND'),
										array('owners', 'l', '%,!' . $_SESSION['id'] . ',%', ''));
	$result = read('queries_science_serge', 'owners, active', $checkCol, '', $bdd);
	$result = $result[0];

	if (!empty($result))
	{
		$userId    = $_SESSION['id'];
		$updateCol = array(array('owners', preg_replace("/,!*$userId,/", ',', $result['owners'])),
											array('active', $result['active'] - 1));
		$checkCol  = array(array('id', ' =', $data['delQueryScience'], ''));
		$execution = update('queries_science_serge', $updateCol, $checkCol, '', $bdd);
	}
}

#Disable science query
if (!empty($data['disableQueryScience']))
{
	$checkCol = array(array('id', '=',$data['disableQueryScience'], 'AND'),
										array('owners', 'l', '%,' . $_SESSION['id'] . ',%', ''));
	$result   = read('queries_science_serge', 'owners, active', $checkCol, '', $bdd);
	$result   = $result[0];


	if (!empty($result))
	{
		$queryOwnerNEW = preg_replace("/,$userId,/', ',!$userId,", $result['owners']);
		$userId        = $_SESSION['id'];
		$updateCol = array(array('owners', preg_replace("/,$userId,/', ',!$userId,", $result['owners'])),
											array('active', $result['active'] - 1));
		$checkCol  = array(array('id', ' =', $data['disableQueryScience'], ''));
		$execution = update('queries_science_serge', $updateCol, $checkCol, '', $bdd);
	}
}

#Activate science query
if (!empty($data['activateQueryScience']))
{
	$checkCol = array(array('id', '=', $data['activateQueryScience'], 'AND'),
										array('owners', 'l', '%,!' . $_SESSION['id'] . ',%', ''));
	$result = read('queries_science_serge', 'owners, active', $checkCol, '', $bdd);
	$result = $result[0];


	if (!empty($result))
	{
		$userId    = $_SESSION['id'];
		$updateCol = array(array('owners', preg_replace("/,!$userId,/', ',$userId,", $result['owners'])),
											array('active', $result['active'] + 1));
		$checkCol  = array(array('id', ' =', $data['activateQueryScience'], ''));
		$execution = update('queries_science_serge', $updateCol, $checkCol, '', $bdd);
	}
}

# Edit patent query
if (!empty($data['action']) && !empty($data['query']) && $data['action'] === 'editQueryPatent')
{
	$checkCol    = array(array('id', '=', $data['query'], 'AND'),
											array('owners', 'l', '%,' . $_SESSION['id'] . ',%', ''));
	$queriesEdit = read('queries_wipo_serge', 'query', $checkCol, '', $bdd);
	$queriesEdit = $queriesEdit[0];

	$query      = urldecode($queriesEdit['query']);
	$query      = preg_replace("/\"/", '', $query);
	$query      = preg_replace("/(\(|\)|[^: ]+:| AND | OR )/', '|$1", $query);
	$query      = preg_replace("/:/', '|", $query);
	$queryArray = explode('|', $query);

		$cpt       = 0;
		$typeQuery = '';
		foreach ($queryArray as $queryPart)
		{
			$cptQuery = ceil($cpt/6) - 1;
			if (preg_match("/(^ AND $|^ OR $)/",$queryPart, $value))
			{
				if (($cpt / 6) != intval($cpt / 6))
				{
					$cpt      = (intval($cpt / 6) + 1) * 6;
					$cptQuery = ceil($cpt/6);
				}
				$value = preg_replace("/ /", '', $value[0]);
				$data['andOrPatent' . $cptQuery] = $value;
			}
			elseif (!empty($queryPart) && $typeQuery != 'displayed')
			{
				$data['patentType' . $cptQuery] = $queryPart;
				$typeQuery = 'displayed';
			}
			elseif (!empty($queryPart))
			{
				$data['patentQuery' . $cptQuery] = $queryPart;
				$typeQuery = '';
			}
			$cpt++;
		}

		$_SESSION['cptPatentQuery'] = ceil(($cptQuery+1)/3) * 3;
}


# Delete editing query
if (!empty($data['delEditingPatentQuery']) && empty($data['extendPatent']))
{
		$checkCol = array(array('id', '=', $data['delEditingPatentQuery'], 'AND'),
											array('owners', 'l', '%,' . $_SESSION['id'] . ',%', ''));
		$queriesEditOwners = read('queries_wipo_serge', 'owners, active', $checkCol, '', $bdd);
		$queriesEditOwners = $queriesEditOwners[0];

		if (!empty($queriesEditOwners))
		{
			$userId    = $_SESSION['id'];
			$updateCol = array(array('owners', preg_replace("/,!*$userId,/", ',', $queriesEditOwners['owners'])),
												array('active', $result['active'] - 1));
			$checkCol  = array(array('id', ' =', $data['delEditingPatentQuery'], ''));
			$execution = update('queries_wipo_serge', $updateCol, $checkCol, '', $bdd);
		}
}

# Add new patents query
if (!empty($data['patentQuerySubmit']) && $data['patentQuerySubmit'] === 'add')
{
	include('model/addNewPatentQuery.php');
	$cpt         = 0;
	$andOrPatent = '';
	$queryPatent = '';
	$_SESSION['cptPatentQuery'] = 3;

	while(!empty($data['patentType' . $cpt]) && !empty($data['patentQuery' . $cpt]))
	{
		if (!preg_match("/^[A-Z_]+$/", $data['patentType' . $cpt]))
		{
			$data['patentType' . $cpt] = 'ALLNAMES';
		}

		$patentQueryInput = urlencode(preg_replace("/(:| $)/", '', $data['patentQuery' . $cpt]));

		$queryPatent = $queryPatent . $andOrPatent . $data['patentType' . $cpt] . '%3A' . $patentQueryInput . '+';

		# Cleaning
		$data['patentType' . $cpt ]  = '';
		$data['patentQuery' . $cpt ] = '';
		$data['andOrPatent' . $cpt ] = '';

		$cpt++;

		if(empty($data['andOrPatent' . $cpt]))
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
if (!empty($data['delQueryPatent']))
{
	$checkCol = array(array('id', '=', $data['delQueryPatent'], 'AND'),
										array('owners', 'l', '%,!' . $_SESSION['id'] . ',%', 'OR'),
										array('id', '=', $data['delQueryPatent'], 'AND'),
										array('owners', 'l', '%,' . $_SESSION['id'] . ',%', ''));
	$result = read('queries_wipo_serge', 'owners, active', $checkCol, '', $bdd);
	$result = $result[0];

	if (!empty($result))
	{
		$userId    = $_SESSION['id'];
		$updateCol = array(array('owners', preg_replace("/,!*$userId,/", ',', $result['owners'])),
											array('active', $result['active'] - 1));
		$checkCol  = array(array('id', ' =', $data['delQueryPatent'], ''));
		$execution = update('queries_wipo_serge', $updateCol, $checkCol, '', $bdd);
	}
}

#Disable patent query
if (!empty($data['disableQueryPatent']))
{
	$checkCol = array(array('id', '=', $data['disableQueryPatent'], 'AND'),
										array('owners', 'l', '%,' . $_SESSION['id'] . ',%', ''));
	$result   = read('queries_wipo_serge', 'owners, active', $checkCol, '', $bdd);
	$result   = $result[0];

	if (!empty($result))
	{
		$userId    = $_SESSION['id'];
		$updateCol = array(array('owners', preg_replace("/,$userId,/', ',!$userId,", $result['owners'])),
											array('active', $result['active'] - 1));
		$checkCol  = array(array('id', '=', $data['disableQueryPatent'], ''));
		$execution = update('queries_wipo_serge', $updateCol, $checkCol, '', $bdd);
	}
}

#Activate patent query
if (!empty($data['activateQueryPatent']))
{
	$checkCol = array(array('id', '=', $data['activateQueryPatent'], 'AND'),
										array('owners', 'l', '%,!' . $_SESSION['id'] . ',%', ''));
	$result   = read('queries_wipo_serge', 'owners, active', $checkCol, '', $bdd);
	$result   = $result[0];

	if (!empty($result))
	{
		$userId    = $_SESSION['id'];
		$updateCol = array(array('owners', preg_replace("/,!$userId,/', ',$userId,", $result['owners'])),
											array('active', $result['active'] + 1));
		$checkCol  = array(array('id', '=', $data['activateQueryPatent'], ''));
		$execution = update('queries_wipo_serge', $updateCol, $checkCol, '', $bdd);
	}
}

# Extend science query
if (!empty($data['extendScience']))
{
	$_SESSION['cptScienceQuery'] += 3;
	if (!empty($data['delEditingScienceQuery']))
	{
		$delEditingScienceQuery = $data['delEditingScienceQuery'];
	}
}

# Extend patent query
if (!empty($data['extendPatent']))
{
	$_SESSION['cptPatentQuery'] += 3;
	if (!empty($data['delEditingPatentQuery']))
	{
		$delEditingPatentQuery = $data['delEditingPatentQuery'];
	}
}

include('view/nav/nav.php');

include('view/body/setting.php');

include('view/footer/footer.php');

?>
