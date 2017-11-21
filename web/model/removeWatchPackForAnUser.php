<?php
// Check if pack exist
$checkCol  = array(array('id', '=', $data['removePack'], ''));
$packExist = read('watch_pack_serge', 'users', $checkCol, '', $bdd);

if (!empty($packExist))
{
	// Get entry where pack id is the id of the pack and source = science
	$checkCol = array(array('pack_id', '=', $data['removePack'], 'AND'),
										array('source', '=' , 'Science', 'OR'),
										array('pack_id', '=', $data['removePack'], 'AND'),
										array('source', '=' , '!Science', ''));
	$result   = read('watch_pack_queries_serge', 'query', $checkCol, '', $bdd);

	foreach ($result as $scienceQuery)
	{
		// Add query to actual user if query is not already own
		$checkCol   = array(array('query_arxiv', '=', $scienceQuery['query'], 'AND'),
												array('owners', 'l', '%,' . $_SESSION['id'] . ',%', 'OR'),
												array('query_arxiv', '=', $scienceQuery['query'], 'AND'),
												array('owners', 'l', '%,!' . $_SESSION['id'] . ',%', ''));
		$queryExist = read('queries_science_serge', 'id, owners', $checkCol, '', $bdd);

		if (!empty($queryExist))
		{
			// Update query with the new owner
			$userId    = $_SESSION['id'];
			$updateCol = array(array('owners', preg_replace("/,!*$userId,/", ',', $queryExist['owners'])),
												array('active', $result['active'] - 1));
			$checkCol  = array(array('id', '=', $queryExist['id'], ''));
			$execution = update('queries_science_serge', $updateCol, $checkCol, '', $bdd);
		}
	}

	// Get entry where pack id is the id of the pack and source = patent
	$checkCol = array(array('pack_id', '=', $data['removePack'], 'AND'),
										array('source', '=' , 'Patent', 'OR'),
										array('pack_id', '=', $data['removePack'], 'AND'),
										array('source', '=' , '!Patent', ''));
	$result   = read('watch_pack_queries_serge', 'query', $checkCol, '', $bdd);

	foreach ($result as $patentQuery)
	{
		// Add query to actual user if query is not already own
		$checkCol   = array(array('query', '=', $patentQuery['query'], 'AND'),
												array('owners', 'l', '%,' . $_SESSION['id'] . ',%', 'OR'),
												array('query', '=', $patentQuery['query'], 'AND'),
												array('owners', 'l', '%,!' . $_SESSION['id'] . ',%', ''));
		$queryExist = read('queries_wipo_serge', 'id, owners', $checkCol, '', $bdd);

		if (!empty($queryExist))
		{
			// Update query with new owner
			$userId    = $_SESSION['id'];
			$updateCol = array(array('owners', preg_replace("/,!*$userId,/", ',', $queryExist['owners'])),
												array('active', $result['active'] - 1));
			$checkCol  = array(array('id', '=', $queryExist['id'], ''));
			$execution = update('queries_wipo_serge', $updateCol, $checkCol, '', $bdd);
		}
	}

	// Get all entries that isn't contain science patent or [!source!]
	$checkCol = array(array('pack_id', '=', $data['removePack'], 'AND'),
										array('source', '<>' , 'Science', 'AND'),
										array('source', '<>' , 'Patent', 'AND'),
										array('query', '<>' , '[!source!]', ''));
	$result   = read('watch_pack_queries_serge', 'query, source', $checkCol, '', $bdd);

	foreach ($result as $couple)
	{
		// Add couple keyword, sources to actual user if couple is not already own
		$sourceId   = preg_replace("/,([^$])/", ",!*$1", $couple['source']);
		$checkCol   = array(array('keyword', '=', strtolower($couple['query']), 'AND'),
												array('applicable_owners_sources', 'REGEXP', '\\|' . $_SESSION['id'] . ':[,0-9+,^\\|]*' . $sourceId, ''));
		$queryExist = read('keyword_news_serge', 'id, applicable_owners_sources, active', $checkCol, '', $bdd);
		$queryExist = $queryExist[0] ?? '';

		if (!empty($queryExist))
		{
			// Update with new source
			$userId    = $_SESSION['id'];
			$sourceId  = preg_replace("/,([^$])/", ",!*$1", $couple['source']);
			$updateCol = array(array('applicable_owners_sources', preg_replace("/(\|$userId:[,0-9+,^\|]*)$sourceId/", '$1,', $queryExist['applicable_owners_sources'])),
												array('active', $queryExist['active'] - 1));
			$checkCol  = array(array('id', '=', $queryExist['id'], ''));
			$execution = update('keyword_news_serge', $updateCol, $checkCol, '', $bdd);
		}
	}

	// Remove unsed sources
	// Read list of sources used by the watch pack
	$checkCol           = array(array('pack_id', '=', $data['removePack'], 'AND'),
															array('query', '=' , '[!source!]', ''));
	$result             = read('watch_pack_queries_serge', 'source', $checkCol, '', $bdd);
	$listOfSource_array = explode(',', $result[0]['source']);

	$checkCol        = array(array('owners', 'l', '%,' . $_SESSION['id'] . ',%', 'AND'),
													array('id', 'IN', $listOfSource_array, 'OR'),
													array('owners', 'l', '%,!' . $_SESSION['id'] . ',%', 'AND'),
													array('id', 'IN', $listOfSource_array, ''));
	$sourceWatchPack = read('rss_serge', 'id, owners, active', $checkCol, '', $bdd);


	foreach ($sourceWatchPack as $source)
	{
		// Read Keyword if it is own by user and use current source
		$checkCol     = array(array('applicable_owners_sources', 'REGEXP', '\\|' . $_SESSION['id'] . ':[,0-9+,^\\|]*,!*' . $source['id'] . ',', ''));
		$sourceIsUsed = read('keyword_news_serge', '', $checkCol, '', $bdd);

		if (!$sourceIsUsed)
		{
			// Remove source
			$userId    = $_SESSION['id'];
			$updateCol = array(array('owners', preg_replace("/,!*$userId,/", ',', $source['owners'])),
												array('active', $source['active'] - 1));
			$checkCol  = array(array('id', '=', $source['id'], ''));
			$execution = update('rss_serge', $updateCol, $checkCol, '', $bdd);
		}
	}

	// Remove user from watchPack
	$userId    = $_SESSION['id'];
	$updateCol = array(array('users', preg_replace("/,!*$userId,/", ',', $packExist[0]['users'])));
	$checkCol  = array(array('id', '=', $data['removePack'], ''));
	$execution = update('watch_pack_serge', $updateCol, $checkCol, '', $bdd);

	header('Location: setting');
	die();
}
?>
