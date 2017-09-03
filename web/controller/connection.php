<?php

# Define variable
$resultTab             = '';
$wikiTab               = '';
$settingTab            = '';

if (!isset($_POST['conn_pseudo']))
{
	$_POST['conn_pseudo'] = '';
}

include_once('model/get_text.php');
include_once('model/read.php');
include_once('controller/generateNonce.php');


$unsafeData = array();
if (isset($_POST['conn_pseudo']))
{
	$unsafeData = array_merge($unsafeData, array(array('pseudo', $_POST['conn_pseudo'], 'str')));
}
if (isset($_POST['conn_password']))
{
	$unsafeData = array_merge($unsafeData, array(array('password', $_POST['conn_password'], '')));
}

include_once('controller/dataProcessing.php');

# Nonce
$nonceTime = time();
$nonce = getNonce($nonceTime);

if ($dataProcessing AND isset($data['pseudo']) AND isset($data['password']))
{
	$password = hash('sha256', 'BlackSalt' . $data['password']);

	$checkCol = array(array("users", "=", $data['pseudo'], "AND"),
										array("password", "=", $password, ""));
	$result = read("users_table_serge", 'id, users, email, send_condition, frequency, link_limit, selected_days, selected_hour, mail_design, language, background_result', $checkCol, '',$bdd);

	if (empty($result))
	{
		$ERRORMESSAGE = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px /> Mauvais identifiant ou mot de passe !';
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

include_once('view/nav/nav.php');

include_once('view/body/connection.php');

include_once('view/footer/footer.php');

?>
