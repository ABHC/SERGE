<?php
if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE']))
{
	$language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
	$language = strtoupper($language[0] . $language[1]);
	$language = preg_replace("/[^A-Z]/", "", $language);
}

if (!empty($_SESSION['lang']))
{
	$language = $_SESSION['lang'];
}

if (empty($language) || $language != 'FR' || $language != 'EN')
{
	$language = 'EN';
}
?>
