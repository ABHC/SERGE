<?php
// Update background result
$req = $bdd->prepare('UPDATE users_table_serge SET background_result = :background_result WHERE id = :id');
$req->execute(array(
	'background_result' => $backgroundResult,
	'id' => $_SESSION['id']));
	$req->closeCursor();
?>
