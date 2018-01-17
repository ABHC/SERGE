<?php

# Define variable
$resultTab             = '';
$wikiTab               = '';
$settingTab            = '';
$ERRORMESSAGE          = '';
$errorMessage          = '';
$checkYourEmails       = FALSE;
$unvalidLink           = FALSE;
$forgotPassphraseStep0 = FALSE;
$forgotPassphraseStep1 = FALSE;

include('model/get_text.php');
include('model/get_text_var.php');
include('model/read.php');
include('model/update.php');
include('controller/generateNonce.php');


$unsafeData = array();
$unsafeData = array_merge($unsafeData, array(array('action', 'action', 'GET', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('token', 'token', 'GET', 'str')));
$unsafeData = array_merge($unsafeData, array(array('checker', 'checker', 'GET', 'str')));
$unsafeData = array_merge($unsafeData, array(array('error', 'error', 'GET', 'str')));

$unsafeData = array_merge($unsafeData, array(array('pseudo', 'conn_pseudo', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('password', 'conn_password', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('email', 'forg_email', 'POST', 'email')));
$unsafeData = array_merge($unsafeData, array(array('captcha', 'captcha', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('reset_password', 'reset_password', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('reset_repassword', 'reset_repassword', 'POST', 'str')));

include('controller/dataProcessing.php');

# Nonce
$nonceTime = $_SERVER['REQUEST_TIME'];
$nonce     = getNonce($nonceTime);

if (!empty($data['pseudo']) && !empty($data['password']))
{
	$checkCol  = array(array('users', '=', $data['pseudo'], ''));
	$userExist = read('users_table_serge', 'salt', $checkCol, '',$bdd);

	if (!empty($userExist))
	{
		$password = hash('sha256', $userExist[0]['salt'] . $data['password']);

		$checkCol = array(array('users', '=', $data['pseudo'], 'AND'),
											array('password', '=', $password, ''));
		$result   = read('users_table_serge', 'id, users, email, send_condition, frequency, link_limit, selected_days, selected_hour, mail_design, language, background_result', $checkCol, '',$bdd);
	}

	# Cleaning
	unset($password);
	unset($data['password']);

	if (!empty($result[0]['id']))
	{
		if (empty($_SESSION['redirectFrom']))
		{
			$_SESSION['redirectFrom'] = 'result';
		}
		$redirect = $_SESSION['redirectFrom'];

		session_destroy();
		session_start();

		$_SESSION['id']            = $result[0]['id'];
		$_SESSION['pseudo']        = $result[0]['users'];
		$_SESSION['email']         = $result[0]['email'];
		$_SESSION['lang']          = $result[0]['language'];
		$_SESSION['ip']            = $_SERVER['REMOTE_ADDR'];
		$_SESSION['user-agent']    = $_SERVER['HTTP_USER_AGENT'];
		$_SESSION['lastSourceUse'] = '';

		header("Location: $redirect");
		die();
	}

	$errorMessage = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px />' . var_get_t('badIdOrPass_error_connection', $bdd);
}

# Step 0 for reset passphrase ask for email and captacha
if (!empty($data['action']) && $data['action'] === 'forgotPassphrase')
{
	$forgotPassphraseStep0 = TRUE;

	# Generate captcha
	include('model/captcha.php');

	$cpt         = 1;
	$captcha_val = '';

	while ($cpt < 5)
	{
		$nb_captcha   = rand(1, 52);
		$captcha_name = 'images/captcha/'.$nb_captcha.'.png';
		copy($captcha_name, 'images/captcha/captcha'.$cpt.'.png');
		$captcha_val  = $captcha_val.$captcha[$nb_captcha-1];

		$cpt++;
	}

	$_SESSION['captcha'] = hash('sha256', $captcha_val);
}
# Step 1 for reset passphrase check captcha and email
elseif (!empty($data['action']) && $data['action'] === 'forgotPassphraseProcessing')
{
	if ($_SESSION['captcha'] !== hash('sha256', $data['captcha']))
	{
		# Cleaning
		$_SESSION['captcha'] = '';

		header('Location: connection?action=forgotPassphrase&error=badCaptcha');
		die();
	}

	# Cleaning
	$_SESSION['captcha'] = '';

	# Check if user exist
	$checkCol   = array(array('email', '=', $data['email'], ''));
	$userExist  = read('users_table_serge', 'salt, token', $checkCol, '',$bdd);
	$cryptoSalt = $userExist[0]['salt'];
	$token      = $userExist[0]['token'];

	if (!empty($userExist))
	{
		# Send mail to user for reset password
		$checker = hash('sha256', $cryptoSalt . preg_replace("/...$/", "", $_SERVER['REQUEST_TIME']));

		$verifyLink = 'http://' . $_SERVER['HTTP_HOST'] . '/connection?action=resetPassphrase&token=' . $token . '&checker='. $checker;

		# Send email verification
		$to      = $data['email'];
		$subject = 'Serge : Passphrase forgot';
		$body    = "By clicking on this link you will reset your passphrase :  $verifyLink";

		include('controller/sendmail.php');

		# Say to user to check emails
		$checkYourEmails = TRUE;
	}
}
elseif (!empty($data['action']) && $data['action'] === 'resetPassphrase'
		&& !empty($data['token']) && !empty($data['checker']))
{
	# Check if checker is good
	$checkCol  = array(array('token', '=', $data['token'], ''));
	$userExist = read('users_table_serge', 'salt', $checkCol, '',$bdd);
	$cryptoSalt = $userExist[0]['salt'];

	$checker = hash('sha256', $cryptoSalt . preg_replace("/...$/", "", $_SERVER['REQUEST_TIME']));

	if ($data['checker'] === $checker)
	{
		$forgotPassphraseStep1 = TRUE;

		# Generate captcha
		include('model/captcha.php');

		$cpt         = 1;
		$captcha_val = '';

		while ($cpt < 5)
		{
			$nb_captcha   = rand(1, 52);
			$captcha_name = 'images/captcha/'.$nb_captcha.'.png';
			copy($captcha_name, 'images/captcha/captcha'.$cpt.'.png');
			$captcha_val  = $captcha_val.$captcha[$nb_captcha-1];

			$cpt++;
		}

		$_SESSION['captcha'] = hash('sha256', $captcha_val);
	}
	else
	{
		$unvalidLink = TRUE;
	}
}
elseif (!empty($data['action']) && $data['action'] === 'resetPassphraseProcessing'
		&& !empty($data['token']) && !empty($data['checker']))
{
	# Check if checker is good
	$checkCol   = array(array('token', '=', $data['token'], ''));
	$userExist  = read('users_table_serge', 'salt, email', $checkCol, '',$bdd);
	$cryptoSalt = $userExist[0]['salt'];
	$email      = $userExist[0]['email'];

	$checker = hash('sha256', $cryptoSalt . preg_replace("/...$/", "", $_SERVER['REQUEST_TIME']));

	if ($data['checker'] === $checker)
	{
		if ($_SESSION['captcha'] !== hash('sha256', $data['captcha']))
		{
			# Cleaning
			$_SESSION['captcha'] = '';

			$args = 'action=resetPassphrase&error=badCaptcha&token=' . $data['token'] . '&checker=' . $data['checker'];
			header("Location: connection?$args");
			die();
		}

		# Cleaning
		$_SESSION['captcha'] = '';

		# Check if passphrase is valid and check size of passphrase
		if($data['reset_password'] === $data['reset_repassword'] && isset($data['reset_password']{8}))
		{
			// Salt generation
			$bytes      = random_bytes(5);
			$cryptoSalt = bin2hex($bytes);
			$password   = hash('sha256', $cryptoSalt . $data['reset_password']);

			# Update password
			$updateCol = array(array('password', $password),
			array('salt', $cryptoSalt));
			$checkCol  = array(array('token', '=', $data['token'], ''));
			$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);

			# Send email verification
			$to      = $email;
			$subject = 'Serge : Passphrase change';
			$body    = 'Your pasphrase has been succesfully change !';

			include('controller/sendmail.php');

			header("Location: connection");
			die();
		}
		$args = 'action=resetPassphrase&error=badPassphrase&token=' . $data['token'] . '&checker=' . $data['checker'];
		header("Location: connection?$args");
		die();
	}
	$unvalidLink = TRUE;
}
elseif (!empty($data['action']))
{
	$unvalidLink = TRUE;
}

if (!empty($data['error']))
{
	if ($data['error'] === 'badCaptcha')
	{
		$errorMessage = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px /> ' . var_get_t('errorMessageCaptcha', $bdd) . '<br>';
	}
	elseif ($data['error'] === 'badPassphrase')
	{
		$errorMessage = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px /> ' . var_get_t('errorMessagePassphrase', $bdd) . '<br>';
	}
}


include('view/nav/nav.php');

include('view/body/connection.php');

include('view/footer/footer.php');

?>
