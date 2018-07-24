<?php
#include('../model/design.php')

$backgroundName = $background[0]['filename'] ?? 'dark.png';
?>

.background
{
	position: fixed;
	top: 0;
	left: 0;
	margin: 0;
	width: 100%;
	height: 100vh;
	z-index: -1;
	background: url('/images/background/<?php echo $backgroundName ?>') center no-repeat;
	background-size: cover;
}

.sub-background
{
	position: fixed;
	top: 0;
	left: 0;
	margin: 0;
	width: 100%;
	height: 100vh;
	z-index: -1;
	background-color: rgba(0, 0, 0, 0.25);
}
