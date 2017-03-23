<?php

// Define variables
$ERROR_MESSAGE = '';

/*include_once('model/get_text.php');*/

# Nav activation for this page
$setting="active";

# User need to be connected to access to this page
if (!isset($_SESSION['pseudo']))
{
	header('Location: connection.php?redirectFrom=setting');
}

# Read owner sources
include_once('model/readOwnerSources.php');
include_once('model/readOwnerSourcesKeyword.php');

# Adding new source
if (isset($_POST['sourceType']))
{
	if ($_POST['sourceType'] == 'inputSource' AND isset($_POST['newSource']))
	{
		$source       = htmlspecialchars($_POST['newSource']);
		$sourceToTest = escapeshellarg($source);
		$cmd          = '/usr/bin/python /var/www/Serge/checkfeed.py ' . $sourceToTest;

		# Check if the link is valid
		exec($cmd, $linkValidation, $errorInCheckfeed);

		if ($linkValidation[1] == 'VALID LINK' AND $errorInCheckfeed == 0)
		{
			include_once('model/addNewSource.php');
			header('Location: setting');
		}
		else
		{
			$ERROR_MESSAGE = 'Your link ' . 'return ' . $linkValidation[0] . ',' . $linkValidation[1] . ', please correct your link';
		}
	}
	else
	{
		$ERROR_MESSAGE = 'You must write the link of the source';
	}
}

# Adding new keyword
if (isset($_POST['sourceKeyword']) AND isset($_POST['newKeyword']))
{
	$sourceId    = preg_replace('/[^0-9]/', '', htmlspecialchars($_POST['sourceKeyword']));
	$newKeyword  = htmlspecialchars($_POST['newKeyword']);

	include_once('model/addNewKeyword.php');
	header('Location: setting');
}

if (isset($_GET['keyword']) AND isset($_GET['action']) AND isset($_GET['source']))
{
	$keywordIdAction = preg_replace('/[^0-9]/', '', htmlspecialchars($_GET['keyword']));
	$sourceIdAction  = preg_replace('/[^0-9]/', '', htmlspecialchars($_GET['source']));
	$action          = htmlspecialchars($_GET['action']);

	# Check if keyword exist for this ownerSourcesList
	$keywordExist = FALSE;
	foreach ($reqReadOwnerSourcesKeywordtmp as $ownerKeywordList)
	{
		# Source of current keyword for current user
		$applicable_owners_sourcestmp = $ownerKeywordList['applicable_owners_sources'];

		# Search for source in applicable_owners_sources
		$sourceInKeyword = preg_match("/\|" . $_SESSION['id'] . ":[,0-9,]*," . $sourceIdAction . ",[,0-9,]*\|/", $applicable_owners_sourcestmp, $applicable_owners_sourceForCurrentUser);

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
		//include_once('model/disableKeyword.php');## A créer
	}
	else
	{
		$ERROR_MESSAGE = 'Keyword doesn\'t exist or invalid action';
	}
}

include_once('view/nav/nav.php');

include_once('view/body/setting.php');

include_once('view/footer/footer.php');

?>
