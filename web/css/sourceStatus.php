<?php
session_start();
header('content-type: text/css');
ob_start('ob_gzhandler');
header('Cache-Control: max-age=31536000, must-revalidate');

include('style.php');
?>

body
{
	color: #f9f9ff;
}
