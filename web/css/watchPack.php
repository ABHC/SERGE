<?php
session_start();
header('content-type: text/css');
ob_start('ob_gzhandler');
header('Cache-Control: max-age=31536000, must-revalidate');

//include_once('../model/connexion_sql.php');

//include_once('../model/design.php');

include_once('style.php');

include_once('nav.php');

if ($_SESSION['type'] == 'create')
{
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

	.selectType
	{
		position: absolute;
		display: flex;
		flex-direction: column;
		justify-content: flex-start;
		align-items: flex-start;
		left: 0;
		width: 80px;
		height: auto;
		margin-top: 20px;
	}

	.selectType a
	{
		width: 80px;
		height: 98px;
		background-color: rgba(0, 0, 0, 0.5);
		text-decoration: none;
	}

	.selectType a:hover
	{
		background-color: rgba(0, 0, 0, 0);
	}

	.selectType .active
	{
		background-color: rgba(0, 0, 0, 0);
	}

	.selectType a div
	{
		display: flex;
		justify-content: center;
		align-items: flex-end;
		width: 70px;
		height: 95px;
		color: #fff;
		text-decoration: none;
		text-transform: uppercase;
		font-size: 10px;
	}

	.selectTypeAddPack
	{
		margin: auto;
		background: url('../images/icoAddPack.png') center no-repeat;
		background-size: contain;
	}

	.selectTypeCreatePack
	{
		margin: auto;
		background: url('../images/icoPackCreation.png') center no-repeat;
		background-size: contain;
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

	form
	{
		width: 100%;
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		align-items: center;
	}

	input
	{
		border-radius: 3px;
		border: 1px solid rgba(255,255,255, 0.15);
		padding: 4px;
		background-color: rgba(255, 255, 255, 0.2);
		color: #fff;
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
		color: #fff;
		-moz-appearance: none;
		-webkit-appearance: none;
		-o-appearance: none;
		appearance: none;
		background-color: rgba(0,0,0,0);
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

	select#sourceKeyword
	{
		flex-grow: 1;
		flex-shrink: 1;
		background-color: rgba(255,89,0,0.4);
		height: 27px;
		margin-left: 10px;
		margin-right: 10px;
		font-size: 14px;
	}

	select#sourceKeyword option
	{
		background-color: rgba(131, 49, 5, 0.8);
	}

	input#email
	{
		width: 80%;
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
		font-size: 0;
		border: 1px solid rgba(255,255,255, 0.15);
		background: url('../images/+.png') center no-repeat;
		background-size: contain;
		background-color: rgb(6, 85, 124);
		cursor: pointer;
		margin-right: 5px;
	}

	.submit:hover
	{
		background-color: rgba(0,99,149, 0.55);
	}

	.newsInput
	{
		display: flex;
		justify-content: flex-start;
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
		width: 40%;
		max-width: 150px;
		height: 40px;
		color: rgb(230,230,230);
		text-decoration: none;
		text-align: center;
		background-color: rgba(0,170,255, 0.4);
		border-radius: 3px;
		border: 1px solid rgba(255,255,255, 0.15);
	}

	.buttonCreatesourcePack:hover
	{
		background-color: rgba(0,170,255, 0.5);
		color: #fff;
	}

	.buttonVisiteCommunitySourcePack
	{
		width: 40%;
		max-width: 150px;
		height: 40px;
		color: rgb(230,230,230);
		text-decoration: none;
		text-align: center;
		background-color: rgba(0,170,127,0.4);
		border-radius: 3px;
		border: 1px solid rgba(255,255,255, 0.15);
	}

	.buttonVisiteCommunitySourcePack:hover
	{
		background-color: rgba(0,170,127,0.5);
		color: #fff;
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
		width: calc(80% - 20px);
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
		margin-bottom: 10px;
		width: 98%;
		height: 30px;
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
		width: 200px;
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
		background-color: rgba(0,120,176,0.4);
		color: #fff;
		cursor: pointer;
	}

	.keywordManagement > input:not(:checked) ~ .sourceList > .unfoldTag,
	.keywordManagement > input:checked ~ .sourceList > .foldTag
	{
		display: block;
		width: 96%;
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
		margin-right: 2%;
		background-color: rgba(0,120,176,0.4);
		color: #fff;
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
		background-color: rgba(0, 85, 127, 0.4);
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
		background-color: rgba(0,120,176,0.4);
		color: #fff;
		cursor: pointer;
	}

	.sourceList > input:checked ~ .keywordList > .foldTag:hover,
	.keywordManagement > input:checked ~ .sourceList > .foldTag:hover,
	.sourceList > input:checked ~ .keywordList > .foldTag:focus,
	.keywordManagement > input:checked ~ .sourceList > .foldTag:focus,
	.sourceList > input:checked ~ .keywordList > .foldTag:active,
	.keywordManagement > input:checked ~ .sourceList > .foldTag:active
	{
		background-color: rgba(0, 85, 127, 0.4);
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
		background-color: rgba(255,89,0,0.4);
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

	.communicationResults
	{
		width: calc(80% - 20px);
		padding: 10px;
		background-color: rgba(0, 0, 0, 0.4);
		border-radius: 3px;
		border: 1px solid rgba(255,255,255,0.1);
		margin-top: 50px;
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
		font-size: 12px;
		left: -20px;
		bottom: 1px;
		cursor: default;
		z-index: 1;
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
		margin-top: 50px;
	}

	.patentManagement
	{
		width: calc(80% - 20px);
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
		color: #fff;
		text-align: center;
	}

	[type="checkbox"]:checked + .andOr:after
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

	.ANDOrNotand,
	.andORNotand,
	.andOrNOTAND
	{
		margin-right: 2px;
		margin-left: 2px;
	}

	[type="radio"]:not(:checked) + .ANDOrNotand,
	[type="radio"]:not(:checked) + .andORNotand,
	[type="radio"]:not(:checked) + .andOrNOTAND
	{
		order: -1;
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
		order: 3;
	}

	[type="radio"]:checked + .andORNotand
	{
		margin-left: 20px;
		margin-right: 20px;
		order: 3;
	}

	[type="radio"]:checked + .andOrNOTAND
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
		border: 1px solid rgba(255,255,255, 0.15);
		background-color: rgba(0,170,0,0.5);
		color: #fff;
		font-size: 15px;
		font-weight: 700;
		cursor: pointer;
		margin-left: 7px;
	}

	.extend:hover
	{
		background-color: rgba(6, 140, 6, 0.5);
	}

	.scientificPublicationManagement > .newQueryContainer > .lineQuery > .extend
	{
		margin-right: 28px;
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

<?php
}
else
{
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

	form.formSearch
	{
		display: flex;
		flex-direction: row;
		justify-content: center;
		align-items: center;
		width: 100%;
	}

	.selectType
	{
		position: absolute;
		display: flex;
		flex-direction: column;
		justify-content: flex-start;
		align-items: flex-start;
		left: 0;
		width: 80px;
		height: auto;
		margin-top: 20px;
	}

	.selectType a
	{
		width: 80px;
		height: 98px;
		background-color: rgba(0, 0, 0, 0.5);
		text-decoration: none;
	}

	.selectType a:hover
	{
		background-color: rgba(0, 0, 0, 0);
	}

	.selectType .active
	{
		background-color: rgba(0, 0, 0, 0);
	}

	.selectType a div
	{
		display: flex;
		justify-content: center;
		align-items: flex-end;
		width: 70px;
		height: 95px;
		color: #fff;
		text-decoration: none;
		text-transform: uppercase;
		font-size: 10px;
	}

	.selectTypeAddPack
	{
		margin: auto;
		background: url('../images/icoAddPack.png') center no-repeat;
		background-size: contain;
	}

	.selectTypeCreatePack
	{
		margin: auto;
		background: url('../images/icoPackCreation.png') center no-repeat;
		background-size: contain;
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
		width: 60px;
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
		color: #fff;
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

	select
	{
		position: relative;
		width: 21px;
		height: 26px;
		font-size: 12px;
		border: none;
		color: #fff;
		text-transform: uppercase;
		-moz-appearance: none;
		-webkit-appearance: none;
		-o-appearance: none;
		appearance: none;
		background-color: rgba(0,0,0,0);
		outline: none;
		z-index: 2;
		text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);
	}

	select:focus
	{
		outline: none;
		outline-style: none
	}

	select option
	{
		background-color: rgb(51,59,68);
		text-transform: capitalize;
		border: none;
		outline: none;
		outline-style: none
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

	th:nth-child(6)
	{
		width: 25px;
	}

	th select
	{
		cursor: pointer;
	}

	th:nth-child(7)
	{
		width: 100px;
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
		width: 15%;
	}

	th:nth-child(4)
	{
		width: 15%;
	}

	td
	{
		flex: 1;
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
	}

	td:nth-child(3)
	{
		width: 15%;
	}

	td:nth-child(4)
	{
		width: 15%;
	}

	td:nth-child(6)
	{
		width: 25px;
	}

	td:nth-child(7)
	{
		width: 100px;
	}

	input[type='submit'].star
	{
		width: 25px;
		height: auto;
		cursor: pointer;
		background: none;
		border: none;
		font-size: 19px;
		color: #fff;
		text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);
	}

	input[type='submit'].star.colorStar
	{
		color: rgb(15, 15, 15);
		text-shadow: 0 0 0 rgba(0, 0, 0, 0);
	}

	input[type='submit'].star:hover
	{
		color: rgb(15, 15, 15);
		text-shadow: 0 0 0 rgba(0, 0, 0, 0);
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
		color: #fff;
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
		color: #fff;
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
		color: #fff;
		font-size: 9px;
		margin-right: 3px;
	}

	.pages
	{
		display: flex;
		flex-direction: row;
		justify-content: space-around;
		align-items: center;
		color: #fff;
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
		color: #fff;
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

<?php
}

include_once('footer.php');
?>