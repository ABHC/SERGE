<?php
# User need to be connected to access to this page
if (!isset($_SESSION['pseudo']))
{
	$_SESSION['redirectFrom'] = 'setting';
	header('Location: connection');
}

// Define variables
$actualLetter = '';
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

# Read owner sources
include_once('model/readOwnerSources.php');
include_once('model/readOwnerSourcesKeyword.php');

# Adding new source
if (isset($_POST['sourceType'])  AND isset($_POST['newSource']))
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
			if (preg_match("/^:all.*/", $newKeyword) AND $sourceId != '00')
			{
				$newKeyword = ':all@' . $sourceId;
				$ERROR_MESSAGE = addNewKeyword($sourceId, $newKeyword, $ERROR_MESSAGE, $reqReadOwnerSourcestmp, $bdd);
			}
			elseif (preg_match("/^:all.*/", $newKeyword) AND $sourceId == '00')
			{
				$updateBDD = FALSE;
				foreach ($reqReadOwnerSourcestmp as $ownerSourcesList)
				{
					$newKeyword = ':all@' . $ownerSourcesList['id'];
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

include_once('view/nav/nav.php');

include_once('view/body/setting.php');

include_once('view/footer/footer.php');

?>
