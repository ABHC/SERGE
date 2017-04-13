<?php
// Read in BDD the results for current owner
$userId = '%,' . $_SESSION['id'] . ',%';
$reqReadOwnerResults = $bdd->prepare("SELECT id, title, link, send_status, read_status, `date`, id_source, keyword_id FROM result_news_serge WHERE owners LIKE :user $ORDERBYorSEARCH LIMIT :base, :lim");
	$reqReadOwnerResults->bindValue('user', $userId, PDO::PARAM_STR);
	$reqReadOwnerResults->bindValue('search', $search, PDO::PARAM_STR);
	$reqReadOwnerResults->bindValue('base', $resultBase, PDO::PARAM_INT);
	$reqReadOwnerResults->bindValue('lim', $resultLimit, PDO::PARAM_INT);
	$reqReadOwnerResults->execute();

$readOwnerResults = $reqReadOwnerResults->fetchAll();
$reqReadOwnerResults->closeCursor();
?>
