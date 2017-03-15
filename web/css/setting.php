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
	width: 50%;
	border-radius: 3px;
	border: 1px solid rgba(255,255,255, 0.15);
	padding: 4px;
	background-color: rgba(255, 255, 255, 0.2);
	color: #fff;
}

select
{
	width: calc(40% - 30px);
	max-width: fit-content;
	border-radius: 3px;
	border: 1px solid rgba(255,255,255, 0.15);
	padding: 4px;
	background-color: rgba(60,60,60,0.4);
	color: #fff;
	height: 25px;
}

select#sourceKeyword
{
	width: calc(50% - 60px);
	border-radius: 3px;
	border: 1px solid rgba(255,255,255, 0.15);
	padding: 4px;
	background-color: rgba(255,89,0,0.4);
	color: #fff;
	height: 27px;
	margin-left: 10px;
	margin-right: 10px;
	font-size: 14px;
}

h3
{
	font-size: 20px;
	margin-top: 35px;
	margin-bottom: 20px;
}

.submit
{
	width: 30px;
	height: 30px;
	border-radius: 50%;
	border: 1px solid rgba(255,255,255, 0.15);
	background: url('../images/+.png') center no-repeat;
	background-size: contain;
	background-color: rgba(0,99,149, 0.55);
	cursor: pointer;
}

.newsInput
{
	display: flex;
	justify-content: flex-start;
	align-items: center;
	margin-bottom: 15px;
}

.newsInput input[type="text"],
.newsInput input[type="url"]
{
	height: 17px;
	font-size: 14px;
}

.inlineButton
{
	display: flex;
	flex-direction: row;
	justify-content: space-around;
	align-items: center;
	width: 100%;
	height: 50px;
	margin-bottom: 30px;
}

.buttonCreatesourcePack
{
	width: 40%;
	max-width: 150px;
	height: 40px;
	color: #fff;
	text-decoration: none;
	text-align: center;
	background-color: rgba(0,170,255, 0.4);
	border-radius: 3px;
	border: 1px solid rgba(255,255,255, 0.15);
}

.buttonVisiteCommunitySourcePack
{
	width: 40%;
	max-width: 150px;
	height: 40px;
	color: #fff;
	text-decoration: none;
	text-align: center;
	background-color: rgba(0,170,127,0.4);
	border-radius: 3px;
	border: 1px solid rgba(255,255,255, 0.15);
}

.graphContainer
{
	display: flex;
	flex-direction: row;
	justify-content: flex-start;
	align-items: flex-start;
	width: 78%;
	height: 75vh;
	background-color: rgba(0, 0, 0, 0.4);
	border-radius: 3px;
	border: 1px solid rgba(255,255,255,0.1);
	margin-bottom: 30px;
	padding: 1%;
	overflow: hidden;
}

.graphSubContainer
{
	display: flex;
	flex-direction: column;
	justify-content: flex-start;
	width: 33.3%;
}

.graph
{
	display: flex;
	flex-direction: column;
	justify-content: space-around;
	align-items: flex-end;
	height: 75vh;
	width: 100%;
}

.graphSubContainer input
{
	display: none;
}

.graphSubContainer input:checked + .graph
{
	order: 1;
}

.graphSubContainer input:not(:checked) + .graph
{
	order: 2;
	display: none;
}

label.prev,
label.next
{
	display: flex;
	justify-content: center;
	align-items: center;
	width: 100%;
	height: 7%;
	background-color: rgba(255,255,255,0.1);
	text-decoration: none;
	color: rgba(255, 255, 255, 0.4);
	font-size: 30px;
	cursor: pointer;
	user-select: none;
	opacity: 0;
	transition: .7s;
}

label.prev .arrow,
label.next .arrow
{
	display: flex;
	flex-direction: row;
	justify-content: center;
	align-items: center;
	height: 3vw;
	transform: rotate(90deg);
	text-decoration: none;
	font-size: 3vw;
	color: rgba(255, 255, 255, 0.4);
	cursor: pointer;
	user-select: none;
}

label.prev:hover,
label.next:hover
{
	opacity: 1;
	transition: .5s;
}

label.prev::selection,
label.next::selection
{
	background-color: rgba(0, 0, 0, 0);
}

.divRow
{
	display: flex;
	flex-direction: row;
	justify-content: flex-start;
	align-items: stretch;
	margin-top: 20px;
	width: 80%;
}

h2
{
	width: 100%;
	text-align: center;
}

.keywordManagement
{
	display: flex;
	flex-direction: column;
	justify-content: flex-start;
	align-items: flex-start;
	width: 48%;
	padding: 10px;
	background-color: rgba(0, 0, 0, 0.4);
	border-radius: 3px;
	border: 1px solid rgba(255,255,255,0.1);
	margin-right: 15px;
}

.keywordList
{
	display: flex;
	justify-content: flex-start;
	align-items: center;
	flex-wrap: wrap;
	margin-left: 2%;
	width: 98%;
	margin-bottom: 10px;
}

.tag
{
	display: flex;
	justify-content: center;
	align-items: center;
	width: fit-content;
	border-radius: 3px 3px 3px 3px;
	border: 1px solid rgba(255,255,255, 0.15);
	padding-left: 5px;
	padding-top: 1px;
	padding-bottom: 2px;
	text-align: center;
	margin-top: 3px;
	margin-bottom: 3px;
	margin-left: 3px;
	margin-right: 3px;
}

.tag.Tactive
{
	background-color: rgba(0,170,0,0.4);
}

.Tdisable
{
	background-color: rgba(255,255,255,0.2);
}

.tag:hover
{
	background-color: rgba(0, 100, 0, 0.4);
}

.tag.Tdisable:hover
{
	background-color: rgba(240,240,240,0.2);
}

.tag:hover a
{
	color: rgb(230,230,230);
}

.tag.Tdisable:hover a
{
	color: rgba(210,210,210,0.55);
}

.tag a
{
	text-decoration: none;
	color: #fff;
	margin-right: 5px;
	margin-left: 2px;
}

.tag.Tdisable a
{
	color: rgba(230,230,230,0.55);
}

.tag a:nth-child(2)
{
	width: 12px;
	height: 12px;
	background: url('../images/Active.png') center no-repeat;
	background-size: contain;
	margin-left: 0;
	margin-right: 1px;
}

.tag a:nth-child(2):hover
{
	width: 12px;
	height: 12px;
	background: url('../images/Disable.png') center no-repeat;
	background-size: contain;
}

.tag.Tdisable a:nth-child(2)
{
	width: 12px;
	height: 12px;
	background: url('../images/Desactivated.png') center no-repeat;
	background-size: contain;
}

.tag.Tdisable a:nth-child(2):hover
{
	width: 12px;
	height: 12px;
	background: url('../images/Activate.png') center no-repeat;
	background-size: contain;
}

.tag a:nth-child(3)
{
	border-left: 1px solid rgba(255, 255, 255, 0.2);
	padding-left: 3px;
}

.tagSource.Tdisable:hover
{
	background-color: rgba(240,240,240,0.2);
}

.tagSource.Tdisable:hover a
{
	color: rgba(210,210,210,0.55);
}

.tag a:first-child
{
	width: 14px;
	height: 14px;
	background: url('../images/Trash.png') center no-repeat;
	background-size: contain;
	margin-left: -3px;
	margin-right: 2px;
}

.tag.Tdisable a:first-child
{
	width: 14px;
	height: 14px;
	background: url('../images/TrashDesactivated.png') center no-repeat;
	background-size: contain;
}

.tag a:first-child:hover
{
	width: 14px;
	height: 14px;
	background: url('../images/TrashRed.png') center no-repeat;
	background-size: contain;
}

.tagSource
{
	display: flex;
	justify-content: center;
	align-items: center;
	width: fit-content;
	border-radius: 3px 3px 3px 3px;
	border: 1px solid rgba(255,255,255, 0.15);
	padding-left: 5px;
	padding-top: 1px;
	padding-bottom: 2px;
	text-align: center;
	margin-bottom: 5px;
	margin-top: 7px;
}

.tagSource.Tactive
{
	background-color: rgba(255,89,0,0.4);
}

.tagSource.Tdisable
{
	background-color: rgba(132,99,85,0.4);
}

.tagSource.Tdisable:hover
{
	background-color:  rgba(104, 77, 67, 0.4);
}

