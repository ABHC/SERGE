<?php
header('content-type: text/css');
ob_start('ob_gzhandler');
header('Cache-Control: max-age=31536000, must-revalidate');

//include('../model/connexion_sql.php');

//include('../model/design.php');

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

.connection
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

.title_connection
{
	font-size: 23px;
	font-weight: bold;
	margin-top: 5px;
	margin-bottom: 10px;
}

.title_form_connection
{
	font-size: 16px;
}

a.text_connection
{
	text-decoration: none;
	color: rgb(230,230,230);
}

.submit_connection
{
	width: 100%;
	background-color: rgba(84,26,102, 0.55);
	cursor: pointer;
	color: rgb(230,230,230);
	margin-top: 10px;
}

.submit_connection:hover
{
	background-color: rgba(91, 37, 108, 0.75);
	color: rgb(255,255,255);
}

/* Responsive */
@media all and (max-width: 1000px)
{
	.body
	{
		min-height: calc(100vh - 500px);
	}

	.connection
	{
		width: 80%;
	}

	.connection a
	{
		font-size: 30px;
	}

	input
	{
		height: 40px;
		font-size: 28px;
	}

	.title_connection
	{
		font-size: 36px;
	}

	.title_form_connection
	{
		font-size: 30px;
	}

	.submit_connection
	{
		height: 50px;
		font-size: 30px;
	}
}

<?php
include('footer.php');
?>
