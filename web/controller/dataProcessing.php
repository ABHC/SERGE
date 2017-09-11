<?php
// Processing data with nonce
$_POST['nonce'] = isset($_POST['nonce']) ? $_POST['nonce']: '';
$nonce = preg_replace("/[^a-z0-9]/", '', $_POST['nonce']);
if (!empty($nonce) AND isset($_SESSION['nonce :' . $nonce]) AND $_SESSION['nonce :' . $nonce] === 0)
{
	foreach ($unsafeData as $varDetails)
	{
		if (empty($varDetails[3]) AND $varDetails[2] === 'POST')
		{
			$data[$varDetails[0]] = !empty($_POST[$varDetails[1]]) ? $_POST[$varDetails[1]]: '';
		}
		elseif ($varDetails[3] === 'str' AND $varDetails[2] === 'POST')
		{
			$data[$varDetails[0]] = !empty(htmlspecialchars($_POST[$varDetails[1]])) ? htmlspecialchars($_POST[$varDetails[1]]): '';
		}
		elseif ($varDetails[3] === 'email' AND $varDetails[2] === 'POST')
		{
			$data[$varDetails[0]] = !empty(filter_var($_POST[$varDetails[1]], FILTER_SANITIZE_EMAIL)) ? filter_var($_POST[$varDetails[1]], FILTER_SANITIZE_EMAIL): '';
		}
		elseif ($varDetails[3] === 'url' AND $varDetails[2] === 'POST')
		{
			$data[$varDetails[0]] = !empty(filter_var($_POST[$varDetails[1]], FILTER_SANITIZE_URL)) ? filter_var($_POST[$varDetails[1]], FILTER_SANITIZE_URL): '';
		}
		elseif ($varDetails[3] === 'Az' AND $varDetails[2] === 'POST')
		{
			$data[$varDetails[0]] = !empty(preg_replace("/[^A-Za-z]/", '', $_POST[$varDetails[1]])) ? preg_replace("/[^A-Za-z]/", '', $_POST[$varDetails[1]]): '';
		}
		elseif ($varDetails[3] === '09' AND $varDetails[2] === 'POST')
		{
			$data[$varDetails[0]] = !empty(preg_replace("/[^0-9]/", '', $_POST[$varDetails[1]])) ? preg_replace("/[^0-9]/", '', $_POST[$varDetails[1]]): '';
		}
	}

	unset($SESSION['nonce :' . $nonce]);
}

// Processing data without nonce
foreach ($unsafeData as $varDetails)
{
	if (empty($varDetails[3]) AND $varDetails[2] === 'GET')
	{
		$data[$varDetails[0]] = !empty($_GET[$varDetails[1]]) ? $_GET[$varDetails[1]]: '';
	}
	elseif ($varDetails[3] === 'str' AND $varDetails[2] === 'GET')
	{
		$data[$varDetails[0]] = !empty(htmlspecialchars($_GET[$varDetails[1]])) ? htmlspecialchars($_GET[$varDetails[1]]): '';
	}
	elseif ($varDetails[3] === 'email' AND $varDetails[2] === 'GET')
	{
		$data[$varDetails[0]] = !empty(filter_var($_GET[$varDetails[1]], FILTER_SANITIZE_EMAIL)) ? filter_var($_GET[$varDetails[1]], FILTER_SANITIZE_EMAIL): '';
	}
	elseif ($varDetails[3] === 'url' AND $varDetails[2] === 'GET')
	{
		$data[$varDetails[0]] = !empty(filter_var($_GET[$varDetails[1]], FILTER_SANITIZE_URL)) ? filter_var($_GET[$varDetails[1]], FILTER_SANITIZE_URL): '';
	}
	elseif ($varDetails[3] === 'Az' AND $varDetails[2] === 'GET')
	{
		$data[$varDetails[0]] = !empty(preg_replace("/[^A-Za-z]/", '', $_GET[$varDetails[1]])) ? preg_replace("/[^A-Za-z]/", '', $_GET[$varDetails[1]]): '';
	}
	elseif ($varDetails[3] === '09' AND $varDetails[2] === 'GET')
	{
		$data[$varDetails[0]] = !empty(preg_replace("/[^0-9]/", '', $_GET[$varDetails[1]])) ? preg_replace("/[^0-9]/", '', $_GET[$varDetails[1]]): '';
	}
}
?>
