.nav
{
	display: flex;
	justify-content: space-around;
	margin: 0;
	height: 75px;
}

.nav-block-container
{
	display: flex;
	justify-content: flex-end;
	flex: 1;
}

.nav-block
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

.nav-block:hover
{
	background: none;
	color: rgb(255, 255, 255);
	border-bottom: 1px solid rgba(255,255,255,0);
}

.nav-cairn-devices-logo
{
	width: 70px;
	height: 70px;
	margin-right: 10px;
	margin-left: 10px;
	background: url(/images/SERGE_logo_norm_nav.svg) center no-repeat;
	background-size: cover;
}

.nav-result-serge-logo
{
	width: 70px;
	height: 70px;
	margin-right: 10px;
	margin-left: 10px;
	background: url(/images/navResultsIco.svg) no-repeat;
	background-size: contain;
}

.nav-serge-setting-logo
{
	width: 70px;
	height: 70px;
	margin-right: 10px;
	margin-left: 10px;
	background: url(/images/navSettingsIco.svg) no-repeat;
	background-size: contain;
}

.nav-wiki-logo
{
	width: 70px;
	height: 70px;
	margin-right: 10px;
	margin-left: 10px;
	background: url(/images/navWikiIco.svg) no-repeat;
	background-size: contain;
}

.nav-title
{
	font-size: 15px;
	text-decoration: none;
	color: rgb(240, 240, 240);
	text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);
}

.nav-logo
{
	display: flex;
	justify-content: flex-start;
	align-items: center;
	flex: 0.7;
	background-color: rgba(0, 0, 0, 0.6);
	border-bottom: 1px solid rgba(255,255,255,0.25);
	text-decoration: none;
}

.nav-block.active
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

	.nav-title
	{
		font-size: 25px;
		text-align: center;
	}

	.nav-logo > .nav-title
	{
		width: 60%;
	}

	.nav-cairn-devices-logo
	{
		width: 90px;
		height: 90px;
		margin-right: 5px;
	}

	.nav-result-serge-logo
	{
		width: 124px;
	}

	.nav-serge-setting-logo
	{
		width: 79px;
		height: 79px;
	}

	.nav-wiki-logo
	{
		width: 95px;
		height: 79px;
	}
}
