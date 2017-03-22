<?php

/*include_once('model/get_text.php');*/

$setting="active";

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
		exec($cmd, $linkValidation, $errorInCheckfeed);
		if ($linkValidation[1] == 'VALID LINK' AND $errorInCheckfeed == 0)
		{
			include_once('model/addNewSource.php');
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
	$sourceId = preg_replace('/[^0-9]/', '', htmlspecialchars($_POST['sourceKeyword']));
	$newKeyword  = htmlspecialchars($_POST['newKeyword']);

	include_once('model/addNewKeyword.php');
}

# Reread owner sources to take care of changes
/* FIXME
include_once('model/readOwnerSources.php');
include_once('model/readOwnerSourcesKeyword.php');
*/
include_once('view/nav/nav.php');

include_once('view/body/setting.php');

include_once('view/footer/footer.php');

?>
