<?php
function addNewPatentQuery(string $queryPatent, $bdd)
{
	$userId = ',' . $_SESSION['id'] . ',';
	$ERROR_PATENTQUERY = '';

	// Check if science query is already in bdd
	$checkCol = array(array("query", "=", mb_strtolower($queryPatent), ""));
	$result = read('queries_wipo_serge', 'id, owners, active', $checkCol, '', $bdd);
	$result = $result[0] ?? '';

	if (empty($result))
	{
		$active = 1;
		// Adding new query
		$insertCol = array(array("query", $queryPatent),
											array("owners", ',' . $_SESSION['id'] . ','),
											array("active", 1));
		$execution = insert('queries_wipo_serge', $insertCol, '', '', $bdd);
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
