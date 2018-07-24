<?php
#include('../model/design.php')
?>

.submit-button
{
	width: 100%;
	max-width: 100%;
	background-color: rgba(84,26,102, 0.55);
	cursor: pointer;
	color: rgb(230,230,230);
	margin-top: 10px;
	font-size: 14px;
}

.submit-button:hover
{
	background-color: rgba(91, 37, 108, 0.75);
	color: rgb(255,255,255);
}

/* Responsive */
@media all and (max-width: 1000px)
{
	.submit-button
	{
		height: 50px;
		font-size: 30px;
	}
}
