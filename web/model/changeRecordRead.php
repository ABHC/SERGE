<?php
// Change record read
$req = $bdd->prepare('UPDATE users_table_serge SET record_read = :record_read WHERE id = :id');
$req->execute(array(
	'record_read' => $recordRead,
	'id' => $_SESSION['id']));
	$req->closeCursor();
?>
