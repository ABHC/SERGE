<?php
header('content-type: text/css');
ob_start('ob_gzhandler');
header('Cache-Control: max-age=31536000, must-revalidate');

include('style.php');

include('nav.php');
?>

.body
{
	position: relative;
	margin-top: 35vh;
}

.backgroundImage
{
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100vh;
	margin: 0;
	background: url(../images/background/Earth.png) scroll no-repeat center;
	background-size: cover;
	z-index: -1;
}

.backgroundDetails
{
	position: relative;
	width: 100%;
}

h2
{
	font-size: 4.8vw;
	font-weight: 200;
	letter-spacing: 1pt;
	font-family: ubuntu;
	margin-top: 19vh;
	margin-bottom: 5px;
	text-align: center;
	color: rgb(255, 255, 255);
	text-shadow: 0 0 5px rgb(0, 0, 0);
}

h3
{
	width: 70%;
	font-size: 1.8vw;
	font-weight: 200;
	letter-spacing: 1pt;
	font-family: ubuntu;
	margin-top: 5px;
	margin-bottom: 14vh;
	margin-left: 15%;
	text-align: center;
	color: rgb(255, 255, 255);
	text-shadow: 0 0 5px rgb(0, 0, 0);
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

.buttonArea
{
	display: flex;
	flex-direction: row;
	align-items: center;
	width: 80%;
	height: 70px;
	margin-left: 10%;
	z-index: 3;
}

.line
{
	width: 40%;
	height: 1px;
	background-color: rgba(255, 255, 255, 0.15);
}

.buttonTry
{
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	width: 20%;
	height: 80%;
	text-align: center;
	background-color: rgba(84,26,102, 0.55);
	border: 1px solid rgba(205, 205, 205, 0.05);
	padding: 5px;
	margin-left: 15px;
	margin-right: 15px;
}

.buttonTry:hover
{
	background-color: rgba(91, 37, 108, 0.75);
}

a.buttonTry
{
	text-decoration: none;
	color: rgb(247,247,247);
	font-size: 22px;
}

.functionality
{
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	width: 100%;
	margin-top: 50px;
}

.functionalityLine
{
	display: flex;
	flex-direction: row;
	justify-content: center;
	align-items: center;
	width: 90%;
	height: 140px;
	margin-bottom: 8vw;
}

.functionalityLine > div
{
	display: flex;
	flex-direction: row;
	justify-content: flex-start;
	width: 47%;
	min-width: 400px;
	margin-right: 4%;
	margin-left: 4%;
}

.iconRSS
{
	width: 140px;
	height: 140px;
	background: url('../images/iconFuncRss.png') no-repeat;
	background-size: contain;
	background-position: left bottom;
}

.iconPatent
{
	width: 140px;
	height: 140px;
	background: url('../images/iconFuncPatent.png') no-repeat;
	background-size: contain;
	background-position: left bottom;
}

.iconScience
{
	width: 140px;
	height: 140px;
	background: url('../images/iconFuncScience.png') no-repeat;
	background-size: contain;
	background-position: left bottom;
}

.iconMail
{
	width: 140px;
	height: 140px;
	background: url('../images/iconFuncMail.png') no-repeat;
	background-size: contain;
	background-position: left bottom;
}

.iconOption
{
	width: 140px;
	height: 140px;
	background: url('../images/iconFuncOption.png') no-repeat;
	background-size: contain;
	background-position: left bottom;
}

.iconHistory
{
	width: 140px;
	height: 140px;
	background: url('../images/iconFuncHistory.png') no-repeat;
	background-size: contain;
	background-position: left bottom;
}

.iconSMS
{
	width: 140px;
	height: 140px;
	background: url('../images/iconFuncSMS.png') no-repeat;
	background-size: contain;
	background-position: left bottom;
}

.iconTwitter
{
	width: 140px;
	height: 140px;
	background: url('../images/iconFuncTwitter.png') no-repeat;
	background-size: contain;
	background-position: left bottom;
}

.iconWiki
{
	width: 140px;
	height: 140px;
	background: url('../images/iconFuncWiki.png') no-repeat;
	background-size: contain;
	background-position: left bottom;
}

.iconStats
{
	width: 140px;
	height: 140px;
	background: url('../images/iconFuncStats.png') no-repeat;
	background-size: contain;
	background-position: left bottom;
}

.functionalityText
{
	display: flex;
	flex-direction: column;
	justify-content: flex-end;
	align-items: flex-start;
	width: 100%;
	height: 140px;
	overflow: hidden;
	font-size: 15px;
	color: rgb(220, 220, 220);
}

.functionalityText div
{
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: flex-start;
	height: 120px;
	width: 100%;
}

h5
{
	font-size: 20px;
	margin-top: 0px;
	margin-bottom: 5px;
	color: rgb(245, 245, 245);
}

.titleButton
{
	display: flex;
	justify-content: center;
	align-items: center;
	text-align: center;
	width: 30%;
	background-color: rgb(230, 230, 230);
	padding: 10px;
	margin-top: 100px;
	margin-bottom: 5px;
	margin-left: 35%;
	border: 1px solid rgba(40,40,40,0.15);
	border-radius: 3px;
	font-size: 20px;
	color: rgb(40, 40, 40);
	cursor: default;
}

/* Sign up */
#signup
{
	width: 100%;
	padding-top: 30px;
}

form
{
	width: 100%;
}

input
{
	width: calc(100% - 8px);
	border-radius: 3px;
	border: 1px solid rgba(255,255,255, 0.15);
	padding: 4px;
	background-color: rgba(255, 255, 255, 0.2);
	color: #fff;
}

.inscription
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
	margin-left: 30%;
}

