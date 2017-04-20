<?php
# Dynamic part
function readUserSettings($column, $bdd)
{
	$req = $bdd->prepare("SELECT $column FROM users_table_serge WHERE users = :pseudo");
	$req->execute(array(
		'pseudo' => $_SESSION['pseudo']));
		$userSettings = $req->fetch();
		$req->closeCursor();

	return $userSettings;
}

$req = $bdd->prepare('SELECT id, email, password, send_condition, frequency, link_limit, selected_days, selected_hour, mail_design, language, record_read, history_lifetime, background_result, record_read FROM users_table_serge WHERE users = :pseudo');
$req->execute(array(
	'pseudo' => $_SESSION['pseudo']));
	$userSettings = $req->fetch();
	$req->closeCursor();

?>
