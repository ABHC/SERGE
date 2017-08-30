<?php
// Update email
/*$req = $bdd->prepare('UPDATE users_table_serge SET email = :email WHERE id = :id');
$req->execute(array(
	'email' => $newEmail,
	'id' => $_SESSION['id']));
	$req->closeCursor();*/

// TODO vérif email
$updateCol = array(array("email", $newEmail));
$checkCol = array(array("id", "=", $_SESSION['id'], ""));
$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);
?>
