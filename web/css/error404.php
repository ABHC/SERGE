<?php
header('content-type: text/css');
ob_start('ob_gzhandler');
header('Cache-Control: max-age=31536000, must-revalidate');

include('style.php');

include('nav.php');


// Atoms

include('atoms/background.php');

include('atoms/error404.php');


// Organisms

include('organisms/body.php');


include('footer.php');
?>
