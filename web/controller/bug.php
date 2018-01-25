<?php

# Define variable
$resultTab    = '';
$wikiTab      = '';
$settingTab   = '';
$mailSent = FALSE;
$errorMessage = '';
$email = $_SESSION['email'] ?? '';

include('model/get_text.php');
include('model/get_text_var.php');
include('model/read.php');
include('model/update.php');
include('controller/generateNonce.php');


$unsafeData = array();
$unsafeData = array_merge($unsafeData, array(array('bugDescription', 'bugDescription', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('email', 'forg_email', 'POST', 'email')));
$unsafeData = array_merge($unsafeData, array(array('captcha', 'captcha', 'POST', 'Az')));

include('controller/dataProcessing.php');

# Nonce
$nonceTime = $_SERVER['REQUEST_TIME'];
$nonce     = getNonce($nonceTime);

if (!empty($data['captcha']))
{
	$captcha = hash('sha256', $data['captcha']);
}

if (!empty($data['email']) && !empty($data['bugDescription']) && $_SESSION['captcha'] === $captcha)
{
	# Send email verification
	$checkCol        = array(array('name', '=', 'support_email', ''));
	$bugRepportEmail = read('miscellaneous_serge', 'value', $checkCol, '',$bdd);

	$to      = $bugRepportEmail[0]['value'];
	$subject = 'Serge : Bug report';
	$body    = 'This is a bug report from ' . $data['email'] . PHP_EOL . $data['bugDescription'];
	include('controller/sendmail.php');

	$mailSent = TRUE;
}

# Error
if (!empty($data['captcha']) && $_SESSION['captcha'] !== $captcha)
{
	$errorMessage = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px /> ' . var_get_t('errorMessageCaptcha', $bdd) . '<br>';
}
elseif (empty($data['email']) && !empty($data['bugDescription']))
{
		$errorMessage = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px /> ' . var_get_t('Bad email', $bdd) . '<br>';
}
elseif (empty($data['bugDescription']) && !empty($data['email']))
{
		$errorMessage = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px /> ' . var_get_t('Bad description', $bdd) . '<br>';
}

if (empty($_SESSION['email']) && !empty($data['email']))
{
	$email = $data['email'];
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

include('view/body/bug.php');

include('view/footer/footer.php');

?>
