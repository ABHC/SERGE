<?php
function readBackgroundList($type, $bdd)
{
	$reqReadBackgroundList = $bdd->prepare('SELECT id, name, filename FROM background_serge WHERE type = :type ORDER BY name');
	$reqReadBackgroundList->execute(array(
		'type' => $type));
		$backgroundList = $reqReadBackgroundList->fetchAll();
		$reqReadBackgroundList->closeCursor();

	return $backgroundList;
}
?>
