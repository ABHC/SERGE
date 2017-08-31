<?php
// Update background result
/*$req = $bdd->prepare('UPDATE users_table_serge SET background_result = :background_result WHERE id = :id');
$req->execute(array(
	'background_result' => $backgroundResult,
	'id' => $_SESSION['id']));
	$req->closeCursor();*/

$updateCol = array(array("background_result", $backgroundResult));
$checkCol  = array(array("id", "=", $_SESSION['id'], ""));
$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);
?>
