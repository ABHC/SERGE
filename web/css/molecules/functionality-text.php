<?php
session_start();

#include('../model/design.php')
?>

.functionality-text
{
	display: flex;
	flex-direction: column;
	justify-content: flex-end;
	align-items: flex-start;
	width: 100%;
	height: 140px;
	overflow: hidden;
	font-size: 15px;
	color: rgb(220, 220, 220);
}

.functionality-text div
{
	display: flex;
	flex-direction: column;
	justify-content: flex-start;
	align-items: flex-start;
	height: 120px;
	width: 100%;
}


/* Responsive */
@media all and (max-width: 1000px)
{
	.functionality-text
	{
		height: 200px;
		font-size: 25px;
	}

	.functionality-text div
	{
		height: auto;
	}
}
