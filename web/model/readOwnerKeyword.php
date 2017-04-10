<?php
// Read owner keyword
$userId = '%|' . $_SESSION['id'] . ':%';
$reqReadOwnerKeyword = $bdd->prepare('SELECT id FROM keyword_news_serge WHERE applicable_owners_sources LIKE :user');
$reqReadOwnerKeyword->execute(array(
	'user' => $userId));
	$readOwnerKeyword = $reqReadOwnerKeyword->fetchAll();
	$reqReadOwnerKeyword->closeCursor();
?>
