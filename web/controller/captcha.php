<?php

# Generate captcha
include('model/captcha.php');

# Timestamp
$_SESSION['REQUEST_PAGE_TIME'] = $_SERVER['REQUEST_TIME'];

$cpt         = 1;
$captcha_val = '';

while ($cpt < 5)
{
	$nb_captcha   = rand(1, 52);

	# Change image
	$rotationDeg  = rand(0, 60);
	$imageSource  = imagecreatefrompng('images/captcha/'.$nb_captcha.'.png');
	$transparency = imagecolorallocatealpha($imageSource, 255, 255, 255, 127);
	$imageRotate  = imagerotate($imageSource, $rotationDeg, $transparency);
	imagepng($imageRotate, 'images/captcha/captcha_' . $_SESSION['REQUEST_PAGE_TIME'] . '_' . session_id() . '_'.$cpt.'.png');

	$captcha_val  = $captcha_val.$captcha[$nb_captcha-1];

	$cpt++;
}

$_SESSION['captcha'] = hash('sha256', $captcha_val);

# Remove picture
exec('{ sleep 2m && rm /var/www/Serge/web/images/captcha/captcha_' . $_SESSION['REQUEST_PAGE_TIME'] . '_' . session_id() . '_* ; }  >> /var/www/Serge/web/logs/error.log 2>&1 &');
?>
