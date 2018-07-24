<?php
header('content-type: text/css');
ob_start('ob_gzhandler');
header('Cache-Control: max-age=31536000, must-revalidate');

include('style.php');

include('nav.php');


// atoms

include('atoms/background.php');

include('atoms/background-index-image.php');

include('atoms/h1.php');

include('atoms/h3.php');

include('atoms/h4.php');

include('atoms/h5.php');

include('atoms/line.php');

include('atoms/icon-RSS.php');

include('atoms/icon-patent.php');

include('atoms/icon-science.php');

include('atoms/icon-mail.php');

include('atoms/icon-option.php');

include('atoms/icon-history.php');

include('atoms/icon-SMS.php');

include('atoms/icon-twitter.php');

include('atoms/icon-wiki.php');

include('atoms/icon-stats.php');

include('atoms/button-internal-link.php');

include('atoms/title-button.php');

include('atoms/single-field-submit-button.php');

include('atoms/single-field.php');

include('atoms/input.php');

include('atoms/submit-button.php');

include('atoms/space.php');


// molecules

include('molecules/captcha.php');

include('molecules/center-area.php');

include('molecules/functionality-text.php');

include('molecules/input-single-field.php');


// organisms

include('organisms/body.php');

include('organisms/background-index-details.php');

include('organisms/functionality-line.php');

include('organisms/form-window.php');



include('footer.php');
?>
