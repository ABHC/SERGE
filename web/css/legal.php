<?php
header('content-type: text/css');
ob_start('ob_gzhandler');
header('Cache-Control: max-age=31536000, must-revalidate');

include('style.php');

include('nav.php');


// atoms

include('atoms/background.php');

include('atoms/h3.php');


// organisms

include('organisms/body.php');

include('organisms/window.php');


include('footer.php');
?>
