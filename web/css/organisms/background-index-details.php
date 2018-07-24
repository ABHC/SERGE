<?php
#include('../model/design.php')
?>

.background-index-details
{
	position: relative;
	width: calc(100% - 0.5vw);
	padding-right: 0.5vw;
	height: calc(100vh - 75px);
	display: flex;
	flex-direction: column;
	justify-content: flex-end;
	align-items: flex-end;
}


/* Responsive */
@media all and (max-width: 1000px)
{
	.background-index-details
	{
		height: calc(52vh - 140px);
		min-height: 360px;
		max-height: 860px;
		font-family: Verdana, Geneva, sans-serif;
	}
}

/* Responsive */
@media all and (max-width: 1000px)and (orientation: landscape)
{
	.background-index-details
	{
		height: calc(100vh - 140px);
	}
}
