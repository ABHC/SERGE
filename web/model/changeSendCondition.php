<?php
// Update background result
/*$req = $bdd->prepare('UPDATE users_table_serge SET send_condition = :send_condition, link_limit = :link_limit, frequency = :frequency, selected_days = :selected_days, selected_hour = :selected_hour WHERE id = :id');
$req->execute(array(
	'send_condition' => $cond,
	'link_limit' => $linkLimit,
	'frequency' => $frequency,
	'selected_days' => $selectedDays,
	'selected_hour' => $selectedHour,
	'id' => $_SESSION['id']));
	$req->closeCursor();*/

$updateCol = array(array("send_condition", $cond),
									array("link_limit", $linkLimit),
									array("frequency", $frequency),
									array("selected_days", $selectedDays),
									array("selected_hour", $selectedHour));
$checkCol  = array(array("id", "=", $_SESSION['id'], ""));
$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);
?>
