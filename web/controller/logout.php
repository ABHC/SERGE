<?php
	session_destroy();
	session_regenerate_id();
	header('Location: connection');
	die();
?>
