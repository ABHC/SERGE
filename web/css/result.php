<?php
header('content-type: text/css');
ob_start('ob_gzhandler');
header('Cache-Control: max-age=31536000, must-revalidate');

session_start();

include('../model/connection_sql.php');

include('../model/read.php');

$checkCol = array(array('id', '=', $_SESSION['id'], ''));
$backgroundName = read('users_table_serge', 'background_result', $checkCol, '', $bdd);

$checkCol = array(array('name', '=', $backgroundName[0]['background_result'], ''));
$background = read('background_serge', 'filename', $checkCol, '', $bdd);

include('style.php');

include('nav.php');
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
	background: url('../images/background/<?php echo $background[0]['filename']; ?>') center no-repeat;
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
	background-color: rgba(0, 0, 0, 0.15);
}

h1
{
	font-size: 30px;
	color: #f9f9ff;
	text-transform: uppercase;
	font-weight: lighter;
	text-align: center;
	margin-bottom: 15px;
}

* input[type='submit']
{
	font-size: 0px;
}

input[type='text']
{
	width: 40%;
	height: 25px;
	color: rgb(245,245,245);
	text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);
	font-size: 17px;
	background-color: rgba(0,0,0,0.4);
	border: 1px solid rgba(255,255,255,0.4);
	border-radius: 3px;
	margin: 20px;
	padding-left: 5px;
	padding-right: 5px;
}

input[type='text']::placeholder
{
	color: rgb(190,190,190);
}

.selectResultsType
{
	position: absolute;
	display: flex;
	flex-direction: column;
	justify-content: flex-start;
	align-items: flex-start;
	left: 0;
	width: 50px;
	height: auto;
	margin-top: 20px;
}

.selectResultsType a
{
	width: 70px;
	height: 98px;
	background-color: rgba(0, 0, 0, 0.5);
	text-decoration: none;
}

.selectResultsType a:hover
{
	background-color: rgba(0, 0, 0, 0);
}

.selectResultsType .active
{
	background-color: rgba(0, 0, 0, 0);
}

.selectResultsType a div
{
	display: flex;
	justify-content: center;
	align-items: flex-end;
	width: 40px;
	height: 88px;
	color: #f9f9ff;
	text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);
	text-decoration: none;
	text-transform: uppercase;
	font-size: 10px;
}

.selectResultsTypeNews
{
	margin: auto;
	background: url('../images/icoNews.png') center no-repeat;
	background-size: contain;
}

.selectResultsTypeSciences
{
	margin: auto;
	background: url('../images/icoSciences.png') center no-repeat;
	background-size: contain;
}

.selectResultsTypePatents
{
	margin: auto;
	background: url('../images/icoPatents.png') center no-repeat;
	background-size: contain;
}

form.formSearch
{
	display: flex;
	flex-direction: row;
	justify-content: center;
	align-items: center;
	width: 100%;
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
	flex: 1;
	padding: 15px 0 15px 0;
	text-align: left;
	font-weight: normal;
	font-size: 12px;
	color: #f9f9ff;
	text-transform: uppercase;
	text-align: center;
	text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);
}

th a
{
	text-decoration: none;
	color: #f9f9ff;
}

th:nth-child(6)
{
	width: 50px;
}

th:nth-child(7)
{
	width: 50px;
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
	width: 30%;
}

th:nth-child(3)
{
	width: 20%;
}

th:last-child
{
	width: 50px;
}

td
{
	flex: 1;
	padding: 15px 0 15px 0;
	text-align: center;
	vertical-align:middle;
	font-weight: normal;
	font-size: 12px;
	color: #f9f9ff;
	border-bottom: solid 1px rgba(255,255,255,0.1);
	text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);
}

td a
{
	text-decoration: none;
	color: #f9f9ff;
}

td img
{
	width: 34px;
	height: auto;
}

td:nth-child(1)
{
	width: 40px;
}


td:nth-child(2)
{
	width: 30%;
	text-align: left;
	padding-left: 5px;
}

td:nth-child(3)
{
	width: 20%;
	padding-left: 5px;
}

td:nth-child(6)
{
	width: 50px;
}

td:nth-child(7)
{
	width: 50px;
}

td:last-child
{
	width: 50px;
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
	width: 26px;
	height: 34px;
}

tr:nth-child(2n)
{
	background-color: rgba(255,255,255,0.08);
}

.queryContainer
{
	display: flex;
	justify-content: center;
	align-items: center;
	flex-wrap: wrap;
	padding: 1px;
}

.queryParenthesisView
{
	display: flex;
	justify-content: center;
	align-items: center;
	color: rgba(245, 245, 245, 0.6);
	font-size: 15px;
	margin-top: -3px;
	margin-left: -2px;
}

.queryTypeView
{
	display: flex;
	justify-content: center;
	align-items: center;
	width: fit-content;
	background-color: rgba(60,60,60,0.4);
	border-radius: 3px;
	border: 1px solid rgba(255,255,255,0.2);
	padding: 1px;
	text-align: center;
	font-size: 9px;
	margin: 1px 0 1px 0;
	margin-right: 3px;
}

.queryKeywordView
{
	display: flex;
	justify-content: center;
	align-items: center;
	width: fit-content;
	background-color: rgba(255,255,255,0.2);
	border-radius: 3px 3px 3px 3px;
	border: 1px solid rgba(255,255,255, 0.15);
	padding: 1px;
	text-align: center;
	font-size: 9px;
	margin: 1px 3px 1px 0;
}

.queryAndView
{
	display: flex;
	justify-content: center;
	align-items: center;
	width: 19px;
	height: 19px;
	border-radius: 50%;
	border: 1px solid rgba(255,255,255, 0.15);
	background-color: rgba(99,49,149, 0.55);
	color: #f9f9ff;
	font-size: 9px;
	margin-right: 3px;
}

.queryOrView
{
	display: flex;
	justify-content: center;
	align-items: center;
	width: 19px;
	height: 19px;
	border-radius: 50%;
	border: 1px solid rgba(255,255,255, 0.15);
	background-color: rgba(0,149,109, 0.55);
	color: #f9f9ff;
	font-size: 9px;
	margin-right: 3px;
}

.queryNotView
{
	display: flex;
	justify-content: center;
	align-items: center;
	width: 19px;
	height: 19px;
	border-radius: 50%;
	border: 1px solid rgba(255,255,255, 0.15);
	background-color: rgba(139, 19, 19, 0.55);
	color: #f9f9ff;
	font-size: 9px;
	margin-right: 3px;
}

.pages
{
	display: flex;
	flex-direction: row;
	justify-content: space-around;
	align-items: center;
	color: #f9f9ff;
}

.pageNumber
{
	display: flex;
	justify-content: center;
	align-items: center;
	min-width: 30px;
	height: 30px;
	margin-left: 10px;
	margin-right: 10px;
	color: #f9f9ff;
	background-color: rgba(0, 0, 0, 0.5);
	border: 1px solid rgba(255,255,255,0.15);
	text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);
}

a.pageNumber
{
	text-decoration: none;
}

a.pageNumber.current
{
	font-weight: bold;
	border: 1px solid rgba(255,255,255,0.5);
}

a.pageNumber.speedPage
{
	font-weight: bold;
}

/*cellspacing="0" border="0"*/
table
{
	border-spacing: 0;
	border: none;
}

<?php
include('footer.php');
?>
