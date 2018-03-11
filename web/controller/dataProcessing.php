<?php
// Processing data with nonce
$nonce = preg_replace("/[^a-z0-9]/", '', $_POST['nonce'] ?? '');
if (!empty($nonce) && isset($_SESSION['nonce :' . $nonce]) && $_SESSION['nonce :' . $nonce] === 0)
{
	$formPostSubmit = TRUE;
	foreach ($unsafeData as $varDetails)
	{
		if (empty($varDetails[3]) && $varDetails[2] === 'POST')
		{
			$data[$varDetails[0]] = $_POST[$varDetails[1]] ?? '';
		}
		elseif ($varDetails[3] === 'str' && $varDetails[2] === 'POST')
		{
			$data[$varDetails[0]] = htmlspecialchars($_POST[$varDetails[1]] ?? '');
		}
		elseif ($varDetails[3] === 'email' && $varDetails[2] === 'POST')
		{
			$data[$varDetails[0]] = filter_var($_POST[$varDetails[1]] ?? '', FILTER_SANITIZE_EMAIL);
		}
		elseif ($varDetails[3] === 'url' && $varDetails[2] === 'POST')
		{
			$data[$varDetails[0]] = filter_var($_POST[$varDetails[1]] ?? '', FILTER_SANITIZE_URL);
		}
		elseif ($varDetails[3] === 'Az' && $varDetails[2] === 'POST')
		{
			$data[$varDetails[0]] = preg_replace("/[^A-Za-z]/", '', $_POST[$varDetails[1]] ?? '');
		}
		elseif ($varDetails[3] === '09' && $varDetails[2] === 'POST')
		{
			$data[$varDetails[0]] = preg_replace("/[^0-9]/", '', $_POST[$varDetails[1]] ?? '');
		}
	}

	unset($_SESSION['nonce :' . $nonce]);
}

// Processing data without nonce
foreach ($unsafeData as $varDetails)
{
	if (empty($varDetails[3]) && $varDetails[2] === 'GET')
	{
		$data[$varDetails[0]] = $_GET[$varDetails[1]] ?? '';
	}
	elseif ($varDetails[3] === 'str' && $varDetails[2] === 'GET')
	{
		$data[$varDetails[0]] = htmlspecialchars($_GET[$varDetails[1]] ?? '');
	}
	elseif ($varDetails[3] === 'email' && $varDetails[2] === 'GET')
	{
		$data[$varDetails[0]] = filter_var($_GET[$varDetails[1]] ?? '', FILTER_SANITIZE_EMAIL);
	}
	elseif ($varDetails[3] === 'url' && $varDetails[2] === 'GET')
	{
		$data[$varDetails[0]] = filter_var($_GET[$varDetails[1]] ?? '', FILTER_SANITIZE_URL);
	}
	elseif ($varDetails[3] === 'Az' && $varDetails[2] === 'GET')
	{
		$data[$varDetails[0]] = preg_replace("/[^A-Za-z]/", '', $_GET[$varDetails[1]] ?? '');
	}
	elseif ($varDetails[3] === '09' && $varDetails[2] === 'GET')
	{
		$data[$varDetails[0]] = preg_replace("/[^0-9]/", '', $_GET[$varDetails[1]] ?? '');
		settype($data[$varDetails[0]], "integer");
	}
}
?>
