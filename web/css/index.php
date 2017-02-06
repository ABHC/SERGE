<?php
header('content-type: text/css');
ob_start('ob_gzhandler');
header('Cache-Control: max-age=31536000, must-revalidate');

include_once('../model/connexion_sql.php');

include_once('../model/design.php');

include_once('style.php');

include_once('nav.php');
?>

.body
{
	position: relative;
	margin-top: 100vh;
}

.background
{
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100vh;
	margin: 0;
	background: url(../images/background/Earth.svg) scroll no-repeat center;
	background-size: cover;
	z-index: -1;
}

.tab
{

}

<?php
include_once('footer.php');
?>
