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
	justify-content: center;
	align-items: center;
	min-height: 100vh;
	color: #f9f9ff;
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

.window
{
	display: flex;
	flex-direction: row;
	justify-content: center;
	align-items: center;
	border-radius: 3px;
	background-color: rgba(0,0,0,0.4);
	width: 60%;
	padding: 10px;
	color: rgb(230,230,230);
	margin-bottom: 5vh;
	font-size: 25px;
}

.emoticon
{
	position: relative;
	background-image: url(../images/Desapoinhat.png);
	width: 30%;
	height: 200px;
	background-size: contain;
	background-repeat: no-repeat;
	background-position: center;
	margin-right: 50px;
}

<?php
include('footer.php');
?>
