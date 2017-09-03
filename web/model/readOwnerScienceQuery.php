<?php
// Read owner science query
/*$req = $bdd->prepare('SELECT id, query_arxiv, owners FROM queries_science_serge WHERE owners LIKE :userId OR owners LIKE :userIdDisable');
$req->execute(array(
	'userId' => '%,' . $_SESSION['id'] . ',%',
	'userIdDisable' => '%,!' . $_SESSION['id'] . ',%'));
	$queries = $req->fetchAll();
	$req->closeCursor();*/

$checkCol = array(array("owners", "l", '%,' . $_SESSION['id'] . ',%', "OR"),
									array("owners", "l", '%,!' . $_SESSION['id'] . ',%', ""));
$queries = read('queries_science_serge', 'id, query_arxiv, owners', $checkCol, '', $bdd);
?>
