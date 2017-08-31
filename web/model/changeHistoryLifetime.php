<?php
// Change history lifetime
/*$req = $bdd->prepare('UPDATE users_table_serge SET history_lifetime = :history_lifetime WHERE id = :id');
$req->execute(array(
	'history_lifetime' => $historyLifetime,
	'id' => $_SESSION['id']));
	$req->closeCursor();*/

$updateCol = array(array("history_lifetime", $historyLifetime));
$checkCol = array(array("id", "=", $_SESSION['id'], ""));
$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);
?>
