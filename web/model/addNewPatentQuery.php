<?php
function addNewPatentQuery($queryPatent, $bdd)
{
	$userId = ',' . $_SESSION['id'] . ',';
	$ERROR_SCIENCEQUERY = '';

	// Check if science query is already in bdd
	$req = $bdd->prepare('SELECT id, owners, active FROM queries_wipo_serge WHERE LOWER(query) = LOWER(:newQuery)');
	$req->execute(array(
		'newQuery' => $queryPatent));
		$result = $req->fetch();
		$req->closeCursor();

	if (!$result)
	{
		$active = 1;
		// Adding new query
		$req = $bdd->prepare('INSERT INTO queries_wipo_serge (query, owners, active) VALUES (:newQuery, :userId, :active)');
		$req->execute(array(
			'newQuery' => $queryPatent,
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
			$req = $bdd->prepare('UPDATE queries_wipo_serge SET owners = :newOwners, active = :active WHERE id = :id');
			$req->execute(array(
				'newOwners' => $newOwners,
				'active' => $active,
				'id' => $result['id']));
				$req->closeCursor();
		}
		else
		{
			$ERROR_PATENTQUERY = 'Query already exist';
		}
	}

	return $ERROR_PATENTQUERY;
}
?>