.tagSource.Tdisable a
{
	color: rgba(230,230,230,0.55);
}

.tagSource.Tactive:hover
{
	background-color: rgba(175, 61, 0, 0.4);
}

.tagSource.Tactive:hover a
{
	color: rgb(230,230,230);
}

.tagSource a
{
	text-decoration: none;
	color: #fff;
	margin-right: 5px;
	margin-left: 2px;
}

.tagSource a:first-child
{
	width: 14px;
	height: 14px;
	background: url('../images/Trash.png') center no-repeat;
	background-size: contain;
	margin-left: -3px;
	margin-right: 2px;
}

.tagSource.Tdisable a:first-child
{
	width: 14px;
	height: 14px;
	background: url('../images/TrashDesactivated.png') center no-repeat;
	background-size: contain;
}

.tagSource a:first-child:hover
{
	width: 14px;
	height: 14px;
	background: url('../images/TrashRed.png') center no-repeat;
	background-size: contain;
}

.tagSource a:nth-child(2)
{
	width: 12px;
	height: 12px;
	background: url('../images/Active.png') center no-repeat;
	background-size: contain;
	margin-left: 0;
	margin-right: 1px;
}

.tagSource a:nth-child(2):hover
{
	width: 12px;
	height: 12px;
	background: url('../images/Disable.png') center no-repeat;
	background-size: contain;
}

.tagSource.Tdisable a:nth-child(2)
{
	width: 12px;
	height: 12px;
	background: url('../images/Desactivated.png') center no-repeat;
	background-size: contain;
}

.tagSource.Tdisable a:nth-child(2):hover
{
	width: 12px;
	height: 12px;
	background: url('../images/Activate.png') center no-repeat;
	background-size: contain;
}

.tagSource a:nth-child(3)
{
	border-left: 1px solid rgba(255, 255, 255, 0.2);
	padding-left: 3px;
}

.communicationResults
{
	width: 48%;
	padding: 10px;
	background-color: rgba(0, 0, 0, 0.4);
	border-radius: 3px;
	border: 1px solid rgba(255,255,255,0.1);
	margin-left: 2%;
}

.selectCommResult
{
	max-width: fit-content;
	font-size: inherit;
	border: none;
	background-color: rgba(0,0,0,0);
}

.selectCommResult:focus
{
	outline: none;
}

.selectCommResult option
{
	background-color: rgb(51,59,68);
	border: none;
	outline: none;
}

.Unit
{
	width: 75px;
}

.alpha
{
	font-size: 16px;
	background-color: rgba(0, 0, 0, 0);
	outline: none;
	border: none;
}

.switch
{
	position: relative;
	display: inline-block;
	width: 56px;
	height: 21px;
	margin-right: 10px;
}

.switch input
{display:none;}

.slider
{
	position: absolute;
	cursor: pointer;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background-color: rgba(255,255,255,0.2);
	border-radius: 5px;
	border: 1px solid rgba(255,255,255, 0.1);
	transition: .6s;
}

.align
{
	display: flex;
	flex-direction: row;
	justify-content: flex-start;
	align-items: center;
}

.slider:before
{
	position: absolute;
	content: "";
	height: 19px;
	width: 19px;
	left: -1px;
	bottom: 0;
	background-color: rgb(230,230,230);
	box-shadow: 0px 0px 3px #302f2f;
	border-radius: 25%;
	transition: .6s;
}

input:checked + .slider
{
	background-color: rgba(0,170,0,0.4);
}

