<?php
#include('../model/design.php')
?>

input
{
	width: calc(100% - 8px);
	max-width: calc(100% - 8px);
	border-radius: 3px;
	border: 1px solid rgba(255,255,255, 0.15);
	padding: 4px;
	background-color: rgba(255, 255, 255, 0.2);
	color: #f9f9f9;
	font-size: 15px;
}

/* Responsive */
@media all and (max-width: 1000px)
{
	input
	{
		height: 40px;
		font-size: 28px;
	}
}
