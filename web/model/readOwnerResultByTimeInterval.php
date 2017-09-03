<?php
/*$reqReadIdResutlToDel = $bdd->prepare('SELECT id FROM result_news_serge WHERE owners LIKE :owners AND `date` >= :deleteTime');
$reqReadIdResutlToDel->execute(array(
	'owners' => $owner,
	'deleteTime' => $deleteTime));
	$readIdResutlToDel = $reqReadIdResutlToDel->fetchAll();
	$reqReadIdResutlToDel->closeCursor();*/

$checkCol = array(array("owners", "l", $owner, "AND"),
									array("date", ">=", $deleteTime, ""));
$readIdResutlToDel = read('result_news_serge', 'id', $checkCol, '', $bdd);
?>
