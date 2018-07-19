<?php
// Check if pack exist
$checkCol  = array(array('id', '=', $data['addPack'], ''));
$packExist = read('watch_pack_serge', 'users', $checkCol, '', $bdd);
$packExist = $packExist[0] ?? '';

if (!empty($packExist))
{
	// Add current user in column users in watch_pack_serge
	$updateCol = array(array('users', $packExist['users'] . $_SESSION['id'] . ','));
	$checkCol  = array(array('id', '=', $data['addPack'], ''));
	$execution = update('watch_pack_serge', $updateCol, $checkCol, '', $bdd);

	// Read list of sources used by watch pack
	$checkCol = array(array('pack_id', '=', $data['addPack'], 'AND'),
										array('query', '=' , '[!source!]', ''));
	$result   = read('watch_pack_queries_serge', 'source', $checkCol, '', $bdd);

	$listOfSource_array = explode(',', $result[0]['source']);

	$checkCol = array(array('owners', 'l', '%,' . $_SESSION['id'] . ',%', 'OR'),
										array('owners', 'l', '%,!' . $_SESSION['id'] . ',%', ''));
	$ownerSources = read('sources_news_serge', 'id', $checkCol, '', $bdd);

	if (!empty($ownerSources))
	{
		$ownerSources_array = ',';

		foreach ($ownerSources as $own)
		{
			$ownerSources_array = $ownerSources_array . $own['id'] . ',';
		}

		$ownerSources_array = explode(',', $ownerSources_array);
	}

	foreach ($listOfSource_array as $source)
	{
		// Add source to actual user if the source is not already own
		if (empty($ownerSources) || !in_array($source, $ownerSources_array))
		{
			$checkCol     = array(array('id', '=', $source, ''));
			$sourceOwners = read('sources_news_serge', 'owners, active', $checkCol, '', $bdd);
			$sourceOwners = $sourceOwners[0]['owners'] ?? '';
			$sourceActive = $sourceOwners[0]['active'] ?? '';

			$updateCol = array(array('owners', $sourceOwners . $_SESSION['id'] . ','),
												array('active', $sourceActive + 1));
			$checkCol  = array(array('id', '=', $source, ''));
			$execution = update('sources_news_serge', $updateCol, $checkCol, '', $bdd);
		}
	}

	// Get entry where pack id is the id of the pack and source = science
	$checkCol = array(array('pack_id', '=', $data['addPack'], 'AND'),
										array('source', '=' , 'Science', 'OR'),
										array('pack_id', '=', $data['addPack'], 'AND'),
										array('source', '=' , '!Science', ''));
	$result = read('watch_pack_queries_serge', 'query', $checkCol, '', $bdd);

	foreach ($result as $scienceQuery)
	{
		// Add query to actual user if query is not already own
		$checkCol = array(array('query_serge', '=', $scienceQuery['query'], 'AND'),
											array('owners', 'l', '%,' . $_SESSION['id'] . ',%', 'OR'),
											array('query_serge', '=', $scienceQuery['query'], 'AND'),
											array('owners', 'l', '%,!' . $_SESSION['id'] . ',%', ''));
		$queryExist = read('inquiries_sciences_serge', '', $checkCol, '', $bdd);

		if (!$queryExist)
		{
			$checkCol   = array(array('query_serge', '=', $scienceQuery['query'], ''));
			$queryExist = read('inquiries_sciences_serge', 'id, owners, active', $checkCol, '', $bdd);
			$queryExist = $queryExist[0] ?? '';

			if (!empty($queryExist))
			{
				// Update query with the new owner
				$updateCol = array(array('owners', $queryExist['owners'] . $_SESSION['id'] . ','),
													array('active', $queryExist['active'] + 1));
				$checkCol  = array(array('id', '=', $queryExist['id'], ''));
				$execution = update('inquiries_sciences_serge', $updateCol, $checkCol, '', $bdd);
			}
			else
			{
				// Add query
				$insertCol = array(array('query_serge', $scienceQuery['query']),
													array('owners', ',' . $_SESSION['id'] . ','),
													array('active', 1));
				$execution = insert('inquiries_sciences_serge', $insertCol, '', '', $bdd);
			}
		}
	}

	// Get entry where pack id is the id of the pack and source = patent
	$checkCol = array(array('pack_id', '=', $data['addPack'], 'AND'),
										array('source', '=' , 'Patent', 'OR'),
										array('pack_id', '=', $data['addPack'], 'AND'),
										array('source', '=' , '!Patent', ''));
	$result   = read('watch_pack_queries_serge', 'query', $checkCol, '', $bdd);

	foreach ($result as $patentQuery)
	{
		// Add query to actual user if query is not already own
		$checkCol   = array(array('query', '=', $patentQuery['query'], 'AND'),
												array('owners', 'l', '%,' . $_SESSION['id'] . ',%', 'OR'),
												array('query', '=', $patentQuery['query'], 'AND'),
												array('owners', 'l', '%,!' . $_SESSION['id'] . ',%', ''));
		$queryExist = read('inquiries_patents_serge', '', $checkCol, '', $bdd);

		if (!$queryExist)
		{
			$checkCol   = array(array('query', '=', $patentQuery['query'], ''));
			$queryExist = read('inquiries_patents_serge', 'id, owners, active', $checkCol, '', $bdd);
			$queryExist = $queryExist[0] ?? '';

			if (!empty($queryExist))
			{
				// Update query with new owner
				$updateCol = array(array('owners', $queryExist['owners'] . $_SESSION['id'] . ','),
													array('active', $queryExist['active'] + 1));
				$checkCol  = array(array('id', '=', $queryExist['id'], ''));
				$execution = update('inquiries_patents_serge', $updateCol, $checkCol, '', $bdd);
			}
			else
			{
				// Add query
				$insertCol = array(array('query', $patentQuery['query']),
													array('owners', ',' . $_SESSION['id'] . ','),
													array('active', 1));
				$execution = insert('inquiries_patents_serge', $insertCol, '', '', $bdd);
			}
		}
	}

	// Get all entries that isn't contain science patent or [!source!]
	$checkCol = array(array('pack_id', '=', $data['addPack'], 'AND'),
										array('source', '<>' , 'Science', 'AND'),
										array('source', '<>' , 'Patent', 'AND'),
										array('query', '<>' , '[!source!]', ''));
	$result = read('watch_pack_queries_serge', 'query, source', $checkCol, '', $bdd);

	foreach ($result as $couple)
	{
		// Add couple keyword, sources to actual user if couple is not already own
		$sourceId   = preg_replace("/,([^$])/", ",!*$1", $couple['source']);
		$checkCol   = array(array('keyword', '=', strtolower($couple['query']), 'AND'),
												array('applicable_owners_sources', 'REGEXP', '\\|' . $_SESSION['id'] . ':[,0-9+,^\\|]*' . $sourceId, ''));
		$queryExist = read('inquiries_news_serge', '', $checkCol, '', $bdd);

		if (!$queryExist)
		{
			$checkCol   = array(array('keyword', '=', strtolower($couple['query']), ''));
			$queryExist = read('inquiries_news_serge', 'id, applicable_owners_sources', $checkCol, '', $bdd);

			if (!empty($queryExist))
			{
				// Check if user already own keyword
				$checkCol       = array(array('keyword', '=', strtolower($couple['query']), 'AND'),
																array('applicable_owners_sources', 'l', '%|' . $_SESSION['id'] . ':%', ''));
				$userOwnKeyword = read('inquiries_news_serge', 'id, applicable_owners_sources, active', $checkCol, '', $bdd);
				$userOwnKeyword = $userOwnKeyword[0] ?? '';

				if (empty($userOwnKeyword))
				{
					// Update with new source
					$updateCol = array(array('applicable_owners_sources', $queryExist['applicable_owners_sources'] . $_SESSION['id'] . ':' . $couple['source'] . '|'),
														 array('active', $userOwnKeyword['active'] + substr_count($couple['source'], ',') - 1));
					$checkCol  = array(array('id', '=', $queryExist['id'], ''));
					$execution = update('inquiries_news_serge', $updateCol, $checkCol, '', $bdd);
				}
				else
				{
					// Update with new owner
					$userId = $_SESSION['id'];
					preg_match("/\|1:([^\|:]+)/", $userOwnKeyword['applicable_owners_sources'], $keywordSources);
					$coupleSource_array   = explode(',', $couple['source']);
					$keywordSources_array = explode(',', $keywordSources[1]);

					$allSources = array_merge($keywordSources_array, $coupleSource_array);
					$allSources = array_unique($allSources);
					$allSources = implode(',', $allSources) . ',';

					$allSources = preg_replace("/(\|$userId:)[,0-9+,]*,*,(.*)/", '$1' . $allSources . '$2', $userOwnKeyword['applicable_owners_sources']);
					$updateCol = array(array('applicable_owners_sources', $allSources),
														array('active', substr_count($allSources, ',') - 1));
					$checkCol  = array(array('id', '=', $userOwnKeyword['id'], ''));
					$execution = update('inquiries_news_serge', $updateCol, $checkCol, '', $bdd);
				}
			}
			else
			{
				// Add query
				$insertCol = array(array('keyword', $couple['query']),
													array('applicable_owners_sources', '|' . $_SESSION['id'] . ':' . $couple['source'] . '|'),
													array('active', substr_count($couple['source'], ',') - 1));
				$execution = insert('inquiries_news_serge', $insertCol, '', '', $bdd);
			}
		}
	}

	header('Location: setting');
	die();
}
?>
