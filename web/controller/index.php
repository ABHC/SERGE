<?php

include('model/get_text.php');
include('model/read.php');
include('model/insert.php');
include('controller/generateNonce.php');

# Initialization of variables
$resultTab    = '';
$wikiTab      = '';
$settingTab   = '';
$errorMessage = '';

# Data processing
$unsafeData = array();
$unsafeData = array_merge($unsafeData, array(array('pseudo', 'reg_pseudo', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('password', 'reg_password', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('repassword', 'reg_repassword', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('email', 'reg_mail', 'POST', 'email')));
$unsafeData = array_merge($unsafeData, array(array('captcha', 'captcha', 'POST', 'Az')));

include('controller/dataProcessing.php');

# Nonce
$nonceTime = $_SERVER['REQUEST_TIME'];
$nonce = getNonce($nonceTime);

if(!empty($data['pseudo']) AND !empty($data['email']) AND !empty($data['password']) AND !empty($data['repassword']) AND !empty($data['captcha']))
{
	$pseudo       = preg_replace("#[^[:alnum:]-]#",'', $data['pseudo']);
	$captcha_user = hash('sha256', $data['captcha']);

	if($_SESSION['captcha'] === $captcha_user)
	{
		$_SESSION['captcha'] = '';
		#Vérification des mots de passes
		if($data['password'] === $data['repassword'])
		{
			#Vérification de la taille des mots de passes
			if(!isset($data['password']{8}))
			{
				$errorMessage = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px /> Passphrase too short <br>';
			}
			else
			{
				# Check
				$checkCol      = array(array('users', ' =', $pseudo, ''));
				$result_pseudo = read('users_table_serge', '', $checkCol, '',$bdd);

				$checkCol     = array(array('email', ' =', $data['email'], ''));
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
					$resultToken = TRUE;
					while ($resultToken)
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

						$checkCol    = array(array('token', ' =', $token, ''));
						$resultToken = read('users_table_serge', '', $checkCol, '',$bdd);
					}

					// Salt generation
					$bytes      = random_bytes(5);
					$cryptoSalt = bin2hex($bytes);
					$password   = hash('sha256', $cryptoSalt . $data['password']);

					// Language
					$language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
					$language = strtoupper($language[0] . $language[1]);
					$language = preg_replace("/[^A-Z]/", '', $language);
					if (empty($language))
					{
						$language = 'EN';
					}

					// Insert new user in database
					$insertCol = array(array('users', $pseudo),
														array('email', $data['email']),
														array('password', $password),
														array('salt', $cryptoSalt),
														array('signup_date', $_SERVER['REQUEST_TIME']),
														array('send_condition', 'link_limit'),
														array('mail_design', 'masterword'),
														array('language', $language),
														array('record_read', 1),
														array('background_result', 'Skyscrapers'),
														array('token', $token));
					$execution = insert('users_table_serge', $insertCol, '', '', $bdd);

					// Read new user information in order to connect it
					$checkCol = array(array('users', '=', $pseudo, 'AND'),
														array('password', '=', $password, ''));
					$result   = read('users_table_serge', 'id, users', $checkCol, '', $bdd);
					$result   = $result[0];

					# Cleaning
					unset($password);
					unset($data['password']);

					# Connection
					session_start();
					$_SESSION['pseudo'] = $result['users'];
					$_SESSION['id']     = $result['id'];

					header('Location: setting');
					die();
				}
			}
		}
		else
		{
			$errorMessage = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px /> Passphrases does not match <br>';
		}
	}
	else
	{
		$errorMessage = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px /> Bad captcha <br>';
		$_SESSION['captcha'] = '';
	}
}

# Generate captcha
include('model/captcha.php');

$cpt         = 1;
$captcha_val = '';

while ($cpt < 5)
{
	$nb_captcha   =rand(1, 52);
	$captcha_name ='images/captcha/'.$nb_captcha.'.png';
	copy($captcha_name, 'images/captcha/captcha'.$cpt.'.png');
	$captcha_val  = $captcha_val.$captcha[$nb_captcha-1];

	$cpt++;
}

$_SESSION['captcha'] = hash('sha256', $captcha_val);

include('view/nav/nav.php');

include('view/body/index.php');

include('view/footer/footer.php');

?>
