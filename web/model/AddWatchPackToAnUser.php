<?php
// Check if pack exist
$checkCol  = array(array("id", "=", $data['addPack'], ""));
$packExist = read('watch_pack_serge', 'users', $checkCol, '', $bdd);
$packExist = $packExist[0] ?? '';

if (!empty($packExist))
{
	// Add current user in column users in watch_pack_serge
	$updateCol = array(array("users", $packExist['users'] . $_SESSION['id'] . ','));
	$checkCol  = array(array("id", "=", $data['addPack'], ""));
	$execution = update('watch_pack_serge', $updateCol, $checkCol, '', $bdd);

	// Read list of sources used by watch pack
	$checkCol = array(array("pack_id", "=", $data['addPack'], "AND"),
										array("query", "=" , "[!source!]", ""));
	$result   = read('watch_pack_queries_serge', 'source', $checkCol, '', $bdd);

	$listOfSource_array = explode(",", $result[0]['source']);

	$checkCol = array(array("owners", "l", '%,' . $_SESSION['id'] . ',%', "OR"),
										array("owners", "l", '%,!' . $_SESSION['id'] . ',%', ""));
	$ownerSources = read('rss_serge', 'id', $checkCol, '', $bdd);

	if (!empty($ownerSources))
	{
		$ownerSources_array=',';

		foreach ($ownerSources as $own)
		{
			$ownerSources_array = $ownerSources_array . $own['id'] . ',';
		}

		$ownerSources_array = explode(",", $ownerSources_array);
	}

	foreach ($listOfSource_array as $source)
	{
		// Add source to actual user if the source is not already own
		if (empty($ownerSources) || !in_array($source, $ownerSources_array))
		{
			$checkCol     = array(array("id", "=", $source, ""));
			$sourceOwners = read('rss_serge', 'owners', $checkCol, '', $bdd);

			$updateCol = array(array("owners", $sourceOwners[0]['owners'] . $_SESSION['id'] . ','));
			$checkCol  = array(array("id", "=", $source, ""));
			$execution = update('rss_serge', $updateCol, $checkCol, '', $bdd);
		}
	}

	// Get entry where pack id is the id of the pack and source = science
	$checkCol = array(array("pack_id", "=", $data['addPack'], "AND"),
										array("source", "=" , "Science", "OR"),
										array("pack_id", "=", $data['addPack'], "AND"),
										array("source", "=" , "!Science", ""));
	$result = read('watch_pack_queries_serge', 'query', $checkCol, '', $bdd);

	foreach ($result as $scienceQuery)
	{
		// Add query to actual user if query is not already own
		$checkCol = array(array("query_arxiv", "=", $scienceQuery['query'], "AND"),
											array("owners", "l", '%,' . $_SESSION['id'] . ',%', "OR"),
											array("query_arxiv", "=", $scienceQuery['query'], "AND"),
											array("owners", "l", '%,!' . $_SESSION['id'] . ',%', ""));
		$queryExist = read('queries_science_serge', '', $checkCol, '', $bdd);

		if (!$queryExist)
		{
			$checkCol   = array(array("query_arxiv", " =", $scienceQuery['query'], ""));
			$queryExist = read('queries_science_serge', 'id, owners', $checkCol, '', $bdd);

			if (!empty($queryExist))
			{
				// Update query with the new owner
				$updateCol = array(array("owners", $queryExist['owners'] . $_SESSION['id'] . ','),
													array("active", $result['active'] + 1));
				$checkCol  = array(array("id", " =", $queryExist['id'], ""));
				$execution = update('queries_science_serge', $updateCol, $checkCol, '', $bdd);
			}
			else
			{
				// Creation of Doaj query
				$doajEq['ti']     = 'bibjson.title';
				$doajEq['au']     = 'bibjson.author.name';
				$doajEq['abs']    = 'bibjson.abstract';
				$doajEq['cat']    = 'bibjson.subject.term';
				$doajEq['all']    = '';
				$doajEq['OR']     = 'OR';
				$doajEq['AND']    = 'AND';
				$doajEq['NOTAND'] = 'NOT';
				$doajEq['+']      = ' ';

				$queryDoaj = $scienceQuery['query'];

				foreach($doajEq as $keyArxiv => $eqDoaj)
				{
						$queryDoaj = str_replace($keyArxiv, $eqDoaj, $queryDoaj);
				}

				// Add query
				$insertCol = array(array("query_arxiv", $scienceQuery['query']),
													array("query_doaj", $queryDoaj),
													array("owners", ',' . $_SESSION['id'] . ','),
													array("active", 1));
				$execution = insert('queries_science_serge', $insertCol, '', '', $bdd);
			}
		}
	}

	// Get entry where pack id is the id of the pack and source = patent
	$checkCol = array(array("pack_id", "=", $data['addPack'], "AND"),
										array("source", "=" , "Patent", "OR"),
										array("pack_id", "=", $data['addPack'], "AND"),
										array("source", "=" , "!Patent", ""));
	$result   = read('watch_pack_queries_serge', 'query', $checkCol, '', $bdd);

	foreach ($result as $patentQuery)
	{
		// Add query to actual user if query is not already own
		$checkCol   = array(array("query", "=", $patentQuery['query'], "AND"),
												array("owners", "l", '%,' . $_SESSION['id'] . ',%', "OR"),
												array("query", "=", $patentQuery['query'], "AND"),
												array("owners", "l", '%,!' . $_SESSION['id'] . ',%', ""));
		$queryExist = read('queries_wipo_serge', '', $checkCol, '', $bdd);

		if (!$queryExist)
		{
			$checkCol   = array(array("query", " =", $patentQuery['query'], ""));
			$queryExist = read('queries_wipo_serge', 'id, owners', $checkCol, '', $bdd);

			if (!empty($queryExist))
			{
				// Update query with new owner
				$updateCol = array(array("owners", $queryExist['owners'] . $_SESSION['id'] . ','),
													array("active", $result['active'] + 1));
				$checkCol  = array(array("id", "=", $queryExist['id'], ""));
				$execution = update('queries_wipo_serge', $updateCol, $checkCol, '', $bdd);
			}
			else
			{
				// Add query
				$insertCol = array(array("query", $patentQuery['query']),
													array("owners", ',' . $_SESSION['id'] . ','),
													array("active", 1));
				$execution = insert('queries_wipo_serge', $insertCol, '', '', $bdd);
			}
		}
	}

	// Get all entries that isn't contain science patnt or [!source!]
	$checkCol = array(array("pack_id", "=", $data['addPack'], "AND"),
										array("source", "<>" , "Science", "AND"),
										array("source", "<>" , "Patent", "AND"),
										array("query", "<>" , "[!source!]", ""));
	$result = read('watch_pack_queries_serge', 'query, source', $checkCol, '', $bdd);

	foreach ($result as $couple)
	{
		// Add couple keyword, sources to actual user if couple is not already own
		$sourceId   = preg_replace("/,([^$])/", ",!*$1", $couple['source']);
		$checkCol   = array(array("keyword", "=", strtolower($couple['query']), "AND"),
												array("applicable_owners_sources", "REGEXP", '\\|' . $_SESSION['id'] . ':[,0-9+,^\\|]*' . $sourceId, ""));
		$queryExist = read('keyword_news_serge', '', $checkCol, '', $bdd);

		if (!$queryExist)
		{
			$checkCol   = array(array("keyword", " =", strtolower($couple['query']), ""));
			$queryExist = read('keyword_news_serge', 'id, applicable_owners_sources', $checkCol, '', $bdd);

			if (!empty($queryExist))
			{
				// Check if user already own keyword
				$checkCol       = array(array("keyword", " =", strtolower($couple['query']), "AND"),
																array("applicable_owners_sources", "l", '%|' . $_SESSION['id'] . ':%', ""));
				$userOwnKeyword = read('keyword_news_serge', 'id, applicable_owners_sources', $checkCol, '', $bdd);

				if (empty($userOwnKeyword))
				{
					// Update with new source
					$updateCol = array(array("applicable_owners_sources", $queryExist['applicable_owners_sources'] . $_SESSION['id'] . ':' . $couple['source'] . '|'),
														array("active", $userOwnKeyword['active'] + 1));
					$checkCol  = array(array("id", "=", $queryExist['id'], ""));
					$execution = update('keyword_news_serge', $updateCol, $checkCol, '', $bdd);
				}
				else
				{
					// Update with new owner
					$userId    = $_SESSION['id'];
					$updateCol = array(array("applicable_owners_sources", preg_replace("/(\|$userId:[,0-9+,]+),(.*)/", '$1' . $couple['source'] . '$2', $userOwnKeyword['applicable_owners_sources'])),
														array("active", $userOwnKeyword['active'] + 1));
					$checkCol  = array(array("id", "=", $userOwnKeyword['id'], ""));
					$execution = update('keyword_news_serge', $updateCol, $checkCol, '', $bdd);
				}
			}
			else
			{
				// Add query
				$insertCol = array(array("keyword", $couple['query']),
													array("applicable_owners_sources", '|' . $_SESSION['id'] . ':' . $couple['source'] . '|'),
													array("active", 1));
				$execution = insert('keyword_news_serge', $insertCol, '', '', $bdd);
			}
		}
	}

	header('Location: setting');
	die();
}
?>
