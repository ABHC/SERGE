<?php
include_once('model/get_text.php');
include_once('model/read.php');
include_once('model/insert.php');
include_once('view/nav/nav.php');
include_once('controller/generateNonce.php');

# Data processing
$unsafeData = array();
$unsafeData = array_merge($unsafeData, array(array('email', 'email', 'POST', 'email')));
$unsafeData = array_merge($unsafeData, array(array('newsletter', 'newsletter', 'POST', 'str')));

include_once('controller/dataProcessing.php');

# Nonce
$nonceTime = time();
$nonce = getNonce($nonceTime);

$deleveryTime = 1505260000 ;
$timeLeft = $deleveryTime - time();
$day = floor($timeLeft / (24*3600));
$hour = floor(($timeLeft - ($day*24*3600)) / (3600));
$minute = floor(($timeLeft - ($day*24*3600) - ($hour*3600)) / 60);
$second = ($timeLeft - ($day*24*3600) - ($hour*3600) - ($minute*60));

if ($dataProcessing AND !empty($data['newsletter']) AND !empty($data['email']))
{
	$checkCol = array(array("email", "=", $data['email'], ""));
	$result_email = read("newsletter_table_serge", '', $checkCol, '',$bdd);

	if (empty($result))
	{
		$insertCol = array(array("email", $data['email']),
											array("signup_date", time()));
		$execution = insert('newsletter_table_serge', $insertCol, '', 'workinprogress', $bdd);
	}
	sleep(1);
}

include_once('view/body/workinprogress.php');

include_once('view/footer/footer.php');

?>
