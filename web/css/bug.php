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

form
{
	width: 100%;
}

input,
textarea
{
	width: calc(100% - 8px);
	max-width: calc(100% - 8px);
	border-radius: 3px;
	border: 1px solid rgba(255,255,255, 0.15);
	padding: 4px;
	background-color: rgba(255, 255, 255, 0.2);
	color: #f9f9ff;
}

textarea
{
	min-height: 18vh;
	min-width: calc(100% - 8px);
}

.bug
{
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	align-items: flex-start;
	border-radius: 3px;
	background-color: rgba(0,0,0,0.4);
	width: 40%;
	padding: 10px;
	color: rgb(230,230,230);
	margin-top: 15vh;
}

.title_bug
{
	font-size: 23px;
	font-weight: bold;
	margin-top: 5px;
	margin-bottom: 10px;
}

.title_form_bug
{
	font-size: 16px;
}

a.text_bug
{
	text-decoration: none;
	color: rgb(230,230,230);
}

.submit_bug
{
	width: 100%;
	max-width: 100%;
	background-color: rgba(84,26,102, 0.55);
	cursor: pointer;
	color: rgb(230,230,230);
	margin-top: 10px;
}

.submit_bug:hover
{
	background-color: rgba(91, 37, 108, 0.75);
	color: rgb(255,255,255);
}

.captcha
{
	position: relative;
	display: flex;
	flex-direction: row;
	justify-content: space-around;
	align-items: center;
	width: 100px;
	margin-left: calc(100% - 100px);
	margin-left: 0px;
	border-radius: 0px 3px 3px 0px;
	border: 1px solid rgba(255,255,255, 0.15);
	background-color: rgba(255, 255, 255, 0.2);
	background-size: cover;
}

.captcha1
{
	position: relative;
	background-image: url(../images/captcha/captcha1.png?t=<?php echo $_SERVER['REQUEST_TIME']; ?>);
	height: 22px;
	width: 22px;
	background-size: cover;
}

.captcha2
{
	position: relative;
	background-image: url(../images/captcha/captcha2.png?t=<?php echo $_SERVER['REQUEST_TIME']; ?>);
	height: 22px;
	width: 22px;
	background-size: cover;
}

.captcha3
{
	position: relative;
	background-image: url(../images/captcha/captcha3.png?t=<?php echo $_SERVER['REQUEST_TIME']; ?>);
	height: 22px;
	width: 22px;
	background-size: cover;
}

.captcha4
{
	background-image: url(../images/captcha/captcha4.png?t=<?php echo $_SERVER['REQUEST_TIME']; ?>);
	position: relative;
	height: 22px;
	width: 22px;
	border-radius: 0px 2px 2px 0px;
	background-size: cover;
}

.captcha_field
{
	width: calc(100% - 100px);
	border-radius: 2px 0px 0px 2px;
}

/* Responsive */
@media all and (max-width: 1000px)
{
	.body
	{
		min-height: calc(100vh - 500px);
	}

	.bug
	{
		width: 80%;
	}

	.bug a
	{
		font-size: 30px;
	}

	input
	{
		height: 40px;
		font-size: 28px;
	}

	.title_bug
	{
		font-size: 36px;
	}

	.title_form_bug
	{
		font-size: 30px;
	}

	.captcha
	{
		width: 140px;
	}

	.captcha1,
	.captcha2,
	.captcha3,
	.captcha4
	{
		height: 30px;
		width: 30px;
	}

	.submit_bug
	{
		height: 50px;
		font-size: 30px;
	}
}

<?php
include('footer.php');
?>
