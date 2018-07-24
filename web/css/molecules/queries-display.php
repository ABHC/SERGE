<?php
#include('../model/design.php')
?>

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
