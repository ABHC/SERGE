<?php
# User need to be connected to access to this page
if (!isset($_SESSION['pseudo']))
{
	$_SESSION['redirectFrom'] = preg_replace("/[^a-zA-Z0-9?=&]/", "", $_SERVER['REQUEST_URI']);
	header('Location: connection');
}
?>
