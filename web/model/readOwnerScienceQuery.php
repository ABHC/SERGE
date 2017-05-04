<?php
// Read owner science query
$req = $bdd->prepare('SELECT query_arxiv FROM queries_science_serge WHERE owners LIKE :userId');
$req->execute(array(
	'userId' => '%,' . $_SESSION['id'] . ',%'));
	$queries = $req->fetchAll();
	$req->closeCursor();
?>
