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
	display: flex;
	flex-direction: column;
	justify-content: space-around;
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

.price
{
	width: 100%;
	font-size: 50px;
	text-align: center;
}

.purchase
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
}

.title_purchase
{
	font-size: 23px;
	font-weight: bold;
	margin-top: 5px;
	margin-bottom: 10px;
}

.title_form_purchase
{
	font-size: 18px;
}

a.text_purchase
{
	text-decoration: none;
	color: rgb(230,230,230);
}

.submit_purchase
{
	width: 100%;
	background-color: rgba(84,26,102, 0.55);
	cursor: pointer;
	color: rgb(230,230,230);
	margin-top: 10px;
}

.submit_purchase:hover
{
	background-color: rgba(91, 37, 108, 0.75);
	color: rgb(255,255,255);
}

.number
{
	width: 35px;
}

.alpha
{
	font-size: 16px;
	background-color: rgba(0, 0, 0, 0);
	outline: none;
	border: none;
}

a
{
	color: rgb(230,230,230);
}

[type="checkbox"]:not(:checked),
[type="checkbox"]:checked
{
	display: none;
}

[type="checkbox"]:not(:checked) + label,
[type="checkbox"]:checked + label
{
	position: relative;
	top: 0;
	margin-right: 20px;
	width: 15px;
	height: 15px;
}

[type="checkbox"]:not(:checked) + label:before
{
	content: '';
	position: absolute;
	left: 0;
	top: 0;
	height: 15px;
	width: 15px;
	border: 1px solid rgba(255,255,255, 0.15);
	border-radius: 3px;
	background-color: rgba(255, 255, 255, 0.15);
	transition: all .3s;
}


[type="checkbox"]:checked + label:before
{
	content: '';
	position: absolute;
	left: 0;
	top: 0;
	height: 15px;
	width: 15px;
	border: 1px solid rgba(255,255,255, 0.15);
	border-radius: 3px;
	background-color: rgba(255, 255, 255, 0.3);
	transition: all .3s;
}

[type="checkbox"]:not(:checked) + label:after
{
	content: '';
	position: absolute;
	top: 0px;
	left: 0px;
}

[type="checkbox"]:checked + label:after
{
	content: '✓';
	position: absolute;
	top: -8px;
	left: -1px;
	font-size: 25px;
	color: #d5d3d3;
	text-align: center;
}

h2
{
	font-size: 40px;
	font-weight: bold;
	letter-spacing: 1pt;
	margin-bottom: 0;
}

h3
{
	font-size: 28px;
	font-weight: 200;
	letter-spacing: 1pt;
	margin-top: 15px;
	margin-bottom: 0;
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

.premiumFunctionalityLine
{
	display: flex;
	flex-direction: row;
	justify-content: center;
	align-items: center;
	width: 90%;
	height: 140px;
	margin-bottom: 8vw;
}

.premiumFunctionalityLine > div
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
	color: rgb(200, 200, 200);
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

/**/
<?php
include_once('footer.php');
?>
