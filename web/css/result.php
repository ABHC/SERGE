<?php
header('content-type: text/css');
ob_start('ob_gzhandler');
header('Cache-Control: max-age=31536000, must-revalidate');

include_once('../model/connexion_sql.php');

include_once('../model/design.php');

include_once('style.php');

include_once('nav.php');
?>

.body
{
	display: flex;
	flex-direction: column;
	align-items: center;
	min-height: 100vh;
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
	background: url('../images/background/Skyscraper01.jpg') center no-repeat;
	background-size: cover;
	/*filter: blur(2px) grayscale(100%);*/
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

h1
{
	font-size: 30px;
	color: #fff;
	text-transform: uppercase;
	font-weight: 300;
	text-align: center;
	margin-bottom: 15px;
}

table
{
	width:100%;
	table-layout: fixed;
	word-wrap: break-word;
	background-color: rgba(0, 0, 0, 0.45);
}

.table-header
{
	width: 80%;
	background-color: rgba(255,255,255,0.3);
	border: 1px solid rgba(255,255,255,0.05);
}

.table-content
{
	width: 80%;
	height: auto;
	overflow-x: auto;
	margin-top: 0px;
	border: 1px solid rgba(255,255,255,0.1);
	margin-bottom: 100px;
}

th
{
	width: 13%;
	padding: 15px 0 15px 0;
	text-align: left;
	font-weight: 500;
	font-size: 12px;
	color: #fff;
	text-transform: uppercase;
	text-align: center;
}

th a
{
	text-decoration: none;
	color: #fff;
}

th:nth-child(1)
{
	width: 27%;
}

th:last-child
{
	width: 8%;
}

td
{
	width: 13%;
	padding: 15px 0 15px 0;
	text-align: center;
	vertical-align:middle;
	font-weight: 300;
	font-size: 12px;
	color: #fff;
	border-bottom: solid 1px rgba(255,255,255,0.1);
}

td:nth-child(1)
{
	width: 27%;
	text-align: left;
	padding-left: 5px;
}

td:last-child
{
	width: 8%;
}

a.wikiLogo
{
	width: 25px;
	height: 25px;
	cursor: pointer;
}

a.wikiLogo img
{
	width: 25px;
	height: 25px;
}

tr:nth-child(2n)
{
	background-color: rgba(255,255,255,0.08);
}

<?php
include_once('footer.php');
?>
