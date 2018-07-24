<?php
#include('../model/design.php')
?>

.window
{
	align-items: center;
	background-color: rgba(0, 0, 0, 0.4);
	border: 1px solid rgba(255,255,255,0.1);
	border-radius: 3px;
	display: flex;
	flex-direction: row;
	justify-content: space-around;
	margin-top: 25px;
	margin-bottom: 50px;
	max-width: 1500px;
	padding: 10px;
	width: 80%;
}

.window > div
{
	align-items: flex-start;
	display: flex;
	flex-direction: column;
	justify-content: flex-start;
	flex: 1;
}

.window > div > div
{
	margin-top: 15px;
	margin-bottom: 15px;
}
