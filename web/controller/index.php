<?php

include('model/get_text.php');
include('model/get_text_var.php');
include('model/read.php');
include('model/update.php');
include('model/insert.php');
include('controller/generateNonce.php');

# Initialization of variables
$resultTab             = '';
$wikiTab               = '';
$settingTab            = '';
$errorMessage          = '';
$ErrorMessageCheckMail = '';

# Data processing
$unsafeData = array();
$unsafeData = array_merge($unsafeData, array(array('emailCheckToken', 'emailCheck', 'GET', 'str')));
$unsafeData = array_merge($unsafeData, array(array('checker', 'checker', 'GET', 'str')));

$unsafeData = array_merge($unsafeData, array(array('pseudo', 'reg_pseudo', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('password', 'reg_password', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('repassword', 'reg_repassword', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('email', 'reg_mail', 'POST', 'email')));
$unsafeData = array_merge($unsafeData, array(array('captcha', 'captcha', 'POST', 'Az')));

include('controller/dataProcessing.php');

# Nonce
$nonceTime = $_SERVER['REQUEST_TIME'];
$nonce     = getNonce($nonceTime);

if(!empty($data['pseudo']) && !empty($data['email']) && !empty($data['password']) && !empty($data['repassword']) && !empty($data['captcha']))
{
	$pseudo       = preg_replace("#[^[:alnum:]-]#",'', $data['pseudo']);
	$captcha_user = hash('sha256', $data['captcha']);

	if($_SESSION['captcha'] === $captcha_user)
	{
		$_SESSION['captcha'] = '';

		# Check passphrase
		if($data['password'] === $data['repassword'])
		{
			# Check size of passphrase
			if(!isset($data['password']{8}))
			{
				$errorMessage = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px /> ' . var_get_t('errorMessagePassphrase', $bdd) . '<br>';
			}
			else
			{
				# Check
				$checkCol      = array(array('users', '=', $pseudo, ''));
				$result_pseudo = read('users_table_serge', '', $checkCol, '',$bdd);

				$checkCol     = array(array('email', '=', $data['email'], ''));
				$result_email = read('users_table_serge', '', $checkCol, '',$bdd);
				if(filter_var($data['email'], FILTER_VALIDATE_EMAIL) === FALSE)
				{
					$errorMessage = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px /> Invalid email <br>';
				}
				elseif($result_pseudo)
				{
					$errorMessage = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px /> Existing pseudo <br>';
				}
				elseif($result_email)
				{
					$errorMessage = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px /> Existing email <br>';
				}
				else
				{
					$tokenExist = TRUE;
					while ($tokenExist)
					{
						// Génération du token
						$bytes       = random_bytes(4);
						$cryptoToken = bin2hex($bytes);
						$cpt         = 0;

						while($cpt < 8)
						{
							if(preg_match("/0/", $cryptoToken[$cpt]))
							{
								$token = $token . $cryptoToken[$cpt];
							}
							elseif(preg_match("/[0-9]/", $cryptoToken[$cpt]))
							{
								$token = $token . strtolower(chr(64 + $cryptoToken[$cpt]));
							}
							else
							{
								$token = $token . strtoupper($cryptoToken[$cpt]);
							}
							$cpt++;
						}

						$checkCol   = array(array('token', '=', $token, ''));
						$tokenExist = read('users_table_serge', '', $checkCol, '',$bdd);
					}

					// Salt generation
					$bytes      = random_bytes(5);
					$cryptoSalt = bin2hex($bytes);
					$password   = hash('sha256', $cryptoSalt . $data['password']);

					// Language
					$language = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
					$language = strtoupper($language[0] . $language[1]);
					$language = preg_replace("/[^A-Z]/", '', $language);
					if (empty($language))
					{
						$language = 'EN';
					}

					$checker = hash('sha256', $cryptoSalt . preg_replace("/...$/", "", $_SERVER['REQUEST_TIME']));

					$verifyLink = 'http://' . $_SERVER['HTTP_HOST'] . '/index?emailCheck=' . $token . '&checker='. $checker;

					// Send email verification
					$to      = $data['email'];
					$subject = 'Serge : Email verification';
					$body    = "Please verify your email by clicking on the link below :  $verifyLink";

					# Read mail address
					$mailAddr  = fopen('/var/www/Serge/web/.mailaddr', 'r');
					$emailAddr = fgets($mailAddr);
					fclose($mailAddr);

					#Cleaning value
					$emailAddr = preg_replace("/(\r\n|\n|\r)/", "", $emailAddr);

					$headers = "From: $emailAddr" . "\r\n" .
					"Reply-To: $emailAddr" . "\r\n" .
					'X-Mailer: PHP/' . phpversion();

					mail($to, $subject, $body, $headers);


					// Insert new user in database
					$insertCol = array(array('users', $pseudo),
														array('email', $data['email']),
														array('password', $password),
														array('salt', $cryptoSalt),
														array('signup_date', $_SERVER['REQUEST_TIME']),
														array('language', $language),
														array('record_read', 1),
														array('token', $token));
					$execution = insert('users_table_serge', $insertCol, '', '', $bdd);

					// Read new user information in order to connect it
					$checkCol = array(array('users', '=', $pseudo, 'AND'),
														array('password', '=', $password, ''));
					$result   = read('users_table_serge', 'id, users, language', $checkCol, '', $bdd);
					$result   = $result[0];

					# Cleaning
					unset($password);
					unset($data['password']);

					# Connection
					session_start();
					$_SESSION['id']            = $result['id'];
					$_SESSION['pseudo']        = $result['users'];
					$_SESSION['lang']          = $result['language'];
					$_SESSION['ip']            = $_SERVER['REMOTE_ADDR'];
					$_SESSION['user-agent']    = $_SERVER['HTTP_USER_AGENT'];
					$_SESSION['lastSourceUse'] = '';

					header('Location: setting');
					die();
				}
			}
		}
		else
		{
			$errorMessage = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px /> ' . var_get_t('errorMessagePassphrase', $bdd) . '<br>';
		}
	}
	else
	{
		$errorMessage = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px /> ' . var_get_t('errorMessageCaptcha', $bdd) . '<br>';
		$_SESSION['captcha'] = '';
	}
}

if (!empty($data['emailCheckToken']) && !empty($data['checker']))
{
	$checkCol   = array(array('token', ' =', $data['emailCheckToken'], ''));
	$result     = read('users_table_serge', 'id, salt', $checkCol, '',$bdd);
	$userId     = $result[0]['id'];
	$cryptoSalt = $result[0]['salt'];

	$checker = hash('sha256', $cryptoSalt . preg_replace("/...$/", "", $_SERVER['REQUEST_TIME']));

	if (!empty($userId) && $checker === $data['checker'])
	{
		$updateCol = array(array('email_validation', 1));
		$checkCol  = array(array('id', '=', $userId, ''));
		$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);

		header('Location: setting');
		die();
	}

	$ErrorMessageCheckMail = '<script>alert("Unvalid email validation link");</script>';
}

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

include('view/nav/nav.php');

include('view/body/index.php');

include('view/footer/footer.php');

?>
