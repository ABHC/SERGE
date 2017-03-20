<?php
header('content-type: text/css');
ob_start('ob_gzhandler');
header('Cache-Control: max-age=31536000, must-revalidate');

//include_once('../model/connexion_sql.php');

//include_once('../model/design.php');

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

.tabContainer
{
	display: flex;
	flex-direction: row;
	justify-content: space-between;
	align-items: center;
	width: 80%;
	margin-left: 10%;
}

.tab
{
	height: 60vh;
	width: 30%;
	background-color: rgba(0,0,0, 0.4);
	border-radius: 3px;
}

.signet
{
	height: 20px;
	width: 80px;
	border: 1px solid rgab(255,255,255, 0.1);
	background-color: rgba(0,99,149, 0.55);
	margin-top: 20%;
	border-radius: 0 3px 3px 0;
	color: #fff;
	padding-left: 10px;
}

<?php
include_once('footer.php');
?>
