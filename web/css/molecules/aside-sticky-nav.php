<?php
#include('../model/design.php')
?>

.aside-sticky-nav
{
	position: -webkit-sticky;
	position: sticky;
	display: flex;
	flex-direction: column;
	justify-content: flex-start;
	align-items: flex-start;
	left: 0;
	width: 0;
	height: auto;
	top: 20vh;
}

.aside-sticky-nav a,
.aside-sticky-nav .selectExportType
{
	width: 70px;
	height: 15vh;
	background-color: rgba(0, 0, 0, 0.5);
	text-decoration: none;
}

.aside-sticky-nav a:hover,
.aside-sticky-nav .selectExportType:hover
{
	background-color: rgba(0, 0, 0, 0);
}

.aside-sticky-nav .active
{
	background-color: rgba(0, 0, 0, 0);
}

.aside-sticky-nav a div
{
	display: flex;
	justify-content: flex-end;
	align-items: center;
	width: 40px;
	height: 15vh;
	color: #f9f9ff;
	text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);
	text-decoration: none;
	text-transform: uppercase;
	font-size: 10px;
}
