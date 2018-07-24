<?php
#include('../model/design.php')
?>

.body
{
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	min-height: calc(100vh - 100px);
	color: #f9f9f9<?php #get_color('global-text'); ?>;
	padding-top: 25px;
}

/* Responsive */
@media all and (max-width: 1000px)
{
	.body
	{
		min-height: calc(100vh - 500px);
		margin-top: 40px;
	}
}
