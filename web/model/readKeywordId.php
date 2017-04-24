<?php
function readKeywordId($userId, $searchKeyword, $bdd)
{
	$reqSearchOwnerKeyword = $bdd->prepare('SELECT id FROM keyword_news_serge WHERE applicable_owners_sources LIKE :user AND LOWER(keyword) LIKE LOWER(:searchKeyword)');
	$reqSearchOwnerKeyword->execute(array(
		'user' => $userId,
		'searchKeyword' => $searchKeyword));
		$searchOwnerKeyword = $reqSearchOwnerKeyword->fetchAll();
		$reqSearchOwnerKeyword->closeCursor();

	return $searchOwnerKeyword;
}
?>
