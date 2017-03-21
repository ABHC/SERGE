<?php
// Check if source is already in bdd
$userId = '%,' . $_SESSION['id'] . ',%';
$reqReadOwnerSources = $bdd->prepare('SELECT link, name, id FROM rss_serge WHERE owners LIKE :user');
$reqReadOwnerSources->execute(array(
	'user' => $userId));
	$reqReadOwnerSourcestmp = $reqReadOwnerSources->fetchAll();
	$reqReadOwnerSources->closeCursor();
?>