.title_inscription
{
	font-size: 23px;
	font-weight: bold;
	margin-top: 5px;
	margin-bottom: 10px;
}

.title_form_inscription
{
	font-size: 16px;
}

.submit_inscription
{
	width: 100%;
	background-color: rgba(84,26,102, 0.55);
	cursor: pointer;
	color: rgb(230,230,230);
	margin-top: 10px;
	border: 1px solid rgba(255,255,255, 0.15);
	border-radius: 3px;
}

.submit_inscription:hover
{
	background-color: rgba(91, 37, 108, 0.75);
	color: white;
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
	.backgroundImage
	{
		height: 52vh;
		min-height: 500px;
		max-height: 1000px;
		filter: brightness(0.8);
	}

	.backgroundDetails
	{
		height: calc(42vh - 140px);
		min-height: 360px;
		max-height: 860px;
		padding-top: 10vh;
	}

	h2
	{
		margin-top: 0;
	}

	h3
	{
		font-size: 2vw;
		margin-top: 2.5vh;
		margin-bottom: 7vh;
	}

	h5
	{
		font-size: 30px;
	}

	.body
	{
		margin-top: 40px;
	}

	.buttonTry
	{
		margin-bottom: 20px;
	}

	.buttonArea
	{
		margin-top: 50px;
	}

	.titleButton
	{
		margin-top: 50px;
	}

	.functionalityLine
	{
		flex-wrap: wrap;
		height: auto;
		width: 90%;
		margin-bottom: 0;
		overflow: hidden;
	}

	.functionalityLine > div
	{
		display: flex;
		flex-direction: row;
		justify-content: center;
		width: 100%;
		min-width: 0;
		margin-right: 2%;
		margin-left: 2%;
		margin-bottom: 20px;
	}

	.functionalityLine > div > div:first-child
	{
		height: 200px;
		width: 200px;
	}

	.functionalityText
	{
		height: 200px;
		font-size: 25px;
	}

	.functionalityText div
	{
		height: auto;
	}

	.inscription
	{
		width: 80%;
		margin-left: 10%;
	}

	input
	{
		height: 40px;
		font-size: 28px;
	}

	.title_inscription
	{
		font-size: 36px;
	}

	.title_form_inscription
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

	.submit_inscription
	{
		height: 50px;
		font-size: 30px;
	}
}
<?php
include('footer.php');
?>
