<?php
#include('../model/design.php')
?>

.background-index-image
{
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100vh;
	margin: 0;
	background: url(/images/background/intro.png) scroll no-repeat center;
	background-size: cover;
	z-index: -1;
}


/* Responsive */
@media all and (max-width: 1000px)
{
	.background-index-image
	{
		height: 52vh;
		min-height: 500px;
		max-height: 1000px;
		filter: brightness(0.8);
		background: url(../images/background/intro_small.png) scroll no-repeat center;
	}
}

/* Responsive */
@media all and (max-width: 1000px)and (orientation: landscape)
{
	.background-index-image
	{
		height: 100vh;
	}
}
