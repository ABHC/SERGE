<?php
$req = $bdd->prepare('SELECT id, email, send_condition, frequency, link_limit, selected_days, selected_hour, mail_design, language, record_read, history_lifetime, background_result FROM users_table_serge WHERE users = :pseudo');
$req->execute(array(
	'pseudo' => $_SESSION['pseudo']));
	$userSettings = $req->fetch();
	$req->closeCursor();
?>
