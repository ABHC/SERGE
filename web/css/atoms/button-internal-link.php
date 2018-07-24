<?php
#include('../model/design.php')
?>

.button-internal-link
{
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	width: 20%;
	height: 80%;
	text-align: center;
	background-color: rgba(84,26,102, 0.55);
	border: 1px solid rgba(205, 205, 205, 0.05);
	padding: 5px;
	margin-left: 15px;
	margin-right: 15px;
	cursor: pointer;
	border-radius: 3px;
	font-size: 22px;
}

.button-internal-link:hover
{
	background-color: rgba(91, 37, 108, 0.75);
}


/* Responsive */
@media all and (max-width: 1000px)
{
	.button-internal-link
	{
		margin-bottom: 20px;
	}
}
