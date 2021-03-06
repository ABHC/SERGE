<?php
session_start();
header('content-type: text/css');
ob_start('ob_gzhandler');
header('Cache-Control: max-age=31536000, must-revalidate');

include('style.php');

include('nav.php');
?>

/* Color */
.red
{
	background-color: rgb(153, 29, 33);
}
/* END Color */

.body
{
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	align-items: center;
	min-height: 100vh;
	color: #f9f9ff;
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

.fakeLink
{
	cursor: pointer;
}

.noPremium
{
	z-index: -1;
	user-select: none;
}

.imgPrem
{
	max-height: 45vh;
	height: 100%;
	width: 100%;
}

.imgSms
{
	background: url('../images/screenPhone.png') center no-repeat;
	background-size: contain;
}

.imgEmail
{
	background: url('../images/SergeEmail.png') center no-repeat;
	background-size: contain;
}

.icoText
{
	width: 40px;
	height: 40px;
}

.icoTextSmall
{
	width: 20px;
	height: 20px;
}

.Vseparator
{
	width: 1px;
	height: 80%;
	min-height: 100px;
	background-color: rgba(245, 245, 245, 0.2);
	margin-left: 5px;
	margin-right: 5px;
}

.Hseparator
{
	width: 80%;
	height: 1px;
	margin-top: 5px;
	margin-bottom: 5px;
	margin-left: 10%;
}

.optionFold
{
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	width: 100%;
}

label
{
	cursor: pointer;
}

form
{
	width: 100%;
}

input
{
	border-radius: 3px;
	border: 1px solid rgba(255,255,255, 0.15);
	padding: 4px;
	background-color: rgba(255, 255, 255, 0.2);
	color: #f9f9ff;
}

input[type='text']
{
	flex-grow: 1;
	flex-shrink: 1;
}

select
{
	position: relative;
	max-width: fit-content;
	height: 26px;
	font-size: 17px;
	padding: 4px;
	padding-right: 10px;
	border-radius: 3px;
	border: 1px solid rgba(255,255,255, 0.15);
	color: #f9f9ff;
	-moz-appearance: none;
	-webkit-appearance: none;
	-o-appearance: none;
	appearance: none;
	background-color: rgba(20,20,20,0.45);
	outline: none;
	z-index: 2;
}

select:focus
{
	outline: none;
	outline-style: none
}

select option
{
	background-color: rgb(51,59,68);
	border: none;
	outline: none;
	outline-style: none
}

select#sourceKeyword,
select#sourceType
{
	flex-grow: 1;
	flex-shrink: 1;
	height: 27px;
	background-color: rgba(20,20,20,0.45);
	margin-right: 10px;
	font-size: 14px;
	cursor: pointer;
}

#backgroundPreview
{
	position: absolute;
	display: none;
	width: 200px;
	color: #f9f9ff;
	border: 1px solid rgba(255,255,255, 0.15);
	border-radius: 3px;
	overflow: hidden;
	z-index: 4000;
}

input#email
{
	width: 80%;
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

h3
{
	font-size: 20px;
	margin-top: 5px;
	margin-bottom: 20px;
}

.falseInput
{
	width: 100%;
	min-width: 280px;
	font-size: 12px;
	height: 14px;
	border-radius: 3px 0 0 3px;
	border: 1px solid rgba(255,255,255, 0.15);
	border-right: none;
	padding: 4px;
	background-color: rgba(255, 255, 255, 0.2);
	color: #f9f9ff;
	resize: none;
	overflow: hidden;
}

.submit
{
	min-width: 30px;
	width: 30px;
	height: 30px;
	border-radius: 50%;
	font-size: 0;
	border: 1px solid rgba(255,255,255, 0.15);
	background: url('../images/tick.png') center no-repeat;
	background-size: contain;
	background-color: rgba(43, 140, 34, 0.55);
	cursor: pointer;
	margin-left: 5px;
}

.submit:hover
{
	background-color: rgba(43, 140, 34, 0.75);
}

.copyButton
{
	width: 30px;
	height: 24px;
	background: url('../images/icoCopy.png') center no-repeat;
	background-size: 22px;
	background-color: rgb(50,50,50);
	border: 1px solid rgba(255,255,255, 0.15);
	border-radius: 0 3px 3px 0;
	cursor: pointer;
	font-size: 0;
}

.copyButton:hover
{
		background-color: rgb(55, 55, 55);
}

.removeWP
{
	width: 30px;
	height: 29px;
	background: url('../images/Trash.png') center no-repeat;
	background-size: 22px;
	background-color: rgb(50,50,50);
	border-left: 1px solid rgba(255,255,255, 0.15);
	border-top: 1px solid rgba(255,255,255, 0.15);
	border-bottom: 1px solid rgba(255,255,255, 0.15);
	border-radius: 3px;
	margin-left: 10px;
	cursor: pointer;
	font-size: 0;
}

.removeWP:hover
{
		background-color: rgb(55, 55, 55);
}

.helpModalWindow
{
	width: 30px;
	height: 30px;
	border-radius: 50%;
	font-size: 0;
	border: 1px solid rgba(255,255,255, 0.15);
	background: url('../images/help.png') center no-repeat;
	background-size: contain;
	background-color: rgb(6, 85, 124);
	cursor: pointer;
	margin-right: 5px;
}

#helpNews
{
	display: none;
}

#helpNews:target
{
	position: absolute;
	display: flex;
	flex-direction: column;
	justify-content: flex-start;
	align-items: flex-start;
	width: 40%;
	margin-left: 20%;
	padding: 10px;
	background-color: rgba(0, 0, 0, 0.4);
	border-radius: 3px;
	border: 1px solid rgba(255,255,255,0.1);
	z-index: 10;
}

.newsInput
{
	display: flex;
	justify-content: center;
	align-items: center;
	margin-bottom: 15px;
	width: 100%;
}

.newsInput input[type="text"],
.newsInput input[type="url"]
{
	height: 17px;
	font-size: 14px;
	flex-grow: 1;
	flex-shrink: 1;
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
	display: flex;
	justify-content: center;
	align-items: center;
	width: 40%;
	max-width: 185px;
	height: 55px;
	color: rgb(230,230,230);
	text-decoration: none;
	text-align: center;
	background-color: rgba(92,53,102,0.6);
	border-radius: 3px;
	border: 1px solid rgba(255,255,255, 0.15);
}

.buttonCreatesourcePack:hover
{
	background-color: rgba(92,53,102,0.8);
	color: #f9f9ff;
}

.buttonVisiteCommunitySourcePack
{
	display: flex;
	justify-content: center;
	align-items: center;
	width: 40%;
	max-width: 185px;
	height: 55px;
	color: rgb(230,230,230);
	text-decoration: none;
	text-align: center;
	background-color: rgba(92,53,102,0.6);
	border-radius: 3px;
	border: 1px solid rgba(255,255,255, 0.15);
}

.buttonVisiteCommunitySourcePack:hover
{
	background-color: rgba(92,53,102,0.8);
	color: #f9f9ff;
}

.purchaseButton
{
	display: flex;
	justify-content: center;
	align-items: center;
	width: 80%;
	height: auto;
	padding: 2px;
	margin: 5px;
	margin-left: 10%;
	color: rgb(230,230,230);
	text-decoration: none;
	text-align: center;
	background-color: rgba(92,53,102,0.6);
	border-radius: 3px;
	border: 1px solid rgba(255,255,255, 0.15);
}

.purchaseButton:hover
{
	background-color: rgba(92,53,102,0.8);
}

.redAlertPicto
{
	width: 30px;
	height: 30px;
	background: url('../images/pictogrammes/redAlertPicto.png') center no-repeat;
	background-size: contain;
}

.redAlert
{
	display: flex;
	flex-direction: row;
	justify-content: flex-start;
	align-items: center;
	margin-top: 4px;
}

.divRow
{
	display: flex;
	flex-direction: row;
	justify-content: space-around;
	align-items: flex-start;
	flex-wrap: wrap;
	margin-top: 20px;
	width: 100%;
}

.divRow > div
{
	width: 45%;
	height: 245px;
	overflow: hidden;
}

h2
{
	width: 100%;
	text-align: center;
	margin-right: 40px;
}

.titleBoard
{
	display: flex;
	flex-direction: row;
	justify-content: space-between;
	align-items: center;
	width: 100%;
}

.keywordManagement
{
	display: flex;
	flex-direction: column;
	justify-content: flex-start;
	align-items: flex-start;
	width: 80%;
	margin-right: 70px;
	padding: 10px;
	background-color: rgba(0, 0, 0, 0.4);
	border-radius: 3px;
	border: 1px solid rgba(255,255,255,0.1);
	margin-top: 50px;
}

.sourceList
{
	display: flex;
	flex-direction: column;
	justify-content: flex-start;
	align-items: flex-start;
	flex-wrap: wrap;
	margin-left: 2%;
	width: 98%;
	height: 35px;
	overflow: hidden;
}

.keywordList
{
	display: flex;
	justify-content: flex-start;
	align-items: center;
	flex-wrap: wrap;
	margin-left: 4%;
	margin-bottom: 10px;
	width: 250px;
	height: 30px;
	overflow: hidden;
}

.sourceList > input:checked + .keywordList,
.keywordManagement  > input:checked + .sourceList
{
	width: 96%;
	height: auto;
}

.foldTag
{
	display: none;
}

.sourceList > input:not(:checked) ~ .keywordList > .unfoldTag
{
	display: block;
	border-radius: 3px 3px 3px 3px;
	border: 1px solid rgba(255,255,255, 0.15);
	padding-top: 1px;
	padding-bottom: 2px;
	padding-left: 4px;
	padding-right: 4px;
	text-align: center;
	margin-top: 3px;
	margin-bottom: 3px;
	margin-left: 3px;
	margin-right: 3px;
	background-color: rgba(20,20,20,0.45);
	color: #f9f9ff;
	cursor: pointer;
}

.keywordManagement > input:not(:checked) ~ .sourceList > .unfoldTag,
.keywordManagement > input:checked ~ .sourceList > .foldTag
{
	display: block;
	width: 96%;
	height: 25px;
	border-radius: 3px 3px 3px 3px;
	border: 1px solid rgba(255,255,255, 0.15);
	padding-top: 1px;
	padding-bottom: 2px;
	padding-left: 4px;
	padding-right: 4px;
	text-align: center;
	margin-top: 4px;
	margin-bottom: 4px;
	margin-left: 3px;
	margin-right: 2%;
	background-color: rgba(20,20,20,0.45);
	color: #f9f9ff;
	cursor: pointer;
}

.keywordManagement > input:checked ~ .sourceList > .foldTag
{
	width: 98%;
}

.sourceList > input:not(:checked) ~ .keywordList > .unfoldTag:hover,
.keywordManagement > input:not(:checked) ~ .sourceList > .unfoldTag:hover,
.sourceList > input:not(:checked) ~ .keywordList > .unfoldTag:focus,
.keywordManagement > input:not(:checked) ~ .sourceList > .unfoldTag:focus,
.sourceList > input:not(:checked) ~ .keywordList > .unfoldTag:active,
.keywordManagement > input:not(:checked) ~ .sourceList > .unfoldTag:active
{
	background-color: rgba(30 ,30 ,30 ,0.4);
}

.sourceList > input:checked + .keywordList > .unfoldTag,
.keywordManagement > input:checked + .sourceList > .unfoldTag
{
	display: none;
}

.sourceList > input:checked ~ .keywordList > .foldTag
{
	display: block;
	border-radius: 3px 3px 3px 3px;
	border: 1px solid rgba(255,255,255, 0.15);
	padding-top: 1px;
	padding-bottom: 2px;
	padding-left: 4px;
	padding-right: 4px;
	text-align: center;
	margin-top: 3px;
	margin-bottom: 3px;
	margin-left: 3px;
	margin-right: 3px;
	background-color: rgba(20,20,20,0.45);
	color: #f9f9ff;
	cursor: pointer;
}

.sourceList > input:checked ~ .keywordList > .foldTag:hover,
.keywordManagement > input:checked ~ .sourceList > .foldTag:hover,
.sourceList > input:checked ~ .keywordList > .foldTag:focus,
.keywordManagement > input:checked ~ .sourceList > .foldTag:focus,
.sourceList > input:checked ~ .keywordList > .foldTag:active,
.keywordManagement > input:checked ~ .sourceList > .foldTag:active
{
	background-color: rgba(30 ,30 ,30 ,0.4);
}

.sourceList > input:not(:checked) + .keywordList > .foldTag,
.keywordManagement > input:not(:checked) + .sourceList > .foldTag
{
	display: none;
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
	background-color: rgba(255,255,255,0.2);
}

.Tdisable
{
	background-color: rgba(255,255,255,0.2);
}

.tag:hover
{
	background-color: rgba(255,255,255,0.3);
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
	color: #f9f9ff;
	margin-right: 5px;
	margin-left: 2px;
}

.tag.Tdisable a
{
	color: rgba(230,230,230,0.55);
}

.tag input[type='submit']:nth-child(2)
{
	width: 12px;
	height: 12px;
	border: none;
	font-size: 0px;
	cursor: pointer;
	background: url('../images/Active.png') center no-repeat;
	background-size: contain;
	margin-left: 0;
	margin-right: 1px;
}

.tag input[type='submit']:nth-child(2):hover
{
	width: 12px;
	height: 12px;
	border: none;
	font-size: 0px;
	cursor: pointer;
	background: url('../images/Disable.png') center no-repeat;
	background-size: contain;
}

.tag.Tdisable input[type='submit']:nth-child(2)
{
	width: 12px;
	height: 12px;
	border: none;
	font-size: 0px;
	cursor: pointer;
	background: url('../images/Desactivated.png') center no-repeat;
	background-size: contain;
}

.tag.Tdisable input[type='submit']:nth-child(2):hover
{
	width: 12px;
	height: 12px;
	border: none;
	font-size: 0px;
	cursor: pointer;
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

.tag input[type='submit']:first-child
{
	width: 14px;
	height: 14px;
	cursor: pointer;
	border: none;
	font-size: 0px;
	background: url('../images/Trash.png') center no-repeat;
	background-size: contain;
	margin-left: -3px;
	margin-right: 2px;
}

.tag.Tdisable input[type='submit']:first-child
{
	width: 14px;
	height: 14px;
	cursor: pointer;
	border: none;
	font-size: 0px;
	background: url('../images/TrashDesactivated.png') center no-repeat;
	background-size: contain;
}

.tag input[type='submit']:first-child:hover
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
	margin-left: 2%;
	margin-bottom: 5px;
	margin-top: 7px;
}

.tagSource.Tactive
{
	background-color: rgba(235, 115, 43, 0.55);
}

.tagSource.Tdisable
{
	background-color: rgba(255,255,255,0.2);
}

.tagSource.Tdisable:hover
{
	background-color:  rgba(240,240,240,0.2);
}

.tagSource.Tdisable a
{
	color: rgba(230,230,230,0.55);
}

.tagSource.Tactive:hover
{
	background-color: rgba(235, 115, 43, 0.7);
}

.tagSource.Tactive:hover a
{
	color: rgb(230,230,230);
}

.tagSource a
{
	text-decoration: none;
	color: #f9f9ff;
	margin-right: 5px;
	margin-left: 2px;
}

.tagSource input[type='submit']:first-child
{
	width: 14px;
	height: 14px;
	cursor: pointer;
	border: none;
	font-size: 0px;
	background: url('../images/Trash.png') center no-repeat;
	background-size: contain;
	margin-left: -3px;
	margin-right: 2px;
}

.tagSource.Tdisable input[type='submit']:first-child
{
	width: 14px;
	height: 14px;
	cursor: pointer;
	border: none;
	font-size: 0px;
	background: url('../images/TrashDesactivated.png') center no-repeat;
	background-size: contain;
}

.tagSource input[type='submit']:first-child:hover
{
	width: 14px;
	height: 14px;
	cursor: pointer;
	border: none;
	font-size: 0px;
	background: url('../images/TrashRed.png') center no-repeat;
	background-size: contain;
}

.tagSource input[type='submit']:nth-child(2)
{
	width: 12px;
	height: 12px;
	cursor: pointer;
	border: none;
	font-size: 0px;
	background: url('../images/Active.png') center no-repeat;
	background-size: contain;
	margin-left: 0;
	margin-right: 1px;
}

.tagSource input[type='submit']:nth-child(2):hover
{
	width: 12px;
	height: 12px;
	cursor: pointer;
	border: none;
	font-size: 0px;
	background: url('../images/Disable.png') center no-repeat;
	background-size: contain;
}

.tagSource.Tdisable input[type='submit']:nth-child(2)
{
	width: 12px;
	height: 12px;
	cursor: pointer;
	border: none;
	font-size: 0px;
	background: url('../images/Desactivated.png') center no-repeat;
	background-size: contain;
}

.tagSource.Tdisable input[type='submit']:nth-child(2):hover
{
	width: 12px;
	height: 12px;
	cursor: pointer;
	border: none;
	font-size: 0px;
	background: url('../images/Activate.png') center no-repeat;
	background-size: contain;
}

.tagSource a:nth-child(3)
{
	border-left: 1px solid rgba(255, 255, 255, 0.2);
	padding-left: 3px;
}

.board
{
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: flex-start;
	width: 80%;
	margin-right: 70px;
	padding: 10px;
	background-color: rgba(0, 0, 0, 0.4);
	border-radius: 3px;
	border: 1px solid rgba(255,255,255,0.1);
	margin-top: 50px;
}
.mainSetting
{
	margin-left: auto;
	margin-right: auto;
}

.mainSettingOption
{
	display: flex;
	flex-direction: row;
	justify-content: flex-start;
	align-items: center;
	margin-top: 15px;
	margin-bottom: 15px;
}

.mainSettingOption a
{
	text-decoration: none;
	color: inherit;
}

.mainSettingOption a:hover
{
	color: #ffffff;
}

.optionList
{
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	width: 100%;
}

input:not(:checked) + .optionList > .unfoldOption,
input:checked + .optionList > .foldOption
{
	display: block;
	width: 96%;
	height: 25px;
	border-radius: 3px 3px 3px 3px;
	border: 1px solid rgba(255,255,255, 0.15);
	padding-top: 1px;
	padding-bottom: 2px;
	padding-left: 4px;
	padding-right: 4px;
	text-align: center;
	margin-top: 4px;
	margin-bottom: 4px;
	margin-left: 3px;
	background-color: rgba(20,20,20,0.45);
	color: #f9f9ff;
	cursor: pointer;
}

input:not(:checked) + .optionList > .unfoldOption:hover,
input:not(:checked) + .optionList > .unfoldOption:focus,
input:not(:checked) + .optionList > .unfoldOption:active,
input:checked + .optionList > .foldOption:hover,
input:checked + .optionList > .foldOption:focus,
input:checked + .optionList > .foldOption:active
{
	background-color: rgba(30 ,30 ,30 ,0.4);
}

input:not(:checked) + .optionList > .foldOption
{
	display: none;
}

input:not(:checked) + .optionList > .option
{
	display: none;
}

input:checked + .optionList > .unfoldOption
{
	display: none;
}

.option
{
	display: flex;
	flex-direction: row;
	justify-content: space-between;
	align-items: center;
	width: 95%;
}

.option > div
{
	height: 100%;
	width: 45%;
	display: flex;
	flex-direction: column;
	justify-content: flex-start;
}

.option > div > div
{
	margin-bottom: 35px;
}

#resultByEmail[type="checkbox"]:not(:checked),
#resultByEmail[type="checkbox"]:checked,
#resultBySMS[type="checkbox"]:not(:checked),
#resultBySMS[type="checkbox"]:checked
{
	display: none;
}

#resultByEmail[type="checkbox"]:not(:checked) + label,
#resultByEmail[type="checkbox"]:checked + label,
#resultBySMS[type="checkbox"]:not(:checked) + label,
#resultBySMS[type="checkbox"]:checked + label
{
	position: relative;
	width: 20px;
	height: 20px;
	margin-right: 15px;
	cursor: pointer;
}

#resultByEmail[type="checkbox"]:not(:checked) + label:before,
#resultBySMS[type="checkbox"]:not(:checked) + label:before
{
	content: '';
	position: absolute;
	left: 0;
	top: 0;
	height: 20px;
	width: 20px;
	border: 1px solid rgba(255,255,255, 0.15);
	background-color: rgba(255, 255, 255, 0.15);
	transition: all .3s;
}


#resultByEmail[type="checkbox"]:checked + label:before,
#resultBySMS[type="checkbox"]:checked + label:before
{
	content: '';
	position: absolute;
	left: 0;
	top: 0;
	height: 20px;
	width: 20px;
	border: 1px solid rgba(255,255,255, 0.15);
	background-color: rgb(46, 102, 48);
	transition: all .3s;
}

#resultByEmail[type="checkbox"]:not(:checked) + label:after,
#resultBySMS[type="checkbox"]:not(:checked) + label:after
{
	content: '';
	position: absolute;
	top: 0px;
	left: 0px;
}

#resultByEmail[type="checkbox"]:checked + label:after,
#resultBySMS[type="checkbox"]:checked + label:after
{
	content: '✓';
	position: absolute;
	top: -15px;
	left: -2px;
	font-size: 40px;
	color: #f9f9f9;
	text-align: center;
}

.board > div
{
	display: flex;
	flex-direction: row;
	justify-content: space-between;
	align-items: center;
	width: 100%;
}

.board > div > div
{
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	align-items: flex-start;
	width: 45%;
}

.board > div > div > div
{
	display: flex;
	flex-direction: row;
	justify-content: flex-start;
	align-items: center;
	width: 100%;
}

.shortSelect
{
	height: 28px;
	width: 55px;
	padding-right: 20px;
	text-transform: uppercase;
}

.shortSelect option
{
	text-transform: capitalize;
}

.bodySetting
{
	display: flex;
	flex-direction: row;
	justify-content: flex-start;
	width: 100%;
}

.bodyBoard
{
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	align-items: center;
	width: 100%;
}

.selectConfigType
{
	position: -webkit-sticky;
	position: sticky;
	display: flex;
	flex-direction: column;
	justify-content: flex-start;
	align-items: flex-start;
	left: 0;
	height: auto;
	top: 20vh;
}

.selectConfigType a
{
	width: 70px;
	height: 10vh;
	background-color: rgba(0, 0, 0, 0.5);
	text-decoration: none;
}

.selectConfigType a:hover
{
	background-color: rgba(0, 0, 0, 0);
}

.selectConfigType .active
{
	background-color: rgba(0, 0, 0, 0);
}

.selectConfigType a div
{
	display: flex;
	justify-content: center;
	align-items: flex-end;
	width: 40px;
	height: 8.9vh;
	color: #f9f9ff;
	text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);
	text-decoration: none;
	text-transform: uppercase;
	font-size: 10px;
}

.selectConfigTypePremium
{
	margin: auto;
	background: url('../images/icoPremium.png') center no-repeat;
	background-size: contain;
}

.selectConfigTypeSetting
{
	margin: auto;
	background: url('../images/icoSetting.png') center no-repeat;
	background-size: contain;
}

.selectConfigTypeWatchPack
{
	margin: auto;
	background: url('../images/icoAddPack.png') center no-repeat;
	background-size: contain;
}

.selectConfigTypeNews
{
	margin: auto;
	background: url('../images/icoNews.png') center no-repeat;
	background-size: contain;
}

.selectConfigTypeScience
{
	margin: auto;
	background: url('../images/icoSciences.png') center no-repeat;
	background-size: contain;
}

.selectConfigTypePatent
{
	margin: auto;
	background: url('../images/icoPatents.png') center no-repeat;
	background-size: contain;
}

.selectCommResult
{
	position: relative;
	width: fit-content;
	max-width: fit-content;
	padding: 0;
	padding-right: 8px;
	border: none;
	-moz-appearance: none;
	-webkit-appearance: none;
	-o-appearance: none;
	appearance: none;
	background-color: rgba(0,0,0,0);
	outline: none;
	z-index: 2;
}

.selectBackgroundBlock
{
	display: flex;
	flex-direction: row;
	justify-content: center;
	align-items: center;
}

.selectBackground
{
	position: relative;
	width: fit-content;
	max-width: fit-content;
	height: 65px;
	padding: 0;
	border: none;
	-moz-appearance: none;
	-webkit-appearance: none;
	-o-appearance: none;
	appearance: none;
	background-color: rgba(0,0,0,0);
	outline: none;
	z-index: 2;
}

.boxScroll
{
	display: flex;
	flex-direction: column;
	justify-content: center;
	height: 40px;
	width: 100%;
	overflow-y: scroll;
	overflow-x: hidden;
	margin-top: 2px;
	margin-bottom: 2px;
}

.boxScroll > div
{
	display: flex;
	flex-direction: row;
	flex-wrap: nowrap;
	justify-content: space-between;
	align-items: flex-start;
}

.arrDown
{
	position: relative;
	font-size: 12px;
	left: -11px;
	bottom: 1px;
	cursor: default;
	z-index: 1;
}

.arrDownBorder
{
	display: block;
	position: relative;
	font-size: 15px;
	left: -23px;
	bottom: 1px;
	cursor: default;
	z-index: 10;
	margin-right: -10px;
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
	margin-left: 4px;
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
	border-radius: 3px;
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
	height: 21px;
	width: 21px;
	left: -3px;
	bottom: -1px;
	background-color: rgb(230,230,230);
	box-shadow: 0px 0px 3px #302f2f;
	border-radius: 25%;
	transition: .6s;
}

input:checked + .slider
{
	background-color: rgba(43, 140, 34, 0.55);
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
	margin-top: 15px;
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
	width: 80px;
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
	width: 80%;
	margin-right: 70px;
	padding: 10px;
	background-color: rgba(0, 0, 0, 0.4);
	border-radius: 3px;
	border: 1px solid rgba(255,255,255,0.1);
	margin-top: 50px;
}

.patentManagement
{
	width: 80%;
	margin-right: 70px;
	padding: 10px;
	background-color: rgba(0, 0, 0, 0.4);
	border-radius: 3px;
	border: 1px solid rgba(255,255,255,0.1);
	margin-top: 50px;
}

.query
{
	flex-grow: 1;
	flex-shrink: 1;
	width: 20px;
}

.queryType
{
	flex-grow: 1;
	flex-shrink: 1;
	width: 50px;
	max-width: fit-content;
	font-size: 12px;
}

.lineQuery
{
	display: flex;
	flex-direction: row;
	justify-content: center;
	align-items: center;
	width: 100%;
}

.andOr
{
	margin-right: 12px;
}

[type="checkbox"]:not(:checked),
[type="checkbox"]:checked
{
	display: none;
}

[type="checkbox"]:not(:checked) + .andOr,
[type="checkbox"]:checked + .andOr
{
	position: relative;
	padding-left: 20px;
	cursor: pointer;
}

[type="checkbox"]:not(:checked) + .andOr:before
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


[type="checkbox"]:checked + .andOr:before
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

[type="checkbox"]:not(:checked) + .andOr:after
{
	content: 'AND';
	position: absolute;
	top: 4px;
	left: 2px;
	font-size: 13px;
	color: #f9f9ff;
	text-align: center;
}

[type="checkbox"]:checked + .andOr:after
{
	content: 'OR';
	position: absolute;
	top: 4px;
	left: 6px;
	font-size: 13px;
	color: #f9f9ff;
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
	display: none;
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

[type="radio"]:checked + label.dot:before
{
	content: '';
	position: absolute;
	left:0;
	top: -4px;
	height: 25px;
	width: 25px;
	border: 1px solid rgba(255,255,255, 0.15);
	background-color: rgba(43, 140, 34, 0.55);
	background: url('../images/dot.png') center no-repeat;
	background-size: contain;
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
	color: #f9f9ff;
	text-align: center;
}

.centerSubmit
{
	display: flex;
	justify-content: center;
	margin-top: 50px;
}

.newQueryContainer
{
	display: flex;
	flex-direction: row;
	justify-content: center;
	align-items: center;
	flex-wrap: wrap;
	min-height: 85px;
	margin-top: 10px;
	margin-bottom: 10px;
}

.newQueryContainer *
{
	margin-left: 5px;
}

.newQueryContainer > .submit
{
	margin-left: -9px;
}

.newQueryContainer label.andOr
{
	margin-left: 5px;
	margin-right: 12px;
	margin-top: 20px;
	margin-bottom: 43px;
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

.queryParenthesis
{
	font-size: 40px;
	padding-bottom: 7px;
	cursor: pointer;
	margin-left: 0;
	margin-right: -5px;
}

.ghostSpace
{
	width: 35px;
	height: 1px;
}

[type="checkbox"]:checked + .queryParenthesis
{
	color: rgba(245, 245, 245, 0.4);
	transition: all 0.6s;
}

[type="checkbox"]:not(:checked) + .queryParenthesis
{
	color: rgba(245, 245, 245, 0.09);
	transition: all 0.3s;
}

.ANDOrNot,
.andORNot,
.andOrNOT
{
	margin-right: 2px;
	margin-left: 2px;
}

[type="radio"]:not(:checked) + .ANDOrNot,
[type="radio"]:not(:checked) + .andORNot,
[type="radio"]:not(:checked) + .andOrNOT
{
	order: -1;
}

[type="radio"]:not(:checked) + .ANDOrNot:before
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

[type="radio"]:not(:checked) + .andORNot:before
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

[type="radio"]:not(:checked) + .andOrNOT:before
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

[type="radio"]:checked + .ANDOrNot:before
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

[type="radio"]:checked + .andORNot:before
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

[type="radio"]:checked + .andOrNOT:before
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

[type="radio"]:not(:checked) + .ANDOrNot:after
{
	content: 'AND';
	position: absolute;
	top: 2px;
	left: 2px;
	font-size: 10px;
	color: rgba(255,255,255,0.3);
	text-align: center;
}

[type="radio"]:not(:checked) + .andORNot:after
{
	content: 'OR';
	position: absolute;
	top: 2px;
	left: 5px;
	font-size: 10px;
	color: rgba(255,255,255,0.3);
	text-align: center;
}

[type="radio"]:not(:checked) + .andOrNOT:after
{
	content: 'NOT';
	position: absolute;
	top: 2px;
	left: 2px;
	font-size: 10px;
	color: rgba(255,255,255,0.3);
	text-align: center;
}

[type="radio"]:checked + .ANDOrNot:after
{
	content: 'AND';
	position: absolute;
	top: 2px;
	left: 2px;
	font-size: 10px;
	color: #f9f9ff;
	text-align: center;
}

[type="radio"]:checked + .andORNot:after
{
	content: 'OR';
	position: absolute;
	top: 2px;
	left: 5px;
	font-size: 10px;
	color: #f9f9ff;
	text-align: center;
}

[type="radio"]:checked + .andOrNOT:after
{
	content: 'NOT';
	position: absolute;
	top: 2px;
	left: 2px;
	font-size: 10px;
	color: #f9f9ff;
	text-align: center;
}

[type="radio"]:checked + .ANDOrNot
{
	margin-left: 20px;
	margin-right: 20px;
	order: 3;
}

[type="radio"]:checked + .andORNot
{
	margin-left: 20px;
	margin-right: 20px;
	order: 3;
}

[type="radio"]:checked + .andOrNOT
{
	margin-left: 20px;
	margin-right: 20px;
	order: 3;
}

.extend
{
	width: 30px;
	height: 30px;
	border-radius: 50%;
	font-size: 0;
	border: 1px solid rgba(255,255,255, 0.15);
	background: url('../images/extend.png') center no-repeat;
	background-size: contain;
	background-color: rgba(43, 140, 34, 0.55);
	color: #f9f9ff;
	cursor: pointer;
	margin-left: 7px;
}

.extend:hover
{
	background-color: rgba(43, 140, 34, 0.75);
}

.queryContainer
{
	display: flex;
	justify-content: flex-start;
	align-items: center;
	flex-wrap: wrap;
	padding: 10px;
	border: 1px solid rgba(255,255,255, 0.15);
	width: 97%;
}

.queryContainer a
{
	text-decoration: none;
	color: #f9f9ff;
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
	font-size: 0;
	cursor: pointer;
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
	font-size: 0;
	cursor: pointer;
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
	font-size: 0;
	cursor: pointer;
}

.deleteQuery:hover
{
	background: url('../images/TrashRed.png') center no-repeat;
	background-size: 20px;
	background-color: rgba(255,255,255, 0.2);
}

.queryParenthesisView
{
	display: flex;
	justify-content: center;
	align-items: center;
	color: rgba(245, 245, 245, 0.4);
	font-size: 40px;
	margin-top: -5px;
	margin-left: -7px;
	margin-right: 2px;
	cursor: pointer;
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
	color: #f9f9ff;
	font-size: 13px;
	margin-right: 10px;
	cursor: pointer;
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
	color: #f9f9ff;
	font-size: 13px;
	margin-right: 10px;
	cursor: pointer;
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
	color: #f9f9ff;
	font-size: 13px;
	margin-right: 10px;
	cursor: pointer;
}

.queryTypeView
{
	display: flex;
	justify-content: center;
	align-items: center;
	width: fit-content;
	background-color: rgba(20,20,20,0.45);
	border-radius: 3px;
	border: 1px solid rgba(255,255,255,0.2);
	padding: 4px;
	text-align: center;
	font-size: 13px;
	margin: 5px 0 5px 0;
	margin-right: 10px;
	cursor: pointer;
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
	cursor: pointer;
}

/* Modal premium window */

/* modal open|close controller */
#modal-controller
{
	position: absolute;
	left: -999vw;
	opacity: 0;
}

.modal-open:checked ~ .modal-wrap,
.modal-open:checked ~ .modal-wrap:before,
.modal-overlay
 {
	display: block;
}

.modal-close
{
	cursor: pointer;
	font-size: 35px;
	font-weight: bold;
	line-height: 20px;
	padding: 10px;
	position: absolute;
	top: 0;
	right: 0;
	z-index: 100;
}


/* modal */
.modal-wrap
{
	display: none;
}

.modal-wrap:before
{
	content: "";
	display: none;
	background: rgba(0, 0, 0, .9);
	position: fixed;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	z-index: 101;
}

.modal-overlay
{
	bottom: 0;
	display: none;
	left: 0;
	position: fixed;
	right: 0;
	top: 0;
	z-index: 102;
}

.modal-body
{
	position: fixed;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
	width: 75vw;
	height: 75vh;
	max-width: 1156px;
	max-height: 650px;
	padding: 15px;
	background: #323a44;
	border-radius: .25em;
	overflow: hidden;
	text-align: center;
	z-index: 103;
}

.modal-content
{
	margin: 5px 15px 50px;
	padding: 20px 0 0;
	position: relative;
	width: 100%;
}

.modal-content > div
{
	display: flex;
	flex-direction: row;
	justify-content: space-between;
	align-items: center;
	width: 100%;
	height: 80%;
	margin-bottom: 20px;
}

.modal-content > div > div
{
	max-width: 40%;
	margin-left: 10px;
	margin-right: 10px;
}

.modal-slide
{
	display: flex;
	position: absolute;
	transition:  all 0.4s ease-in;
	width: 100%;
	height: 100%;
}

.modal-content-2
{
	left: 100%;
}

.modal-content-3
{
	left: 200%;
}

#modal-content-1:checked ~ .content-1
{
	left: 0;
	overflow: auto;
	position: relative;
}
#modal-content-1:checked ~ .content-2
{
	left: 100%;
}

#modal-content-2:checked ~ .content-1
{
	left: -100%;
}

#modal-content-2:checked ~ .content-2
{
	left: 0;
	overflow: auto;
	position: relative;
}

#modal-content-2:checked ~ .content-3
{
	left: 100%;
}

#modal-content-3:checked ~ .content-1
{
	left: -200%;
}

#modal-content-3:checked ~ .content-2
{
	left: -100%;
}

#modal-content-3:checked ~ .content-3
{
	left: 0;
	overflow: auto;
	position: relative;
}

.modal-nav label,
label.prev-slide,
label.next-slide
{
	cursor: pointer;
	font-size: 110px;
	text-decoration: none;
	vertical-align: middle;
	text-align: center;
}

/**/

<?php
if (!empty($_SESSION['additionalStyle']))
{
	echo $_SESSION['additionalStyle'];
	$_SESSION['additionalStyle'] = '';
}

include('footer.php');
?>
