<?php
// Change history lifetime
$req = $bdd->prepare('UPDATE users_table_serge SET history_lifetime = :history_lifetime WHERE id = :id');
$req->execute(array(
	'history_lifetime' => $historyLifetime,
	'id' => $_SESSION['id']));
	$req->closeCursor();
?>
