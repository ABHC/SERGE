<?php
// Check email and password
/*$reqPasswordPseudoWithId = $bdd->prepare('SELECT users, password FROM users_table_serge WHERE id = :id');
$reqPasswordPseudoWithId->execute(array(
	'id' => $id));
	$passwordPseudoWithId = $reqPasswordPseudoWithId->fetch();
	$reqPasswordPseudoWithId->closeCursor();*/

$checkCol = array(array("id", "=", $id, ""));
$result = read('users_table_serge', 'users, password', $checkCol, '', $bdd);
$passwordPseudoWithId = $result[0];
?>
