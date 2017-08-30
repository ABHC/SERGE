<?php
function addNewPatentQuery($queryPatent, $bdd)
{
	$userId = ',' . $_SESSION['id'] . ',';
	$ERROR_SCIENCEQUERY = '';

	// Check if science query is already in bdd
	/*$req = $bdd->prepare('SELECT id, owners, active FROM queries_wipo_serge WHERE LOWER(query) = LOWER(:newQuery)');
	$req->execute(array(
		'newQuery' => $queryPatent));
		$result = $req->fetch();
		$req->closeCursor();*/

	$checkCol = array(array("LOWER(query)", "=", mb_strtolower($queryPatent), ""));
	$result = read('queries_wipo_serge', 'id, owners, active', $checkCol, '', $bdd);
	$result = $result[0];

	if (!$result)
	{
		$active = 1;
		// Adding new query
		/*$req = $bdd->prepare('INSERT INTO queries_wipo_serge (query, owners, active) VALUES (:newQuery, :userId, :active)');
		$req->execute(array(
			'newQuery' => $queryPatent,
			'userId' => $userId,
			'active' => $active));
			$req->closeCursor();*/

		$insertCol = array(array("query", $queryPatent),
											array("owners", ',' . $_SESSION['id'] . ','),
											array("active", 1));
		$execution = insert('queries_wipo_serge', $insertCol, '', 'setting', $bdd);
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
			$req = $bdd->prepare('UPDATE queries_wipo_serge SET owners = :newOwners, active = :active WHERE id = :id');
			$req->execute(array(
				'newOwners' => $newOwners,
				'active' => $active,
				'id' => $result['id']));
				$req->closeCursor();*/

			$updateCol = array(array("owners", $owners . $_SESSION['id'] . ','),
													array("active", $result['active'] + 1));
			$checkCol = array(array("id", "=", $result['id'], ""));
			$execution = update('queries_wipo_serge', $updateCol, $checkCol, '', $bdd);
		}
		else
		{
			$ERROR_PATENTQUERY = 'Query already exist';
		}
	}

	return $ERROR_PATENTQUERY;
}
?>
