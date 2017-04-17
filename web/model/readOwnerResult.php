<?php
// Read in BDD the results for current owner
$userId = '%,' . $_SESSION['id'] . ',%';
$reqReadOwnerResults = $bdd->prepare("$QUERYRESULT");
	$reqReadOwnerResults->bindValue('user', $userId, PDO::PARAM_STR);
	$reqReadOwnerResults->bindValue('search', $search, PDO::PARAM_STR);
	$reqReadOwnerResults->bindValue('searchBoolean', $searchBoolean, PDO::PARAM_STR);
	#$reqReadOwnerResults->bindValue('base', $resultBase, PDO::PARAM_INT);
	#$reqReadOwnerResults->bindValue('lim', $resultLimit, PDO::PARAM_INT);
	$reqReadOwnerResults->execute();

$readOwnerResults = $reqReadOwnerResults->fetchAll();
$nbResults = $reqReadOwnerResults->rowCount();
$reqReadOwnerResults->closeCursor();
?>
