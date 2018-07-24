<?php
session_start();

#include('../model/design.php')
?>

.captcha
{
	position: relative;
	display: flex;
	flex-direction: row;
	justify-content: space-around;
	align-items: center;
	width: 100px;
	margin-left: calc(100% - 100px);
	margin-left: 0;
	border-radius: 0 3px 3px 0;
	border: 1px solid rgba(255,255,255, 0.15);
	background-color: rgba(255, 255, 255, 0.2);
	background-size: cover;
}

.captcha1
{
	position: relative;
	background-image: url(/images/captcha/<?php echo 'captcha_' . $_SESSION['REQUEST_PAGE_TIME'] . '_' . session_id() . '_1.png'; ?>);
	height: 22px;
	width: 22px;
	background-size: cover;
}

.captcha2
{
	position: relative;
	background-image: url(/images/captcha/<?php echo 'captcha_' . $_SESSION['REQUEST_PAGE_TIME'] . '_' . session_id() . '_2.png'; ?>);
	height: 22px;
	width: 22px;
	background-size: cover;
}

.captcha3
{
	position: relative;
	background-image: url(/images/captcha/<?php echo 'captcha_' . $_SESSION['REQUEST_PAGE_TIME'] . '_' . session_id() . '_3.png'; ?>);
	height: 22px;
	width: 22px;
	background-size: cover;
}

.captcha4
{
	background-image: url(/images/captcha/<?php echo 'captcha_' . $_SESSION['REQUEST_PAGE_TIME'] . '_' . session_id() . '_4.png'; ?>);
	position: relative;
	height: 22px;
	width: 22px;
	border-radius: 0 2px 2px 0;
	background-size: cover;
}

.captcha-field
{
	width: calc(100% - 100px);
	border-radius: 2px 0px 0px 2px;
}

/* Responsive */
@media all and (max-width: 1000px)
{
	.captcha
	{
		width: 140px;
	}

	.captcha1,
	.captcha2,
	.captcha3,
	.captcha4
	{
		height: 30px;
		width: 30px;
	}
}

<?php unset($_SESSION['REQUEST_PAGE_TIME']); ?>
