<?php

//include_once('model/get_text.php');
#Déclaration des varibales
$error_pass_length = "";
$error_bad_email = "";
$error_existing_pseudo = "";
$error_existing_email = "";
$error_pass_doesnt_match = "";
$error_bad_captcha = "";

if(!isset($_SESSION['captcha']))
{
	$_SESSION['captcha']= "";
}
if(isset($_POST['reg_pseudo']) && isset($_POST['reg_mail']) && isset($_POST['reg_password']) && isset($_POST['reg_repassword']) && isset($_POST['captcha']))
{
	$pseudo       = preg_replace("#[^[:alnum:]-]#","", $_POST['reg_pseudo']);
	$email        = htmlspecialchars($_POST['reg_mail']);
	$captcha_user = hash('sha256', $_POST['captcha']);

	if($_SESSION['captcha'] == $captcha_user)
	{
		unset($_SESSION['captcha']);
		#Vérification des mots de passes
		if(htmlspecialchars($_POST['reg_password']) == htmlspecialchars($_POST['reg_repassword']))
		{
			#Vérification de la taille des mots de passes
			$nb_carac_password = iconv_strlen(htmlspecialchars($_POST['reg_password']));

			if( $nb_carac_password < 8)
			{
				$error_pass_length = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px /> Passphrase too short';
			}
			else
			{
				# Check email
				include_once('model/verif_mail_pseudo.php');
				if(filter_var($email, FILTER_VALIDATE_EMAIL) == FALSE)
				{
					$error_bad_email = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px /> Invalid email';
				}
				else if($result_pseudo)
				{
					$error_existing_pseudo = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px /> Existing pseudo';
				}
				else if($result_email)
				{
					$error_existing_pseudo = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px /> Existing email';
				}
				else
				{
					$password = hash('sha256', $_POST['reg_password']);
					include_once('model/signup.php');
					unset($password);

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
			$error_pass_doesnt_match = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px /> Passphrases does not match';
		}
	}
	else
	{
		$error_bad_captcha = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px /> Bad captcha';
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
