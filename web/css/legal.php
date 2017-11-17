<?php
header('content-type: text/css');
ob_start('ob_gzhandler');
header('Cache-Control: max-age=31536000, must-revalidate');

include('style.php');

include('nav.php');
?>

.body
{
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	align-items: center;
	min-height: 100vh;
	color: #fff;
}

.background
{
	position: fixed;
	top: 0;
	left: 0;
	margin: 0;
	width: 100%;
	height: 100vh;
	z-index: -1;
	background: url('../images/background/dark.png') center no-repeat;
	background-size: cover;
}

.subBackground
{
	position: fixed;
	top: 0;
	left: 0;
	margin: 0;
	width: 100%;
	height: 100vh;
	z-index: -1;
}

.legalText
{
	width: 80%;
	margin-top: 100px;
}

<?php
include('footer.php');
?>
