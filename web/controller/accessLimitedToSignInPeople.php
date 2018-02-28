<?php
# User need to be connected to access to this page
if (empty($_SESSION['pseudo']) || $_SESSION['ip'] !== $_SERVER['REMOTE_ADDR'] || $_SESSION['user-agent'] !== $_SERVER['HTTP_USER_AGENT'])
{
	$redirect = preg_replace("/[^a-zA-Z0-9?=&]/", '', $_SERVER['REQUEST_URI']);

	session_destroy();
	session_start();

	$_SESSION['redirectFrom'] = $redirect;

	header('Location: connection');
	die();
}
?>
