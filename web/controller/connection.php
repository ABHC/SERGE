<?php

# Define variable
$resultTab    = '';
$wikiTab      = '';
$settingTab   = '';
$ERRORMESSAGE = '';

include('model/get_text.php');
include('model/get_text_var.php');
include('model/read.php');
include('controller/generateNonce.php');


$unsafeData = array();
$unsafeData = array_merge($unsafeData, array(array('pseudo', 'conn_pseudo', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('password', 'conn_password', 'POST', 'str')));

include('controller/dataProcessing.php');

# Nonce
$nonceTime = $_SERVER['REQUEST_TIME'];
$nonce     = getNonce($nonceTime);

if (!empty($data['pseudo']) && !empty($data['password']))
{
	$checkCol = array(array('users', '=', $data['pseudo']));
	$salt     = read('users_table_serge', 'salt', $checkCol, '',$bdd);

	if (!empty($salt))
	{
		$password = hash('sha256', $salt[0]['salt'] . $data['password']);

		$checkCol = array(array('users', '=', $data['pseudo'], 'AND'),
											array('password', '=', $password, ''));
		$result   = read('users_table_serge', 'id, users, email, send_condition, frequency, link_limit, selected_days, selected_hour, mail_design, language, background_result', $checkCol, '',$bdd);
	}

	if (!empty($result))
	{
		$redirect                  = $_SESSION['redirectFrom'];
		session_regenerate_id();
		session_start();
		$_SESSION['id']            = $result[0]['id'];
		$_SESSION['pseudo']        = $result[0]['users'];
		$_SESSION['lang']          = $result[0]['language'];
		$_SESSION['ip']            = $_SERVER['REMOTE_ADDR'];
		$_SESSION['user-agent']    = $_SERVER['HTTP_USER_AGENT'];
		$_SESSION['lastSourceUse'] = '';

		if (empty($redirect))
		{
			$redirect = 'result';
		}

		header("Location: $redirect");
		die();
	}

	$ERRORMESSAGE = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px />' . var_get_t('badIdOrPass_error_connection', $bdd);
}

include('view/nav/nav.php');

include('view/body/connection.php');

include('view/footer/footer.php');

?>
