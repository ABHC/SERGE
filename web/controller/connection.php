<?php

# Define variable
$result             = '';
$wiki               = '';
$setting            = '';

if (!isset($_POST['conn_pseudo']))
{
	$_POST['conn_pseudo'] = '';
}

include_once('model/get_text.php');

include_once('model/read.php');

include_once('view/nav/nav.php');

include_once('view/body/connection.php');

include_once('view/footer/footer.php');

if (isset($_POST['conn_pseudo']) && isset($_POST['conn_password']))
{
	$pseudo   = htmlspecialchars($_POST['conn_pseudo']);
	$password = hash('sha256', $_POST['conn_password']);

	#include_once('model/connection.php');

	$checkCol = array(array("users", "=", $pseudo, "AND"),
										array("password", "=", $password, ""));
	$result = read("users_table_serge", 'id, users, email, send_condition, frequency, link_limit, selected_days, selected_hour, mail_design, language, background_result', $checkCol, '',$bdd);

	if (!$result)
	{
		echo 'Mauvais identifiant ou mot de passe !';
	}
	else
	{
		session_start();
		$_SESSION['id']                = $result[0]['id'];
		$_SESSION['pseudo']            = $result[0]['users'];
		$_SESSION['lang']              = $result[0]['language'];
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
