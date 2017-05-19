<?php
// Read in BDD the results for current owner
$userId = '%,' . $_SESSION['id'] . ',%';
$reqReadOwnerResults = $bdd->prepare("$QUERYRESULT");
	$reqReadOwnerResults->execute(array(
		'user' => $userId,
		'search' => $search,
		'searchBoolean' => $searchBoolean,
		'searchSOUNDEX' => $searchSOUNDEX));

$readOwnerResults = $reqReadOwnerResults->fetchAll();
$nbResults = $reqReadOwnerResults->rowCount();
$reqReadOwnerResults->closeCursor();
?>
