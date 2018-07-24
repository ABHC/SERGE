<?php
header('content-type: text/css');
ob_start('ob_gzhandler');
header('Cache-Control: max-age=31536000, must-revalidate');

include('style.php');

include('nav.php');


// atoms

include('atoms/background.php');

include('atoms/h3.php');

include('atoms/a.php');

include('atoms/input.php');

include('atoms/textarea.php');

include('atoms/submit-button.php');


// molecules

include('molecules/captcha.php');


// organisms

include('organisms/body.php');

include('organisms/form-window.php');


include('footer.php');
?>