input:focus + .slider
{
	box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before
{
	transform: translateX(37px);
}

.deleteContainer
{
	display: flex;
	flex-direction: row;
	justify-content: flex-start;
	align-items: center;
}

.deleteLogo
{
	width: 30px;
	height: 29px;
	background: url('../images/Trash.png') center no-repeat;
	background-size: 22px;
	background-color: rgb(50,50,50);
	border-left: 1px solid rgba(255,255,255, 0.15);
	border-top: 1px solid rgba(255,255,255, 0.15);
	border-bottom: 1px solid rgba(255,255,255, 0.15);
	border-radius: 3px 0 0 3px;
	cursor: default;
}

.deleteButton
{
	width: 100px;
	height: 31px;
	border-radius: 0 3px 3px 0;
	border: 1px solid rgba(255,255,255, 0.15);
	background-color: rgba(255, 0, 0, 0.5);
	margin-right: 10px;
	cursor: pointer;
	font-size: 14px;
}

.deleteButton:hover
{
	background-color: rgba(255, 0, 0, 0.55);
}

.scientificPublicationManagement
{
	width: calc(80% - 20px);
	padding: 10px;
	background-color: rgba(0, 0, 0, 0.4);
	border-radius: 3px;
	border: 1px solid rgba(255,255,255,0.1);
	margin-top: 40px;
}

.patentManagement
{
	width: calc(80% - 20px);
	padding: 10px;
	background-color: rgba(0, 0, 0, 0.4);
	border-radius: 3px;
	border: 1px solid rgba(255,255,255,0.1);
	margin-top: 40px;
}

.query
{
	flex: 0.40;
	min-width: 3.5%;
	max-width: 15%;
}

.queryType
{
	width: 130px;
	font-size: 12px;
}

.andOr
{
	margin-right: 12px;
}

[type="checkbox"]:not(:checked),
[type="checkbox"]:checked
{
	position: absolute;
	left: -9999px;
}

[type="checkbox"]:not(:checked) + label,
[type="checkbox"]:checked + label
{
	position: relative;
	padding-left: 20px;
	margin-bottom: 23px;
	cursor: pointer;
}

[type="checkbox"]:not(:checked) + label:before
{
	content: '';
	position: absolute;
	left:0;
	top: -4px;
	height: 30px;
	width: 30px;
	border: 1px solid rgba(255,255,255, 0.15);
	background-color: rgba(99,49,149, 0.55);
	border-radius: 50%;
	transition: all .5s;
}


[type="checkbox"]:checked + label:before
{
	content: '';
	position: absolute;
	left:0;
	top: -4px;
	height: 30px;
	width: 30px;
	border: 1px solid rgba(255,255,255, 0.15);
	background-color: rgba(0,149,109, 0.55);
	border-radius: 50%;
	transition: all .5s;
}

[type="checkbox"]:not(:checked) + label:after
{
	content: 'AND';
	position: absolute;
	top: 4px;
	left: 2px;
	font-size: 13px;
	color: #fff;
	text-align: center;
}

[type="checkbox"]:checked + label:after
{
	content: 'OR';
	position: absolute;
	top: 4px;
	left: 6px;
	font-size: 13px;
	color: #fff;
	text-align: center;
}

.number
{
	width: 45px;
}

.radio
{
	margin-right: 10px;
}

[type="radio"]:not(:checked),
[type="radio"]:checked
{
	position: absolute;
	left: -9999px;
}

[type="radio"]:not(:checked) + label,
[type="radio"]:checked + label
{
	position: relative;
	padding-left: 25px;
	cursor: pointer;
}

[type="radio"]:not(:checked) + label:before
{
	content: '';
	position: absolute;
	left:0;
	top: -4px;
	height: 25px;
	width: 25px;
	border: 1px solid rgba(255,255,255, 0.15);
	background-color: rgba(255,255,255, 0.2);
	border-radius: 50%;
	transition: all .6s;
}


[type="radio"]:checked + label:before
{
	content: '';
	position: absolute;
	left:0;
	top: -4px;
	height: 25px;
	width: 25px;
	border: 1px solid rgba(255,255,255, 0.15);
	background-color: rgba(0,170,0,0.4);
	border-radius: 50%;
	transition: all .3s;
}

[type="radio"]:not(:checked) + label:after
{
	content: '';
	position: absolute;
	top: 4px;
	left: 2px;
	font-size: 13px;
	color: #fff;
	text-align: center;
}

[type="radio"]:checked + label:after
{
	content: '•';
	position: absolute;
	top: -6px;
	left: 7px;
	font-size: 24px;
	color: #fff;
	text-align: center;
}

.centerSubmit
{
	display: flex;
	justify-content: center;
	margin-top: 40px;
}

.newQueryContainer
{
	display: flex;
	flex-direction: row;
	justify-content: flex-start;
	align-items: center;
	flex-wrap: wrap;
	min-height: 85px;
	margin-top: 10px;
	margin-bottom: 10px;
}

.newQueryContainer *
{
	margin-left: 5px;
	margin-right: 5px;
}

.newQueryContainer label.andOr
{
	margin-left: 5px;
	margin-right: 15px;
}

.btnList
{
	display: flex;
	flex-direction: row;
	justify-content: center;
	flex-wrap: wrap;
	width: 60px;
	height: 50px;
	margin-bottom: 15px;
}

.ANDOrNotand,
.andORNotand,
.andOrNOTAND
{
	margin-right: 2px;
	margin-left: 2px;
}

[type="radio"]:not(:checked) + .ANDOrNotand:before
{
	content: '';
	position: absolute;
	left: 0;
	top: -4px;
	height: 23px;
	width: 23px;
	border: 1px solid rgba(255,255,255, 0.15);
	background-color: rgba(99,49,149, 0.2);
	border-radius: 50%;
	transition: all .6s;
}

[type="radio"]:not(:checked) + .andORNotand:before
{
	content: '';
	position: absolute;
	left: 0;
	top: -4px;
	height: 23px;
	width: 23px;
	border: 1px solid rgba(255,255,255, 0.15);
	background-color: rgba(0,149,109, 0.2);
	border-radius: 50%;
	transition: all .6s;
}

[type="radio"]:not(:checked) + .andOrNOTAND:before
{
	content: '';
	position: absolute;
	left: 0;
	top: -4px;
	height: 23px;
	width: 23px;
	border: 1px solid rgba(255,255,255, 0.15);
	background-color: rgba(139, 19, 19, 0.2);
	border-radius: 50%;
	transition: all .6s;
}

[type="radio"]:checked + .ANDOrNotand:before
{
	content: '';
	position: absolute;
	left: 0;
	top: -4px;
	height: 23px;
	width: 23px;
	border: 1px solid rgba(255,255,255, 0.15);
	background-color: rgba(99,49,149, 0.55);
	border-radius: 50%;
	transition: all .3s;
}

[type="radio"]:checked + .andORNotand:before
{
	content: '';
	position: absolute;
	left: 0;
	top: -4px;
	height: 23px;
	width: 23px;
	border: 1px solid rgba(255,255,255, 0.15);
	background-color: rgba(0,149,109, 0.55);
	border-radius: 50%;
	transition: all .3s;
}

[type="radio"]:checked + .andOrNOTAND:before
{
	content: '';
	position: absolute;
	left: 0;
	top: -4px;
	height: 23px;
	width: 23px;
	border: 1px solid rgba(255,255,255, 0.15);
	background-color: rgba(139, 19, 19, 0.55);
	border-radius: 50%;
	transition: all .3s;
}

[type="radio"]:not(:checked) + .ANDOrNotand:after
{
	content: 'AND';
	position: absolute;
	top: 2px;
	left: 2px;
	font-size: 10px;
	color: rgba(255,255,255,0.3);
	text-align: center;
}

[type="radio"]:not(:checked) + .andORNotand:after
{
	content: 'OR';
	position: absolute;
	top: 2px;
	left: 5px;
	font-size: 10px;
	color: rgba(255,255,255,0.3);
	text-align: center;
}

[type="radio"]:not(:checked) + .andOrNOTAND:after
{
	content: 'NOT';
	position: absolute;
	top: 2px;
	left: 2px;
	font-size: 10px;
	color: rgba(255,255,255,0.3);
	text-align: center;
}

[type="radio"]:checked + .ANDOrNotand:after
{
	content: 'AND';
	position: absolute;
	top: 2px;
	left: 2px;
	font-size: 10px;
	color: #fff;
	text-align: center;
}

[type="radio"]:checked + .andORNotand:after
{
	content: 'OR';
	position: absolute;
	top: 2px;
	left: 5px;
	font-size: 10px;
	color: #fff;
	text-align: center;
}

[type="radio"]:checked + .andOrNOTAND:after
{
	content: 'NOT';
	position: absolute;
	top: 2px;
	left: 2px;
	font-size: 10px;
	color: #fff;
	text-align: center;
}

[type="radio"]:checked + .ANDOrNotand
{
	margin-left: 20px;
	margin-right: 20px;
	order: 1;
}

[type="radio"]:checked + .andORNotand
{
	margin-left: 20px;
	margin-right: 20px;
	order: 1;
}

[type="radio"]:checked + .andOrNOTAND
{
	margin-left: 20px;
	margin-right: 20px;
	order: 1;
}

.extend
{
	width: 30px;
	height: 30px;
	border-radius: 50%;
	border: 1px solid rgba(255,255,255, 0.15);
	background-color: rgba(0,170,0,0.5);
	color: #fff;
	font-size: 15px;
	font-weight: 700;
	cursor: pointer;
}

.queryContainer
{
	display: flex;
	justify-content: flex-start;
	align-items: center;
	flex-wrap: wrap;
	padding: 10px;
	border: 1px solid rgba(255,255,255, 0.15);
}

.queryContainer a
{
	text-decoration: none;
	color: #fff;
}

.Qdisable div
{
	z-index: -1;
	opacity: 0.5;
}

.queryContainer:nth-child(2n)
{
	background-color: rgba(255,255,255,0.08);
}

.disableQuery
{
	display: flex;
	justify-content: center;
	align-items: center;
	width: 30px;
	height: 30px;
	border-radius: 50%;
	border: 1px solid rgba(255,255,255, 0.15);
	background: url('../images/Desactivated.png') center no-repeat;
	background-size: 20px;
	background-color: rgba(255,255,255, 0.2);
	margin-right: 10px;
}

.disableQuery:hover
{
	background: url('../images/Disable.png') center no-repeat;
	background-size: 20px;
	background-color: rgba(255,255,255, 0.2);
}

.activateQuery
{
	display: flex;
	justify-content: center;
	align-items: center;
	width: 30px;
	height: 30px;
	border-radius: 50%;
	border: 1px solid rgba(255,255,255, 0.15);
	background: url('../images/Desactivated.png') center no-repeat;
	background-size: 20px;
	background-color: rgba(255,255,255, 0.2);
	margin-right: 10px;
}

.activateQuery:hover
{
	background: url('../images/Activate.png') center no-repeat;
	background-size: 20px;
	background-color: rgba(255,255,255, 0.2);
}

.deleteQuery
{
	display: flex;
	justify-content: center;
	align-items: center;
	width: 30px;
	height: 30px;
	border-radius: 50%;
	border: 1px solid rgba(255,255,255, 0.15);
	background: url('../images/TrashDesactivated.png') center no-repeat;
	background-size: 20px;
	background-color: rgba(255,255,255, 0.2);
	margin-right: 10px;
}

.deleteQuery:hover
{
	background: url('../images/TrashRed.png') center no-repeat;
	background-size: 20px;
	background-color: rgba(255,255,255, 0.2);
}

.queryOrView
{
	display: flex;
	justify-content: center;
	align-items: center;
	width: 30px;
	height: 30px;
	border-radius: 50%;
	border: 1px solid rgba(255,255,255, 0.15);
	background-color: rgba(0,149,109, 0.55);
	color: #fff;
	font-size: 13px;
	margin-right: 10px;
}

.queryAndView
{
	display: flex;
	justify-content: center;
	align-items: center;
	width: 30px;
	height: 30px;
	border-radius: 50%;
	border: 1px solid rgba(255,255,255, 0.15);
	background-color: rgba(99,49,149, 0.55);
	color: #fff;
	font-size: 13px;
	margin-right: 10px;
}

.queryNotView
{
	display: flex;
	justify-content: center;
	align-items: center;
	width: 30px;
	height: 30px;
	border-radius: 50%;
	border: 1px solid rgba(255,255,255, 0.15);
	background-color: rgba(139, 19, 19, 0.55);
	color: #fff;
	font-size: 13px;
	margin-right: 10px;
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
	padding: 4px;
	text-align: center;
	font-size: 13px;
	margin: 5px 0 5px 0;
	margin-right: 10px;
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
	padding: 4px;
	text-align: center;
	font-size: 13px;
	margin: 5px 10px 5px 0;
}

/* Charts */
/* BEGIN 00-00 Chart */

.containerChart
{
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	align-items: center;
	width: 90%;
	height: 85%;
}

ul
{
	list-style-type: none;
	margin: 0;
	padding-left: 0;
}

.titleChart
{
	display: block;
	margin: 0;
	text-align: center;
	font-size: 15px;
	color: #fff;
}

.axis-x,
.axis-y
{
	left: 0;
	width: 100%;
	height: 80%;
}

[data-axisy]:before
{
	content: attr(data-axisy);
	display: inline-block;
	position: relative;
	width: 2rem;
	text-align: right;
	line-height: 0;
	left: -15%;
	top: 80%;
}

.axis-x
{
	height: 18.7%;
}

.axis-x li
{
	width: 33%;
	float: left;
	text-align: center;
}

.bar-chart
{
	width: 95%;
	height: 95%;
	position: relative;
	color: #fff;
	font-size: 80%;
}

.bar-chart .axis-x
{
	bottom: 0;
}

.axis-y li
{
	height: calc(20% - 1px);
	border-bottom: 1px solid rgba(255,255,255,0.15);
}

.bar-chart .axis-x li
{
	width: 12.5%;
	height: 40%;
	position: relative;
	text-align: left;
	bottom: 0;
}

.bar-chart .axis-x li i
{
	display: flex;
	flex-direction: row-reverse;
	transform: rotatez(-45deg);
	transform-origin: 70% 220%;
	white-space : nowrap;
}

.bar-chart .axis-x li:before
{
	content: '';
	position: absolute;
	bottom: 100%;
	width: 70%;
}
/* TODO Replace id selectro # by class . selector */
#graph_00_00 .bar-chart .axis-x li:nth-child(1):before
{
	background: rgba(67,127,255,0.8);
	height: 570%;
	animation-duration: 5s;
	animation-name: charts00_01;
}

@keyframes charts00_01
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 570%;
	}
}

