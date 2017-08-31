<?php
include_once('model/read.php');
include_once('model/update.php');

if (isset($_GET['link']) AND isset($_GET['id']) AND isset($_GET['hash']))
{
	$link = urldecode($_GET['link']);
	$id   = htmlspecialchars($_GET['id']);
	$type = htmlspecialchars($_GET['type']);
	$hash = htmlspecialchars($_GET['hash']);

	# Read hash password and pseudo for user with this id
	include_once('model/readPasswordPseudoWithId.php');

	if (!empty($passwordPseudoWithId))
	{

		$pass   = $passwordPseudoWithId['password'];
		$pseudo = $passwordPseudoWithId['users'];
		$salt   = 'blackSalt';

		$checkHash = hash('sha256', $salt . ':' . $pass . $pseudo . $id);

		if ($hash == $checkHash)
		{
			$userId = ',' . $id . ',';
			include_once('model/changeReadStatus.php');
		}

		header("Location: $link");
	}
	else
	{
		header("Location: error404");
	}
}
else
{
	header("Location: error404");
}


?>
