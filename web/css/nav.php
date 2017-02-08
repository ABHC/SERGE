<?php
header('content-type: text/css');
ob_start('ob_gzhandler');
header('Cache-Control: max-age=31536000, must-revalidate');

include_once('../model/connexion_sql.php');

include_once('../model/design.php');

?>

.nav
{
	display: flex;
	justify-content: space-around;
	margin: 0;
	height: 75px;
}

.navBlockContainer
{
	display: flex;
	justify-content: flex-end;
}

.navBlock
{
	display: flex;
	justify-content: flex-start;
	align-items: center;
	padding-right: 10px;
	flex: 1;
	background-color: rgba(0, 0, 0, 0.5);
	border-bottom: 1px solid rgba(255,255,255,0.25);
	text-decoration: none;
	min-width: 150px;
}

.navBlock:hover
{
	background: none;
	color: rgb(255, 255, 255);
	border-bottom: 1px solid rgba(255,255,255,0);
}

.navCairnDevicesLogo
{
	width: 70px;
	height: 70px;
	margin-right: 10px;
	margin-left: 10px;
	background: url(../images/SERGE_logo_final_dark_background.svg) center no-repeat;
	background-size: cover;
	border-radius: 50%;
}

.navResultSergeLogo
{
	width: 70px;
	height: 50px;
	margin-right: 10px;
	margin-left: 10px;
	background: url(../images/navResultIco.png) no-repeat;
	background-size: contain;
}

.navSergeSettingLogo
{
	width: 50px;
	height: 50px;
	margin-right: 10px;
	margin-left: 10px;
	background: url(../images/navSettingsIco.png) no-repeat;
	background-size: contain;
}

.navWikiLogo
{
	width: 60px;
	height: 50px;
	margin-right: 10px;
	margin-left: 10px;
	background: url(../images/navWikiIco.png) no-repeat;
	background-size: contain;
}

.navTitle
{
	font-size: 15px;
	text-decoration: none;
	color: rgb(240, 240, 240);
}

.navlogo
{
	display: flex;
	justify-content: flex-start;
	align-items: center;
	flex: 1;
	background-color: rgba(0, 0, 0, 0.5);
	border-bottom: 1px solid rgba(255,255,255,0.25);
	text-decoration: none;
}

.navBlock.active
{
	background: none;
	color: rgb(255, 255, 255);
	border-bottom: none;
}