#graph_00_00 .bar-chart .axis-x li:nth-child(2):before
{
	background: rgba(52,99,200,0.8);
	height: 885%;
	animation-duration: 5s;
	animation-name: charts00_02;
}

@keyframes charts00_02
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 885%;
	}
}

#graph_00_00 .bar-chart .axis-x li:nth-child(3):before
{
	background: rgba(67,127,255,0.8);
	height: 400%;
	animation-duration: 5s;
	animation-name: charts00_03;
}

@keyframes charts00_03
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 400%;
	}
}

#graph_00_00 .bar-chart .axis-x li:nth-child(4):before
{
	background: rgba(52,99,200,0.8);
	height: 290%;
	animation-duration: 5s;
	animation-name: charts00_04;
}

@keyframes charts00_04
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 290%;
	}
}

#graph_00_00 .bar-chart .axis-x li:nth-child(5):before
{
	background: rgba(67,127,255,0.8);
	height: 680%;
	animation-duration: 5s;
	animation-name: charts00_05;
}

@keyframes charts00_05
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 680%;
	}
}

#graph_00_00 .bar-chart .axis-x li:nth-child(6):before
{
	background: rgba(52,99,200,0.8);
	height: 885%;
	animation-duration: 5s;
	animation-name: charts00_06;
}

@keyframes charts00_06
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 885%;
	}
}

#graph_00_00 .bar-chart .axis-x li:nth-child(7):before
{
	background: rgba(67,127,255,0.8);
	height: 520%;
	animation-duration: 5s;
	animation-name: charts00_07;
}

@keyframes charts00_07
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 520%;
	}
}

#graph_00_00 .bar-chart .axis-x li:nth-child(8):before
{
	background: rgba(52,99,200,0.8);
	height: 620%;
	animation-duration: 5s;
	animation-name: charts00_08;
}

@keyframes charts00_08
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 620%;
	}
}

#graph_00_00 .bar-chart .axis-x li:hover::before
{
	background: rgba(67,127,255,1);
}

#graph_00_00 .bar-chart .axis-x li:nth-child(2n):hover::before
{
	background: rgba(52,99,200,1);
}

#graph_01_00 .bar-chart .axis-x li:nth-child(1):before
{
	background: rgba(67,127,255,0.8);
	height: 500%;
	animation-duration: 5s;
	animation-name: charts01_01;
}

@keyframes charts01_01
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 500%;
	}
}

#graph_01_00 .bar-chart .axis-x li:nth-child(2):before
{
	background: rgba(52,99,200,0.8);
	height: 445%;
	animation-duration: 5s;
	animation-name: charts01_02;
}

@keyframes charts01_02
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 445%;
	}
}

#graph_01_00 .bar-chart .axis-x li:nth-child(3):before
{
	background: rgba(67,127,255,0.8);
	height: 670%;
	animation-duration: 5s;
	animation-name: charts01_03;
}

@keyframes charts01_03
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 670%;
	}
}

#graph_01_00 .bar-chart .axis-x li:nth-child(4):before
{
	background: rgba(52,99,200,0.8);
	height: 205%;
	animation-duration: 5s;
	animation-name: charts01_04;
}

@keyframes charts01_04
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 205%;
	}
}

#graph_01_00 .bar-chart .axis-x li:nth-child(5):before
{
	background: rgba(67,127,255,0.8);
	height: 450%;
	animation-duration: 5s;
	animation-name: charts01_05;
}

@keyframes charts01_05
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 450%;
	}
}

#graph_01_00 .bar-chart .axis-x li:nth-child(6):before
{
	background: rgba(52,99,200,0.8);
	height: 825%;
	animation-duration: 5s;
	animation-name: charts01_06;
}

@keyframes charts01_06
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 825%;
	}
}

#graph_01_00 .bar-chart .axis-x li:nth-child(7):before
{
	background: rgba(67,127,255,0.8);
	height: 420%;
	animation-duration: 5s;
	animation-name: charts01_07;
}

@keyframes charts01_07
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 420%;
	}
}

#graph_01_00 .bar-chart .axis-x li:nth-child(8):before
{
	background: rgba(52,99,200,0.8);
	height: 600%;
	animation-duration: 5s;
	animation-name: charts01_08;
}

@keyframes charts01_08
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 600%;
	}
}

#graph_01_00 .bar-chart .axis-x li:hover::before
{
	background: rgba(67,127,255,1);
}

#graph_01_00 .bar-chart .axis-x li:nth-child(2n):hover::before
{
	background: rgba(52,99,200,1);
}

#graph_02_00 .bar-chart .axis-x li:nth-child(1):before
{
	background: rgba(67,127,255,0.8);
	height: 450%;
	animation-duration: 5s;
	animation-name: charts02_01;
}

@keyframes charts02_01
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 450%;
	}
}

#graph_02_00 .bar-chart .axis-x li:nth-child(2):before
{
	background: rgba(52,99,200,0.8);
	height: 50%;
	animation-duration: 5s;
	animation-name: charts02_02;
}

@keyframes charts02_02
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 50%;
	}
}

#graph_02_00 .bar-chart .axis-x li:nth-child(3):before
{
	background: rgba(67,127,255,0.8);
	height: 400%;
	animation-duration: 5s;
	animation-name: charts02_03;
}

@keyframes charts02_03
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 400%;
	}
}

#graph_02_00 .bar-chart .axis-x li:nth-child(4):before
{
	background: rgba(52,99,200,0.8);
	height: 190%;
	animation-duration: 5s;
	animation-name: charts02_04;
}

@keyframes charts02_04
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 190%;
	}
}

#graph_02_00 .bar-chart .axis-x li:nth-child(5):before
{
	background: rgba(67,127,255,0.8);
	height: 420%;
	animation-duration: 5s;
	animation-name: charts02_05;
}

@keyframes charts02_05
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 420%;
	}
}

#graph_02_00 .bar-chart .axis-x li:nth-child(6):before
{
	background: rgba(52,99,200,0.8);
	height: 205%;
	animation-duration: 5s;
	animation-name: charts02_06;
}

@keyframes charts02_06
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 205%;
	}
}

#graph_02_00 .bar-chart .axis-x li:nth-child(7):before
{
	background: rgba(67,127,255,0.8);
	height: 480%;
	animation-duration: 5s;
	animation-name: charts02_07;
}

@keyframes charts02_07
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 480%;
	}
}

#graph_02_00 .bar-chart .axis-x li:nth-child(8):before
{
	background: rgba(52,99,200,0.8);
	height: 600%;
	animation-duration: 5s;
	animation-name: charts02_08;
}

