<?php
include_once('model/get_text.php');
include_once('model/read.php');
include_once('model/insert.php');
include_once('view/nav/nav.php');

$deleveryTime = 1504260000 ;
$timeLeft = $deleveryTime - time();
$day = floor($timeLeft / (24*3600));
$hour = floor(($timeLeft - ($day*24*3600)) / (3600));
$minute = floor(($timeLeft - ($day*24*3600) - ($hour*3600)) / 60);
$second = ($timeLeft - ($day*24*3600) - ($hour*3600) - ($minute*60));

if (isset($_POST['newsletter']) AND !empty($_POST['email']))
{
	$email = htmlspecialchars($_POST['email']);

	$checkCol = array(array("email", "=", $email, ""));
	$result_email = read("newsletter_table_serge", '', $checkCol, '',$bdd);

	if (empty($result))
	{
		$insertCol = array(array("email", $email),
											array("signup_date", time()));
		$execution = insert('newsletter_table_serge', $insertCol, '', $bdd);
	}
	sleep(1);
}

include_once('view/body/workinprogress.php');

include_once('view/footer/footer.php');

?>
