<?php

/*include_once('model/get_text.php');*/

$setting="active";

if (!isset($_SESSION['pseudo']))
{
	header('Location: connection.php?redirectFrom=setting');
}

include_once('view/nav/nav.php');

include_once('view/body/setting.php');

include_once('view/footer/footer.php');

?>
