<?php
// Update email
$req = $bdd->prepare('UPDATE users_table_serge SET email = :email WHERE id = :id');
$req->execute(array(
	'email' => $newEmail,
	'id' => $_SESSION['id']));
	$req->closeCursor();
?>
