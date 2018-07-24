<?php
#include('../model/design.php')
?>

.form-window
{
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	align-items: flex-start;
	border-radius: 3px;
	background-color: rgba(0,0,0,0.4);
	width: 40%;
	padding: 10px;
	margin-bottom: 25px;
}

form
{
	width: 100%;
}

.form-window form > div
{
	margin-top: 30px;
	margin-bottom: 10px;
}

/* Responsive */
@media all and (max-width: 1000px)
{
	.form-window
	{
		width: 80%;
	}
}
