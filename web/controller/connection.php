<?php

//include_once('model/get_text.php');

include_once('view/nav/nav.php');

include_once('view/body/connection.php');

include_once('view/footer/footer.php');

if (isset($_POST['conn_pseudo']) && isset($_POST['conn_password']))
{
	$pseudo   = htmlspecialchars($_POST['conn_pseudo']);
	$password = hash('sha256', $_POST['conn_password']);

	include_once('model/connection.php');

	if (!$result)
	{
		echo 'Mauvais identifiant ou mot de passe !';
	}
	else
	{
		session_start();
		$_SESSION['pseudo']            = $pseudo;
		$_SESSION['id']                = $result['id'];
		$_SESSION['lastSourceUse']     = '';
		$redirect                      = $_SESSION['redirectFrom'];

		if (empty($redirect))
		{
			$redirect = 'result';
		}

		header("Location: $redirect");
	}
}

?>
