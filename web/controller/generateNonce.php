<?php
function getNonce($time)
{
	$bytes = random_bytes(5);
	$cryptoSalt = bin2hex($bytes);
	$nonce = hash('sha256', 'BlackSalt' . $cryptoSalt . $time);

	$_SESSION['nonce :' . $nonce] = 0;
	return $nonce;
}
?>
