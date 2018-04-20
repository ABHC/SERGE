<?php
preg_match("/Search for /", $status, $test);
if ($test[0] == 'Search for ')
{
	?>
	<body onLoad="setTimeout(function(){location.href='/statusOfSourceAddition';}, '3000')">
		<img alt="Loading" title="Loading please wait" src="../images/pictogrammes/pictoLoading.gif" width="20px" height="20px"/>&nbsp;
		<?php
		$textStatus = preg_replace("/: .+/", "", $status);
		$link       = preg_replace("/.+ :/", "", $status) ?? '';
		get_t($textStatus, $bdd);
		echo htmlspecialchars($link);
		?>
	</body>
	<?php
}
elseif ($status == 'END')
{
	include('model/updateStatusOfSourceAddition.php');
	?>
	<body onload="parent.document.location.href='setting';"></body>
	<?php
}
elseif (!empty($status))
{
	?>
	<body>
		<?php
		include('model/updateStatusOfSourceAddition.php');
		$textStatus = preg_replace("/: .+/", "", $status);
		$link       = preg_replace("/.+ :/", "", $status) ?? '';
		get_t($textStatus, $bdd);
		echo htmlspecialchars($link);
		?>
	</body>
	<?php
}
?>
