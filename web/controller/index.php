<?php

include_once('model/get_text.php');
include_once('model/read.php');

# Initialization of variables
$resultTab       = '';
$wikiTab         = '';
$settingTab      = '';
$errorMessage = '';

if(isset($_POST['reg_pseudo']) && isset($_POST['reg_mail']) && isset($_POST['reg_password']) && isset($_POST['reg_repassword']) && isset($_POST['captcha']))
{
	$pseudo       = preg_replace("#[^[:alnum:]-]#","", $_POST['reg_pseudo']);
	$email        = htmlspecialchars($_POST['reg_mail']);
	$captcha_user = hash('sha256', $_POST['captcha']);

	if($_SESSION['captcha'] == $captcha_user)
	{
		$_SESSION['captcha'] = "";
		#Vérification des mots de passes
		if(htmlspecialchars($_POST['reg_password']) == htmlspecialchars($_POST['reg_repassword']))
		{
			#Vérification de la taille des mots de passes
			$nb_carac_password = iconv_strlen(htmlspecialchars($_POST['reg_password']));

			if($nb_carac_password < 8)
			{
				$errorMessage = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px /> Passphrase too short <br>';
			}
			else
			{
				# Check
				$checkCol = array(array("users", "=", $pseudo, ""));
				$result_pseudo = read("users_table_serge", '', $checkCol, '',$bdd);

				$checkCol = array(array("email", "=", $email, ""));
				$result_email = read("users_table_serge", '', $checkCol, '',$bdd);
				if(filter_var($email, FILTER_VALIDATE_EMAIL) == FALSE)
				{
					$errorMessage = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px /> Invalid email <br>';
				}
				else if($result_pseudo)
				{
					$errorMessage = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px /> Existing pseudo <br>';
				}
				else if($result_email)
				{
					$errorMessage = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px /> Existing email <br>';
				}
				else
				{
					$password = hash('sha256', 'BlackSalt' . $_POST['reg_password']);
					include_once('model/signup.php');
					$password = "";

					# Connexion
					session_start();
					$_SESSION['pseudo'] = $pseudo;
					$_SESSION['id'] = $idNewUser;
					header("Location: setting");
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
		$_SESSION['captcha'] = "";
	}
}
# Generate captcha
include_once('model/captcha.php');
$cpt=1;
$captcha_val = "";
while ($cpt < 5)
{
	$nb_captcha=rand(1, 52);
	$captcha_name="images/captcha/".$nb_captcha.".png";
	copy($captcha_name, "images/captcha/captcha".$cpt.".png");
	$captcha_val = $captcha_val.$captcha[$nb_captcha-1];
	$cpt++;
}
$_SESSION['captcha'] = hash('sha256', $captcha_val);

include_once('view/nav/nav.php');

include_once('view/body/index.php');

include_once('view/footer/footer.php');

?>
