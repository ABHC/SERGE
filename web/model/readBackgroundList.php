<?php
function readBackgroundList($type, $bdd)
{
	/*$reqReadBackgroundList = $bdd->prepare('SELECT id, name, filename FROM background_serge WHERE type = :type ORDER BY name');
	$reqReadBackgroundList->execute(array(
		'type' => $type));
		$backgroundList = $reqReadBackgroundList->fetchAll();
		$reqReadBackgroundList->closeCursor();*/

	$checkCol = array(array("type", "=", $type, ""));
	$backgroundList = read('background_serge', 'id, name, filename', $checkCol, 'ORDER BY name', $bdd);

	return $backgroundList;
}
?>
