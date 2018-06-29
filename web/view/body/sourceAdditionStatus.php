<?php
preg_match("/Search for /", $status, $test);
if ($test[0] == 'Search for ')
{
	?>
	<img alt="Loading" title="Loading please wait" src="../images/pictogrammes/pictoLoading.gif" width="20px" height="20px"/>&nbsp;
	<?php
	$textStatus = preg_replace("/: .+/", "", $status);
	$link       = preg_replace("/.+ :/", "", $status) ?? '';
	get_t($textStatus, $bdd);
	echo htmlspecialchars($link);
}
elseif ($status == 'END')
{
	echo 'END';
}
elseif (!empty($status))
{
	include('model/updateSourceAdditionStatus.php');
	$textStatus = preg_replace("/: .+/", "", $status);
	$link       = preg_replace("/.+ :/", "", $status) ?? '';
	get_t($textStatus, $bdd);
	echo htmlspecialchars($link);
}
?>
