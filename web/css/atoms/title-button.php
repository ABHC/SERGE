<?php
#include('../model/design.php')
?>

.title-button
{
	display: flex;
	justify-content: center;
	align-items: center;
	text-align: center;
	width: 30%;
	background-color: rgb(230, 230, 230);
	padding: 10px;
	margin-top: 100px;
	margin-bottom: 5px;
	border: 1px solid rgba(40,40,40,0.15);
	border-radius: 3px;
	font-size: 20px;
	color: rgb(40, 40, 40);
	cursor: default;
}

/* Responsive */
@media all and (max-width: 1000px)
{
	.titleButton
	{
		margin-top: 50px;
	}
}
