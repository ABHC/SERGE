<?php
// Change record read
/*$req = $bdd->prepare('UPDATE users_table_serge SET record_read = :record_read WHERE id = :id');
$req->execute(array(
	'record_read' => $recordRead,
	'id' => $_SESSION['id']));
	$req->closeCursor();*/

$updateCol = array(array("record_read", $recordRead));
$checkCol  = array(array("id", "=", $_SESSION['id'], ""));
$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);
?>
