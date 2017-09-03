<?php
// Check pseudo
/*$req = $bdd->prepare('SELECT id FROM users_table_serge WHERE users = :pseudo');
$req->execute(array(
	'pseudo' => $pseudo));
	$result_pseudo = $req->fetch();
	$req->closeCursor();*/

$checkCol = array(array("users", "=", $pseudo, ""));
$result = read('users_table_serge', 'id', $checkCol, '', $bdd);
$result_pseudo = $result[0];

	// Check mail
/*$req = $bdd->prepare('SELECT id FROM users_table_serge WHERE email = :email');
$req->execute(array(
	'email' => $email));
	$result_email = $req->fetch();
	$req->closeCursor();*/

$checkCol = array(array("email", "=", $email, ""));
$result = read('users_table_serge', 'id', $checkCol, '', $bdd);
$result_email = $result[0];
?>
