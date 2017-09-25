<?php
# User need to be connected to access to this page
if (empty($_SESSION['pseudo']) || $_SESSION['ip'] !== $_SERVER['REMOTE_ADDR'] || $_SESSION['user-agent'] !== $_SERVER['HTTP_USER_AGENT'])
{
	session_destroy();
	session_regenerate_id();
	$_SESSION['redirectFrom'] = preg_replace("/[^a-zA-Z0-9?=&]/", '', $_SERVER['REQUEST_URI']);
	header('Location: connection');
	die();
}
?>
