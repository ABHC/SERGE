<?php

$dataProcessing = FALSE;
if ((isset($_POST['nonce']) AND isset($_SESSION['nonce :' . $_POST['nonce']]) AND $_SESSION['nonce :' . $_POST['nonce']] == 0) OR
	 ((isset($_GET['nonce']) AND isset($_SESSION['nonce :' . $_GET['nonce']]) AND $_SESSION['nonce :' . $_GET['nonce']] == 0)))
{
	$dataProcessing = TRUE;
	foreach ($unsafeData as $varDetails)
	{
		if (empty($varDetails[2]))
		{
			$data[$varDetails[0]] = $varDetails[1];
		}
		elseif ($varDetails[2] == 'str')
		{
			$data[$varDetails[0]] = htmlspecialchars($varDetails[1]);
		}
	}

	if (!empty($_POST['nonce']))
	{
		$nonce = $_POST['nonce'];
	}
	elseif (!empty($_GET['nonce']))
	{
		$nonce = $_GET['nonce'];
	}

	unset($SESSION['nonce :' . $nonce]);
}
?>
