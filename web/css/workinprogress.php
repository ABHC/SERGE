<?php
header('content-type: text/css');
ob_start('ob_gzhandler');
header('Cache-Control: max-age=31536000, must-revalidate');

include('style.php');

include('nav.php');
?>

.background
{
	position: fixed;
	top: 0;
	left: 0;
	margin: 0;
	width: 100%;
	height: 100vh;
	z-index: -1;
	background: url('../images/background/Space02.jpg') center no-repeat;
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
	background-color: rgba(0, 0, 0, 0.1);
}

.body
{
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	min-height: calc(100vh - 75px);
	color: #fff;
	text-shadow: 0 0 3px rgba(0, 0, 0, 0.8);
	text-transform: uppercase;
	font-size: 30px;
	font-family: ubuntu;
	letter-spacing: 1px;
}

#timer
{
	font-size: 75px;
}

.name
{
	font-size: 55px;
	margin-top: 20px;
}

form
{
	display: flex;
	flex-direction: row;
	justify-content: center;
	align-items: center;
	margin-top: 10px;
}

input
{
	width: 350px;
	height: 22px;
	border-radius: 3px;
	border: 1px solid rgba(255,255,255, 0.15);
	padding: 4px;
	background-color: rgba(0,0,0,0.4);
	color: #fff;
}

input::placeholder
{
	color: rgb(190,190,190);
}

.submit
{
	width: 35px;
	height: 35px;
	border: 1px solid rgba(255,255,255, 0.15);
	background: url('../images/>.png') center no-repeat;
	background-size: contain;
	background-color: rgba(84,26,102, 0.55);
	cursor: pointer;
	color: rgb(230,230,230);
	border-radius: 50%;
	font-weight: bold;
	font-size: 0;
}

<?php
include('footer.php');
?>
