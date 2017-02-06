<?php
header('content-type: text/css');
ob_start('ob_gzhandler');
header('Cache-Control: max-age=31536000, must-revalidate');

include_once('../model/connexion_sql.php');

include_once('../model/design.php');

include_once('style.php');

include_once('nav.php');
?>

.body
{
	position: relative;
	min-height: 100vh;
	margin-top: -75px;
	padding-top: 75px;
	z-index: -1;
	/*background: linear-gradient(to right, rgba(0, 191, 99, 0.35), rgba(0, 171, 187, 0.35));*/
}

<?php
include_once('footer.php');
?>
