<?php
header('content-type: text/css');
ob_start('ob_gzhandler');
header('Cache-Control: max-age=31536000, must-revalidate');

include('../model/randomBackground.php');

include('style.php');

include('nav.php');


// atoms

include('atoms/background.php');

include('atoms/input.php');

include('atoms/select.php');

include('atoms/tile-news.php');

include('atoms/tile-sciences.php');

include('atoms/tile-patents.php');

include('atoms/tile-export.php');

include('atoms/submit-button-alpha.php');


include('atoms/button-submit-trash.php');

include('atoms/wiki-logo.php');


// molecules

include('molecules/aside-sticky-nav.php');

include('molecules/aside-nav-background.php');

include('molecules/table-header.php');

include('molecules/table-content.php');

include('molecules/table-data.php');

include('molecules/checkbox.php');

include('molecules/table-special-result-page.php');

include('molecules/queries-display.php');

include('molecules/pages-numbers.php');


// organisms

include('organisms/body.php');

include('organisms/form-window.php');

include('organisms/body-row.php');

include('organisms/table.php');


include('footer.php');
?>
