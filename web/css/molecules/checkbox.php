<?php
#include('../model/design.php')
?>

[type="checkbox"]:not(:checked),
[type="checkbox"]:checked
{
	display: none;
}

[type="checkbox"]:not(:checked) + label,
[type="checkbox"]:checked + label
{
	position: relative;
	top: 0;
	width: 12px;
	height: 12px;
	cursor: pointer;
}

[type="checkbox"]:not(:checked) + label:before
{
	content: '';
	position: absolute;
	left: 0;
	top: 0;
	height: 12px;
	width: 12px;
	border: 1px solid rgba(255,255,255, 0.15);
	border-radius: 2px;
	background-color: rgba(255, 255, 255, 0.15);
	transition: all .3s;
}


[type="checkbox"]:checked + label:before
{
	content: '';
	position: absolute;
	left: 0;
	top: 0;
	height: 12px;
	width: 12px;
	border: 1px solid rgba(255,255,255, 0.15);
	border-radius: 3px;
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
	font-size: 25px;
	color: #d5d3d3;
	text-align: center;
}
