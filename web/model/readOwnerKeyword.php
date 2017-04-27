<?php
// Read owner keyword
$userIdKeywordRead = '%|' . $_SESSION['id'] . ':%';
$reqReadOwnerKeyword = $bdd->prepare('SELECT id FROM keyword_news_serge WHERE applicable_owners_sources LIKE :user');
$reqReadOwnerKeyword->execute(array(
	'user' => $userIdKeywordRead));
	$readOwnerKeyword = $reqReadOwnerKeyword->fetchAll();
	$reqReadOwnerKeyword->closeCursor();
?>