@keyframes charts02_08
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 600%;
	}
}

#graph_02_00 .bar-chart .axis-x li:hover::before
{
	background: rgba(67,127,255,1);
}

#graph_02_00 .bar-chart .axis-x li:nth-child(2n):hover::before
{
	background: rgba(52,99,200,1);
}


/* END 00-00 chart */
/* BEGIN 00-01 Chart */
.donut-chart *
{
	box-sizing: border-box;
}

.torChart
{
	position: relative;
	width: 200px;
	height: 200px;
	margin: 0 auto 2rem;
	border-radius: 100%
}

p.center-date
{
	position: absolute;
	top:0;
	left:0;
	bottom:0;
	right:0;
	display: flex;
	justify-content: center;
	align-items: center;
	background: #333b44;
	text-align: center;
	font-size: 28px;
	width: 67%;
	height: 67%;
	margin: auto;
	border-radius: 50%;
}

.resultPart
{
	border-radius: 50%;
	clip: rect(0px, 200px, 200px, 100px);
	height: 100%;
	position: absolute;
	width: 100%;
	transition: transform .8s;
}

.source
{
	border-radius: 50%;
	clip: rect(0px, 100px, 200px, 0px);
	height: 100%;
	position: absolute;
	width: 100%;
	font-family: monospace;
	font-size: 1.5rem;
	transition: transform .8s;
}

.resultPart:nth-child(1)
{
	transform: rotate(0deg);
}

.resultPart:nth-child(1) .source
{
	background-color: rgb(0,85,127);
	transform: rotate(76deg);
	opacity: 0.7;
}

.resultPart:nth-child(1) .source:hover
{
	opacity: 1;
}

.resultPart:nth-child(2)
{
	transform: rotate(76deg);
}

.resultPart:nth-child(2) .source
{
	background-color: rgb(32,117,32);
	transform: rotate(139deg);
	opacity: 0.7;
	z-index: 300;
}

.resultPart:nth-child(2):hover
{
	opacity: 1;
}

.resultPart:nth-child(3)
{
	transform: rotate(215deg);
}

.resultPart:nth-child(3) .source
{
	background-color: rgb(64,22,64);
	transform: rotate(113deg);
	opacity: 0.7;
}

.resultPart:nth-child(3) .source:hover
{
	opacity: 1;
}

.resultPart:nth-child(4)
{
	transform:rotate(-32deg);
}

.resultPart:nth-child(4) .source
{
	background-color: rgb(96,54,96);
	transform: rotate(32deg);
	opacity: 0.7;
}

.resultPart:nth-child(4) .source:hover
{
	opacity: 1;
}
/* END 00-01 chart */
/**/
.torChart,
.circle
{
	width: 200px;
	height: 200px;
	border-radius: 50%;
	overflow: hidden;
}

#graph_00_01 .source1
{
	position: relative;
	width: 100%;
	height: 100%;
	transform: rotate(90deg);
}

#graph_00_01 .s1
{
	position: relative;
	left: 50%;
	top: 0;
	height: 50%;
	width: 50px;
	background-color: rgb(0,85,127);
	transform: rotate(-30deg);
	transform-origin: left bottom;
}

#graph_00_01 .s1:before
{
	content: '';
	position: absolute;
	left: -100%;
	right: 0;
	height: 100%;
	width: 50px;
	background-color: rgb(0,85,127);
	transform: rotate(60deg);
	transform-origin: right bottom;
}

#graph_00_01 .s1:hover
{
	background-color: rgb(0,120,150);
}

#graph_00_01 .s1:hover:before
{
	background-color: rgb(0,120,150);
}

#graph_00_01 .source2
{
	position: relative;
	width: 100%;
	height: 100%;
	transform: rotate(90deg);
}

#graph_00_01 .s2
{
	position: relative;
	left: -50%;
	top: 0;
	height: 50%;
	width: 25px;
	background-color: rgb(32,117,32);
	transform: rotate(-58deg);
	transform-origin: left bottom;
	opacity: 0.7;
}

#graph_00_01 .s2:before
{
	content: '';
	position: absolute;
	left: -100%;
	right: 0;
	height: 100%;
	width: 25px;
	background-color: rgb(32,117,32);
	transform: rotate(29deg);
	transform-origin: right bottom;
}

#graph_00_01 .s2:hover
{
	background-color: rgb(41,150,41);
}

#graph_00_01 .s2:hover:before
{
	background-color: rgb(41,150,41);
}

#graph_00_01 .source3
{
	position: relative;
	width: 100%;
	height: 100%;
	transform: rotate(90deg);
}

#graph_00_01 .s3
{
	position: relative;
	top: 0;
	left: -150%;
	height: 50%;
	width: 100px;
	background-color: rgb(64,22,64);
	transform: rotate(-172deg);
	transform-origin: left bottom;
}

#graph_00_01 .s3:before
{
	content: '';
	position: absolute;
	left: -100%;
	right: 0;
	height: 100%;
	width: 100px;
	background-color: rgb(64,22,64);
	transform: rotate(114deg);
	transform-origin: right bottom;
}

#graph_00_01 .s3:hover
{
	background-color: rgb(80,27,80);

}

#graph_00_01 .s3:hover:before
{
	background-color: rgb(80,27,80);

}

#graph_00_01 .source4
{
	position: relative;
	width: 100%;
	height: 100%;
	transform: rotate(90deg);
}

#graph_00_01 .s4
{
	position: relative;
	top: -50%;
	left: -250%;
	height: 100%;
	width: 80%;
	background-color: rgb(96,54,96);
	transform: rotate(98deg);
	transform-origin: left bottom;
}

#graph_00_01 .s4:before
{
	content: '';
	position: absolute;
	left: -100%;
	right: 0;
	height: 100%;
	width: 100%;
	background-color: rgb(96,54,96);
	transform: rotate(22deg);
	transform-origin: right bottom;
}

#graph_00_01 .s4:hover
{
	background-color: rgb(110,61,110);
}

#graph_00_01 .s4:hover:before
{
	background-color: rgb(110,61,110);
}

#graph_01_02 .source1
{
	position: relative;
	width: 100%;
	height: 100%;
	transform: rotate(90deg);
}

#graph_01_02 .s1
{
	position: relative;
	left: 50%;
	top: 0;
	height: 50%;
	width: 50px;
	background-color: rgb(0,85,127);
	transform: rotate(-30deg);
	transform-origin: left bottom;
}

#graph_01_02 .s1:before
{
	content: '';
	position: absolute;
	left: -100%;
	right: 0;
	height: 100%;
	width: 50px;
	background-color: rgb(0,85,127);
	transform: rotate(60deg);
	transform-origin: right bottom;
}

#graph_01_02 .s1:hover
{
	background-color: rgb(0,120,150);
}

#graph_01_02 .s1:hover:before
{
	background-color: rgb(0,120,150);
}

#graph_01_02 .source2
{
	position: relative;
	width: 100%;
	height: 100%;
	transform: rotate(90deg);
}

#graph_01_02 .s2
{
	position: relative;
	left: -50%;
	top: 0;
	height: 50%;
	width: 25px;
	background-color: rgb(32,117,32);
	transform: rotate(-58deg);
	transform-origin: left bottom;
	opacity: 0.7;
}

#graph_01_02 .s2:before
{
	content: '';
	position: absolute;
	left: -100%;
	right: 0;
	height: 100%;
	width: 25px;
	background-color: rgb(32,117,32);
	transform: rotate(29deg);
	transform-origin: right bottom;
}

#graph_01_02 .s2:hover
{
	background-color: rgb(41,150,41);
}

#graph_01_02 .s2:hover:before
{
	background-color: rgb(41,150,41);
}

#graph_01_02 .source3
{
	position: relative;
	width: 100%;
	height: 100%;
	transform: rotate(90deg);
}

#graph_01_02 .s3
{
	position: relative;
	top: 0;
	left: -150%;
	height: 50%;
	width: 100px;
	background-color: rgb(64,22,64);
	transform: rotate(-172deg);
	transform-origin: left bottom;
}

#graph_01_02 .s3:before
{
	content: '';
	position: absolute;
	left: -100%;
	right: 0;
	height: 100%;
	width: 100px;
	background-color: rgb(64,22,64);
	transform: rotate(114deg);
	transform-origin: right bottom;
}

