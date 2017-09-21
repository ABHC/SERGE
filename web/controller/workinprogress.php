<?php
include('model/get_text.php');
include('model/read.php');
include('model/insert.php');
include('view/nav/nav.php');
include('controller/generateNonce.php');

# Initialization of variables
$resultTab    = '';
$wikiTab      = '';
$settingTab   = '';

# Data processing
$unsafeData = array();
$unsafeData = array_merge($unsafeData, array(array('email', 'email', 'POST', 'email')));
$unsafeData = array_merge($unsafeData, array(array('newsletter', 'newsletter', 'POST', 'str')));

include('controller/dataProcessing.php');

# Nonce
$nonceTime = $_SERVER['REQUEST_TIME'];
$nonce     = getNonce($nonceTime);

$deleveryTime = 1505260000 ;
$timeLeft     = $deleveryTime - $_SERVER['REQUEST_TIME'];
$day          = floor($timeLeft / (24*3600));
$hour         = floor(($timeLeft - ($day*24*3600)) / (3600));
$minute       = floor(($timeLeft - ($day*24*3600) - ($hour*3600)) / 60);
$second       = ($timeLeft - ($day*24*3600) - ($hour*3600) - ($minute*60));

if (!empty($data['newsletter']) && !empty($data['email']))
{
	$checkCol     = array(array('email', ' =', $data['email'], ''));
	$result_email = read('newsletter_table_serge', '', $checkCol, '',$bdd);

	if (empty($result))
	{
		$insertCol = array(array('email', $data['email']),
											array('signup_date', $_SERVER['REQUEST_TIME']));
		$execution = insert('newsletter_table_serge', $insertCol, '', 'workinprogress', $bdd);
	}
}

include('view/body/workinprogress.php');

include('view/footer/footer.php');

?>
