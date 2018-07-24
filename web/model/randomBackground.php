<?php
session_start();

include('connection_sql.php');

include('read.php');

$checkCol = array(array('id', '=', $_SESSION['id'], ''));
$backgroundName = read('users_table_serge', 'background_result', $checkCol, '', $bdd);

if ($backgroundName[0]['background_result'] == 'random')
{
	# Number of background
	$req = $bdd->prepare("SELECT max(id) FROM background_serge WHERE 1");
	$req->execute();
	$maxBackgroundId = $req->fetch();
	$req->closeCursor();

	# Random number
	$randomBackgroundId = rand(1, $maxBackgroundId['max(id)']);

	$checkCol = array(array('id', '=', $randomBackgroundId, ''));
	$background = read('background_serge', 'filename', $checkCol, '', $bdd);
}
else
{
	$checkCol = array(array('name', '=', $backgroundName[0]['background_result'], ''));
	$background = read('background_serge', 'filename', $checkCol, '', $bdd);
}
?>
