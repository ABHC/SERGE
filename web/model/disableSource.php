<?php
	$userId = $_SESSION['id'];
	/*$sourceOwnerNEW = preg_replace("/,$userId,/", ",!$userId,", $owners);

	$active = $activeForCurrentSource - 1;

	$req = $bdd->prepare('UPDATE rss_serge SET owners = :owners, active = :active WHERE id = :id');
	$req->execute(array(
		'owners' => $sourceOwnerNEW,
		'active' => $active,
		'id' => $sourceIdAction));
		$req->closeCursor();*/

	$updateCol = array(array("owners", preg_replace("/,$userId,/", ",!$userId,", $owners)),
										array("active", $activeForCurrentSource - 1));
	$checkCol  = array(array("id", "=", $sourceIdAction, ""));
	$execution = update('rss_serge', $updateCol, $checkCol, '', $bdd);
?>
