<?php
// Check pseudo
$req = $bdd->prepare('SELECT id FROM users_table_serge WHERE users = :pseudo');
$req->execute(array(
	'pseudo' => $pseudo));
	$result_pseudo = $req->fetch();
	$req->closeCursor();

	// Check mail
$req = $bdd->prepare('SELECT id FROM users_table_serge WHERE email = :email');
$req->execute(array(
	'email' => $email));
	$result_email = $req->fetch();
	$req->closeCursor();
?>
