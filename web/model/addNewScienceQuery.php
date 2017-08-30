<?php
function addNewScienceQuery($queryScience_Arxiv, $queryScience_Doaj, $bdd)
{
	$userId = ',' . $_SESSION['id'] . ',';
	$ERROR_SCIENCEQUERY = '';
	// Check if science query is already in bdd
	/*$req = $bdd->prepare('SELECT id, owners, active FROM queries_science_serge WHERE LOWER(query_Arxiv) = LOWER(:newQuery_Arxiv)');
	$req->execute(array(
		'newQuery_Arxiv' => $queryScience_Arxiv));
		$result = $req->fetch();
		$req->closeCursor();*/

	$checkCol = array(array("LOWER(query_Arxiv)", "=", mb_strtolower($queryScience_Arxiv), ""));
	$result = read('queries_science_serge', 'id, owners, active', $checkCol, '', $bdd);
	$result = $result[0];

	if (!$result)
	{
		$active = 1;
		// Adding new query
		/*$req = $bdd->prepare('INSERT INTO queries_science_serge (query_Arxiv, query_Doaj, owners, active) VALUES (:query_Arxiv, :query_Doaj, :userId, :active)');
		$req->execute(array(
			'query_Arxiv' => $queryScience_Arxiv,
			'query_Doaj' => $queryScience_Doaj,
			'userId' => $userId,
			'active' => $active));
			$req->closeCursor();*/

			$insertCol = array(array("query_Arxiv", $queryScience_Arxiv),
												array("query_Doaj", $queryScience_Doaj),
												array("owners", ',' . $_SESSION['id'] . ','),
												array("active", 1));
			$execution = insert('queries_science_serge', $insertCol, '', 'setting', $bdd);
	}
	else
	{
		// Update owners
		$active = $result['active'] + 1;
		// Search in owners if userId exist
		$owners = $result['owners'];

		if (!preg_match("$userId", $owners))
		{
			/*$newOwners = $owners . $_SESSION['id'] . ',';
			$req = $bdd->prepare('UPDATE queries_science_serge SET owners = :newOwners, active = :active WHERE id = :id');
			$req->execute(array(
				'newOwners' => $newOwners,
				'active' => $active,
				'id' => $result['id']));
				$req->closeCursor();*/

			$updateCol = array(array("owners", $owners . $_SESSION['id'] . ','),
												array("active", $result['active'] + 1));
			$checkCol = array(array("id", "=", $result['id'], ""));
			$execution = update('queries_science_serge', $updateCol, $checkCol, '', $bdd);
		}
		else
		{
			$ERROR_SCIENCEQUERY = 'Query already exist';
		}
	}

	return $ERROR_SCIENCEQUERY;
}
?>
