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
	background: url('../images/background/Skyscrapers01.jpg') center no-repeat;
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

.tableContainer
{
	display: flex;
	flex-direction: column;
	justify-content: flex-start;
	align-items: center;
}

table
{
	width: 100%;
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
	margin-bottom: 40px;
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
	text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);
}

th a
{
	text-decoration: none;
	color: #fff;
}

.submit
{
	width: 20px;
	height: 25px;
	background: url(../images/Trash.png) center no-repeat;
	background-size: contain;
	border: none;
	outline: none;
	cursor: pointer;
}

th:nth-child(1)
{
	width: 40px;
}

th:nth-child(2)
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
	text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);
}

td a
{
	text-decoration: none;
	color: #fff;
}

td:nth-child(1)
{
	width: 40px;
}


td:nth-child(2)
{
	width: 27%;
	text-align: left;
	padding-left: 5px;
}

td:last-child
{
	width: 8%;
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
	margin-left: -15px;
	width: 10px;
	height: 10px;
}

[type="checkbox"]:not(:checked) + label:before
{
	content: '';
	position: absolute;
	left: 0;
	top: 0;
	height: 10px;
	width: 10px;
	border: 1px solid rgba(255,255,255, 0.15);
	background-color: rgba(255, 255, 255, 0.15);
	transition: all .3s;
}


[type="checkbox"]:checked + label:before
{
	content: '';
	position: absolute;
	left: 0;
	top: 0;
	height: 10px;
	width: 10px;
	border: 1px solid rgba(255,255,255, 0.15);
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
	font-size: 20px;
	color: #d5d3d3;
	text-align: center;
}

.deleteLink
{
	display: inline-block;
	width: 30px;
	height: 30px;
	background: url(../images/TrashDesactivated.png) center no-repeat;
	background-size: 20px;
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

.pages
{
	display: flex;
	flex-direction: row;
	justify-content: space-around;
	align-items: center;
}

.pageNumber
{
	display: flex;
	justify-content: center;
	align-items: center;
	width: 30px;
	height: 30px;
	margin-left: 10px;
	margin-right: 10px;
	color: #fff;
	background-color: rgba(0, 0, 0, 0.5);
	border: 1px solid rgba(255,255,255,0.15);
	text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);
}

a.pageNumber
{
	text-decoration: none;
}

<?php
include_once('footer.php');
?>