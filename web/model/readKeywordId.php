<?php
function readKeywordId($userId, $searchKeyword, $bdd, $tableNameQuery, $ownersColumn,  $queryColumn)
{
	$reqSearchOwnerKeyword = $bdd->prepare('SELECT id FROM ' . $tableNameQuery . ' WHERE ' . $ownersColumn . ' LIKE :user AND LOWER(' . $queryColumn . ') LIKE LOWER(:searchKeyword)');
	$reqSearchOwnerKeyword->execute(array(
		'user' => $userId,
		'searchKeyword' => $searchKeyword));
		$searchOwnerKeyword = $reqSearchOwnerKeyword->fetchAll();
		$reqSearchOwnerKeyword->closeCursor();

	return $searchOwnerKeyword;
}
?>