#graph_01_02 .s3:hover
{
	background-color: rgb(80,27,80);

}

#graph_01_02 .s3:hover:before
{
	background-color: rgb(80,27,80);

}

#graph_01_02 .source4
{
	position: relative;
	width: 100%;
	height: 100%;
	transform: rotate(90deg);
}

#graph_01_02 .s4
{
	position: relative;
	top: -50%;
	left: -250%;
	height: 100%;
	width: 80%;
	background-color: rgb(96,54,96);
	transform: rotate(98deg);
	transform-origin: left bottom;
}

#graph_01_02 .s4:before
{
	content: '';
	position: absolute;
	left: -100%;
	right: 0;
	height: 100%;
	width: 100%;
	background-color: rgb(96,54,96);
	transform: rotate(22deg);
	transform-origin: right bottom;
}

#graph_01_02 .s4:hover
{
	background-color: rgb(110,61,110);
}

#graph_01_02 .s4:hover:before
{
	background-color: rgb(110,61,110);
}

#graph_02_02 .source1
{
	position: relative;
	width: 100%;
	height: 100%;
	transform: rotate(90deg);
}

#graph_02_02 .s1
{
	position: relative;
	left: 50%;
	top: 0;
	height: 50%;
	width: 50px;
	background-color: rgb(0,85,127);
	transform: rotate(-30deg);
	transform-origin: left bottom;
}

#graph_02_02 .s1:before
{
	content: '';
	position: absolute;
	left: -100%;
	right: 0;
	height: 100%;
	width: 50px;
	background-color: rgb(0,85,127);
	transform: rotate(60deg);
	transform-origin: right bottom;
}

#graph_02_02 .s1:hover
{
	background-color: rgb(0,120,150);
}

#graph_02_02 .s1:hover:before
{
	background-color: rgb(0,120,150);
}

#graph_02_02 .source2
{
	position: relative;
	width: 100%;
	height: 100%;
	transform: rotate(90deg);
}

#graph_02_02 .s2
{
	position: relative;
	left: -50%;
	top: 0;
	height: 50%;
	width: 25px;
	background-color: rgb(32,117,32);
	transform: rotate(-58deg);
	transform-origin: left bottom;
	opacity: 0.7;
}

#graph_02_02 .s2:before
{
	content: '';
	position: absolute;
	left: -100%;
	right: 0;
	height: 100%;
	width: 25px;
	background-color: rgb(32,117,32);
	transform: rotate(29deg);
	transform-origin: right bottom;
}

#graph_02_02 .s2:hover
{
	background-color: rgb(41,150,41);
}

#graph_02_02 .s2:hover:before
{
	background-color: rgb(41,150,41);
}

#graph_02_02 .source3
{
	position: relative;
	width: 100%;
	height: 100%;
	transform: rotate(90deg);
}

#graph_02_02 .s3
{
	position: relative;
	top: 0;
	left: -150%;
	height: 50%;
	width: 100px;
	background-color: rgb(64,22,64);
	transform: rotate(-172deg);
	transform-origin: left bottom;
}

#graph_02_02 .s3:before
{
	content: '';
	position: absolute;
	left: -100%;
	right: 0;
	height: 100%;
	width: 100px;
	background-color: rgb(64,22,64);
	transform: rotate(114deg);
	transform-origin: right bottom;
}

#graph_02_02 .s3:hover
{
	background-color: rgb(80,27,80);

}

#graph_02_02 .s3:hover:before
{
	background-color: rgb(80,27,80);

}

#graph_02_02 .source4
{
	position: relative;
	width: 100%;
	height: 100%;
	transform: rotate(90deg);
}

#graph_02_02 .s4
{
	position: relative;
	top: -50%;
	left: -250%;
	height: 100%;
	width: 80%;
	background-color: rgb(96,54,96);
	transform: rotate(98deg);
	transform-origin: left bottom;
}

#graph_02_02 .s4:before
{
	content: '';
	position: absolute;
	left: -100%;
	right: 0;
	height: 100%;
	width: 100%;
	background-color: rgb(96,54,96);
	transform: rotate(22deg);
	transform-origin: right bottom;
}

#graph_02_02 .s4:hover
{
	background-color: rgb(110,61,110);
}

#graph_02_02 .s4:hover:before
{
	background-color: rgb(110,61,110);
}

.center
{
	position: relative;
	left: 12.5%;
	top: -87.5%;
	background-color: rgb(51,59,68);
	width: 150px;
	height: 150px;
	border-radius: 50%;
	z-index: 1000;
}
/**/

/*Chart point curve*/
.backChart
{
	width: 95%;
	height: 95%;
	font-size: 80%;
}


[data-time]:before
{
	content: attr(data-time);
	display: inline-block;
	position: relative;
	width: 2rem;
	text-align: right;
	line-height: 0;
	left: -15%;
	top: 80%;
}

.axis-time
{
	display: flex;
	flex-direction: row;
	justify-content: space-between;
	width: 95%;
	margin-top: 5%;
}

.axis-time li
{
	display: flex;
	flex-direction: row-reverse;
	transform: rotatez(-45deg);
	transform-origin: 70% 220%;
	white-space : nowrap;
}

.curveChart
{
	position: relative;
	top: -68%;
	display: flex;
	flex-direction: row;
	justify-content: flex-start;
	width: 100%;
	height: 64%;
}

#graph_01_01 .curveChartPart
{
	display: flex;
	flex-direction: column;
	justify-content: flex-start;
	align-items: center;
	width: 16.7%;
	height: 100%;
}

#graph_01_01 .curveChartPart:nth-child(1) .query01
{
	position: relative;
	top: calc(10% - 0.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(67,127,255,1);
	z-index: 1;
}


#graph_01_01 .curveChartPart:nth-child(1) .query01:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 850%;
	height: 0.6vw;
	background-color: rgba(67,127,255,0.8);
	transform: rotate(65deg);
	transform-origin: left center;
	z-index: 1;
}

#graph_01_01 .curveChartPart:nth-child(1) .query02
{
	position: relative;
	top: calc(20% - 1.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(68, 255, 69, 1);
	z-index: 2;
}

#graph_01_01 .curveChartPart:nth-child(1) .query02:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 450%;
	height: 0.6vw;
	background-color: rgba(68, 255, 69, 0.8);
	transform: rotate(-39deg);
	transform-origin: left center;
	z-index: 2;
}

#graph_01_01 .curveChartPart:nth-child(1) .query03
{
	position: relative;
	top: calc(3% - 2.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(161, 68, 255, 1);
	z-index: 3;
}

#graph_01_01 .curveChartPart:nth-child(1) .query03:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 600%;
	height: 0.6vw;
	background-color: rgba(161, 68, 255, 0.8);
	transform: rotate(55deg);
	transform-origin: left center;
	z-index: 3;
}

#graph_01_01 .curveChartPart:nth-child(2) .query01
{
	position: relative;
	top: calc(50% - 0.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(67,127,255,1);
	z-index: 1;
}

#graph_01_01 .curveChartPart:nth-child(2) .query01:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 400%;
	height: 0.6vw;
	background-color: rgba(67,127,255,0.8);
	transform: rotate(-15deg);
	transform-origin: left center;
	z-index: 1;
}

#graph_01_01 .curveChartPart:nth-child(2) .query02
{
	position: relative;
	top: calc(5% - 1.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(68, 255, 69, 1);
	z-index: 2;
}

#graph_01_01 .curveChartPart:nth-child(2) .query02:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 950%;
	height: 0.6vw;
	background-color: rgba(68, 255, 69, 0.8);
	transform: rotate(67deg);
	transform-origin: left center;
	z-index: 2;
}

#graph_01_01 .curveChartPart:nth-child(2) .query03
{
	position: relative;
	top: calc(30% - 2.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(161, 68, 255, 1);
	z-index: 3;
}

#graph_01_01 .curveChartPart:nth-child(2) .query03:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 550%;
	height: 0.6vw;
	background-color: rgba(161, 68, 255, 0.8);
	transform: rotate(-47deg);
	transform-origin: left center;
	z-index: 3;
}

#graph_01_01 .curveChartPart:nth-child(3) .query01
{
	position: relative;
	top: calc(45% - 0.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(67,127,255,1);
	z-index: 1;
}

#graph_01_01 .curveChartPart:nth-child(3) .query01:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 700%;
	height: 0.6vw;
	background-color: rgba(67,127,255,0.8);
	transform: rotate(58deg);
	transform-origin: left center;
	z-index: 1;
}

