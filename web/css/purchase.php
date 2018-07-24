<?php
header('content-type: text/css');
ob_start('ob_gzhandler');
header('Cache-Control: max-age=31536000, must-revalidate');

include('style.php');

include('nav.php');


// atoms

include('atoms/a.php');

include('atoms/background.php');

include('atoms/input.php');

include('atoms/icon-RSS.php');

include('atoms/icon-mail.php');

include('atoms/icon-SMS.php');

include('atoms/icon-twitter.php');

include('atoms/h3.php');

include('atoms/h4.php');

include('atoms/h5.php');

include('atoms/h6.php');

include('atoms/submit-button.php');

include('atoms/number.php');

include('atoms/alpha.php');

include('atoms/emoticon-sad.php');


// molecules
include('molecules/checkbox.php');

include('molecules/functionality-text.php');


// organisms

include('organisms/body.php');

include('organisms/window.php');

include('organisms/form-window.php');

include('organisms/functionality-line.php');



include('footer.php');
?>
