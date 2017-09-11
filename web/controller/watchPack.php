<?php

include('controller/accessLimitedToSignInPeople.php');
include('model/get_text.php');
include('model/read.php');
include('model/update.php');
include('controller/generateNonce.php');

// Define variables
$actualLetter = '';
$style = '';
$orderByKeyword = '';
$orderBySource  = '';
$orderByType    = '';


# Data processing
$unsafeData = array();
$unsafeData = array_merge($unsafeData, array(array('action', 'action', 'GET', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('query', 'query', 'GET', '09')));
$unsafeData = array_merge($unsafeData, array(array('search', 'search', 'GET', 'str')));
$unsafeData = array_merge($unsafeData, array(array('type', 'type', 'GET', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('orderBy', 'orderBy', 'GET', 'str')));
$unsafeData = array_merge($unsafeData, array(array('language', 'language', 'GET', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('packId', 'packId', 'GET', '09')));

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
$unsafeData = array_merge($unsafeData, array(array('patentQuerySubmit', 'scienceQuerySubmit', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('delQueryPatent', 'delQueryScience', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('disableQueryPatent', 'disableQueryScience', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('activateQueryPatent', 'activateQueryScience', 'POST', '09')));

foreach($_POST as $key => $val)
{
		$key = htmlspecialchars($key);
		if (preg_match("/radio-s./", $key, $name))
		{
			$unsafeData = array_merge($unsafeData, array(array($name[0], $name[0], 'POST', 'str')));
		}
		elseif (preg_match("/radio-ks[0-9]+/", $key, $name))
		{
			$unsafeData = array_merge($unsafeData, array(array($name[0], $name[0], 'POST', 'str')));
		}
		elseif(preg_match("/andOrAndnot[0-9]+/", $key, $name))
		{
			$unsafeData = array_merge($unsafeData, array(array($name[0], $name[0], 'POST', 'str')));
		}
		elseif(preg_match("/openParenthesis[0-9]+/", $key, $name))
		{
			$unsafeData = array_merge($unsafeData, array(array($name[0], $name[0], 'POST', 'str')));
		}
		elseif(preg_match("/closeParenthesis[0-9]+/", $key, $name))
		{
			$unsafeData = array_merge($unsafeData, array(array($name[0], $name[0], 'POST', 'str')));
		}
		elseif(preg_match("/scienceType[0-9]+/", $key, $name))
		{
			$unsafeData = array_merge($unsafeData, array(array($name[0], $name[0], 'POST', 'str')));
		}
		elseif(preg_match("/scienceQuery[0-9]+/", $key, $name))
		{
			$unsafeData = array_merge($unsafeData, array(array($name[0], $name[0], 'POST', 'str')));
		}
		elseif(preg_match("/andOrPatent[0-9]+/", $key, $name))
		{
			$unsafeData = array_merge($unsafeData, array(array($name[0], $name[0], 'POST', 'str')));
		}
		elseif(preg_match("/patentType[0-9]+/", $key, $name))
		{
			$unsafeData = array_merge($unsafeData, array(array($name[0], $name[0], 'POST', 'str')));
		}
		elseif(preg_match("/patentQuery[0-9]+/", $key, $name))
		{
			$unsafeData = array_merge($unsafeData, array(array($name[0], $name[0], 'POST', 'str')));
		}
}

include('controller/dataProcessing.php');

# Nonce
$nonceTime = $_SERVER['REQUEST_TIME'];
$nonce = getNonce($nonceTime);


if (empty($_SESSION['cptScienceQuery']))
{
	$_SESSION['cptScienceQuery'] = 3;
}

if (empty($_SESSION['cptPatentQuery']))
{
	$_SESSION['cptPatentQuery'] = 3;
}

# Scroll position
if (!empty($data['scrollPos']))
{
	$_SESSION['scrollPos'] = $data['scrollPos'];
}
elseif (!empty($_SESSION['scrollPos']))
{
	$_SESSION['scrollPos'] = 0;
}

# Save folding state
if (!empty($data['sourceType']))
{
	foreach($_SESSION as $key => $val)
	{
		if (preg_match("/radio-s./", $key))
		{
			$_SESSION[$key] = '';
		}
		elseif (preg_match("/radio-ks[0-9]+/", $key))
		{
			$_SESSION[$key] = '';
		}
	}
	foreach($data as $key => $val)
	{
		$key = htmlspecialchars($key);
		if (preg_match("/radio-s./", $key))
		{
			$_SESSION[$key] = $val;
		}
		elseif (preg_match("/radio-ks[0-9]+/", $key))
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
if (!empty($data['type']))
{
	$type = $data['type'];

	if ($type === 'add')
	{
		$addActive     = 'class="active"';
		$tableName      = 'result_news_serge';
		$tableNameQuery = 'keyword_news_serge';
		$tableNameSource = 'rss_serge';
		$ownersColumn   = 'applicable_owners_sources';
		$userId        = '|' . $_SESSION['id'] . ':';
		$keywordQueryId = 'keyword_id';
		$queryColumn    = 'keyword';
		$specialColumn  = ', id_source, keyword_id ';
		$displayColumn  = 'Keyword';
		$_SESSION['type'] = 'add';
		$limit = 15;
	}
	elseif ($type === 'create')
	{
		$createActive = 'class="active"';
		$tableName      = 'result_science_serge';
		$tableNameQuery = 'queries_science_serge';
		$tableNameSource = 'science_sources_serge';
		$ownersColumn   = 'owners';
		$userId        = ',' . $_SESSION['id'] . ',';
		$keywordQueryId = 'query_id';
		$queryColumn    = 'query_arxiv';
		$specialColumn  = ',query_id, id_source ';
		$displayColumn  = 'Query';
		$_SESSION['type'] = 'create';
	}
	else
	{
		$type           = 'add';
		$addActive = 'class="active"';
		$tableName      = 'result_science_serge';
		$tableNameQuery = 'queries_science_serge';
		$tableNameSource = 'science_sources_serge';
		$ownersColumn   = 'owners';
		$userId        = ',' . $_SESSION['id'] . ',';
		$keywordQueryId = 'query_id';
		$queryColumn    = 'query_arxiv';
		$specialColumn  = ',query_id, id_source ';
		$displayColumn  = 'Query';
		$_SESSION['type'] = 'add';
		$limit = 15;
	}
}
else
{
	$type           = 'add';
	$addActive = 'class="active"';
	$tableName      = 'result_science_serge';
	$tableNameQuery = 'queries_science_serge';
	$tableNameSource = 'science_sources_serge';
	$ownersColumn   = 'owners';
	$userId        = ',' . $_SESSION['id'] . ',';
	$keywordQueryId = 'query_id';
	$queryColumn    = 'query_arxiv';
	$specialColumn  = ',query_id, id_source ';
	$displayColumn  = 'Query';
	$_SESSION['type'] = 'add';
	$limit = 15;
}

if ($type === 'add')
{
	$checkCol = array();
	$languageBDD = read('language_serge', 'code, name', $checkCol, '', $bdd);

	$colOrder['language'] = '<select name="language" onchange="this.form.submit();">';
	$colOrder['language'] = $colOrder['language'] . PHP_EOL . '<option value="all" selected>All languages</option>';

	$languageGET = preg_replace("/[^a-z]/", '', $data['language']);

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

	# Add a star
	if (!empty($data['AddStar']))
	{
		/*$req = $bdd->prepare('SELECT rating FROM watch_pack_serge WHERE id = :id');
		$req->execute(array(
			'id' => $packId));
			$usersStars = $req->fetch();
			$req->closeCursor();*/

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

		/*$req = $bdd->prepare('UPDATE watch_pack_serge SET rating = :usersStars WHERE id = :id');
		$req->execute(array(
			'usersStars' => $usersStars,
			'id' => $packId));
			$req->closeCursor();*/

		$updateCol = array(array('rating', $usersStars));
		$checkCol = array(array('id', '=', $data['AddStar'], ''));
		$execution = update('watch_pack_serge', $updateCol, $checkCol, '', $bdd);

		header('Location: watchPack');
		die();
	}

	# Order results
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
		else
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
	elseif (empty($data['search']))
	{
		$colOrder['rate'] = ' ▴';
		$colOrder['DESC'] = '';

		# WARNING sensitive variable [SQLI]
		$ORDERBY = 'ORDER BY `NumberOfStars` DESC';
	}

	# Search engine

	# Read watchPack
	/*$req = $bdd->prepare("SELECT id, name, description, author, users, category, language, update_date, rating, ((LENGTH(`rating`) - LENGTH(REPLACE(`rating`, ',', '')))-1) AS `NumberOfStars` FROM `watch_pack_serge` WHERE $OPTIONALCOND $ORDERBY;");
	$req->execute();
		$watchPacks = $req->fetchAll();
		$req->closeCursor();*/

	$watchPacks = read('watch_pack_serge', 'id, name, description, author, users, category, language, update_date, rating, ((LENGTH(`rating`) - LENGTH(REPLACE(`rating`, \',\', \'\')))-1) AS `NumberOfStars`', $checkCol, $ORDERBY, $bdd);
}
else
{
	if (!empty($data['packId']))
	{

		/*$req = $bdd->prepare('SELECT name, description, category, language FROM watch_pack_serge WHERE author = :pseudo AND id = :pack_idInUse');
		$req->execute(array(
			'pseudo' => $_SESSION['pseudo'],
			'pack_idInUse' => $data['packId']));
			$packDetails = $req->fetch();
			$req->closeCursor();*/

		$checkCol = array(array('author', '=', $_SESSION['pseudo'], 'AND'),
											array('id', '=', $data['packId'], ''));
		$result = read('watch_pack_serge', 'name, description, category, language', $checkCol, '', $bdd);
		$packDetails = $result[0];

		if (empty($packDetails))
		{
			header('Location: watchPack?type=create');
			die();
		}

		/*$reqReadPackSources = $bdd->prepare('SELECT source FROM watch_pack_queries_serge WHERE pack_id = :pack_id AND query = "[!source!]"');
		$reqReadPackSources->execute(array(
			'pack_id' => $data['packId']));
			$reqReadPackSourcestmp = $reqReadPackSources->fetchAll();
			$reqReadPackSources->closeCursor();*/

		$checkCol = array(array('pack_id', '=', $data['packId'], 'AND'),
											array('query', '=', '[!source!]', ''));
		$resultPackSources = read('watch_pack_queries_serge', 'source', $checkCol, '', $bdd);

			$packSource = array();
			foreach ($resultPackSources as $resultSources)
			{
				if (preg_match("/^[,!0-9,]+$/", $resultSources['source']))
				{
					$resultSources['source'] = preg_replace("/!/", '', $resultSources['source']);
					$packSource = array_merge(preg_split('/,/', $resultSources['source'], -1, PREG_SPLIT_NO_EMPTY), $packSource);
				}
			}

			$sourcesIds = implode(',', $packSource);

			/*$req = $bdd->prepare("SELECT id, link, name, owners, active FROM rss_serge WHERE id IN ($sourcesIds) ORDER BY name");
			$req->execute(array(
				'user' => $userId,
				'userDesactivated' => $userIdDesactivated));
				$listAllSources = $req->fetchAll();
				$req->closeCursor();*/

			$checkCol = array(array('id', 'IN', implode(',', $packSource), ''));
			$listAllSources = read('rss_serge', 'id, link, name, owners, active', $checkCol, 'ORDER BY name', $bdd);

			/*$reqReadPackSources = $bdd->prepare('SELECT source FROM watch_pack_queries_serge WHERE pack_id = :pack_id AND query <> "[!source!]"');
			$reqReadPackSources->execute(array(
				'pack_id' => $data['packId']));
				$reqReadPackSourcestmp = $reqReadPackSources->fetchAll();
				$reqReadPackSources->closeCursor();*/

			$checkCol = array(array('pack_id', '=', $data['packId'], 'AND'),
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

			/*$sourcesIdsUsed = implode(',', $packSourceUsed);

			$req = $bdd->prepare("SELECT id, link, name, owners, active FROM rss_serge WHERE id IN ($sourcesIdsUsed) ORDER BY name");
			$req->execute(array());
				$readPackSources = $req->fetchAll();
				$req->closeCursor();*/

			$checkCol = array(array('id', 'IN', implode(',', $packSourceUsed), ''));
			$readPackSources = read('rss_serge', 'id, link, name, owners, active', $checkCol, 'ORDER BY name', $bdd);
	}
	else
	{
		/*$req = $bdd->prepare('SELECT language FROM users_table_serge WHERE id = :userId');
		$req->execute(array(
			'userId' => $_SESSION['id']));
			$packDetails = $req->fetch();
			$req->closeCursor();*/

		$checkCol    = array(array('id', '=', $_SESSION['id'], ''));
		$packDetails = read('users_table_serge', 'language', $checkCol, '', $bdd);
		$packDetails = $packDetails[0];
	}

	$checkCol    = array();
	$languageBDD = read('language_serge', 'code, name', $checkCol, '', $bdd);

	$userLang = strtolower($packDetails['language']);

	$selectLanguage = '<select class="shortSelect" name="language">' . PHP_EOL;

	foreach ($languageBDD as $languageLine)
	{
		if ($userLang === $languageLine['code'])
		{
			$selectLanguage = $selectLanguage . PHP_EOL . '<option value="' . $languageLine['code'] . '" selected>' . $languageLine['code'] . ' &nbsp;&nbsp;' . $languageLine['name'] . '</option>';
			$selectedLanguageCode = $code;
		}
		else
		{
			$selectLanguage = $selectLanguage . PHP_EOL . '<option value="' . $languageLine['code'] . '">' . $languageLine['code'] . ' &nbsp;&nbsp;' . $languageLine['name'] . '</option>';
		}
	}

	$selectLanguage = $selectLanguage . PHP_EOL . '</select>';

	// Edit a pack
	if (!empty($data['watchPackList']) AND !empty($data['addNewPack']) AND !empty($data['watchPackName']) AND !empty($data['watchPackDescription']))
	{
		// Check if watch pack is own by the user
		/*$req = $bdd->prepare('SELECT id FROM watch_pack_serge WHERE author = :username AND id = :packIdEdit');
		$req->execute(array(
			'username' => $_SESSION['pseudo'],
			'packIdEdit' => $packIdEdit[0]));
			$result = $req->fetch();
			$req->closeCursor();*/

		$checkCol  = array(array('author', '=', $_SESSION['pseudo'], 'AND'),
											array('id', '=', $data['watchPackList'], ''));
		$packIsOwn = read('watch_pack_serge', '', $checkCol, '', $bdd);

		$checkCol  = array(array('name', '=', $data['watchPackName'], 'AND'),
											array('id', '<>', $data['watchPackList'], ''));
		$nameExist = read('watch_pack_serge', '', $checkCol, '', $bdd);

		if (!$packIsOwn AND !$nameExist)
		{
			$updateCol = array(array('names', $data['watchPackName']),
												array('description', $data['watchPackDescription']),
												array('category', $data['watchPackCategory']),
												array('language', $data['language']),
												array('update_date', $_SERVER['REQUEST_TIME']));
			$checkCol = array(array('id', '=', $data['watchPackList'], ''));
			$execution = update('watch_pack_serge', $updateCol, $checkCol, '', $bdd);

		}
	}
	elseif (!empty($data['addNewKeyword']) AND !empty($data['sourceKeyword']) AND !empty($data['newKeyword']))
	{
		$newKeywordArray = preg_split('/,/', $data['newKeyword'], -1, PREG_SPLIT_NO_EMPTY);

		if ($data['sourceKeyword'] === '00')
		{
			# Add keyword on all sources
			foreach ($listAllSources as $sourcesList)
			{
				foreach ($newKeywordArray as $newKeyword)
				{
					/*$req = $bdd->prepare('SELECT id, source FROM watch_pack_queries_serge WHERE lower(query) = lower(:keyword) AND pack_id = :pack_id AND source <> "Science" AND source <> "Patent"');
					$req->execute(array(
						'keyword' => $newKeyword,
						'pack_id' => $data['packId']));
						$resultKeyword = $req->fetch();
						$req->closeCursor();*/

					$checkCol = array(array('LOWER(query)', '=', mb_strtolower($newKeyword), 'AND'),
														array('pack_id', '=', $data['packId'], 'AND'),
														array('source', '<>', 'Science', 'AND'),
														array('source', '<>', 'Patent', ''));
					$result = read('watch_pack_queries_serge', 'id, source', $checkCol, '', $bdd);
					$resultKeyword = $result[0];

					if (empty($resultKeyword))
					{
						/*$req = $bdd->prepare('INSERT INTO watch_pack_queries_serge (pack_id, query, source) VALUES (:pack_id, :query, :source)');
						$req->execute(array(
							'pack_id' => $data['packId'],
							'query' =>  $newKeyword,
							'source' => ',' . $sourcesList['id'] . ','));
							$req->closeCursor();*/

						$insertCol = array(array('pack_id', $data['packId']),
															array('query',  $newKeyword),
															array('source', ',' . $sourcesList['id'] . ','));
						$execution = insert('watch_pack_queries_serge', $insertCol, '', '', $bdd);
					}
					else
					{ # TODO Vérif qu'on ajoute pas deux fois les sources
						/*$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = :source WHERE id = :keywordId');
						$req->execute(array(
							'source' => $resultKeyword['source'] . $sourcesList['id'] . ',',
							'keywordId' => $resultKeyword['id']));
							$req->closeCursor();*/

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
				/*$req = $bdd->prepare('SELECT id FROM rss_serge WHERE id = :sourceId');
				$req->execute(array(
					'sourceId' => $sourceId[0]));
					$resultSource = $req->fetch();
					$req->closeCursor();*/

				$checkCol = array(array('id', '=', $data['sourceKeyword'], ''));
				$result = read('rss_serge', 'id', $checkCol, '', $bdd);
				$resultSource = $result[0];

				if (!empty($resultSource))
				{
					/*$req = $bdd->prepare('SELECT id, source FROM watch_pack_queries_serge WHERE lower(query) = lower(:keyword) AND pack_id = :pack_id AND source <> "Science" AND source <> "Patent"');
					$req->execute(array(
						'keyword' => $newKeyword,
						'pack_id' => $data['packId']));
						$resultKeyword = $req->fetch();
						$req->closeCursor();*/

					$checkCol = array(array('LOWER(query)', '=', mb_strtolower($newKeyword), 'AND'),
														array('pack_id', '=', $data['packId'], 'AND'),
														array('source', '<>', 'Science', 'AND'),
														array('source', '<>', 'Patent', ''));
					$result = read('watch_pack_queries_serge', 'id, source', $checkCol, '', $bdd);
					$resultKeyword = $result[0];

					$newKeywordSource = ',' . $data['sourceKeyword'] . ',';

					if (empty($resultKeyword))
					{
						/*$req = $bdd->prepare('INSERT INTO watch_pack_queries_serge (pack_id, query, source) VALUES (:pack_id, :query, :source)');
						$req->execute(array(
							'pack_id' => $data['packId'],
							'query' =>  $newKeyword,
							'source' => ',' . $sourceId[0] . ','));
							$req->closeCursor();*/

						$insertCol = array(array('pack_id', $data['packId']),
															array('query', $newKeyword),
															array('source', ',' . $data['sourceKeyword'] . ','));
						$execution = insert('watch_pack_queries_serge', $insertCol, '', '', $bdd);
					}
					elseif (!preg_match("/$newKeywordSource/", $resultKeyword['source']))
					{
						/*$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = :source WHERE id = :keywordId');
						$req->execute(array(
							'source' => $resultKeyword['source'] . $sourceId[0] . ',',
							'keywordId' => $resultKeyword['id']));
							$req->closeCursor();*/

						$updateCol = array(array('source', $resultKeyword['source'] . $data['sourceKeyword'] . ','));
						$checkCol = array(array('id', '=', $resultKeyword['id'], ''));
						$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
					}
				}
			}
		}
	}
	elseif (!empty($data['addNewSource']) AND !empty($data['newSource']))
	{
		$checkCol = array(array('link', '=', $newSource, ''));
		$result = read('rss_serge', 'id', $checkCol, '', $bdd);
		$resultSource = $result[0];

		/*$req = $bdd->prepare('SELECT source FROM watch_pack_queries_serge WHERE query = "[!source!]" AND pack_id = :packIdInUse');
		$req->execute(array(
			'packIdInUse' => $data['packId']));
			$sources = $req->fetch();
			$req->closeCursor();*/

		$checkCol = array(array('query', '=', '[!source!]', 'AND'),
											array('pack_id', '=', $data['packId'], ''));
		$result = read('watch_pack_queries_serge', 'source', $checkCol, '', $bdd);
		$sources = $result[0];

		$newSourceId = ',' . $resultSource['id'] . ',';

		if (!empty($resultSource) AND !preg_match("/$newSourceId/", $sources['source']))
		{

			/*$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = :source WHERE pack_id = :packIdInUse AND query = "[!source!]"');
			$req->execute(array(
				'source' => $sources['source'] . $resultSource['id'] . ',',
				'packIdInUse' => $data['packId']));
				$req->closeCursor();*/

			$updateCol = array(array('source', $sources['source'] . $resultSource['id'] . ','));
			$checkCol = array(array('pack_id', '=', $data['packId'], 'AND'),
												array('query', '=', '[!source!]', ''));
			$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
		}
		elseif (!preg_match("/$newSourceId/", $sources['source']))
		{
			// Check if source is valid
			$sourceToTest = escapeshellarg($newSource);
			$cmd          = '/usr/bin/python /var/www/Serge/checkfeed.py ' . $sourceToTest;

			# Check if the link is valid
			exec($cmd, $linkValidation, $errorInCheckfeed);

			if ($linkValidation[0] === 'valid link' AND $errorInCheckfeed === 0)
			{
				// Adding new source
				preg_match('@^(?:http.*://[www.]*)?([^/]+)@i', $newSource, $matches);
				/*$owners = ',' . $_SESSION['id'] . ',';
				$active = 1;
				$name = ucfirst($matches[1] . '[!NEW!]');
				$req = $bdd->prepare('INSERT INTO rss_serge (link, owners, name, active) VALUES
				(:link, :owners, :name, :active)');
				$req->execute(array(
					'link' => $newSource,
					'owners' => $owners,
					'name' => $name,
					'active' => $active));
					$req->closeCursor();*/

				$insertCol = array(array('link', $newSource),
													array('owners', ',' . $_SESSION['id'] . ','),
													array('name', ucfirst($matches[1] . '[!NEW!]')),
													array('active', 1));
				$execution = insert('rss_serge', $insertCol, '', '', $bdd);

				/*$req = $bdd->prepare('SELECT id FROM rss_serge WHERE link = :newSource');
				$req->execute(array(
					'newSource' => $newSource));
					$resultSource = $req->fetch();
					$req->closeCursor();*/

				$checkCol = array(array('link', '=', $newSource, ''));
				$result = read('rss_serge', 'id', $checkCol, '', $bdd);
				$resultSource = $result[0];

					/*$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = :source WHERE pack_id = :packIdInUse AND query = "[!source!]"');
					$req->execute(array(
						'source' => $sources['source'] . $resultSource['id'] . ',',
						'packIdInUse' => $data['packId']));
						$req->closeCursor();*/

				$updateCol = array(array('source', $sources['source'] . $resultSource['id'] . ','));
				$checkCol = array(array('pack_id', '=', $data['packId'], 'AND'),
													array('query', '=', '[!source!]', ''));
				$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
			}
			else
			{
				$ERROR_MESSAGE = 'Your link ' . 'return ' . $linkValidation[0] . ',' . $linkValidation[1] . ', please correct your link';
			}
		}
	}
	elseif (!empty($data['delKeyword']) OR !empty($data['disableKeyword']) OR !empty($data['activateKeyword']))
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

		if (isset($sourceIdAction) AND isset($keywordIdAction) AND isset($action))
		{
			# Check if keyword exist
			/*$req = $bdd->prepare('SELECT source FROM watch_pack_queries_serge WHERE id = :keywordIdAction AND (source LIKE :sourceIdAction OR source LIKE :sourceIdActionDesactivated) AND pack_id = :packIdInUse');
			$req->execute(array(
				'keywordIdAction' => $keywordIdAction,
				'sourceIdAction' => "%," . $sourceIdAction . ",%",
				'sourceIdActionDesactivated' => "%,!" . $sourceIdAction . ",%",
				'packIdInUse' => $data['packId']));
				$result = $req->fetch();
				$req->closeCursor();*/

			$checkCol = array(array('id', '=', $keywordIdAction, 'AND'),
												array('pack_id', '=', $data['packId'], 'AND'),
												array('source', 'l', '%,' . $sourceIdAction . ',%', 'OR'),
												array('id', '=', $keywordIdAction, 'AND'),
												array('source', 'l', '%,!' . $sourceIdAction . ',%', 'AND'),
												array('pack_id', '=', $data['packId'], ''));
			$result = read('watch_pack_queries_serge', 'source', $checkCol, '', $bdd);
			$result = $result[0];

			# Delete an existing keyword
			if (!empty($result) AND $action === 'delKeyword')
			{
				/*$sourceNew = preg_replace("/,!*$sourceIdAction,/", ',', $result['source']);
				$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = :sources WHERE id = :id');
				$req->execute(array(
					'sources' => $sourceNew,
					'id' => $keywordIdAction));
					$req->closeCursor();*/

				$updateCol = array(array('source', preg_replace("/,!*$sourceIdAction,/", ',', $result['source'])));
				$checkCol = array(array('id', '=', $keywordIdAction, ''));
				$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
			}
			elseif (!empty($result) AND $action === 'disableKeyword')
			{
				/*$sourceNew = preg_replace("/,$sourceIdAction,/", ",!$sourceIdAction,", $result['source']);
				$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = :sources WHERE id = :id');
				$req->execute(array(
					'sources' => $sourceNew,
					'id' => $keywordIdAction));
					$req->closeCursor();*/

				$updateCol = array(array('source', preg_replace("/,$sourceIdAction,/", ",!$sourceIdAction,", $result['source'])));
				$checkCol = array(array('id', '=', $keywordIdAction, ''));
				$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
			}
			elseif (!empty($result) AND $action === 'activateKeyword')
			{
				/*$sourceNew = preg_replace("/,!$sourceIdAction,/", ",$sourceIdAction,", $result['source']);
				$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = :sources WHERE id = :id');
				$req->execute(array(
					'sources' => $sourceNew,
					'id' => $keywordIdAction));
					$req->closeCursor();*/

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
	elseif (!empty($data['delSource']) OR !empty($data['disableSource']) OR !empty($data['activateSource']))
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

		if (isset($sourceIdAction) AND isset($action))
		{
			# Check if source exist for this owner
			/*$req = $bdd->prepare('SELECT id FROM watch_pack_queries_serge WHERE query = "[!source!]" AND (source LIKE :sourceIdAction OR source LIKE :sourceIdActionDesactivated) AND pack_id = :packIdInUse');
			$req->execute(array(
				'sourceIdAction' => "%," . $sourceIdAction . ",%",
				'sourceIdActionDesactivated' => "%,!" . $sourceIdAction . ",%",
				'packIdInUse' => $data['packId']));
				$result = $req->fetch();
				$req->closeCursor();*/

			$checkCol = array(array('query', '=', '[!source!]', 'AND'),
												array('pack_id', '=', $data['packId'], 'AND'),
												array('source', 'l', '%,' . $sourceIdAction . ',%', 'OR'),
												array('id', '=', $keywordIdAction, 'AND'),
												array('source', 'l', '%,!' . $sourceIdAction . ',%', 'AND'),
												array('pack_id', '=', $data['packId'], ''));
			$sourceExist = read('watch_pack_queries_serge', '', $checkCol, '', $bdd);

			# Delete an existing sources
			if ($sourceExist AND $action === 'delSource')
			{
				/*$req = $bdd->prepare('SELECT id FROM rss_serge WHERE owners LIKE :owner');
				$req->execute(array(
					'owner' => "%," . $_SESSION['id'] . ",%"));
					$result = $req->fetchAll();
					$req->closeCursor();*/

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
				/*$req = $bdd->prepare("SELECT id, source FROM watch_pack_queries_serge WHERE pack_id = :packIdInUse AND (source LIKE :sourceIdAction OR source LIKE :sourceIdActionDesactivated) AND query <> $isSourceOwned");
				$req->execute(array(
					'packIdInUse' => $data['packId'],
					'sourceIdAction' => "%," . $sourceIdAction . ",%",
					'sourceIdActionDesactivated' => "%,!" . $sourceIdAction . ",%"));
					$result = $req->fetchAll();
					$req->closeCursor();*/

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

					/*$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = :sources WHERE id = :id');
					$req->execute(array(
						'sources' => $sourceNew,
						'id' => $resultLine['id']));
						$req->closeCursor();*/

					$updateCol = array(array('source', preg_replace("/,!*$sourceIdAction,/", ',', $resultLine['source'])));
					$checkCol = array(array('id', '=', $resultLine['id'], ''));
					$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
				}
			}
			elseif ($sourceExist AND $action === 'disableSource')
			{
				// Disable source on all keywords
				/*$req = $bdd->prepare('SELECT id, source FROM watch_pack_queries_serge WHERE pack_id = :packIdInUse AND source LIKE :sourceIdAction');
				$req->execute(array(
					'packIdInUse' => $data['packId'],
					'sourceIdAction' => "%," . $sourceIdAction . ",%"));
					$result = $req->fetchAll();
					$req->closeCursor();*/

				$checkCol = array(array('pack_id', '=',$data['packId'], 'AND'),
													array('source', 'l', '%,' . $sourceIdAction . ',%', ''));
				$result = read('watch_pack_queries_serge', 'id, source', $checkCol, '', $bdd);

				foreach ($result as $resultLine)
				{
					/*$sourceNew = preg_replace("/,$sourceIdAction,/", ",!$sourceIdAction,", $resultLine['source']);
					$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = :sources WHERE id = :id');
					$req->execute(array(
						'sources' => $sourceNew,
						'id' => $resultLine['id']));
						$req->closeCursor();*/

					$updateCol = array(array('source', preg_replace("/,$sourceIdAction,/", ",!$sourceIdAction,", $resultLine['source'])));
					$checkCol = array(array('id', '=', $resultLine['id'], ''));
					$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
				}
			}
			elseif ($sourceExist AND $action === 'activateSource')
			{
				// Activate source on all keywords
				/*$req = $bdd->prepare('SELECT id, source FROM watch_pack_queries_serge WHERE pack_id = :packIdInUse AND source LIKE :sourceIdAction');
				$req->execute(array(
					'packIdInUse' => $data['packId'],
					'sourceIdAction' => "%,!" . $sourceIdAction . ",%"));
					$result = $req->fetchAll();
					$req->closeCursor();*/

				$checkCol = array(array('pack_id', '=',$data['packId'], 'AND'),
													array('source', 'l', '%,!' . $sourceIdAction . ',%', ''));
				$result = read('watch_pack_queries_serge', 'id, source', $checkCol, '', $bdd);

				foreach ($result as $resultLine)
				{
					/*$sourceNew = preg_replace("/,!$sourceIdAction,/", ",$sourceIdAction,", $resultLine['source']);
					$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = :sources WHERE id = :id');
					$req->execute(array(
						'sources' => $sourceNew,
						'id' => $resultLine['id']));
						$req->closeCursor();*/

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
	elseif (!empty($data['scienceQuerySubmit']))
	{
		$cpt = 0;
		$open = 0;
		$close = 0;
		$nbscienceType = 'scienceType0';
		$queryScience_Arxiv = '';
		$_SESSION['cptScienceQuery'] = 3;

		while(!empty($data[$nbscienceType]) AND !empty($data['scienceQuery' . $cpt]))
		{
			if (!empty($data['andOrAndnot' . $cpt])
					AND preg_match("/(^AND$|^OR$|^NOTAND$)/", $data['andOrAndnot' . $cpt]))
			{
				$queryScience_Arxiv = $queryScience_Arxiv . '+' . $data['andOrAndnot' . $cpt] . '+';
			}
			elseif (!empty($data['andOrAndnot' . $cpt])
							AND !preg_match("/(^AND$|^OR$|^NOTAND$)/", $data['andOrAndnot' . $cpt]))
			{
				$queryScience_Arxiv = $queryScience_Arxiv . '+OR+';
			}

			if (preg_match("/(^ti$|^au$|^abs$|^jr$|^cat$|^all$)/", $data['scienceType' . $cpt]))
			{
				$openParenthesis = '';
				$closeParenthesis = '';
				if ($data['openParenthesis' . $cpt] === 'active')
				{
					$openParenthesis = '%28';
					$open ++;
				}

				if ($data['closeParenthesis' . $cpt] === 'active')
				{
					$closeParenthesis = '%29';
					$close ++;
				}

				$queryScience_Arxiv = $queryScience_Arxiv . $openParenthesis . $data['scienceType' . $cpt] . ':';

				$scienceQuery = $data['scienceQuery' . $cpt];
				$scienceQuery = urlencode($scienceQuery);
				$scienceQuery = preg_replace("/( |:|`|%22|%28|%29)/", '+', $scienceQuery);
				$queryScience_Arxiv = $queryScience_Arxiv . '%22' . $scienceQuery . '%22' . $closeParenthesis;
			}

			# Cleaning
			$data['andOrAndnot' . $cpt] = '';
			$data['openParenthesis' . $cpt] = '';
			$data['scienceType' . $cpt] = '';
			$data['scienceQuery' . $cpt] = '';
			$data['closeParenthesis' . $cpt] = '';

			$cpt ++;
			$nbscienceType = 'scienceType' . $cpt;
		}

		if ($open != $close)
		{
			$ERROR_SCIENCEQUERY = 'Invalid query : parenthesis does not match';
		}

		if (empty($ERROR_SCIENCEQUERY) AND !empty($queryScience_Arxiv))
		{
			$userId = ',' . $_SESSION['id'] . ',';
			$ERROR_SCIENCEQUERY = '';

			// Check if science query is already in bdd
			/*$req = $bdd->prepare('SELECT id FROM watch_pack_queries_serge WHERE LOWER(query) = LOWER(:newQuery) AND pack_id = :packIdInUse AND source = "Science"');
			$req->execute(array(
				'newQuery' => $queryScience_Arxiv,
				'packIdInUse' => $data['packId']));
				$result = $req->fetch();
				$req->closeCursor();*/

			$checkCol = array(array('LOWER(query)', '=', mb_strtolower($queryScience_Arxiv), 'AND'),
												array('pack_id', '=', $data['packId'], 'AND'),
												array('source', '=', 'Science', ''));
			$queryExist = read('watch_pack_queries_serge', '', $checkCol, '', $bdd);

			if (!$queryExist)
			{
				$active = 1;
				// Adding new query
				/*$req = $bdd->prepare('INSERT INTO watch_pack_queries_serge (pack_id, query, source) VALUES (:packIdInUse, :query, :source)');
				$req->execute(array(
					'packIdInUse' => $data['packId'],
					'query' => $queryScience_Arxiv,
					'source' => "Science"));
					$req->closeCursor();*/

				$insertCol = array(array('pack_id',  $data['packId']),
													array('query', $queryScience_Arxiv),
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
	elseif (!empty($data['delQueryScience']))
	{
		// Read owner science query
		/*$req = $bdd->prepare('SELECT id FROM watch_pack_queries_serge WHERE id = :queryId AND pack_id = :packIdInUse AND (source = "Science" OR source = "!Science")');
		$req->execute(array(
			'queryId' => $idQueryToDel[0],
			'packIdInUse' => $data['packId']));
			$result = $req->fetch();
			$req->closeCursor();*/

		$checkCol = array(array('id', '=', $data['delQueryScience'], 'AND'),
											array('pack_id', '=', $data['packId'], 'AND'),
											array('source', '=', 'Science', 'OR'),
											array('id', '=', $data['delQueryScience'], 'AND'),
											array('pack_id', '=', $data['packId'], 'AND'),
											array('source', '=', '!Science', ''),);
		$queryExist = read('watch_pack_queries_serge', '', $checkCol, '', $bdd);

		if ($queryExist)
		{
			/*$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = 'Delete' WHERE id = :id');
			$req->execute(array(
				'id' => $idQueryToDel[0]));
				$req->closeCursor();*/

			$updateCol = array(array('source', 'Delete'));
			$checkCol = array(array('id', '=', $data['delQueryScience'], ''));
			$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
		}
	}
	#Disable science query
	elseif (!empty($data['disableQueryScience']))
	{
		// Read owner science query
		/*$req = $bdd->prepare('SELECT id FROM watch_pack_queries_serge WHERE id = :queryId AND pack_id = :packIdInUse AND source = "Science"');
		$req->execute(array(
			'queryId' => $idQueryToDisable[0],
			'packIdInUse' => $data['packId']));
			$result = $req->fetch();
			$req->closeCursor();*/

		$checkCol = array(array('id', '=', $data['disableQueryScience'], 'AND'),
											array('pack_id', '=', $data['packId'], 'AND'),
											array('source', '=', 'Science', ''));
		$result = read('watch_pack_queries_serge', 'id', $checkCol, '', $bdd);
		$result = $result[0];

		if (!empty($result))
		{
			/*$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = '!Science' WHERE id = :id');
			$req->execute(array(
				'id' => $idQueryToDisable[0]));
				$req->closeCursor();*/

			$updateCol = array(array('source', '!Science'));
			$checkCol = array(array('id', '=', $data['disableQueryScience'], ''));
			$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
		}
	}
	#Activate science query
	elseif (!empty($data['activateQueryScience']))
	{
		// Read owner science query
		/*$req = $bdd->prepare('SELECT id FROM watch_pack_queries_serge WHERE id = :queryId AND pack_id = :packIdInUse  AND source = "!Science"');
		$req->execute(array(
			'queryId' => $idQueryToActivate[0],
			'packIdInUse' => $data['packId']));
			$result = $req->fetch();
			$req->closeCursor();*/

		$checkCol = array(array('id', '=', $data['activateQueryScience'], 'AND'),
											array('pack_id', '=', $data['packId'], 'AND'),
											array('source', '=', '!Science', ''));
		$result = read('watch_pack_queries_serge', 'id', $checkCol, '', $bdd);
		$result = $result[0];

		if (!empty($result))
		{
			/*$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = "Science" WHERE id = :id');
			$req->execute(array(
				'id' => $idQueryToActivate[0]));
				$req->closeCursor();*/

			$updateCol = array(array('source', 'Science'));
			$checkCol = array(array('id', '=', $data['activateQueryScience'], ''));
			$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
		}
	}
	elseif (!empty($data['patentQuerySubmit']))
	{
		$cpt = 0;
		$andOrPatent = '';
		$queryPatent = '';
		$_SESSION['cptPatentQuery'] = 3;

		while(!empty($data['patentType' . $cpt]) AND !empty($data['patentQuery' . $cpt]))
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
			/*$req = $bdd->prepare('SELECT id FROM watch_pack_queries_serge WHERE LOWER(query) = LOWER(:newQuery) AND pack_id = :packIdInUse AND source = "Patent"');
			$req->execute(array(
				'newQuery' => $queryPatent,
				'packIdInUse' => $data['packId']));
				$result = $req->fetch();
				$req->closeCursor();*/

			$checkCol = array(array('LOWER(query)', '=', mb_strtolower($queryPatent), 'AND'),
												array('pack_id', '=', $data['packId'], 'AND'),
												array('source', '=', 'Patent', ''));
			$scienceQueryExist = read('watch_pack_queries_serge', '', $checkCol, '', $bdd);

			if (!$scienceQueryExist)
			{
				/*$active = 1;
				// Adding new query
				$req = $bdd->prepare('INSERT INTO watch_pack_queries_serge (pack_id, query, source) VALUES (:packIdInUse, :query, :source)');
				$req->execute(array(
					'packIdInUse' => $data['packId'],
					'query' => $queryPatent,
					'source' => "Patent"));
					$req->closeCursor();*/

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
	elseif (!empty($data['delQueryPatent']))
	{
		// Read owner patent query
		/*$req = $bdd->prepare('SELECT id FROM watch_pack_queries_serge WHERE id = :queryId AND pack_id = :packIdInUse AND (source = "Patent" OR source = "!Patent")');
		$req->execute(array(
			'queryId' => $idQueryToDel[0],
			'packIdInUse' => $data['packId']));
			$result = $req->fetch();
			$req->closeCursor();*/

		$checkCol = array(array('id', '=', $data['delQueryPatent'], 'AND'),
											array('pack_id', '=', $data['packId'], 'AND'),
											array('source', '=', 'Patent', 'OR'),
											array('id', '=', $data['delQueryPatent'], 'AND'),
											array('pack_id', '=', $data['packId'], 'AND'),
											array('source', '=', '!Patent', ''));
		$result = read('queries_science_serge', 'id', $checkCol, '', $bdd);
		$result = $result[0];

		if (!empty($result))
		{
			/*$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = "Delete" WHERE id = :id');
			$req->execute(array(
				'id' => $idQueryToDel[0]));
				$req->closeCursor();*/

			$updateCol = array(array('source', 'Delete'));
			$checkCol = array(array('id', '=', $data['delQueryPatent'], ''));
			$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
		}
	}
	#Disable patent query
	elseif (!empty($data['disableQueryPatent']))
	{
		// Read owner patent query
		/*$req = $bdd->prepare('SELECT id FROM watch_pack_queries_serge WHERE id = :queryId AND pack_id = :packIdInUse  AND source = "Patent"');
		$req->execute(array(
			'queryId' => $idQueryToDisable[0],
			'packIdInUse' => $data['packId']));
			$result = $req->fetch();
			$req->closeCursor();*/

		$checkCol = array(array('id', '=', $data['disableQueryPatent'], 'AND'),
											array('pack_id', '=', $data['packId'], 'AND'),
											array('source', '=', 'Patent', ''));
		$queryExist = read('watch_pack_queries_serge', '', $checkCol, '', $bdd);

		if ($queryExist)
		{
			/*$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = "!Patent" WHERE id = :id');
			$req->execute(array(
				'id' => $idQueryToDisable[0]));
				$req->closeCursor();*/

			$updateCol = array(array('source', '!Patent'));
			$checkCol = array(array('id', '=', $data['disableQueryPatent'], ''));
			$execution = update('watch_pack_queries_serge', $updateCol, $checkCol, '', $bdd);
		}
	}
	#Activate patent query
	elseif (!empty($data['activateQueryPatent']))
	{
		// Read owner patent query
		/*$req = $bdd->prepare('SELECT id FROM watch_pack_queries_serge WHERE id = :queryId AND pack_id = :packIdInUse AND source = "!Patent"');
		$req->execute(array(
			'queryId' => $idQueryToActivate[0],
			'packIdInUse' => $data['packId']));
			$result = $req->fetch();
			$req->closeCursor();*/

		$checkCol = array(array('id', '=', $data['activateQueryPatent'], 'AND'),
											array('pack_id', '=', $data['packId'], 'AND'),
											array('source', '=', '!Patent', ''));
		$queryExist = read('watch_pack_queries_serge', '', $checkCol, '', $bdd);

		if ($queryExist)
		{
			/*$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = 'Patent' WHERE id = :id');
			$req->execute(array(
				'id' => $idQueryToActivate[0]));
				$req->closeCursor();*/

				$updateCol = array(array('source', 'Patent'));
				$checkCol = array(array('id', '=', $data['activateQueryPatent'], ''));
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
	elseif (!empty($data['addNewPack']) AND $data['watchPackList'] === 'NewPack' AND !empty($data['watchPackName']) AND !empty($data['watchPackDescription']))
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
		/*$req = $bdd->prepare('SELECT id FROM watch_pack_serge WHERE name = :newName');
		$req->execute(array(
			'newName' => $newWatchPackName));
			$result = $req->fetch();
			$req->closeCursor();*/

		$checkCol = array(array('name', '=', $newWatchPackName, ''));
		$nameExist  = read('watch_pack_serge', '', $checkCol, '', $bdd);

		// Add new pack in database
		if (!$nameExist)
		{
			$insertCol = array(array('name', $newWatchPackName),
												array('description', $data['watchPackDescription']),
												array('author', $_SESSION['pseudo']),
												array('category', $category),
												array('language', $language),
												array('update_date', $_SERVER['REQUEST_TIME']),
												array('rating', ','));
			$execution = insert('watch_pack_serge', $insertCol, '', '', $bdd);

			/*$req = $bdd->prepare('SELECT id FROM watch_pack_serge WHERE LOWER(name) = LOWER(:newName)');
			$req->execute(array(
				'newName' => $newWatchPackName));
				$result = $req->fetch();
				$req->closeCursor();*/

			$checkCol = array(array('LOWER(name)', '=', mb_strtolower($newWatchPackName), ''));
			$result   = read('watch_pack_serge', 'id', $checkCol, '', $bdd);
			$result = $result[0];

			// Creation of list of available sources
			/*$userId = '%,' . $_SESSION['id'] . ',%';
			$userIdDesactivated = '%,!' . $_SESSION['id'] . ',%';
			$req = $bdd->prepare("SELECT id FROM rss_serge WHERE owners LIKE :user OR owners LIKE :userDesactivated ORDER BY id");
			$req->execute(array(
				'user' => $userId,
				'userDesactivated' => $userIdDesactivated));
				$listAllSources = $req->fetchAll();
				$req->closeCursor();*/

			$checkCol = array(array('owners', 'l', '%,' . $_SESSION['id'] . ',%', 'OR'),
												array('owners', 'l', '%,!' . $_SESSION['id'] . ',%', ''));
			$result   = read('rss_serge', 'id', $checkCol, 'ORDER BY id', $bdd);

			$sources = ',';
			foreach ($listAllSources as $allSources)
			{
				$sources = $sources . $allSources['id'] . ',';
			}

			/*$req = $bdd->prepare('INSERT INTO watch_pack_queries_serge (pack_id, query, source) VALUES (:pack_id, :query, :source)');
			$req->execute(array(
				'pack_id' => $result['id'],
				'query' => '[!source!]',
				'source' => $sources));
				$req->closeCursor();*/

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
	elseif (!empty($data['addNewPack']) AND $data['watchPackList'] === 'NewPack' AND (empty($data['watchPackName']) OR empty($data['watchPackDescription'])))
	{
		$ERRORMESSAGEEMPTYNAMEORDESC = 'You have to enter a name and a description for your watch pack';
	}
	elseif (!empty($data['watchPackList']))
	{
		preg_match("/[0-9]+/", $data['watchPackList'], $pack_idInUse);

		/*$req = $bdd->prepare('SELECT id FROM watch_pack_serge WHERE author = :pseudo AND id = :pack_idInUse');
		$req->execute(array(
			'pseudo' => $_SESSION['pseudo'],
			'pack_idInUse' => $data['packId']));
			$result = $req->fetch();
			$req->closeCursor();*/

		$checkCol = array(array('author', '=', $_SESSION['pseudo'], 'AND'),
											array('id', '=',$data['packId'], ''));
		$result   = read('watch_pack_serge', 'id', $checkCol, '', $bdd);
		$result = $result[0];

		header('Location: watchPack?type=create&packId=' . $data['packId']);
		die();
	}

	# TODO Faire une fonction qui va relir toute les sources et les mots clefs
	if (!empty($data['packId']))
	{
		preg_match("/[0-9]+/", $data['packId'], $pack_idInUse);

		/*$req = $bdd->prepare('SELECT name, description, category, language FROM watch_pack_serge WHERE author = :pseudo AND id = :pack_idInUse');
		$req->execute(array(
			'pseudo' => $_SESSION['pseudo'],
			'pack_idInUse' => $data['packId']));
			$packDetails = $req->fetch();
			$req->closeCursor();*/

		$checkCol = array(array('author', '=', $_SESSION['pseudo'], 'AND'),
											array('id', '=',$data['packId'], ''));
		$result   = read('watch_pack_serge', 'name, description, category, language', $checkCol, '', $bdd);
		$packDetails = $result[0];

		if (empty($packDetails))
		{
			header('Location: watchPack?type=create');
			die();
		}
		/*$reqReadPackSources = $bdd->prepare('SELECT source FROM watch_pack_queries_serge WHERE pack_id = :pack_id AND query = "[!source!]"');
		$reqReadPackSources->execute(array(
			'pack_id' => $data['packId']));
			$reqReadPackSourcestmp = $reqReadPackSources->fetchAll();
			$reqReadPackSources->closeCursor();*/

			$checkCol = array(array('pack_id', '=', $data['packId'], 'AND'),
												array('query', '=', '[!source!]', ''));
			$reqReadPackSourcestmp = read('watch_pack_queries_serge', 'source', $checkCol, '', $bdd);

			$packSource = array();
			foreach ($reqReadPackSourcestmp as $readPackSources)
			{
				if (preg_match("/^[,!0-9,]+$/", $readPackSources['source']))
				{
					$readPackSources['source'] = preg_replace("/!/", '', $readPackSources['source']);
					$packSource = array_merge(preg_split('/,/', $readPackSources['source'], -1, PREG_SPLIT_NO_EMPTY), $packSource);
				}
			}

			/*$sourcesIds = implode(',', $packSource);
			$req = $bdd->prepare("SELECT id, link, name, owners, active FROM rss_serge WHERE id IN ($sourcesIds) ORDER BY name");
			$req->execute(array(
				'user' => $userId,
				'userDesactivated' => $userIdDesactivated));
				$listAllSources = $req->fetchAll();
				$req->closeCursor();*/

				$checkCol = array(array('id', 'IN', implode(',', $packSource), ''));
				$listAllSources = read('rss_serge', 'id, link, name, owners, active', $checkCol, 'ORDER BY name', $bdd);

			/*$reqReadPackSources = $bdd->prepare('SELECT source FROM watch_pack_queries_serge WHERE pack_id = :pack_id AND query <> "[!source!]"');
			$reqReadPackSources->execute(array(
				'pack_id' => $data['packId']));
				$reqReadPackSourcestmp = $reqReadPackSources->fetchAll();
				$reqReadPackSources->closeCursor();*/

				$checkCol = array(array('pack_id', '=', $data['packId'], 'AND'),
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

			$sourcesIdsUsed = implode(',', $packSourceUsed);

			/*$req = $bdd->prepare("SELECT id, link, name, owners, active FROM rss_serge WHERE id IN ($sourcesIdsUsed) ORDER BY name");
			$req->execute(array());
				$readPackSources = $req->fetchAll();
				$req->closeCursor();*/

			$checkCol = array(array('id', 'IN', implode(',', $packSourceUsed), ''));
			$result   = read('rss_serge', 'id, link, name, owners, active', $checkCol, 'ORDER BY name', $bdd);
			$result = $result[0];
	}
}
include('view/nav/nav.php');

include('view/body/watchPack.php');

include('view/footer/footer.php');

?>
