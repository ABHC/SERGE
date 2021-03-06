footer
{
	position: relative;
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	margin-left: 0;
	margin-right: 0;
	margin-bottom: 0;
	margin-top: 100px;
	background-color: rgba(0, 0, 0, 0.6);
	border-top: 1px solid rgba(255,255,255,0.2);
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
	background-color: rgba(84,26,102, 0.55);
	color: grey;
	font-size: 12px;
	min-height: 60px;
	text-align: center;
	border-top: 1px solid rgba(255,255,255,0.1);
}

.bugFooter
{
	position: absolute;
	bottom: 70px;
	left: 10px;
	float: left;
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
}

a.bugFooterLogo
{
	width: 90px;
	height: 105px;
	background: url('../images/bug.png') center no-repeat;
	background-size: contain;
}

.bugFooter a
{
	text-decoration: none;
	color: rgb(230,230,230);
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
	width: 80px;
	height: 80px;
	border-radius: 50%;
	margin-left: 15px;
	margin-right: 15px;
}

.roundLinks a img
{
	width: 80px;
	height: 80px;
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
	align-items: center;
	margin: 5px;
}

.subLinks a
{
	text-decoration: none;
	color: rgb(230,230,230);
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
	width: 19%;
	margin-right: 1%;
	margin-top: 20px;
	padding: 3px;
	border-radius: 0 3px 3px 0;
	border: 1px solid rgba(255,255,255, 0.1);
	background-color: rgba(84,26,102, 0.55);
}

.copyright a
{
	text-decoration: none;
	font-size: 1.3vw;
	text-align: center;
	color: rgb(230,230,230);
}

@media all and (min-width: 1200px)
{
	.copyright
	{
		display: flex;
		align-items: center;
		height: 23px;
		width: 220px;
		margin-top: 20px;
		padding: 3px;
		border-radius: 0 3px 3px 0;
		border: 1px solid rgba(255,255,255, 0.1);
		background-color: rgba(84,26,102, 0.55);
	}

	.copyright a
	{
		text-decoration: none;
		font-size: 16px;
		text-align: center;
		color: rgb(230,230,230);
	}
}

@media all and (max-width: 1000px)
{
	.footerContainer1
	{
		font-size: 25px;
	}

	.footerContainer2
	{
		font-size: 17px;
	}

	.roundLinks a
	{
		width: 120px;
		height: 120px;
	}

	.roundLinks a img
	{
		width: 119px;
		height: 119px;
	}

	.social a img
	{
		width: 63px;
		height: 63px;
	}

	.copyright a
	{
		font-size: 25px;
	}

	.copyright
	{
		height: 50px;
	}

	.bugFooter
	{
		width: 200px;
	}

	a.bugFooterLogo
	{
		width: 80px;
	}
}
