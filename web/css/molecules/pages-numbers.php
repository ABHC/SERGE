<?php
#include('../model/design.php')
?>

.pages
{
	display: flex;
	flex-direction: row;
	justify-content: space-around;
	align-items: center;
	color: #f9f9ff;
}

.page-number
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


.current
{
	font-weight: bold;
	border: 1px solid rgba(255,255,255,0.5);
}

.speedPage
{
	font-weight: bold;
}
