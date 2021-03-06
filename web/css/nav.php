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
	background-color: rgba(0, 0, 0, 0.6);
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
	background: url(../images/SERGE_logo_norm_nav.png) center no-repeat;
	background-size: cover;
}

.navResultSergeLogo
{
	width: 70px;
	height: 53px;
	margin-right: 10px;
	margin-left: 10px;
	background: url(../images/navResultIco.png) no-repeat;
	background-size: contain;
}

.navSergeSettingLogo
{
	width: 92px;
	height: 55px;
	margin-right: 10px;
	margin-left: 10px;
	background: url(../images/navSettingsIco.png) no-repeat;
	background-size: contain;
}

.navWikiLogo
{
	width: 60px;
	height: 44px;
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
	text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);
}

.navlogo
{
	display: flex;
	justify-content: flex-start;
	align-items: center;
	flex: 1;
	background-color: rgba(0, 0, 0, 0.6);
	border-bottom: 1px solid rgba(255,255,255,0.25);
	text-decoration: none;
}

.navBlock.active
{
	background: none;
	color: rgb(255, 255, 255);
	border-bottom: none;
}

@media all and (max-width: 1000px)
{
	.nav
	{
		height: 140px;
	}

	.navTitle
	{
		font-size: 25px;
		text-align: center;
	}

	.navlogo > .navTitle
	{
		width: 60%;
	}

	.navCairnDevicesLogo
	{
		width: 90px;
		height: 90px;
		margin-right: 5px;
	}

	.navResultSergeLogo
	{
		width: 124px;
	}

	.navSergeSettingLogo
	{
		width: 79px;
		height: 79px;
	}

	.navWikiLogo
	{
		width: 95px;
		height: 79px;
	}
}
