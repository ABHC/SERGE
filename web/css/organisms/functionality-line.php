<?php
#include('../model/design.php')
?>

.functionality-line
{
	display: flex;
	flex-direction: row;
	justify-content: center;
	align-items: center;
	width: 90%;
	height: 140px;
	margin-bottom: 6vw;
	margin-top: 50px;
}

.functionality-line > div
{
	display: flex;
	flex-direction: row;
	justify-content: flex-start;
	width: 47%;
	min-width: 400px;
	margin-right: 4%;
	margin-left: 4%;
}


/* Responsive */
@media all and (max-width: 1000px)
{
	.functionality-line
	{
		flex-wrap: wrap;
		height: auto;
		width: 90%;
		margin-bottom: 0;
		overflow: hidden;
	}

	.functionality-line > div
	{
		display: flex;
		flex-direction: row;
		justify-content: center;
		width: 100%;
		min-width: 0;
		margin-right: 2%;
		margin-left: 2%;
		margin-bottom: 20px;
	}

	.functionality-line > div > div:first-child
	{
		height: 200px;
		width: 200px;
	}
}
