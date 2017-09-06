<?php

# Define variable
$resultTab             = '';
$wikiTab               = '';
$settingTab            = '';

include_once('model/get_text.php');
include_once('model/get_text_var.php');
include_once('model/read.php');
include_once('controller/generateNonce.php');


$unsafeData = array();
$unsafeData = array_merge($unsafeData, array(array('pseudo', 'conn_pseudo', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('password', 'conn_password', 'POST', 'str')));

include_once('controller/dataProcessing.php');

# Nonce
$nonceTime = time();
$nonce = getNonce($nonceTime);

if ($dataProcessing AND isset($data['pseudo']) AND isset($data['password']))
{
	$checkCol = array(array("users", "=", $data['pseudo']));
	$salt = read("users_table_serge", 'salt', $checkCol, '',$bdd);

	if (!empty($salt))
	{
		$password = hash('sha256', $salt[0]['salt'] . $data['password']);

		$checkCol = array(array("users", "=", $data['pseudo'], "AND"),
		array("password", "=", $password, ""));
		$result = read("users_table_serge", 'id, users, email, send_condition, frequency, link_limit, selected_days, selected_hour, mail_design, language, background_result', $checkCol, '',$bdd);
	}

	if (!empty($result))
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

	$ERRORMESSAGE = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px />' . var_get_t('badIdOrPass_error_connection', $bdd);
}

include_once('view/nav/nav.php');

include_once('view/body/connection.php');

include_once('view/footer/footer.php');

?>
