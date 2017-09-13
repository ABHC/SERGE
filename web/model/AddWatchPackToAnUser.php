<?php
// Check if packId exist
$checkCol = array(array("id", "=", $data['AddPack'], ""));
$result = read('watch_pack_serge', '', $checkCol, '', $bdd);

if ($result)
{
	// Add current user in column users in watch_pack_serge
	$userId = $_SESSION['id'];
	$updateCol = array(array("users", $userId));
	$checkCol = array(array("id", "=", $data['AddPack'], ""));
	$execution = update('watch_pack_serge', $updateCol, $checkCol, '', $bdd);

	// read list of sources used by watch pack
	$checkCol = array(array("pack_id", "=", $data['AddPack'], "AND"),
										array("query", "=" , "[!source!]", ""));
	$result = read('watch_pack_serge', 'source', $checkCol, '', $bdd);
	$listOfSource_array = explode(",", $result[0]);

	$checkCol = array(array("users", "l", '%,' . $_SESSION['id'] . ',%', ""));
	$ownerSources = read('rss_serge', 'id', $checkCol, '', $bdd);

	foreach ($listOfSource_array as $source)
	{
		// Add source to actual user if the source is not already own
		if (!in_array($source, $ownerSources['id']))
		{
			$checkCol = array(array("id", "=", $source, ""));
			$sourceOwners = read('rss_serge', 'owners', $checkCol, '', $bdd);

			$updateCol = array(array("owners", $sourceOwners[0] . $_SESSION['id'] . ','));
			$checkCol = array(array("id", "=", $source, ""));
			$execution = update('rss_serge', $updateCol, $checkCol, '', $bdd);
		}
	}

	// récup les lignes WHERE packid est l'id du pack et source = science ajouter chaque ligne dans le la table science
	$checkCol = array(array("pack_id", "=", $data['AddPack'], "AND"),
										array("source", "=" , "Science", ""));
	$result = read('watch_pack_serge', 'query', $checkCol, '', $bdd);

	foreach ($result as $scienceQuery)
	{
		// Add query to actual user if query is not already own
		$checkCol = array(array("query_arxiv", "=", $scienceQuery['query'], "AND"),
											array("owners", "l", '%,' . $_SESSION['id'] . ',%', ""));
		$queryExist = read('queries_science_serge', '', $checkCol, '', $bdd);

		if (empty($queryExist))
		{
			$checkCol = array(array("query_arxiv", "=", $scienceQuery['query'], ""));
			$queryExist = read('queries_science_serge', 'id, owners', $checkCol, '', $bdd);

			if (!empty($queryExist))
			{
				$updateCol = array(array("owners", $queryExist['owners'] . $_SESSION['id'] . ','),
														array("active", $result['active'] + 1));
				$checkCol = array(array("id", "=", $queryExist['id'], ""));
				$execution = update('queries_science_serge', $updateCol, $checkCol, '', $bdd);
			}
		}
		else
		{
			// Creation of Doaj query


			// Add query
			$insertCol = array(array("query_arxiv", $scienceQuery['query']),
			array("query_doaj", $queryDoaj),
			array("active", 1));
			$execution = insert('queries_science_serge', $insertCol, '', '', $bdd);
		}
	}

	// récup les lignes WHERE packid est l'id du pack et source = patent ajouter chaque ligne dans le la table patent
	$checkCol = array(array("pack_id", "=", $data['AddPack'], "AND"),
										array("source", "=" , "Patent", ""));
	$result = read('watch_pack_serge', 'query', $checkCol, '', $bdd);

	foreach ($result as $patentQuery)
	{
		// Add query to actual user if query is not already own
	}

	// Récup toute les lignes qui ne contienne pas science et patent et ajouter chaque mot clef à chaque source
	$checkCol = array(array("pack_id", "=", $data['AddPack'], "AND"),
										array("source", "<>" , "Science", "AND"),
										array("source", "<>" , "Patent", ""));
	$result = read('watch_pack_serge', 'query, source', $checkCol, '', $bdd);

	foreach ($result as $keyword)
	{
		// Add couple keyword, sources to actual user if couple is not already own
	}
}
?>
