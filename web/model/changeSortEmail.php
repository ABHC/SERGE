<?php
// Update background result
/*$req = $bdd->prepare('UPDATE users_table_serge SET mail_design = :mail_design WHERE id = :id');
$req->execute(array(
	'mail_design' => $orderBy,
	'id' => $_SESSION['id']));
	$req->closeCursor();*/

$updateCol = array(array("mail_design", $orderBy));
$checkCol  = array(array("id", "=", $_SESSION['id'], ""));
$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);
?>
