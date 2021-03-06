<?php
include('controller/accessLimitedToSignInPeople.php');
include('model/get_text.php');
include('model/get_text_var.php');
include('model/read.php');
include('model/update.php');
include('model/insert.php');
include('controller/generateNonce.php');

// Define variables
$actualLetter         = '';
$style                = '';
$orderByKeyword       = '';
$orderBySource        = '';
$orderByType          = '';
$searchSort           = '';
$OPTIONALCOND         = '';
$actualPageLink       = '';
$selectedLanguageCode = '';
$colOrder['name']     = '';
$colOrder['author']   = '';
$colOrder['category'] = '';
$colOrder['date']     = '';
$limit                = 15;
$base                 = 0;
$page                 = 0;
$readPackSources      = array();
$listAllSources       = array();
$packSelected         = FALSE;

# Data processing
$unsafeData = array();
$unsafeData = array_merge($unsafeData, array(array('action', 'action', 'GET', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('query', 'query', 'GET', '09')));
$unsafeData = array_merge($unsafeData, array(array('search', 'search', 'GET', 'str')));
$unsafeData = array_merge($unsafeData, array(array('type', 'type', 'GET', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('orderBy', 'orderBy', 'GET', 'str')));
$unsafeData = array_merge($unsafeData, array(array('languageGET', 'language', 'GET', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('packId', 'packId', 'GET', '09')));
$unsafeData = array_merge($unsafeData, array(array('page', 'page', 'GET', '09')));
$unsafeData = array_merge($unsafeData, array(array('optionalCond', 'optionalCond', 'GET', 'Az')));

$unsafeData = array_merge($unsafeData, array(array('addPack', 'addPack', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('AddStar', 'AddStar', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('watchPackList', 'watchPackList', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('addNewPack', 'addNewPack', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('watchPackName', 'watchPackName', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('watchPackDescription', 'watchPackDescription', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('watchPackCategory', 'watchPackCategory', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('language', 'language', 'POST', 'str')));

$unsafeData = array_merge($unsafeData, array(array('scrollPos', 'scrollPos', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('sourceType', 'sourceType', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('newSource', 'newSource', 'POST', 'url')));
$unsafeData = array_merge($unsafeData, array(array('addNewKeyword', 'addNewKeyword', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('sourceKeyword', 'sourceKeyword', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('newKeyword', 'newKeyword', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('delKeyword', 'delKeyword', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('disableKeyword', 'disableKeyword', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('activateKeyword', 'activateKeyword', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('addNewSource', 'addNewSource', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('delSource', 'delSource', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('disableSource', 'disableSource', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('activateSource', 'activateSource', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('delEditingScienceQuery', 'delEditingScienceQuery', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('extendScience', 'extendScience', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('scienceQuerySubmit', 'scienceQuerySubmit', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('delQueryScience', 'delQueryScience', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('disableQueryScience', 'disableQueryScience', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('activateQueryScience', 'activateQueryScience', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('delEditingPatentQuery', 'delEditingPatentQuery', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('extendPatent', 'extendPatent', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('patentQuerySubmit', 'patentQuerySubmit', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('delQueryPatent', 'delQueryPatent', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('disableQueryPatent', 'disableQueryPatent', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('activateQueryPatent', 'activateQueryPatent', 'POST', '09')));

foreach($_POST as $key => $val)
{
		$key = htmlspecialchars($key);
		if (preg_match("/radio-s./", $key, $name) ||
		preg_match("/radio-ks[0-9]+/", $key, $name) ||
		preg_match("/andOrNot[0-9]+/", $key, $name) ||
		preg_match("/openParenthesis[0-9]+/", $key, $name) ||
		preg_match("/closeParenthesis[0-9]+/", $key, $name) ||
		preg_match("/scienceType[0-9]+/", $key, $name) ||
		preg_match("/scienceQuery[0-9]+/", $key, $name) ||
		preg_match("/andOrPatent[0-9]+/", $key, $name) ||
		preg_match("/patentType[0-9]+/", $key, $name) ||
		preg_match("/patentQuery[0-9]+/", $key, $name))
		{
			$unsafeData = array_merge($unsafeData, array(array($name[0], $name[0], 'POST', 'str')));
		}
}
include('controller/dataProcessing.php');

# Nonce
$nonceTime = $_SERVER['REQUEST_TIME'];
$nonce     = getNonce($nonceTime);


if (empty($_SESSION['cptScienceQuery']))
{
	$_SESSION['cptScienceQuery'] = 3;
}

if (empty($_SESSION['cptPatentQuery']))
{
	$_SESSION['cptPatentQuery'] = 3;
}

# Save folding state
if (!empty($data['sourceType']))
{
	foreach($_SESSION as $key => $val)
	{
		if (preg_match("/radio-s./", $key) || preg_match("/radio-ks[0-9]+/", $key))
		{
			$_SESSION[$key] = '';
		}
	}
	foreach($data as $key => $val)
	{
		if (preg_match("/radio-s./", $key) || preg_match("/radio-ks[0-9]+/", $key))
		{
			$_SESSION[$key] = $val;
		}
	}
}

# Nav activation for this page
$resultTab  = '';
$wikiTab    = '';
$settingTab = 'active';

# Type
$type             = 'add';
$addActive        = 'class="active"';
$createActive     = '';
$tableName        = 'result_science_serge';
$tableNameQuery   = 'queries_science_serge';
$tableNameSource  = 'science_sources_serge';
$ownersColumn     = 'owners';
$userId           = ',' . $_SESSION['id'] . ',';
$keywordQueryId   = 'query_id';
$queryColumn      = 'query_serge';
$specialColumn    = ',query_id, id_source ';
$displayColumn    = 'Query';
$_SESSION['type'] = 'add';
$limit            = 15;

if (!empty($data['type']) && $data['type'] === 'create')
{
	$type             = 'create';
	$createActive     = 'class="active"';
	$addActive        = '';
	$tableName        = 'result_science_serge';
	$tableNameQuery   = 'queries_science_serge';
	$tableNameSource  = 'science_sources_serge';
	$ownersColumn     = 'owners';
	$userId           = ',' . $_SESSION['id'] . ',';
	$keywordQueryId   = 'query_id';
	$queryColumn      = 'query_serge';
	$specialColumn    = ',query_id, id_source ';
	$displayColumn    = 'Query';
	$_SESSION['type'] = 'create';
	unset($limit);
}

# Read if user mail is check
$checkCol     = array(array('email_validation', '=', 1, 'AND'),
											array('id', '=', $_SESSION['id'], ''));
$emailIsCheck = read('users_table_serge', '', $checkCol, '', $bdd);

if (!$emailIsCheck)
{
	# Javascript message if the user has not checked his email address
	echo '<script>alert("Your email is not verified, you will not be able to use Serge")</script>';
}

# Read science search fields
include_once('model/readColumns.php');

$nextColumnName = FALSE;
foreach ($columnsNames as $columnsName)
{
	if ($nextColumnName && $columnsName['Field'] != 'active')
	{
		$selected[$columnsName['Field']] = '';
	}

	if ($columnsName['Field'] === 'quote')
	{
		$nextColumnName = TRUE;
	}
}

if ($type === 'add')
{
	# Page number
	if (!empty($data['page']))
	{
		$actualPageLink = '&page=' . $data['page'];
		$limit          = 15;
		$page           = $data['page'] - 1;
		$base           = $limit * $page;
	}

	$checkCol    = array();
	$languageBDD = read('language_serge', 'code, name', $checkCol, '', $bdd);

	$colOrder['language'] = '<select name="language" onchange="this.form.submit();">';
	$colOrder['language'] = $colOrder['language'] . PHP_EOL . '<option value="all" selected>All languages</option>';

	$languageGET = preg_replace("/[^a-z]/", '', $data['languageGET']);

	foreach ($languageBDD as $languageLine)
	{
		if ($languageGET === $languageLine['code'])
		{
			$colOrder['language'] = $colOrder['language'] . PHP_EOL . '<option value="' . $languageLine['code'] . '" selected>' . $languageLine['code'] . ' &nbsp;&nbsp;' . $languageLine['name'] . '</option>';
			$selectedLanguageCode = $languageLine['code'];
		}
		else
		{
			$colOrder['language'] = $colOrder['language'] . PHP_EOL . '<option value="' . $languageLine['code'] . '">' . $languageLine['code'] . ' &nbsp;&nbsp;' . $languageLine['name'] . '</option>';
		}
	}

	$orderBy = $data['orderBy'];

	$colOrder['language'] = $colOrder['language'] . PHP_EOL . '</select>
	<input type="hidden" name="orderBy" value="' . $orderBy . '"/>';

	if ($emailIsCheck && !empty($data['addPack']))
	{
		include('model/AddWatchPackToAnUser.php');
	}

	# Add a star
	if ($emailIsCheck && !empty($data['AddStar']))
	{
		$checkCol = array(array('id', '=', $data['AddStar'], ''));
		$result = read('watch_pack_serge', 'rating', $checkCol, '', $bdd);
		$usersStars = $result[0];

		if (empty($usersStars['rating']))
		{
			$usersStars['rating'] = ',';
		}

		$pattern = ',' . $_SESSION['id'] . ',';
		if (preg_match("/$pattern/", $usersStars['rating']))
		{
			$usersStars = preg_replace("/$pattern/", ',', $usersStars['rating']);
		}
		else
		{
			$usersStars = $usersStars['rating'] . $_SESSION['id'] . ',';
		}

		$updateCol = array(array('rating', $usersStars));
		$checkCol = array(array('id', '=', $data['AddStar'], ''));
		$execution = update('watch_pack_serge', $updateCol, $checkCol, '', $bdd);

		header('Location: watchPack');
		die();
	}

	# Order results
	$colOrder['rate'] = ' ▴';
	$colOrder['DESC'] = '';
	# WARNING sensitive variable [SQLI]
	$ORDERBY = 'ORDER BY `NumberOfStars` DESC';
	if (!empty($data['search']))
	{
		# Order results
		$colOrder['rate'] = '';
		$colOrder['DESC'] = '';
		# WARNING sensitive variable [SQLI]
		$ORDERBY = '';
	}

	if (!empty($data['orderBy']))
	{
		$orderBy = $data['orderBy'];
		if ($orderBy === 'name')
		{
			$colOrder['name'] = '▾';
			$colOrder['DESC'] = 'DESC';

			# WARNING sensitive variable [SQLI]
			$ORDERBY = 'ORDER BY name';
		}
		elseif ($orderBy === 'nameDESC')
		{
			$colOrder['name'] = '▴';
			$colOrder['DESC'] = '';

			# WARNING sensitive variable [SQLI]
			$ORDERBY = 'ORDER BY name DESC';
		}
		elseif ($orderBy === 'author')
		{
			$colOrder['author'] = '▾';
			$colOrder['DESC'] = 'DESC';

			# WARNING sensitive variable [SQLI]
			$ORDERBY = 'ORDER BY author';
		}
		elseif ($orderBy === 'authorDESC')
		{
			$colOrder['author'] = '▴';
			$colOrder['DESC'] = '';

			# WARNING sensitive variable [SQLI]
			$ORDERBY = 'ORDER BY author DESC';
		}
		elseif ($orderBy === 'category')
		{
			$colOrder['category'] = '▾';
			$colOrder['DESC'] = 'DESC';

			# WARNING sensitive variable [SQLI]
			$ORDERBY = 'ORDER BY category';
		}
		elseif ($orderBy === 'categoryDESC')
		{
			$colOrder['category'] = '▴';
			$colOrder['DESC'] = '';

			# WARNING sensitive variable [SQLI]
			$ORDERBY = 'ORDER BY category DESC';
		}
		elseif ($orderBy === 'date')
		{
			$colOrder['date'] = '▾';
			$colOrder['DESC'] = 'DESC';

			# WARNING sensitive variable [SQLI]
			$ORDERBY = 'ORDER BY update_date';
		}
		elseif ($orderBy === 'dateDESC')
		{
			$colOrder['date'] = '▴';
			$colOrder['DESC'] = '';

			# WARNING sensitive variable [SQLI]
			$ORDERBY = 'ORDER BY update_date DESC';
		}
		elseif ($orderBy === 'rate')
		{
			$colOrder['rate'] = ' ▾';
			$colOrder['DESC'] = 'DESC';

			# WARNING sensitive variable [SQLI]
			$ORDERBY = 'ORDER BY `NumberOfStars`';
		}
		elseif ($orderBy === 'rateDESC')
		{
			$colOrder['rate'] = ' ▴';
			$colOrder['DESC'] = '';

			# WARNING sensitive variable [SQLI]
			$ORDERBY = 'ORDER BY `NumberOfStars` DESC';
		}
		$orderBy = '&orderBy=' . $orderBy;
	}
	elseif (!empty($data['language']))
	{
		$checkCol = array(array('language', '=', mb_strtoupper($selectedLanguageCode), ''));
	}

	# Search engine
	$tableName = 'watch_pack_serge';
	$tableNameQuery = '';
	# Warning sensitive variables [SQLI]
	$SELECTRESULT = '(SELECT id, name, description, author, users, category, language, update_date, rating, ((LENGTH(`rating`) - LENGTH(REPLACE(`rating`, \',\', \'\')))-1) AS `NumberOfStars` FROM ' . $tableName . ' WHERE search_index IS NOT NULL';
	$WP = 'WP';
	$arrayValues = array();
	include('controller/searchEngine.php');

	include('model/readOwnerResult.php');

	$watchPacks = $readOwnerResults;
}
else
{
	if (!empty($data['packId']))
	{
		$checkCol    = array(array('author', '=', $_SESSION['pseudo'], 'AND'),
												array('id', '=', $data['packId'], ''));
		$result      = read('watch_pack_serge', 'name, description, category, language', $checkCol, '', $bdd);
		$packDetails = $result[0] ?? '';

		if (empty($packDetails))
		{
			header('Location: watchPack?type=create');
			die();
		}

		$checkCol = array(array('pack_id', '=', $data['packId'], 'AND'),
											array('query', '=', '[!source!]', ''));
		$resultPackSources = read('watch_pack_queries_serge', 'source', $checkCol, '', $bdd);

		$packSource = array('0');
		foreach ($resultPackSources as $resultSources)
		{
			if (preg_match("/^[,!0-9,]+$/", $resultSources['source']))
			{
				$resultSources['source'] = preg_replace("/!/", '', $resultSources['source']);
				$packSource = array_merge(preg_split('/,/', $resultSources['source'], -1, PREG_SPLIT_NO_EMPTY), $packSource);
			}
		}

		$checkCol       = array(array('id', 'IN', $packSource, ''));
		$listAllSources = read('rss_serge', 'id, link, name, owners, active', $checkCol, 'ORDER BY name', $bdd);

		$checkCol    = array(array('pack_id', '=', $data['packId'], 'AND'),
											array('query', '<>', '[!source!]', ''));
		$packSources = read('watch_pack_queries_serge', 'source', $checkCol, '', $bdd);

		$packSourceUsed = array('0');
		foreach ($packSources as $packSourcesLine)
		{
			if (preg_match("/^[,!0-9,]+$/", $packSourcesLine['source']))
			{
				$packSourcesLine['source'] = preg_replace("/!/", '', $packSourcesLine['source']);
				$packSourceUsed = array_merge(preg_split('/,/', $packSourcesLine['source'], -1, PREG_SPLIT_NO_EMPTY), $packSourceUsed);
			}
		}
		$checkCol = array(array('id', 'IN', $packSourceUsed, ''));
		$readPackSources = read('rss_serge', 'id, link, name, owners, active', $checkCol, 'ORDER BY name', $bdd);
	}
	else
	{
		$checkCol    = array(array('id', '=', $_SESSION['id'], ''));
		$packDetails = read('users_table_serge', 'language', $checkCol, '', $bdd);
		$packDetails = $packDetails[0];
	}

	$checkCol    = array();
	$languageBDD = read('language_serge', 'code, name', $checkCol, '', $bdd);

	$userLang       = mb_strtolower($packDetails['language']);
	$selectLanguage = '<select class="shortSelect" name="language">' . PHP_EOL;

	foreach ($languageBDD as $languageLine)
	{
		if ($userLang === $languageLine['code'])
		{
			$selectLanguage = $selectLanguage . PHP_EOL . '<option value="' . $languageLine['code'] . '" selected>' . $languageLine['code'] . ' &nbsp;&nbsp;&nbsp;&nbsp;' . $languageLine['name'] . '</option>';
			$selectedLanguageCode = $languageLine['code'];
		}
		else
		{
			$selectLanguage = $selectLanguage . PHP_EOL . '<option value="' . $languageLine['code'] . '">' . $languageLine['code'] . ' &nbsp;&nbsp;&nbsp;&nbsp;' . $languageLine['name'] . '</option>';
		}
	}

	$selectLanguage = $selectLanguage . PHP_EOL . '</select>';

	if ($emailIsCheck && isset($data['watchPackList']) && $data['watchPackList'] == 0 && empty($data['addNewPack']))
	{
		header('Location: watchPack?type=create');
		die();
	}
	// Edit a pack
	elseif ($emailIsCheck && !empty($data['packId']) && !empty($data['addNewPack']) && !empty($data['watchPackName']) && !empty($data['watchPackDescription']))
	{
		// Check if watch pack is own by the user
		$checkCol  = array(array('author', '=', $_SESSION['pseudo'], 'AND'),
											array('id', '=', $data['packId'], ''));
		$packIsOwn = read('watch_pack_serge', '', $checkCol, '', $bdd);

		$checkCol  = array(array('name', '=', mb_strtolower($data['watchPackName']), 'AND'),
											array('id', '<>', $data['packId'], ''));
		$nameExist = read('watch_pack_serge', '', $checkCol, '', $bdd);

		if ($packIsOwn && !$nameExist)
		{
			$updateCol = array(array('name', mb_strtolower($data['watchPackName'])),
												array('description', $data['watchPackDescription']),
												array('category', $data['watchPackCategory']),
												array('language', $data['language']),
												array('update_date', $_SERVER['REQUEST_TIME']),
												array('search_index', NULL));
			$checkCol = array(array('id', '=', $data['packId'], ''));
			$execution = update('watch_pack_serge', $updateCol, $checkCol, '', $bdd);
		}
	}
	elseif ($emailIsCheck && !empty($data['addNewKeyword']) && isset($data['sourceKeyword']) && !empty($data['newKeyword']))
	{
		$data['newKeyword'] = preg_replace("/,\s+/", ",", $data['newKeyword']);
		$newKeywordArray    = preg_split('/,/', $data['newKeyword'], -1, PREG_SPLIT_NO_EMPTY);

		if ($data['sourceKeyword'] == '0')
		{
			# Add keyword on all sources
			foreach ($listAllSources as $sourcesList)
			{
				foreach ($newKeywordArray as $newKeyword)
				{
					$checkCol = array(array('query', '=', mb_strtolower($newKeyword), 'AND'),
														array('pack_id', '=', $data['packId'], 'AND'),
														array('source', '<>', 'Science', 'AND'),
														array('source', '<>', 'Patent', ''));
					$result = read('watch_pack_queries_serge', 'id, source', $checkCol, '', $bdd);
					$resultKeyword = $result[0] ?? '';

					if (empty($resultKeyword))
					{
						$insertCol = array(array('pack_id', $data['packId']),
															array('query', mb_strtolower($newKeyword)),
															array('source', ',' . $sourcesList['id'] . ','));
						$execution = insert('watch_pack_queries_serge', $insertCol, '', '', $bdd);
					}
					else
					{
						# TODO Vérif qu'on ajoute pas deux fois les sources
						$updateCol = array(array('source', $resultKeyword['source'] . $sourcesList['id'] . ','));
						$checkCol = array(array('id', '=', $resultKeyword['id'], ''));
						$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
					}
				}
			}
		}
		else
		{
			foreach ($newKeywordArray as $newKeyword)
			{
				$checkCol = array(array('id', '=', $data['sourceKeyword'], ''));
				$result = read('rss_serge', 'id', $checkCol, '', $bdd);
				$resultSource = $result[0];

				if (!empty($resultSource))
				{
					$checkCol = array(array('query', '=', mb_strtolower($newKeyword), 'AND'),
														array('pack_id', '=', $data['packId'], 'AND'),
														array('source', '<>', 'Science', 'AND'),
														array('source', '<>', 'Patent', ''));
					$result = read('watch_pack_queries_serge', 'id, source', $checkCol, '', $bdd);
					$resultKeyword = $result[0] ?? '';

					$newKeywordSource = ',' . $data['sourceKeyword'] . ',';

					if (empty($resultKeyword))
					{
						$insertCol = array(array('pack_id', $data['packId']),
															array('query', mb_strtolower($newKeyword)),
															array('source', ',' . $data['sourceKeyword'] . ','));
						$execution = insert('watch_pack_queries_serge', $insertCol, '', '', $bdd);
					}
					elseif (!preg_match("/$newKeywordSource/", $resultKeyword['source']))
					{
						$updateCol = array(array('source', $resultKeyword['source'] . $data['sourceKeyword'] . ','));
						$checkCol = array(array('id', '=', $resultKeyword['id'], ''));
						$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
					}
				}
			}
		}
	}
	elseif ($emailIsCheck && !empty($data['addNewSource']) && !empty($data['newSource']))
	{
		$checkCol     = array(array('link', '=', $data['newSource'], ''));
		$result       = read('rss_serge', 'id', $checkCol, '', $bdd);
		$resultSource = $result[0]['id'] ?? '';

		$checkCol = array(array('query', '=', '[!source!]', 'AND'),
											array('pack_id', '=', $data['packId'], ''));
		$result   = read('watch_pack_queries_serge', 'source', $checkCol, '', $bdd);
		$sources  = $result[0]['source'] ?? '';

		$newSourceId = ',' . $resultSource . ',';

		if (!empty($resultSource) && !preg_match("/$newSourceId/", $sources))
		{
			$updateCol = array(array('source', $sources . $resultSource . ','));
			$checkCol  = array(array('pack_id', '=', $data['packId'], 'AND'),
												array('query', '=', '[!source!]', ''));
			$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
		}
		elseif (!preg_match("/$newSourceId/", $sources))
		{
			// Check if source is valid
			$sourceToTest = escapeshellarg($data['newSource']);
			$packIdCmd    = escapeshellarg($data['packId']);
			$cmd          = 'timeout 150  /usr/bin/python /var/www/Serge/checkfeed.py ' . $sourceToTest . ' ' . $userId . ' watchpack ' . $packIdCmd . ' >> /var/www/Serge/web/logs/error.log 2>&1 &';

			# Check if the link is valid
			exec($cmd);
		}
	}
	elseif ($emailIsCheck && (!empty($data['delKeyword']) || !empty($data['disableKeyword']) || !empty($data['activateKeyword'])))
	{
		# Delete, disable, active keyword
		if (!empty($data['delKeyword']))
		{
			preg_match_all("/[0-9]*&/", $data['delKeyword'], $matchKeywordAndSource);
			$sourceIdAction  = preg_replace("/[^0-9]/", '', $matchKeywordAndSource[0][0]);
			$keywordIdAction = preg_replace("/[^0-9]/", '', $matchKeywordAndSource[0][1]);
			$action          = 'delKeyword';
		}
		elseif (!empty($data['disableKeyword']))
		{
			preg_match_all("/[0-9]*&/", $data['disableKeyword'], $matchKeywordAndSource);
			$sourceIdAction  = preg_replace("/[^0-9]/", '', $matchKeywordAndSource[0][0]);
			$keywordIdAction = preg_replace("/[^0-9]/", '', $matchKeywordAndSource[0][1]);
			$action          = 'disableKeyword';
		}
		elseif (!empty($data['activateKeyword']))
		{
			preg_match_all("/[0-9]*&/", $data['activateKeyword'], $matchKeywordAndSource);
			$sourceIdAction  = preg_replace("/[^0-9]/", '', $matchKeywordAndSource[0][0]);
			$keywordIdAction = preg_replace("/[^0-9]/", '', $matchKeywordAndSource[0][1]);
			$action          = 'activateKeyword';
		}

		if (isset($sourceIdAction) && isset($keywordIdAction) && isset($action))
		{
			# Check if keyword exist
			$checkCol = array(array('id', '=', $keywordIdAction, 'AND'),
												array('pack_id', '=', $data['packId'], 'AND'),
												array('source', 'l', '%,' . $sourceIdAction . ',%', 'OR'),
												array('id', '=', $keywordIdAction, 'AND'),
												array('source', 'l', '%,!' . $sourceIdAction . ',%', 'AND'),
												array('pack_id', '=', $data['packId'], ''));
			$result = read('watch_pack_queries_serge', 'source', $checkCol, '', $bdd);
			$result = $result[0] ?? '';

			# Delete an existing keyword
			if (!empty($result) && $action === 'delKeyword')
			{
				$updateCol = array(array('source', preg_replace("/,!*$sourceIdAction,/", ',', $result['source'])));
				$checkCol = array(array('id', '=', $keywordIdAction, ''));
				$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
			}
			elseif (!empty($result) && $action === 'disableKeyword')
			{
				$updateCol = array(array('source', preg_replace("/,$sourceIdAction,/", ",!$sourceIdAction,", $result['source'])));
				$checkCol = array(array('id', '=', $keywordIdAction, ''));
				$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
			}
			elseif (!empty($result) && $action === 'activateKeyword')
			{
				$updateCol = array(array('source', preg_replace("/,!$sourceIdAction,/", ",$sourceIdAction,", $result['source'])));
				$checkCol = array(array('id', '=', $keywordIdAction, ''));
				$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
			}
			else
			{
				$ERROR_MESSAGE = 'Keyword doesn\'t exist or invalid action';
			}
		}
	}
	elseif ($emailIsCheck && (!empty($data['delSource']) || !empty($data['disableSource']) || !empty($data['activateSource'])))
	{
		# Delete, disable, active sources
		if (!empty($data['delSource']))
		{
			preg_match("/[0-9]*&/", $data['delSource'], $matchSourceId);
			$sourceIdAction  = preg_replace("/[^0-9]/", '', $matchSourceId[0]);
			$action          = 'delSource';
		}
		elseif (!empty($data['disableSource']))
		{
			preg_match("/[0-9]*&/", $data['disableSource'], $matchSourceId);
			$sourceIdAction  = preg_replace("/[^0-9]/", '', $matchSourceId[0]);
			$action          = 'disableSource';
		}
		elseif (!empty($data['activateSource']))
		{
			preg_match("/[0-9]*&/", $data['activateSource'], $matchSourceId);
			$sourceIdAction  = preg_replace("/[^0-9]/", '', $matchSourceId[0]);
			$action          = 'activateSource';
		}

		if (isset($sourceIdAction) && isset($action))
		{
			# Check if source exist for this owner
			$checkCol = array(array('query', '=', '[!source!]', 'AND'),
												array('pack_id', '=', $data['packId'], 'AND'),
												array('source', 'l', '%,' . $sourceIdAction . ',%', ''));
			$sourceExist = read('watch_pack_queries_serge', '', $checkCol, '', $bdd);

			# Delete an existing sources
			if ($sourceExist && $action === 'delSource')
			{

				$checkCol = array(array('owners', 'l',  '%,' . $_SESSION['id'] . ',%', ''));
				$result = read('rss_serge', 'id', $checkCol, '', $bdd);

				$isSourceOwned = ',';
				foreach ($result as $ownerSource)
				{
					if ($ownerSource['id'] === $sourceIdAction)
					{
						$isSourceOwned = '[!source!]';
						break;
					}
				}

				// Remove source on all keywords

				$checkCol = array(array('pack_id', '=', $data['packId'], 'AND'),
													array('query', '<>', $isSourceOwned, 'AND'),
													array('source', 'l', '%,' . $sourceIdAction . ',%', 'OR'),
													array('pack_id', '=', $data['packId'], 'AND'),
													array('query', '<>', $isSourceOwned, 'AND'),
													array('source', 'l','%,!' . $sourceIdAction . ',%', ''));
				$result = read('watch_pack_queries_serge', 'id, source', $checkCol, '', $bdd);

				foreach ($result as $resultLine)
				{
					$sourceNew = preg_replace("/,!*$sourceIdAction,/", ',', $resultLine['source']);


					$updateCol = array(array('source', preg_replace("/,!*$sourceIdAction,/", ',', $resultLine['source'])));
					$checkCol = array(array('id', '=', $resultLine['id'], ''));
					$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
				}
			}
			elseif ($sourceExist && $action === 'disableSource')
			{
				// Disable source on all keywords

				$checkCol = array(array('pack_id', '=',$data['packId'], 'AND'),
													array('source', 'l', '%,' . $sourceIdAction . ',%', ''));
				$result = read('watch_pack_queries_serge', 'id, source', $checkCol, '', $bdd);

				foreach ($result as $resultLine)
				{
					$updateCol = array(array('source', preg_replace("/,$sourceIdAction,/", ",!$sourceIdAction,", $resultLine['source'])));
					$checkCol = array(array('id', '=', $resultLine['id'], ''));
					$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
				}
			}
			elseif ($sourceExist && $action === 'activateSource')
			{
				// Activate source on all keywords

				$checkCol = array(array('pack_id', '=',$data['packId'], 'AND'),
													array('source', 'l', '%,!' . $sourceIdAction . ',%', ''));
				$result = read('watch_pack_queries_serge', 'id, source', $checkCol, '', $bdd);

				foreach ($result as $resultLine)
				{
					$updateCol = array(array('source', preg_replace("/,!$sourceIdAction,/", ",$sourceIdAction,", $resultLine['source'])));
					$checkCol = array(array('id', '=', $resultLine['id'], ''));
					$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
				}
			}
			else
			{
				$ERROR_MESSAGE = 'Source doesn\'t exist or invalid action';
			}
		}
	}
	elseif ($emailIsCheck && !empty($data['scienceQuerySubmit']))
	{
		$cpt = 0;
		$open = 0;
		$close = 0;
		$nbscienceType = 'scienceType0';
		$queryScience = '';
		$_SESSION['cptScienceQuery'] = 3;

		while(!empty($data[$nbscienceType]) && !empty($data['scienceQuery' . $cpt]))
		{
			if (!empty($data['andOrNot' . $cpt])
					&& preg_match("/(^AND$|^OR$|^NOT$)/", $data['andOrNot' . $cpt]))
			{
				$queryScience = $queryScience . '|' . $data['andOrNot' . $cpt];
			}
			elseif (!empty($data['andOrNot' . $cpt])
							&& !preg_match("/(^AND$|^OR$|^NOT$)/", $data['andOrNot' . $cpt]))
			{
				$queryScience = $queryScience . '|OR';
			}

			if (isset($selected[$data['scienceType' . $cpt]]))
			{
				$openParenthesis = '';
				$closeParenthesis = '';
				if (!empty($data['openParenthesis' . $cpt]) && $data['openParenthesis' . $cpt] === 'active')
				{
					$openParenthesis = $separator . '(';
					$separator = '|';
					$open ++;
				}

				if (!empty($data['closeParenthesis' . $cpt]) && $data['closeParenthesis' . $cpt] === 'active')
				{
					$closeParenthesis = '|)';
					$close ++;
				}

				$queryScience = $queryScience . $openParenthesis . $separator . $data['scienceType' . $cpt] . '|';

				$scienceQuery = $data['scienceQuery' . $cpt];
				$scienceQuery = urlencode($scienceQuery);
				$scienceQuery = preg_replace("/( |:|`|%22|%28|%29)/", '+', $scienceQuery);
				$queryScience = $queryScience . '#' . $scienceQuery . $closeParenthesis;
			}

			# Cleaning
			$data['andOrNot' . $cpt]         = '';
			$data['openParenthesis' . $cpt]  = '';
			$data['scienceType' . $cpt]      = '';
			$data['scienceQuery' . $cpt]     = '';
			$data['closeParenthesis' . $cpt] = '';

			$cpt ++;
			$nbscienceType = 'scienceType' . $cpt;
			$separator = '|';
		}

		if ($open != $close)
		{
			$ERROR_SCIENCEQUERY = 'Invalid query : parenthesis does not match';
		}

		if (empty($ERROR_SCIENCEQUERY) && !empty($queryScience))
		{
			$userId = ',' . $_SESSION['id'] . ',';
			$ERROR_SCIENCEQUERY = '';

			// Check if science query is already in bdd
			$checkCol = array(array('query', '=', mb_strtolower($queryScience), 'AND'),
												array('pack_id', '=', $data['packId'], 'AND'),
												array('source', '=', 'Science', ''));
			$queryExist = read('watch_pack_queries_serge', '', $checkCol, '', $bdd);

			if (!$queryExist)
			{
				$active = 1;
				// Adding new query
				$insertCol = array(array('pack_id',  $data['packId']),
													array('query', $queryScience),
													array('source', 'Science'));
				$execution = insert('watch_pack_queries_serge', $insertCol, '', '', $bdd);
			}
			else
			{
				$ERROR_SCIENCEQUERY = 'Query already exist';
			}
		}
	}
	#Delete science query
	elseif ($emailIsCheck && !empty($data['delQueryScience']))
	{
		// Read owner science query

		$checkCol = array(array('id', '=', $data['delQueryScience'], 'AND'),
											array('pack_id', '=', $data['packId'], 'AND'),
											array('source', '=', 'Science', 'OR'),
											array('id', '=', $data['delQueryScience'], 'AND'),
											array('pack_id', '=', $data['packId'], 'AND'),
											array('source', '=', '!Science', ''));
		$queryExist = read('watch_pack_queries_serge', '', $checkCol, '', $bdd);

		if ($queryExist)
		{

			$updateCol = array(array('source', 'Delete'));
			$checkCol = array(array('id', '=', $data['delQueryScience'], ''));
			$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
		}
	}
	#Disable science query
	elseif ($emailIsCheck && !empty($data['disableQueryScience']))
	{
		// Read owner science query

		$checkCol = array(array('id', '=', $data['disableQueryScience'], 'AND'),
											array('pack_id', '=', $data['packId'], 'AND'),
											array('source', '=', 'Science', ''));
		$result = read('watch_pack_queries_serge', 'id', $checkCol, '', $bdd);
		$result = $result[0] ?? '';

		if (!empty($result))
		{
			$updateCol = array(array('source', '!Science'));
			$checkCol  = array(array('id', '=', $data['disableQueryScience'], ''));
			$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
		}
	}
	#Activate science query
	elseif ($emailIsCheck && !empty($data['activateQueryScience']))
	{
		// Read owner science query
		$checkCol = array(array('id', '=', $data['activateQueryScience'], 'AND'),
											array('pack_id', '=', $data['packId'], 'AND'),
											array('source', '=', '!Science', ''));
		$result = read('watch_pack_queries_serge', 'id', $checkCol, '', $bdd);
		$result = $result[0] ?? '';

		if (!empty($result))
		{
			$updateCol = array(array('source', 'Science'));
			$checkCol = array(array('id', '=', $data['activateQueryScience'], ''));
			$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
		}
	}
	elseif ($emailIsCheck && !empty($data['patentQuerySubmit']))
	{
		$cpt                        = 0;
		$andOrPatent                = '';
		$queryPatent                = '';
		$_SESSION['cptPatentQuery'] = 3;

		while(!empty($data['patentType' . $cpt]) && !empty($data['patentQuery' . $cpt]))
		{
			if (!preg_match("/^[A-Z_]+$/", $data['patentType' . $cpt]))
			{
				$data['patentType' . $cpt] = 'ALLNAMES';
			}

			$patentQueryInput = urlencode(preg_replace("/(:| $)/", '', $data['patentQuery' . $cpt]));

			$queryPatent = $queryPatent . $andOrPatent . $data['patentType' . $cpt] . '%3A' . $patentQueryInput . '+';

			# Cleaning
			$data['patentType' . $cpt ] = '';
			$data['patentQuery' . $cpt ] = '';
			$data['andOrPatent' . $cpt ] = '';

			$cpt++;

			if(empty($data['andOrPatent' . $cpt]))
			{
				$andOrPatent = 'AND+';
			}
			else
			{
				$andOrPatent = 'OR+';
			}
		}

		if (!empty($queryPatent))
		{
			$userId = ',' . $_SESSION['id'] . ',';
			$ERROR_SCIENCEQUERY = '';

			// Check if science query is already in bdd

			$checkCol = array(array('query', '=', mb_strtolower($queryPatent), 'AND'),
												array('pack_id', '=', $data['packId'], 'AND'),
												array('source', '=', 'Patent', ''));
			$scienceQueryExist = read('watch_pack_queries_serge', '', $checkCol, '', $bdd);

			if (!$scienceQueryExist)
			{
				$insertCol = array(array('pack_id', $data['packId']),
													array('query', $queryPatent),
													array('source', 'Patent'));
				$execution = insert('watch_pack_queries_serge', $insertCol, '', '', $bdd);
			}
			else
			{
					$ERROR_PATENTQUERY = 'Query already exist';
			}
		}
	}
	#Delete patent query
	elseif ($emailIsCheck && !empty($data['delQueryPatent']))
	{
		// Read owner patent query
		$checkCol = array(array('id', '=', $data['delQueryPatent'], 'AND'),
											array('pack_id', '=', $data['packId'], 'AND'),
											array('source', '=', 'Patent', 'OR'),
											array('id', '=', $data['delQueryPatent'], 'AND'),
											array('pack_id', '=', $data['packId'], 'AND'),
											array('source', '=', '!Patent', ''));
		$result = read('watch_pack_queries_serge', 'id', $checkCol, '', $bdd);
		$result = $result[0] ?? '';

		if (!empty($result))
		{
			$updateCol = array(array('source', 'Delete'));
			$checkCol = array(array('id', '=', $data['delQueryPatent'], ''));
			$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
		}
	}
	#Disable patent query
	elseif ($emailIsCheck && !empty($data['disableQueryPatent']))
	{
		// Read owner patent query

		$checkCol = array(array('id', '=', $data['disableQueryPatent'], 'AND'),
											array('pack_id', '=', $data['packId'], 'AND'),
											array('source', '=', 'Patent', ''));
		$queryExist = read('watch_pack_queries_serge', '', $checkCol, '', $bdd);

		if ($queryExist)
		{

			$updateCol = array(array('source', '!Patent'));
			$checkCol = array(array('id', '=', $data['disableQueryPatent'], ''));
			$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
		}
	}
	#Activate patent query
	elseif ($emailIsCheck && !empty($data['activateQueryPatent']))
	{
		// Read owner patent query

		$checkCol = array(array('id', '=', $data['activateQueryPatent'], 'AND'),
											array('pack_id', '=', $data['packId'], 'AND'),
											array('source', '=', '!Patent', ''));
		$queryExist = read('watch_pack_queries_serge', '', $checkCol, '', $bdd);

		if ($queryExist)
		{

				$updateCol = array(array('source', 'Patent'));
				$checkCol  = array(array('id', '=', $data['activateQueryPatent'], ''));
				$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
		}
	}
	# Extend science query
	elseif (!empty($data['extendScience']))
	{
		$_SESSION['cptScienceQuery'] += 3;
		if (!empty($data['delEditingScienceQuery']))
		{
			$delEditingScienceQuery = $data['delEditingScienceQuery'];
		}
	}
	# Extend patent query
	elseif (!empty($data['extendPatent']))
	{
		$_SESSION['cptPatentQuery'] += 3;
		if (!empty($data['delEditingPatentQuery']))
		{
			$delEditingPatentQuery = $data['delEditingPatentQuery'];
		}
	}
	elseif ($emailIsCheck && !empty($data['addNewPack']) && isset($data['watchPackList']) && $data['watchPackList'] == 0 && !empty($data['watchPackName']) && !empty($data['watchPackDescription']))
	{
		$newWatchPackName = $data['watchPackName'];
		$language = strtoupper($data['language']);
		if ($data['watchPackCategory'] === 'NewCategory')
		{
			$category = $data['watchPackNewCategory'];
		}
		else
		{
			$category = $data['watchPackCategory'];
		}

		// Check if the name already exist
		$checkCol  = array(array('name', '=', mb_strtolower($newWatchPackName), ''));
		$nameExist = read('watch_pack_serge', '', $checkCol, '', $bdd);

		// Add new pack in database
		if (!$nameExist)
		{
			$insertCol = array(array('name', mb_strtolower($newWatchPackName)),
												array('description', $data['watchPackDescription']),
												array('author', $_SESSION['pseudo']),
												array('users', ','),
												array('category', $category),
												array('language', $language),
												array('update_date', $_SERVER['REQUEST_TIME']),
												array('rating', ','));
			$execution = insert('watch_pack_serge', $insertCol, '', '', $bdd);


			$checkCol = array(array('name', '=', mb_strtolower($newWatchPackName), ''));
			$result   = read('watch_pack_serge', 'id', $checkCol, '', $bdd);
			$result   = $result[0];

			// Creation of list of available sources
			$checkCol = array(array('owners', 'l', '%,' . $_SESSION['id'] . ',%', 'OR'),
												array('owners', 'l', '%,!' . $_SESSION['id'] . ',%', ''));
			$listAllSources = read('rss_serge', 'id', $checkCol, 'ORDER BY id', $bdd);

			$sources = ',';
			foreach ($listAllSources as $allSources)
			{
				$sources = $sources . $allSources['id'] . ',';
			}


			$insertCol = array(array('pack_id', $result['id']),
												array('query', '[!source!]'),
												array('source', $sources));
			$execution = insert('watch_pack_queries_serge', $insertCol, '', '', $bdd);

			header('Location: watchPack?type=create&packId=' . $result['id']);
			die();
		}
		else
		{
			$ERRORMESSAGENEWPACKNAME = 'A watch pack with this name already exist, please change the name';
		}
	}
	elseif ($emailIsCheck && !empty($data['addNewPack']) && isset($data['watchPackList']) && $data['watchPackList'] == 0 && (empty($data['watchPackName']) || empty($data['watchPackDescription'])))
	{
		$ERRORMESSAGEEMPTYNAMEORDESC = 'You have to enter a name and a description for your watch pack';
	}
	elseif (!empty($data['watchPackList']))
	{

		$checkCol = array(array('author', '=', $_SESSION['pseudo'], 'AND'),
											array('id', '=', $data['watchPackList'], ''));
		$result   = read('watch_pack_serge', 'id', $checkCol, '', $bdd);
		$result   = $result[0] ?? '';

		if (!empty($result['id']))
		{
			header('Location: watchPack?type=create&packId=' . $result['id']);
			die();
		}
		header('Location: watchPack?type=create');
		die();
	}

	# TODO Faire une fonction qui va relir toute les sources et les mots clefs
	if (!empty($data['packId']))
	{

		$checkCol    = array(array('author', '=', $_SESSION['pseudo'], 'AND'),
												array('id', '=', $data['packId'], ''));
		$result      = read('watch_pack_serge', 'name, description, category, language', $checkCol, '', $bdd);
		$packDetails = $result[0] ?? '';

		if (empty($packDetails))
		{
			header('Location: watchPack?type=create');
			die();
		}

			$checkCol = array(array('pack_id', '=', $data['packId'], 'AND'),
												array('query', '=', '[!source!]', ''));
			$reqReadPackSourcestmp = read('watch_pack_queries_serge', 'source', $checkCol, '', $bdd);

			$packSource = array('0');
			foreach ($reqReadPackSourcestmp as $readPackSources)
			{
				if (preg_match("/^[,!0-9,]+$/", $readPackSources['source']))
				{
					$readPackSources['source'] = preg_replace("/!/", '', $readPackSources['source']);
					$packSource = array_merge(preg_split('/,/', $readPackSources['source'], -1, PREG_SPLIT_NO_EMPTY), $packSource);
				}
			}

			$checkCol       = array(array('id', 'IN', $packSource, ''));
			$listAllSources = read('rss_serge', 'id, link, name, owners, active', $checkCol, 'ORDER BY name', $bdd);

			$checkCol              = array(array('pack_id', '=', $data['packId'], 'AND'),
			array('query', '<>', '[!source!]', ''));
			$reqReadPackSourcestmp = read('watch_pack_queries_serge', 'source', $checkCol, '', $bdd);

			$packSourceUsed = array('0');
			foreach ($reqReadPackSourcestmp as $readPackSources)
			{
				if (preg_match("/^[,!0-9,]+$/", $readPackSources['source']))
				{
					$readPackSources['source'] = preg_replace("/!/", '', $readPackSources['source']);
					$packSourceUsed = array_merge(preg_split('/,/', $readPackSources['source'], -1, PREG_SPLIT_NO_EMPTY), $packSourceUsed);
				}
			}

			$checkCol = array(array('id', 'IN', $packSourceUsed, ''));
			$readPackSources = read('rss_serge', 'id, link, name, owners, active', $checkCol, 'ORDER BY name', $bdd);
	}
}
include('view/nav/nav.php');

include('view/body/watchPack.php');

include('view/footer/footer.php');

?>
