<?php
include_once('model/get_text.php');
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

	$req = $bdd->prepare('SELECT id FROM newsletter_table_serge WHERE email LIKE :email');
	$req->execute(array(
		'email' => $email));
		$result = $req->fetchAll();
		$req->closeCursor();

	if (empty($result))
	{
		$req = $bdd->prepare('INSERT INTO newsletter_table_serge(email, signup_date) VALUES(:email, :signup_date)');
		$req->execute(array(
			'email' => $email,
			'signup_date' => time()));
		$req->closeCursor();
	}
	sleep(1);
}

include_once('view/body/workinprogress.php');

include_once('view/footer/footer.php');

?>
