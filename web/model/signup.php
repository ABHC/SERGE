<?php
/*$req = $bdd->prepare('INSERT INTO users_table_serge(users, email, password, signup_date, send_condition, mail_design, record_read, background_result) VALUES(:pseudo, :email, :password, :signup_date, :send_condition, :mail_design, :record_read, :background_result)');
$req->execute(array(
	'pseudo' => $pseudo,
	'email' => $email,
	'password' => $password,
	'signup_date' => time(),
	'send_condition' =>'link_limit',
	'mail_design' => 'masterword',
	'record_read' => 1,
	'background_result' => 'Skyscrapers'));
$req->closeCursor();*/

$insertCol = array(array("users", $pseudo),
									array("email", $email),
									array("password", $password),
									array("signup_date", time()),
									array("send_condition", 'link_limit'),
									array("mail_design", 'masterword'),
									array("record_read", 1),
									array("background_result", 'Skyscrapers'));
$execution = insert('users_table_serge', $insertCol, '', '', $bdd);

/*$req = $bdd->prepare('SELECT id FROM users_table_serge WHERE users = :pseudo AND password = :pass');
$req->execute(array(
	'pseudo' => $pseudo,
	'pass' => $password));
	$result = $req->fetch();
	$req->closeCursor();*/

$checkCol = array(array("users", "=", $pseudo, "AND"),
									array("password", "=", $password, ""));
$result = read('users_table_serge', 'id', $checkCol, '', $bdd);
$result = $result[0];

$idNewUser = $result['id'];
?>
