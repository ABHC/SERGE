<?php
function addNewScienceQuery($queryScience_Arxiv, $queryScience_Doaj, $bdd)
{
	$userId = ',' . $_SESSION['id'] . ',';
	$ERROR_SCIENCEQUERY = '';

	// Check if science query is already in bdd
	$req = $bdd->prepare('SELECT id, owners, active FROM queries_science_serge WHERE LOWER(query_Arxiv) = LOWER(:newQuery_Arxiv)');
	$req->execute(array(
		'newQuery_Arxiv' => $queryScience_Arxiv));
		$result = $req->fetch();
		$req->closeCursor();

	if (!$result)
	{
		$active = 1;
		// Adding new query
		$req = $bdd->prepare('INSERT INTO queries_science_serge (query_Arxiv, query_Doaj, owners, active) VALUES (:query_Arxiv, :query_Doaj, :userId, :active)');
		$req->execute(array(
			'query_Arxiv' => $queryScience_Arxiv,
			'query_Doaj' => $queryScience_Doaj,
			'userId' => $userId,
			'active' => $active));
			$req->closeCursor();
	}
	else
	{
		// Update owners
		$active = $result['active'] + 1;
		// Search in owners if userId exist
		$owners = $result['owners'];

		if (!preg_match("$userId", $owners))
		{
			$newOwners = $owners . $_SESSION['id'] . ',';
			$req = $bdd->prepare('UPDATE queries_science_serge SET owners = :newOwners, active = :active WHERE id = :id');
			$req->execute(array(
				'newOwners' => $newOwners,
				'active' => $active,
				'id' => $result['id']));
				$req->closeCursor();
		}
		else
		{
			$ERROR_SCIENCEQUERY = 'Query already exist';
		}
	}

	return $ERROR_SCIENCEQUERY;
}
?>
