<?php
// Change record read
$req = $bdd->prepare('UPDATE result_news_serge SET read_status = :userId WHERE link = :link');
$req->execute(array(
	'userId' => $userId,
	'link' => $link));
	$req->closeCursor();
?>
