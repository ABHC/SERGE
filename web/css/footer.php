<?php
header('content-type: text/css');
ob_start('ob_gzhandler');
header('Cache-Control: max-age=31536000, must-revalidate');

include_once('../model/connexion_sql.php');

include_once('../model/design.php');

?>

footer
{
	display: flex;
	justify-content: space-around;
	align-items: center;
	height: 200px;
	margin: 0;
	background-color: #131425;
}
