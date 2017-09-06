<?php

$dataProcessing = FALSE;
if ((isset($_POST['nonce']) AND isset($_SESSION['nonce :' . $_POST['nonce']]) AND $_SESSION['nonce :' . $_POST['nonce']] === 0) OR
	 ((isset($_GET['nonce']) AND isset($_SESSION['nonce :' . $_GET['nonce']]) AND $_SESSION['nonce :' . $_GET['nonce']] === 0)))
{
	$dataProcessing = TRUE;
	foreach ($unsafeData as $varDetails)
	{
		if (empty($varDetails[3]))
		{
			if ($varDetails[2] === 'POST')
			{
				$data[$varDetails[0]] = !empty($_POST[$varDetails[1]]) ? $_POST[$varDetails[1]]: "";
			}
			elseif ($varDetails[2] === 'GET')
			{
				$data[$varDetails[0]] = !empty($_GET[$varDetails[1]]) ? $_GET[$varDetails[1]]: "";
			}
		}
		elseif ($varDetails[3] === 'str')
		{
			if ($varDetails[2] === 'POST')
			{
				$data[$varDetails[0]] = !empty(htmlspecialchars($_POST[$varDetails[1]])) ? $_POST[$varDetails[1]]: "";
			}
			elseif ($varDetails[2] === 'GET')
			{
				$data[$varDetails[0]] = !empty(htmlspecialchars($_GET[$varDetails[1]])) ? $_GET[$varDetails[1]]: "";
			}
		}
		elseif ($varDetails[3] === 'email')
		{
			if ($varDetails[2] === 'POST')
			{
				$data[$varDetails[0]] = !empty(filter_var($_POST[$varDetails[1]], FILTER_SANITIZE_EMAIL)) ? $_POST[$varDetails[1]]: "";
			}
			elseif ($varDetails[2] === 'GET')
			{
				$data[$varDetails[0]] = !empty(filter_var($_GET[$varDetails[1]], FILTER_SANITIZE_EMAIL)) ? $_GET[$varDetails[1]]: "";
			}
		}
		elseif ($varDetails[3] === 'Az')
		{
			if ($varDetails[2] === 'POST')
			{
				$data[$varDetails[0]] = !empty(preg_replace("/[^A-Za-z]/", "", $_POST[$varDetails[1]])) ? $_POST[$varDetails[1]]: "";
			}
			elseif ($varDetails[2] === 'GET')
			{
				$data[$varDetails[0]] = !empty(preg_replace("/[^A-Za-z]/", "", $_GET[$varDetails[1]])) ? $_GET[$varDetails[1]]: "";
			}
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
