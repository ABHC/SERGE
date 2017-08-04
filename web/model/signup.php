<?php
$req = $bdd->prepare('INSERT INTO users_table_serge(users, email, password, signup_date, send_condition, mail_design, record_read, background_result) VALUES(:pseudo, :email, :password, :signup_date, :send_condition, :mail_design, :record_read, :background_result)');
$req->execute(array(
	'pseudo' => $pseudo,
	'email' => $email,
	'password' => $password,
	'signup_date' => time(),
	'send_condition' =>'link_limit',
	'mail_design' => 'masterword',
	'record_read' => 1,
	'background_result' => 'Skyscrapers'));
$req->closeCursor();

$req = $bdd->prepare('SELECT id FROM users_table_serge WHERE users = :pseudo AND password = :pass');
$req->execute(array(
	'pseudo' => $pseudo,
	'pass' => $password));
	$result = $req->fetch();
	$req->closeCursor();

$idNewUser = $result['id'];
?>
