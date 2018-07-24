<?php
#include('../model/design.php')
?>

.body-row
{
	display: flex;
	flex-direction: row;
	justify-content: flex-start;
	width: 100%;
	min-height: calc(100vh - 100px);
	color: #f9f9f9<?php #get_color('global-text'); ?>;
}
