<?php
// Read in BDD the results for current user
$req = $bdd->prepare("$QUERYRESULT");
	$req->execute($arrayValues);
	$readOwnerResults = $req->fetchAll();
	$nbResults = $req->rowCount();
	$req->closeCursor();
?>
