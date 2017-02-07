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
	flex-direction: column;
	justify-content: space-between;
	margin: 0;
	background-color: rgba(0, 0, 0, 0.55);
	border-top: 1px solid rgba(255,255,255,0.2);
	/* background-color: #131425; */
}

.footerContainer1
{
	display: flex;
	justify-content: space-between;
}

.footerContainer2
{
	display: flex;
	justify-content: center;
	align-items: center;
	background-color: rgba(0,99,149, 0.55);
	color: grey;
	font-size: 12px;
	min-height: 60px;
	text-align: center;
	border-top: 1px solid rgba(255,255,255,0.1);
}

.links
{
	display: flex;
	flex-direction: column;
	justify-content: space-around;
	min-width: 60%;
}

.roundLinks
{
	display: flex;
	justify-content: center;
	margin-top: 20px;
}

.roundLinks a
{
	width: 50px;
	height: 50px;
	border-radius: 50%;
	background-color: rgba(0,99,149, 0.55);
	margin-left: 15px;
	margin-right: 15px;
}

.subLinksContainer
{
	display: flex;
	justify-content: space-around;
}

.subLinks
{
	display: flex;
	flex-direction: column;
	justify-content: space-around;
	margin: 5px;
}

.subLinks a
{
	text-decoration: none;
	color: rgb(255, 255, 255);
	margin: 5px;
}

.social
{
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: flex-end;
	min-width: 20%;
	margin-top: 5px;
	margin-bottom: 5px;
}

.social a img
{
	width: 40px;
	height: 40px;
}

.copyright
{
	display: flex;
	align-items: center;
	height: 1.6vw;
	width: 20%;
	margin-top: 20px;
	padding: 3px;
	border-radius: 0 3px 3px 0;
	border: 1px solid rgba(255,255,255,0.1);
	background-color: rgba(0,99,149, 0.55);
}

.copyright a
{
	text-decoration: none;
	font-size: 1.4vw;
	text-align: center;
	color: rgb(255,255,255);
}