#graph_01_01 .curveChartPart:nth-child(3) .query02
{
	position: relative;
	top: calc(50% - 1.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(68, 255, 69, 1);
	z-index: 2;
}

#graph_01_01 .curveChartPart:nth-child(3) .query02:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 450%;
	height: 0.6vw;
	background-color: rgba(68, 255, 69, 0.8);
	transform: rotate(28deg);
	transform-origin: left center;
	z-index: 2;
}

#graph_01_01 .curveChartPart:nth-child(3) .query03
{
	position: relative;
	top: calc(10% - 2.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(161, 68, 255, 1);
	z-index: 3;
}

#graph_01_01 .curveChartPart:nth-child(3) .query03:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 350%;
	height: 0.6vw;
	background-color: rgba(161, 68, 255, 0.8);
	transform: rotate(-10deg);
	transform-origin: left center;
	z-index: 3;
}

#graph_01_01 .curveChartPart:nth-child(4) .query01
{
	position: relative;
	top: calc(75% - 0.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(67,127,255,1);
	z-index: 1;
}

#graph_01_01 .curveChartPart:nth-child(4) .query01:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 350%;
	height: 0.6vw;
	background-color: rgba(67,127,255,0.8);
	transform: rotate(13.5deg);
	transform-origin: left center;
	z-index: 1;
}

#graph_01_01 .curveChartPart:nth-child(4) .query02
{
	position: relative;
	top: calc(60% - 1.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(68, 255, 69, 1);
	z-index: 2;
}

#graph_01_01 .curveChartPart:nth-child(4) .query02:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 400%;
	height: 0.6vw;
	background-color: rgba(68, 255, 69,0.8);
	transform: rotate(27deg);
	transform-origin: left center;
	z-index: 2;
}

#graph_01_01 .curveChartPart:nth-child(4) .query03
{
	position: relative;
	top: calc(7% - 2.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(161, 68, 255, 1);
	z-index: 3;
}

#graph_01_01 .curveChartPart:nth-child(4) .query03:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 500%;
	height: 0.6vw;
	background-color: rgba(161, 68, 255,0.8);
	transform: rotate(43deg);
	transform-origin: left center;
	z-index: 3;
}

#graph_01_01 .curveChartPart:nth-child(5) .query01
{
	position: relative;
	top: calc(80% - 0.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(67,127,255,1);
	z-index: 1;
}

#graph_01_01 .curveChartPart:nth-child(5) .query01:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 500%;
	height: 0.6vw;
	background-color: rgba(67,127,255,0.8);
	transform: rotate(-47deg);
	transform-origin: left center;
	z-index: 1;
}

#graph_01_01 .curveChartPart:nth-child(5) .query02
{
	position: relative;
	top: calc(70% - 1.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(68, 255, 69, 1);
	z-index: 2;
}

#graph_01_01 .curveChartPart:nth-child(5) .query02:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 400%;
	height: 0.6vw;
	background-color: rgba(68, 255, 69,0.8);
	transform: rotate(-16deg);
	transform-origin: left center;
	z-index: 2;
}

#graph_01_01 .curveChartPart:nth-child(5) .query03
{
	position: relative;
	top: calc(25% - 2.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(161, 68, 255, 1);
	z-index: 3;
}

#graph_01_01 .curveChartPart:nth-child(5) .query03:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 400%;
	height: 0.6vw;
	background-color: rgba(161, 68, 255,0.8);
	transform: rotate(-28.5deg);
	transform-origin: left center;
	z-index: 3;
}

