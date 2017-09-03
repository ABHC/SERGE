<?php
// Read owner source keyword
/*$userId = '%|' . $_SESSION['id'] . ':%';
$reqReadOwnerSourcesKeyword = $bdd->prepare('SELECT id, keyword, applicable_owners_sources, active FROM keyword_news_serge WHERE applicable_owners_sources LIKE :user ORDER BY keyword');
$reqReadOwnerSourcesKeyword->execute(array(
	'user' => $userId));
	$reqReadOwnerSourcesKeywordtmp = $reqReadOwnerSourcesKeyword->fetchAll();
	$reqReadOwnerSourcesKeyword->closeCursor();*/

$checkCol = array(array("applicable_owners_sources", "l", '%|' . $_SESSION['id'] . ':%', ""));
$reqReadOwnerSourcesKeywordtmp = read('keyword_news_serge', 'id, keyword, applicable_owners_sources, active', $checkCol, 'ORDER BY keyword', $bdd);
?>
