<?php
function addNewScienceQuery($queryScience_Arxiv, $queryScience_Doaj, $bdd)
{
	$userId = ',' . $_SESSION['id'] . ',';
	$ERROR_SCIENCEQUERY = '';
	// Check if science query is already in bdd
	$checkCol = array(array("query_Arxiv", "=", mb_strtolower($queryScience_Arxiv), ""));
	$result = read('queries_science_serge', 'id, owners, active', $checkCol, '', $bdd);
	$result = $result[0];

	if (!$result)
	{
		$active = 1;
		// Adding new query
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