#graph_01_01 .curveChartPart:nth-child(6) .query01
{
	position: relative;
	top: calc(60% - 0.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(67,127,255,1);
	z-index: 1;
}

#graph_01_01 .curveChartPart:nth-child(6) .query02
{
	position: relative;
	top: calc(65% - 1.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(68, 255, 69, 1);
	z-index: 2;
}

#graph_01_01 .curveChartPart:nth-child(6) .query03
{
	position: relative;
	top: calc(15% - 2.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(161, 68, 255, 1);
	z-index: 3;
}

#graph_02_01 .curveChartPart
{
	display: flex;
	flex-direction: column;
	justify-content: flex-start;
	align-items: center;
	width: 16.7%;
	height: 100%;
}

#graph_02_01 .curveChartPart:nth-child(1) .query01
{
	position: relative;
	top: calc(10% - 0.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(67,127,255,1);
	z-index: 1;
}


#graph_02_01 .curveChartPart:nth-child(1) .query01:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 850%;
	height: 0.6vw;
	background-color: rgba(67,127,255,0.8);
	transform: rotate(65deg);
	transform-origin: left center;
	z-index: 1;
}

#graph_02_01 .curveChartPart:nth-child(1) .query02
{
	position: relative;
	top: calc(20% - 1.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(68, 255, 69, 1);
	z-index: 2;
}

#graph_02_01 .curveChartPart:nth-child(1) .query02:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 450%;
	height: 0.6vw;
	background-color: rgba(68, 255, 69, 0.8);
	transform: rotate(-39deg);
	transform-origin: left center;
	z-index: 2;
}

#graph_02_01 .curveChartPart:nth-child(1) .query03
{
	position: relative;
	top: calc(3% - 2.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(161, 68, 255, 1);
	z-index: 3;
}

#graph_02_01 .curveChartPart:nth-child(1) .query03:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 600%;
	height: 0.6vw;
	background-color: rgba(161, 68, 255, 0.8);
	transform: rotate(55deg);
	transform-origin: left center;
	z-index: 3;
}

#graph_02_01 .curveChartPart:nth-child(2) .query01
{
	position: relative;
	top: calc(50% - 0.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(67,127,255,1);
	z-index: 1;
}

#graph_02_01 .curveChartPart:nth-child(2) .query01:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 400%;
	height: 0.6vw;
	background-color: rgba(67,127,255,0.8);
	transform: rotate(-15deg);
	transform-origin: left center;
	z-index: 1;
}

#graph_02_01 .curveChartPart:nth-child(2) .query02
{
	position: relative;
	top: calc(5% - 1.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(68, 255, 69, 1);
	z-index: 2;
}

#graph_02_01 .curveChartPart:nth-child(2) .query02:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 950%;
	height: 0.6vw;
	background-color: rgba(68, 255, 69, 0.8);
	transform: rotate(67deg);
	transform-origin: left center;
	z-index: 2;
}

#graph_02_01 .curveChartPart:nth-child(2) .query03
{
	position: relative;
	top: calc(30% - 2.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(161, 68, 255, 1);
	z-index: 3;
}

#graph_02_01 .curveChartPart:nth-child(2) .query03:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 550%;
	height: 0.6vw;
	background-color: rgba(161, 68, 255, 0.8);
	transform: rotate(-47deg);
	transform-origin: left center;
	z-index: 3;
}

#graph_02_01 .curveChartPart:nth-child(3) .query01
{
	position: relative;
	top: calc(45% - 0.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(67,127,255,1);
	z-index: 1;
}

#graph_02_01 .curveChartPart:nth-child(3) .query01:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 700%;
	height: 0.6vw;
	background-color: rgba(67,127,255,0.8);
	transform: rotate(58deg);
	transform-origin: left center;
	z-index: 1;
}

#graph_02_01 .curveChartPart:nth-child(3) .query02
{
	position: relative;
	top: calc(50% - 1.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(68, 255, 69, 1);
	z-index: 2;
}

#graph_02_01 .curveChartPart:nth-child(3) .query02:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 450%;
	height: 0.6vw;
	background-color: rgba(68, 255, 69, 0.8);
	transform: rotate(28deg);
	transform-origin: left center;
	z-index: 2;
}

#graph_02_01 .curveChartPart:nth-child(3) .query03
{
	position: relative;
	top: calc(10% - 2.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(161, 68, 255, 1);
	z-index: 3;
}

#graph_02_01 .curveChartPart:nth-child(3) .query03:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 350%;
	height: 0.6vw;
	background-color: rgba(161, 68, 255, 0.8);
	transform: rotate(-10deg);
	transform-origin: left center;
	z-index: 3;
}

#graph_02_01 .curveChartPart:nth-child(4) .query01
{
	position: relative;
	top: calc(75% - 0.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(67,127,255,1);
	z-index: 1;
}

#graph_02_01 .curveChartPart:nth-child(4) .query01:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 350%;
	height: 0.6vw;
	background-color: rgba(67,127,255,0.8);
	transform: rotate(13.5deg);
	transform-origin: left center;
	z-index: 1;
}

#graph_02_01 .curveChartPart:nth-child(4) .query02
{
	position: relative;
	top: calc(60% - 1.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(68, 255, 69, 1);
	z-index: 2;
}

#graph_02_01 .curveChartPart:nth-child(4) .query02:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 400%;
	height: 0.6vw;
	background-color: rgba(68, 255, 69,0.8);
	transform: rotate(27deg);
	transform-origin: left center;
	z-index: 2;
}

#graph_02_01 .curveChartPart:nth-child(4) .query03
{
	position: relative;
	top: calc(7% - 2.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(161, 68, 255, 1);
	z-index: 3;
}

#graph_02_01 .curveChartPart:nth-child(4) .query03:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 500%;
	height: 0.6vw;
	background-color: rgba(161, 68, 255,0.8);
	transform: rotate(43deg);
	transform-origin: left center;
	z-index: 3;
}

#graph_02_01 .curveChartPart:nth-child(5) .query01
{
	position: relative;
	top: calc(80% - 0.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(67,127,255,1);
	z-index: 1;
}

#graph_02_01 .curveChartPart:nth-child(5) .query01:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 500%;
	height: 0.6vw;
	background-color: rgba(67,127,255,0.8);
	transform: rotate(-47deg);
	transform-origin: left center;
	z-index: 1;
}

#graph_02_01 .curveChartPart:nth-child(5) .query02
{
	position: relative;
	top: calc(70% - 1.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(68, 255, 69, 1);
	z-index: 2;
}

#graph_02_01 .curveChartPart:nth-child(5) .query02:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 400%;
	height: 0.6vw;
	background-color: rgba(68, 255, 69,0.8);
	transform: rotate(-16deg);
	transform-origin: left center;
	z-index: 2;
}

#graph_02_01 .curveChartPart:nth-child(5) .query03
{
	position: relative;
	top: calc(25% - 2.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(161, 68, 255, 1);
	z-index: 3;
}

#graph_02_01 .curveChartPart:nth-child(5) .query03:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 400%;
	height: 0.6vw;
	background-color: rgba(161, 68, 255,0.8);
	transform: rotate(-28.5deg);
	transform-origin: left center;
	z-index: 3;
}

#graph_02_01 .curveChartPart:nth-child(6) .query01
{
	position: relative;
	top: calc(60% - 0.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(67,127,255,1);
	z-index: 1;
}

#graph_02_01 .curveChartPart:nth-child(6) .query02
{
	position: relative;
	top: calc(65% - 1.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(68, 255, 69, 1);
	z-index: 2;
}

#graph_02_01 .curveChartPart:nth-child(6) .query03
{
	position: relative;
	top: calc(15% - 2.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(161, 68, 255, 1);
	z-index: 3;
}

/* Double bart chart */

.bar-chart .axis-x li:after
{
	content: '';
	position: absolute;
	left: 0;
	width: 70%;
}

#graph_00_02 .bar-chart .axis-x li:nth-child(1):before
{
	background: rgba(67,127,255,0.8);
	height: 520%;
	animation-duration: 5s;
	animation-name: doubleCharts00_01before;
}

@keyframes doubleCharts00_01before
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 520%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(1):after
{
	top: -220%;
	background: rgba(68, 255, 148, 0.9);
	height: 220%;
	animation-duration: 5s;
	animation-name: doubleCharts00_01after;
}

@keyframes doubleCharts00_01after
{
	from
	{
		top: -2%;
		height: 2%;
	}

	to
	{
		top: -220%;
		height: 220%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(2):before
{
	background: rgba(52,99,200,0.8);
	height: 285%;
	animation-duration: 5s;
	animation-name: doubleCharts00_02before;
}

@keyframes doubleCharts00_02before
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 285%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(2):after
{
	top: -255%;
	background: rgba(85,170,127,0.8);
	height: 255%;
	animation-duration: 5s;
	animation-name: doubleCharts00_02after;
}

@keyframes doubleCharts00_02after
{
	from
	{
		top: -2%;
		height: 2%;
	}

	to
	{
		top: -255%;
		height: 255%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(3):before
{
	background: rgba(67,127,255,0.8);
	height: 420%;
	animation-duration: 5s;
	animation-name: doubleCharts00_03before;
}

@keyframes doubleCharts00_03before
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 420%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(3):after
{
	top: -55%;
	background: rgba(68, 255, 148, 0.9);
	height: 55%;
	animation-duration: 5s;
	animation-name: doubleCharts00_03after;
}

@keyframes doubleCharts00_03after
{
	from
	{
		top: -2%;
		height: 2%;
	}

	to
	{
		top: -55%;
		height: 55%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(4):before
{
	background: rgba(52,99,200,0.8);
	height: 220%;
	animation-duration: 5s;
	animation-name: doubleCharts00_04before;
}

@keyframes doubleCharts00_04before
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 220%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(4):after
{
	top: -110%;
	background: rgba(85,170,127,0.8);
	height: 110%;
	animation-duration: 5s;
	animation-name: doubleCharts00_04after;
}

@keyframes doubleCharts00_04after
{
	from
	{
		top: -2%;
		height: 2%;
	}

	to
	{
		top: -110%;
		height: 110%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(5):before
{
	background: rgba(67,127,255,0.8);
	height: 282%;
	animation-duration: 5s;
	animation-name: doubleCharts00_05before;
}

@keyframes doubleCharts00_05before
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 282%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(5):after
{
	top: -210%;
	background: rgba(68, 255, 148, 0.9);
	height: 210%;
	animation-duration: 5s;
	animation-name: doubleCharts00_05after;
}

@keyframes doubleCharts00_05after
{
	from
	{
		top: -2%;
		height: 2%;
	}

	to
	{
		top: -210%;
		height: 210%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(6):before
{
	background: rgba(52,99,200,0.8);
	height: 285%;
	animation-duration: 5s;
	animation-name: doubleCharts00_06before;
}

@keyframes doubleCharts00_06before
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 285%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(6):after
{
	top: -110%;
	background: rgba(85,170,127,0.8);
	height: 110%;
	animation-duration: 5s;
	animation-name: doubleCharts00_06after;
}

@keyframes doubleCharts00_06after
{
	from
	{
		top: -2%;
		height: 2%;
	}

	to
	{
		top: -110%;
		height: 110%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(7):before
{
	background: rgba(67,127,255,0.8);
	height: 20%;
	animation-duration: 5s;
	animation-name: doubleCharts00_07before;
}

@keyframes doubleCharts00_07before
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 20%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(7):after
{
	top: -10%;
	background: rgba(68, 255, 148, 0.9);
	height: 10%;
	animation-duration: 5s;
	animation-name: doubleCharts00_07after;
}

@keyframes doubleCharts00_07after
{
	from
	{
		top: -2%;
		height: 2%;
	}

	to
	{
		top: -10%;
		height: 10%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(8):before
{
	background: rgba(52,99,200,0.8);
	height: 220%;
	animation-duration: 5s;
	animation-name: doubleCharts00_08;
}

@keyframes doubleCharts00_08
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 220%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(8):after
{
	top: -100%;
	background: rgba(85,170,127,0.9);
	height: 100%;
	animation-duration: 5s;
	animation-name: doubleCharts00_08after;
}

@keyframes doubleCharts00_08after
{
	from
	{
		top: -2%;
		height: 2%;
	}

	to
	{
		top: -100%;
		height: 100%;
	}
}

#graph_00_02 .bar-chart .axis-x li:hover::before
{
	background: rgba(67,127,255,1);
}

#graph_00_02 .bar-chart .axis-x li:nth-child(2n):hover::before
{
	background: rgba(52,99,200,1);
}

#graph_00_02 .bar-chart .axis-x li:hover::after
{
	background: rgba(68, 255, 148, 1);
}

#graph_00_02 .bar-chart .axis-x li:nth-child(2n):hover::after
{
	background: rgba(85,170,127,1);
}

/**/
<?php
include_once('footer.php');
?>
