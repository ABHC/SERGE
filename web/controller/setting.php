<?php

/*include_once('model/get_text.php');*/

$setting="active";

if (!isset($_SESSION['pseudo']))
{
	header('Location: connection.php?redirectFrom=setting');
}

# Read owner sources
include_once('model/readOwnerSources.php');

# Adding new source
if (isset($_POST['sourceKeyword']))
{
	if ($_POST['sourceKeyword'] == 'inputSource' AND isset($_POST['source']))
	{
		$source       = htmlspecialchars($_POST['source']);
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


include_once('view/nav/nav.php');

include_once('view/body/setting.php');

include_once('view/footer/footer.php');

?>
