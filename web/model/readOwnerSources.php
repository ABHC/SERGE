<?php
// Check if source is already in bdd
$userId = '%,' . $_SESSION['id'] . ',%';
$userIdDesactivated = '%,!' . $_SESSION['id'] . ',%';
$reqReadOwnerSources = $bdd->prepare('SELECT id, link, name, owners, active FROM rss_serge WHERE owners LIKE :user OR owners LIKE :userDesactivated ORDER BY name');
$reqReadOwnerSources->execute(array(
	'user' => $userId,
	'userDesactivated' => $userIdDesactivated));
	$reqReadOwnerSourcestmp = $reqReadOwnerSources->fetchAll();
	$reqReadOwnerSources->closeCursor();
?>
