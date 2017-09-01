<?php

include_once('controller/accessLimitedToSignInPeople.php');
include_once('model/get_text.php');
include_once('model/read.php');
include_once('model/update.php');

// Define variables
$actualLetter = '';
$style = '';
$orderByKeyword = '';
$orderBySource  = '';
$orderByType    = '';

if (empty($_SESSION['cptScienceQuery']))
{
	$_SESSION['cptScienceQuery'] = 3;
}

if (empty($_SESSION['cptPatentQuery']))
{
	$_SESSION['cptPatentQuery'] = 3;
}

# Scroll position
if (isset($_POST['scrollPos']))
{
	$_SESSION['scrollPos'] = htmlspecialchars($_POST['scrollPos']);
}
elseif (!isset($_SESSION['scrollPos']) OR $_SESSION['scrollPos'] == '')
{
	$_SESSION['scrollPos'] = 0;
}

# Save folding state
if (isset($_POST['sourceType']))
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
	foreach($_POST as $key => $val)
	{
		$key = htmlspecialchars($key);
		$val = htmlspecialchars($val);
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
$settingTab = "active";

# Type
if (!empty($_GET['type']))
{
	$type = htmlspecialchars($_GET['type']);

	if ($type == 'add')
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
	elseif ($type == 'create')
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

if ($type == 'add')
{
	$checkCol = array();
	$languageBDD = read('language_serge', 'code, name', $checkCol, '', $bdd);

	$colOrder['language'] = '<select name="language" onchange="this.form.submit();">';
	$colOrder['language'] = $colOrder['language'] . PHP_EOL . '<option value="all" selected>All languages</option>';

	$languageGET = preg_replace("/[^a-z]/", "", $_GET['language']);

	foreach ($languageBDD as $languageLine)
	{
		if ($languageGET == $languageLine['code'])
		{
			$colOrder['language'] = $colOrder['language'] . PHP_EOL . '<option value="' . $languageLine['code'] . '" selected>' . $languageLine['code'] . ' &nbsp;&nbsp;' . $languageLine['name'] . '</option>';
			$selectedLanguageCode = $languageLine['code'];
		}
		else
		{
			$colOrder['language'] = $colOrder['language'] . PHP_EOL . '<option value="' . $languageLine['code'] . '">' . $languageLine['code'] . ' &nbsp;&nbsp;' . $languageLine['name'] . '</option>';
		}
	}

	$orderBy = htmlspecialchars($_GET['orderBy']);

	$colOrder['language'] = $colOrder['language'] . PHP_EOL . '</select>
	<input type="hidden" name="orderBy" value="' . $orderBy . '"/>';

	# Add a star
	$pattern = '★ [0-9]+';
	if (!empty($_POST['AddStar']) AND preg_match("/$pattern/", $_POST['AddStar']))
	{
		preg_match("/ [0-9]+/", $_POST['AddStar'], $packId);
		$packId = $packId[0];

		$req = $bdd->prepare('SELECT rating FROM watch_pack_serge WHERE id = :id');
		$req->execute(array(
			'id' => $packId));
			$usersStars = $req->fetch();
			$req->closeCursor();

		if (empty($usersStars['rating']))
		{
			$usersStars['rating'] = ',';
		}

		$pattern = ',' . $_SESSION['id'] . ',';
		if (preg_match("/$pattern/", $usersStars['rating']))
		{
			$usersStars = preg_replace("/$pattern/", ",", $usersStars['rating']);
		}
		else
		{
			$usersStars = $usersStars['rating'] . $_SESSION['id'] . ',';
		}

		$req = $bdd->prepare('UPDATE watch_pack_serge SET rating = :usersStars WHERE id = :id');
		$req->execute(array(
			'usersStars' => $usersStars,
			'id' => $packId));
			$req->closeCursor();

			header('Location: watchPack');
	}

	$OPTIONALCOND = '1';

	# Order results
	if (!empty($_GET['orderBy']))
	{
		$orderBy = htmlspecialchars($_GET['orderBy']);
		if ($orderBy == 'name')
		{
			$colOrder['name'] = '▾';
			$colOrder['DESC'] = 'DESC';

			# WARNING sensitive variable [SQLI]
			$ORDERBY = 'ORDER BY name';
		}
		elseif ($orderBy == 'nameDESC')
		{
			$colOrder['name'] = '▴';
			$colOrder['DESC'] = '';

			# WARNING sensitive variable [SQLI]
			$ORDERBY = 'ORDER BY name DESC';
		}
		elseif ($orderBy == 'author')
		{
			$colOrder['author'] = '▾';
			$colOrder['DESC'] = 'DESC';

			# WARNING sensitive variable [SQLI]
			$ORDERBY = 'ORDER BY author';
		}
		elseif ($orderBy == 'authorDESC')
		{
			$colOrder['author'] = '▴';
			$colOrder['DESC'] = '';

			# WARNING sensitive variable [SQLI]
			$ORDERBY = 'ORDER BY author DESC';
		}
		elseif ($orderBy == 'category')
		{
			$colOrder['category'] = '▾';
			$colOrder['DESC'] = 'DESC';

			# WARNING sensitive variable [SQLI]
			$ORDERBY = 'ORDER BY category';
		}
		elseif ($orderBy == 'categoryDESC')
		{
			$colOrder['category'] = '▴';
			$colOrder['DESC'] = '';

			# WARNING sensitive variable [SQLI]
			$ORDERBY = 'ORDER BY category DESC';
		}
		elseif ($orderBy == 'date')
		{
			$colOrder['date'] = '▾';
			$colOrder['DESC'] = 'DESC';

			# WARNING sensitive variable [SQLI]
			$ORDERBY = 'ORDER BY update_date';
		}
		elseif ($orderBy == 'dateDESC')
		{
			$colOrder['date'] = '▴';
			$colOrder['DESC'] = '';

			# WARNING sensitive variable [SQLI]
			$ORDERBY = 'ORDER BY update_date DESC';
		}
		elseif ($orderBy == 'rate')
		{
			$colOrder['rate'] = ' ▾';
			$colOrder['DESC'] = 'DESC';

			# WARNING sensitive variable [SQLI]
			$ORDERBY = 'ORDER BY `NumberOfStars`';
		}
		elseif ($orderBy == 'rateDESC')
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
	elseif (!empty($_GET['language']))
	{
		# WARNING sensitive variable [SQLI]
		$OPTIONALCOND = 'language = UPPER(\'' . $selectedLanguageCode . '\')';
	}
	elseif (empty($_GET['search']))
	{
		$colOrder['rate'] = ' ▴';
		$colOrder['DESC'] = '';

		# WARNING sensitive variable [SQLI]
		$ORDERBY = 'ORDER BY `NumberOfStars` DESC';
	}

	# Search engine

	# Read watchPack
	$req = $bdd->prepare("SELECT id, name, description, author, users, category, language, update_date, rating, ((LENGTH(`rating`) - LENGTH(REPLACE(`rating`, ',', '')))-1) AS `NumberOfStars` FROM `watch_pack_serge` WHERE $OPTIONALCOND $ORDERBY;");
	$req->execute();
		$watchPacks = $req->fetchAll();
		$req->closeCursor();
}
else
{
	if (!empty($_GET['packId']))
	{
		preg_match("/[0-9]+/", $_GET['packId'], $pack_idInUse);

		$req = $bdd->prepare('SELECT name, description, category, language FROM watch_pack_serge WHERE author = :pseudo AND id = :pack_idInUse');
		$req->execute(array(
			'pseudo' => $_SESSION['pseudo'],
			'pack_idInUse' => $pack_idInUse[0]));
			$packDetails = $req->fetch();
			$req->closeCursor();

		if (empty($packDetails))
		{
			header('Location: watchPack?type=create');
		}

		$reqReadPackSources = $bdd->prepare('SELECT source FROM watch_pack_queries_serge WHERE pack_id = :pack_id AND query = "[!source!]"');
		$reqReadPackSources->execute(array(
			'pack_id' => $pack_idInUse[0]));
			$reqReadPackSourcestmp = $reqReadPackSources->fetchAll();
			$reqReadPackSources->closeCursor();

			$packSource = array();
			foreach ($reqReadPackSourcestmp as $readPackSources)
			{
				if (preg_match("/^[,!0-9,]+$/", $readPackSources['source']))
				{
					$readPackSources['source'] = preg_replace("/!/", "", $readPackSources['source']);
					$packSource = array_merge(preg_split('/,/', $readPackSources['source'], -1, PREG_SPLIT_NO_EMPTY), $packSource);
				}
			}

			$sourcesIds = implode(',', $packSource);

			$req = $bdd->prepare("SELECT id, link, name, owners, active FROM rss_serge WHERE id IN ($sourcesIds) ORDER BY name");
			$req->execute(array(
				'user' => $userId,
				'userDesactivated' => $userIdDesactivated));
				$listAllSources = $req->fetchAll();
				$req->closeCursor();

			$reqReadPackSources = $bdd->prepare('SELECT source FROM watch_pack_queries_serge WHERE pack_id = :pack_id AND query <> "[!source!]"');
			$reqReadPackSources->execute(array(
				'pack_id' => $pack_idInUse[0]));
				$reqReadPackSourcestmp = $reqReadPackSources->fetchAll();
				$reqReadPackSources->closeCursor();

				$packSourceUsed = array("0");
				foreach ($reqReadPackSourcestmp as $readPackSources)
				{
					if (preg_match("/^[,!0-9,]+$/", $readPackSources['source']))
					{
						$readPackSources['source'] = preg_replace("/!/", "", $readPackSources['source']);
						$packSourceUsed = array_merge(preg_split('/,/', $readPackSources['source'], -1, PREG_SPLIT_NO_EMPTY), $packSourceUsed);
					}
				}

			$sourcesIdsUsed = implode(',', $packSourceUsed);

			$req = $bdd->prepare("SELECT id, link, name, owners, active FROM rss_serge WHERE id IN ($sourcesIdsUsed) ORDER BY name");
			$req->execute(array());
				$readPackSources = $req->fetchAll();
				$req->closeCursor();
	}
	else
	{
		/*$req = $bdd->prepare('SELECT language FROM users_table_serge WHERE id = :userId');
		$req->execute(array(
			'userId' => $_SESSION['id']));
			$packDetails = $req->fetch();
			$req->closeCursor();*/

		$checkCol    = array(array("id", " = ", $_SESSION['id'], ""));
		$packDetails = read('users_table_serge', 'language', $checkCol, '', $bdd);
		$packDetails = $packDetails[0];
	}

	$checkCol    = array();
	$languageBDD = read('language_serge', 'code, name', $checkCol, '', $bdd);

	$userLang = strtolower($packDetails['language']);

	$selectLanguage = '<select class="shortSelect" name="language">' . PHP_EOL;

	foreach ($languageBDD as $languageLine)
	{
		if ($userLang == $languageLine['code'])
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
	if (preg_match("/[0-9]+/", $_POST['watchPackList']) AND isset($_POST['addNewPack']) AND !empty($_POST['watchPackName']) AND !empty($_POST['watchPackDescription']))
	{
		preg_match("/[0-9]+/", $_POST['watchPackList'], $packIdEdit);
		// Check if watch pack is own by the user
		$req = $bdd->prepare('SELECT id FROM watch_pack_serge WHERE author = :username AND id = :packIdEdit');
		$req->execute(array(
			'username' => $_SESSION['pseudo'],
			'packIdEdit' => $packIdEdit[0]));
			$result = $req->fetch();
			$req->closeCursor();

		$req = $bdd->prepare('SELECT id FROM watch_pack_serge WHERE name = :newName AND id <> :packIdEdit');
		$req->execute(array(
			'newName' => htmlspecialchars($_POST['watchPackName']),
			'packIdEdit' => $packIdEdit[0]));
			$resultName = $req->fetch();
			$req->closeCursor();

		if (!empty($result) AND empty($resultName))
		{
			$update_date = time();

			$req = $bdd->prepare('UPDATE watch_pack_serge SET name = :name, description = :description, category = :category, language = :language, update_date = :update_date WHERE id = :packIdEdit');
			$req->execute(array(
				'name' => htmlspecialchars($_POST['watchPackName']),
				'description' =>  htmlspecialchars($_POST['watchPackDescription']),
				'category' => htmlspecialchars($_POST['watchPackCategory']),
				'language' => strtoupper(htmlspecialchars($_POST['language'])),
				'update_date' => $update_date,
				'packIdEdit' => $packIdEdit[0]));
				$req->closeCursor();

		}
	}
	elseif (isset($_POST['addNewKeyword']) AND preg_match("/[0-9]+/", $_POST['sourceKeyword']) AND isset($_POST['newKeyword']))
	{
		preg_match("/[0-9]+/", $_POST['sourceKeyword'], $sourceId);

		$newKeywordArray = preg_split('/,/', htmlspecialchars($_POST['newKeyword']), -1, PREG_SPLIT_NO_EMPTY);

		if ($sourceId[0] == '00')
		{
			# Add keyword on all sources
			foreach ($listAllSources as $sourcesList)
			{
				foreach ($newKeywordArray as $newKeyword)
				{
					$req = $bdd->prepare('SELECT id, source FROM watch_pack_queries_serge WHERE lower(query) = lower(:keyword) AND pack_id = :pack_id AND source <> "Science" AND source <> "Patent"');
					$req->execute(array(
						'keyword' => $newKeyword,
						'pack_id' => $pack_idInUse[0]));
						$resultKeyword = $req->fetch();
						$req->closeCursor();

					if (empty($resultKeyword))
					{
						$req = $bdd->prepare('INSERT INTO watch_pack_queries_serge (pack_id, query, source) VALUES (:pack_id, :query, :source)');
						$req->execute(array(
							'pack_id' => $pack_idInUse[0],
							'query' =>  $newKeyword,
							'source' => ',' . $sourcesList['id'] . ','));
							$req->closeCursor();
					}
					else
					{ # TODO Vérif qu'on ajoute pas deux fois les sources
						$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = :source WHERE id = :keywordId');
						$req->execute(array(
							'source' => $resultKeyword['source'] . $sourcesList['id'] . ',',
							'keywordId' => $resultKeyword['id']));
							$req->closeCursor();
					}
				}
			}
		}
		else
		{
			foreach ($newKeywordArray as $newKeyword)
			{
				$req = $bdd->prepare('SELECT id FROM rss_serge WHERE id = :sourceId');
				$req->execute(array(
					'sourceId' => $sourceId[0]));
					$resultSource = $req->fetch();
					$req->closeCursor();

				if (!empty($resultSource))
				{
					$req = $bdd->prepare('SELECT id, source FROM watch_pack_queries_serge WHERE lower(query) = lower(:keyword) AND pack_id = :pack_id AND source <> "Science" AND source <> "Patent"');
					$req->execute(array(
						'keyword' => $newKeyword,
						'pack_id' => $pack_idInUse[0]));
						$resultKeyword = $req->fetch();
						$req->closeCursor();

					$newKeywordSource = ',' . $sourceId[0] . ',';

					if (empty($resultKeyword))
					{
						$req = $bdd->prepare('INSERT INTO watch_pack_queries_serge (pack_id, query, source) VALUES (:pack_id, :query, :source)');
						$req->execute(array(
							'pack_id' => $pack_idInUse[0],
							'query' =>  $newKeyword,
							'source' => ',' . $sourceId[0] . ','));
							$req->closeCursor();
					}
					elseif (!preg_match("/$newKeywordSource/", $resultKeyword['source']))
					{
						$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = :source WHERE id = :keywordId');
						$req->execute(array(
							'source' => $resultKeyword['source'] . $sourceId[0] . ',',
							'keywordId' => $resultKeyword['id']));
							$req->closeCursor();
					}
				}
			}
		}
	}
	elseif (isset($_POST['addNewSource']) AND isset($_POST['newSource']))
	{
		$newSource = htmlspecialchars($_POST['newSource']);
		$req = $bdd->prepare('SELECT id FROM rss_serge WHERE link = :newSource');
		$req->execute(array(
			'newSource' => $newSource));
			$resultSource = $req->fetch();
			$req->closeCursor();

		$req = $bdd->prepare('SELECT source FROM watch_pack_queries_serge WHERE query = "[!source!]" AND pack_id = :packIdInUse');
		$req->execute(array(
			'packIdInUse' => $pack_idInUse[0]));
			$sources = $req->fetch();
			$req->closeCursor();

		$newSourceId = ',' . $resultSource['id'] . ',';

		if (!empty($resultSource) AND !preg_match("/$newSourceId/", $sources['source']))
		{

			$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = :source WHERE pack_id = :packIdInUse AND query = "[!source!]"');
			$req->execute(array(
				'source' => $sources['source'] . $resultSource['id'] . ',',
				'packIdInUse' => $pack_idInUse[0]));
				$req->closeCursor();
		}
		elseif (!preg_match("/$newSourceId/", $sources['source']))
		{
			// Check if source is valid
			$sourceToTest = escapeshellarg($newSource);
			$cmd          = '/usr/bin/python /var/www/Serge/checkfeed.py ' . $sourceToTest;

			# Check if the link is valid
			exec($cmd, $linkValidation, $errorInCheckfeed);

			if ($linkValidation[0] == 'valid link' AND $errorInCheckfeed == 0)
			{
				// Adding new source
				$owners = ',' . $_SESSION['id'] . ',';
				$active = 1;
				preg_match('@^(?:http.*://[www.]*)?([^/]+)@i', $newSource, $matches);
				$name = ucfirst($matches[1] . '[!NEW!]');
				$req = $bdd->prepare('INSERT INTO rss_serge (link, owners, name, active) VALUES
				(:link, :owners, :name, :active)');
				$req->execute(array(
					'link' => $newSource,
					'owners' => $owners,
					'name' => $name,
					'active' => $active));
					$req->closeCursor();

				$req = $bdd->prepare('SELECT id FROM rss_serge WHERE link = :newSource');
				$req->execute(array(
					'newSource' => $newSource));
					$resultSource = $req->fetch();
					$req->closeCursor();

					$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = :source WHERE pack_id = :packIdInUse AND query = "[!source!]"');
					$req->execute(array(
						'source' => $sources['source'] . $resultSource['id'] . ',',
						'packIdInUse' => $pack_idInUse[0]));
						$req->closeCursor();
			}
			else
			{
				$ERROR_MESSAGE = 'Your link ' . 'return ' . $linkValidation[0] . ',' . $linkValidation[1] . ', please correct your link';
			}
		}
	}
	elseif (isset($_POST['delKeyword']) OR isset($_POST['disableKeyword']) OR isset($_POST['activateKeyword']))
	{
		# Delete, disable, active keyword
		if (isset($_POST['delKeyword']))
		{
			preg_match_all("/[0-9]*&/", htmlspecialchars($_POST['delKeyword']), $matchKeywordAndSource);
			$sourceIdAction  = preg_replace("/[^0-9]/", "", $matchKeywordAndSource[0][0]);
			$keywordIdAction = preg_replace("/[^0-9]/", "", $matchKeywordAndSource[0][1]);
			$action          = 'delKeyword';
		}
		elseif (isset($_POST['disableKeyword']))
		{
			preg_match_all("/[0-9]*&/", htmlspecialchars($_POST['disableKeyword']), $matchKeywordAndSource);
			$sourceIdAction  = preg_replace("/[^0-9]/", "", $matchKeywordAndSource[0][0]);
			$keywordIdAction = preg_replace("/[^0-9]/", "", $matchKeywordAndSource[0][1]);
			$action          = 'disableKeyword';
		}
		elseif (isset($_POST['activateKeyword']))
		{
			preg_match_all("/[0-9]*&/", htmlspecialchars($_POST['activateKeyword']), $matchKeywordAndSource);
			$sourceIdAction  = preg_replace("/[^0-9]/", "", $matchKeywordAndSource[0][0]);
			$keywordIdAction = preg_replace("/[^0-9]/", "", $matchKeywordAndSource[0][1]);
			$action          = 'activateKeyword';
		}

		if (isset($sourceIdAction) AND isset($keywordIdAction) AND isset($action))
		{
			# Check if keyword exist
			$req = $bdd->prepare('SELECT source FROM watch_pack_queries_serge WHERE id = :keywordIdAction AND (source LIKE :sourceIdAction OR source LIKE :sourceIdActionDesactivated) AND pack_id = :packIdInUse');
			$req->execute(array(
				'keywordIdAction' => $keywordIdAction,
				'sourceIdAction' => "%," . $sourceIdAction . ",%",
				'sourceIdActionDesactivated' => "%,!" . $sourceIdAction . ",%",
				'packIdInUse' => $pack_idInUse[0]));
				$result = $req->fetch();
				$req->closeCursor();

			# Delete an existing keyword
			if (!empty($result) AND $action == 'delKeyword')
			{
				$sourceNew = preg_replace("/,!*$sourceIdAction,/", ',', $result['source']);

				$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = :sources WHERE id = :id');
				$req->execute(array(
					'sources' => $sourceNew,
					'id' => $keywordIdAction));
					$req->closeCursor();
			}
			elseif (!empty($result) AND $action == 'disableKeyword')
			{
				$sourceNew = preg_replace("/,$sourceIdAction,/", ",!$sourceIdAction,", $result['source']);

				$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = :sources WHERE id = :id');
				$req->execute(array(
					'sources' => $sourceNew,
					'id' => $keywordIdAction));
					$req->closeCursor();
			}
			elseif (!empty($result) AND $action == 'activateKeyword')
			{
				$sourceNew = preg_replace("/,!$sourceIdAction,/", ",$sourceIdAction,", $result['source']);

				$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = :sources WHERE id = :id');
				$req->execute(array(
					'sources' => $sourceNew,
					'id' => $keywordIdAction));
					$req->closeCursor();
			}
			else
			{
				$ERROR_MESSAGE = 'Keyword doesn\'t exist or invalid action';
			}
		}
	}
	elseif (isset($_POST['delSource']) OR isset($_POST['disableSource']) OR isset($_POST['activateSource']))
	{
		# Delete, disable, active sources
		if (isset($_POST['delSource']))
		{
			preg_match("/[0-9]*&/", htmlspecialchars($_POST['delSource']), $matchSourceId);
			$sourceIdAction  = preg_replace("/[^0-9]/", "", $matchSourceId[0]);
			$action          = 'delSource';
		}
		elseif (isset($_POST['disableSource']))
		{
			preg_match("/[0-9]*&/", htmlspecialchars($_POST['disableSource']), $matchSourceId);
			$sourceIdAction  = preg_replace("/[^0-9]/", "", $matchSourceId[0]);
			$action          = 'disableSource';
		}
		elseif (isset($_POST['activateSource']))
		{
			preg_match("/[0-9]*&/", htmlspecialchars($_POST['activateSource']), $matchSourceId);
			$sourceIdAction  = preg_replace("/[^0-9]/", "", $matchSourceId[0]);
			$action          = 'activateSource';
		}

		if (isset($sourceIdAction) AND isset($action))
		{
			# Check if source exist for this owner
			$req = $bdd->prepare('SELECT id FROM watch_pack_queries_serge WHERE query = "[!source!]" AND (source LIKE :sourceIdAction OR source LIKE :sourceIdActionDesactivated) AND pack_id = :packIdInUse');
			$req->execute(array(
				'sourceIdAction' => "%," . $sourceIdAction . ",%",
				'sourceIdActionDesactivated' => "%,!" . $sourceIdAction . ",%",
				'packIdInUse' => $pack_idInUse[0]));
				$result = $req->fetch();
				$req->closeCursor();

			# Delete an existing sources
			if (!empty($result) AND $action == 'delSource')
			{
				$req = $bdd->prepare('SELECT id FROM rss_serge WHERE owners LIKE :owner');
				$req->execute(array(
					'owner' => "%," . $_SESSION['id'] . ",%"));
					$result = $req->fetchAll();
					$req->closeCursor();

				$isSourceOwned = ",";
				foreach ($result as $ownerSource)
				{
					if ($ownerSource['id'] == $sourceIdAction)
					{
						$isSourceOwned = "'[!source!]'";
						break;
					}
				}

				// Remove source on all keywords
				$req = $bdd->prepare("SELECT id, source FROM watch_pack_queries_serge WHERE pack_id = :packIdInUse AND (source LIKE :sourceIdAction OR source LIKE :sourceIdActionDesactivated) AND query <> $isSourceOwned");
				$req->execute(array(
					'packIdInUse' => $pack_idInUse[0],
					'sourceIdAction' => "%," . $sourceIdAction . ",%",
					'sourceIdActionDesactivated' => "%,!" . $sourceIdAction . ",%"));
					$result = $req->fetchAll();
					$req->closeCursor();

				foreach ($result as $resultLine)
				{
					$sourceNew = preg_replace("/,!*$sourceIdAction,/", ',', $resultLine['source']);

					$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = :sources WHERE id = :id');
					$req->execute(array(
						'sources' => $sourceNew,
						'id' => $resultLine['id']));
						$req->closeCursor();
				}
			}
			elseif (!empty($result) AND $action == 'disableSource')
			{
				// Disable source on all keywords
				$req = $bdd->prepare('SELECT id, source FROM watch_pack_queries_serge WHERE pack_id = :packIdInUse AND source LIKE :sourceIdAction');
				$req->execute(array(
					'packIdInUse' => $pack_idInUse[0],
					'sourceIdAction' => "%," . $sourceIdAction . ",%"));
					$result = $req->fetchAll();
					$req->closeCursor();

				foreach ($result as $resultLine)
				{
					$sourceNew = preg_replace("/,$sourceIdAction,/", ",!$sourceIdAction,", $resultLine['source']);

					$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = :sources WHERE id = :id');
					$req->execute(array(
						'sources' => $sourceNew,
						'id' => $resultLine['id']));
						$req->closeCursor();
				}
			}
			elseif (!empty($result) AND $action == 'activateSource')
			{
				// Activate source on all keywords
				$req = $bdd->prepare('SELECT id, source FROM watch_pack_queries_serge WHERE pack_id = :packIdInUse AND source LIKE :sourceIdAction');
				$req->execute(array(
					'packIdInUse' => $pack_idInUse[0],
					'sourceIdAction' => "%,!" . $sourceIdAction . ",%"));
					$result = $req->fetchAll();
					$req->closeCursor();

				foreach ($result as $resultLine)
				{
					$sourceNew = preg_replace("/,!$sourceIdAction,/", ",$sourceIdAction,", $resultLine['source']);

					$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = :sources WHERE id = :id');
					$req->execute(array(
						'sources' => $sourceNew,
						'id' => $resultLine['id']));
						$req->closeCursor();
				}
			}
			else
			{
				$ERROR_MESSAGE = 'Source doesn\'t exist or invalid action';
			}
		}
	}
	elseif (isset($_POST['scienceQuerySubmit']))
	{
		$cpt = 0;
		$open = 0;
		$close = 0;
		$nbscienceType = 'scienceType0';
		$queryScience_Arxiv = '';
		$_SESSION['cptScienceQuery'] = 3;

		while(!empty($_POST[$nbscienceType]) AND !empty($_POST['scienceQuery' . $cpt]))
		{
			if (!empty($_POST['andOrAndnot' . $cpt])
					AND preg_match("/(^AND$|^OR$|^NOTAND$)/", $_POST['andOrAndnot' . $cpt]))
			{
				$queryScience_Arxiv = $queryScience_Arxiv . '+' . $_POST['andOrAndnot' . $cpt] . '+';
			}
			elseif (!empty($_POST['andOrAndnot' . $cpt])
							AND !preg_match("/(^AND$|^OR$|^NOTAND$)/", $_POST['andOrAndnot' . $cpt]))
			{
				$queryScience_Arxiv = $queryScience_Arxiv . '+OR+';
			}

			if (preg_match("/(^ti$|^au$|^abs$|^jr$|^cat$|^all$)/", $_POST['scienceType' . $cpt]))
			{
				$openParenthesis = '';
				$closeParenthesis = '';
				if ($_POST['openParenthesis' . $cpt] == 'active')
				{
					$openParenthesis = '%28';
					$open ++;
				}

				if ($_POST['closeParenthesis' . $cpt] == 'active')
				{
					$closeParenthesis = '%29';
					$close ++;
				}

				$queryScience_Arxiv = $queryScience_Arxiv . $openParenthesis . $_POST['scienceType' . $cpt] . ':';

				$scienceQuery = htmlspecialchars($_POST['scienceQuery' . $cpt]);
				$scienceQuery = urlencode($scienceQuery);
				$scienceQuery = preg_replace("/( |:|`|%22|%28|%29)/", "+", $scienceQuery);
				$queryScience_Arxiv = $queryScience_Arxiv . '%22' . $scienceQuery . '%22' . $closeParenthesis;
			}

			# Cleaning
			$_POST['andOrAndnot' . $cpt] = '';
			$_POST['openParenthesis' . $cpt] = '';
			$_POST['scienceType' . $cpt] = '';
			$_POST['scienceQuery' . $cpt] = '';
			$_POST['closeParenthesis' . $cpt] = '';

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
			$req = $bdd->prepare('SELECT id FROM watch_pack_queries_serge WHERE LOWER(query) = LOWER(:newQuery) AND pack_id = :packIdInUse AND source = "Science"');
			$req->execute(array(
				'newQuery' => $queryScience_Arxiv,
				'packIdInUse' => $pack_idInUse[0]));
				$result = $req->fetch();
				$req->closeCursor();

			if (!$result)
			{
				$active = 1;
				// Adding new query
				$req = $bdd->prepare('INSERT INTO watch_pack_queries_serge (pack_id, query, source) VALUES (:packIdInUse, :query, :source)');
				$req->execute(array(
					'packIdInUse' => $pack_idInUse[0],
					'query' => $queryScience_Arxiv,
					'source' => "Science"));
					$req->closeCursor();
			}
			else
			{
				$ERROR_SCIENCEQUERY = 'Query already exist';
			}
		}
	}
	#Delete science query
	elseif (!empty($_POST['delQueryScience']))
	{
		preg_match("/[0-9]+/", $_POST['delQueryScience'], $idQueryToDel);

		// Read owner science query
		$req = $bdd->prepare('SELECT id FROM watch_pack_queries_serge WHERE id = :queryId AND pack_id = :packIdInUse AND (source = "Science" OR source = "!Science")');
		$req->execute(array(
			'queryId' => $idQueryToDel[0],
			'packIdInUse' => $pack_idInUse[0]));
			$result = $req->fetch();
			$req->closeCursor();

		if (!empty($result))
		{
			$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = "Delete" WHERE id = :id');
			$req->execute(array(
				'id' => $idQueryToDel[0]));
				$req->closeCursor();
		}
	}
	#Disable science query
	elseif (!empty($_POST['disableQueryScience']))
	{
		preg_match("/[0-9]+/", $_POST['disableQueryScience'], $idQueryToDisable);

		// Read owner science query
		$req = $bdd->prepare('SELECT id FROM watch_pack_queries_serge WHERE id = :queryId AND pack_id = :packIdInUse  AND source = "Science"');
		$req->execute(array(
			'queryId' => $idQueryToDisable[0],
			'packIdInUse' => $pack_idInUse[0]));
			$result = $req->fetch();
			$req->closeCursor();

		if (!empty($result))
		{
			$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = "!Science" WHERE id = :id');
			$req->execute(array(
				'id' => $idQueryToDisable[0]));
				$req->closeCursor();
		}
	}
	#Activate science query
	elseif (!empty($_POST['activateQueryScience']))
	{
		preg_match("/[0-9]+/", $_POST['activateQueryScience'], $idQueryToActivate);

		// Read owner science query
		$req = $bdd->prepare('SELECT id FROM watch_pack_queries_serge WHERE id = :queryId AND pack_id = :packIdInUse  AND source = "!Science"');
		$req->execute(array(
			'queryId' => $idQueryToActivate[0],
			'packIdInUse' => $pack_idInUse[0]));
			$result = $req->fetch();
			$req->closeCursor();

		if (!empty($result))
		{
			$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = "Science" WHERE id = :id');
			$req->execute(array(
				'id' => $idQueryToActivate[0]));
				$req->closeCursor();
		}
	}
	elseif (isset($_POST['patentQuerySubmit']))
	{
		$cpt = 0;
		$andOrPatent = '';
		$queryPatent = '';
		$_SESSION['cptPatentQuery'] = 3;

		while(!empty($_POST['patentType' . $cpt]) AND !empty($_POST['patentQuery' . $cpt]))
		{
			if (!preg_match("/^[A-Z_]+$/", $_POST['patentType' . $cpt]))
			{
				$_POST['patentType' . $cpt] = 'ALLNAMES';
			}

			$patentQueryInput = urlencode(preg_replace("/(:| $)/", "", $_POST['patentQuery' . $cpt]));

			$queryPatent = $queryPatent . $andOrPatent . $_POST['patentType' . $cpt] . '%3A' . $patentQueryInput . '+';

			# Cleaning
			$_POST['patentType' . $cpt ] = '';
			$_POST['patentQuery' . $cpt ] = '';
			$_POST['andOrPatent' . $cpt ] = '';

			$cpt++;

			if(empty($_POST['andOrPatent' . $cpt]))
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
			$req = $bdd->prepare('SELECT id FROM watch_pack_queries_serge WHERE LOWER(query) = LOWER(:newQuery) AND pack_id = :packIdInUse AND source = "Patent"');
		$req->execute(array(
				'newQuery' => $queryPatent,
				'packIdInUse' => $pack_idInUse[0]));
				$result = $req->fetch();
				$req->closeCursor();

			if (!$result)
			{
				$active = 1;
				// Adding new query
				$req = $bdd->prepare('INSERT INTO watch_pack_queries_serge (pack_id, query, source) VALUES (:packIdInUse, :query, :source)');
				$req->execute(array(
					'packIdInUse' => $pack_idInUse[0],
					'query' => $queryPatent,
					'source' => "Patent"));
					$req->closeCursor();
			}
			else
			{
					$ERROR_PATENTQUERY = 'Query already exist';
			}
		}
	}
	#Delete patent query
	elseif (!empty($_POST['delQueryPatent']))
	{
		preg_match("/[0-9]+/", $_POST['delQueryPatent'], $idQueryToDel);

		// Read owner patent query
		$req = $bdd->prepare('SELECT id FROM watch_pack_queries_serge WHERE id = :queryId AND pack_id = :packIdInUse AND (source = "Patent" OR source = "!Patent")');
		$req->execute(array(
			'queryId' => $idQueryToDel[0],
			'packIdInUse' => $pack_idInUse[0]));
			$result = $req->fetch();
			$req->closeCursor();

		if (!empty($result))
		{
			$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = "Delete" WHERE id = :id');
			$req->execute(array(
				'id' => $idQueryToDel[0]));
				$req->closeCursor();
		}
	}
	#Disable patent query
	elseif (!empty($_POST['disableQueryPatent']))
	{
		preg_match("/[0-9]+/", $_POST['disableQueryPatent'], $idQueryToDisable);

		// Read owner patent query
		$req = $bdd->prepare('SELECT id FROM watch_pack_queries_serge WHERE id = :queryId AND pack_id = :packIdInUse  AND source = "Patent"');
		$req->execute(array(
			'queryId' => $idQueryToDisable[0],
			'packIdInUse' => $pack_idInUse[0]));
			$result = $req->fetch();
			$req->closeCursor();

		if (!empty($result))
		{
			$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = "!Patent" WHERE id = :id');
			$req->execute(array(
				'id' => $idQueryToDisable[0]));
				$req->closeCursor();
		}
	}
	#Activate patent query
	elseif (!empty($_POST['activateQueryPatent']))
	{
		preg_match("/[0-9]+/", $_POST['activateQueryPatent'], $idQueryToActivate);

		// Read owner patent query
		$req = $bdd->prepare('SELECT id FROM watch_pack_queries_serge WHERE id = :queryId AND pack_id = :packIdInUse  AND source = "!Patent"');
		$req->execute(array(
			'queryId' => $idQueryToActivate[0],
			'packIdInUse' => $pack_idInUse[0]));
			$result = $req->fetch();
			$req->closeCursor();

		if (!empty($result))
		{
			$req = $bdd->prepare('UPDATE watch_pack_queries_serge SET source = "Patent" WHERE id = :id');
			$req->execute(array(
				'id' => $idQueryToActivate[0]));
				$req->closeCursor();
		}
	}
	# Extend science query
	elseif (!empty($_POST['extendScience']))
	{
		$_SESSION['cptScienceQuery'] += 3;
		if (!empty($_POST['delEditingScienceQuery']))
		{
			preg_match("/[0-9]+/", $_POST['delEditingScienceQuery'], $idQueryToDel);
			$delEditingScienceQuery = $idQueryToDel[0];
		}
	}
	# Extend patent query
	elseif (!empty($_POST['extendPatent']))
	{
		$_SESSION['cptPatentQuery'] += 3;
		if (!empty($_POST['delEditingScienceQuery']))
		{
			preg_match("/[0-9]+/", $_POST['delEditingPatentQuery'], $idQueryToDel);
			$delEditingPatentQuery = $idQueryToDel[0];
		}
	}
	elseif (isset($_POST['addNewPack']) AND $_POST['watchPackList'] == 'NewPack' AND !empty($_POST['watchPackName']) AND !empty($_POST['watchPackDescription']))
	{
		$newWatchPackName = htmlspecialchars($_POST['watchPackName']);
		$language = strtoupper(htmlspecialchars($_POST['language']));
		if ($_POST['watchPackCategory'] == 'NewCategory')
		{
			$category = htmlspecialchars($_POST['watchPackNewCategory']);
		}
		else
		{
			$category = htmlspecialchars($_POST['watchPackCategory']);
		}

		// Check if the name already exist
		$req = $bdd->prepare('SELECT id FROM watch_pack_serge WHERE name = :newName');
		$req->execute(array(
			'newName' => $newWatchPackName));
			$result = $req->fetch();
			$req->closeCursor();

		// Add new pack in database
		if (empty($result))
		{
			$update_date = time();

			$req = $bdd->prepare('INSERT INTO watch_pack_serge (name, description, author, category, language, update_date, rating) VALUES (:name, :description, :author, :category, :language, :update_date, :rating)');
			$req->execute(array(
				'name' => $newWatchPackName,
				'description' =>  htmlspecialchars($_POST['watchPackDescription']),
				'author' => $_SESSION['pseudo'],
				'category' => $category,
				'language' => $language,
				'update_date' => $update_date,
				'rating' => ','));
				$req->closeCursor();

			$req = $bdd->prepare('SELECT id FROM watch_pack_serge WHERE LOWER(name) = LOWER(:newName)');
			$req->execute(array(
				'newName' => $newWatchPackName));
				$result = $req->fetch();
				$req->closeCursor();

			// Creation of list of available sources
			$userId = '%,' . $_SESSION['id'] . ',%';
			$userIdDesactivated = '%,!' . $_SESSION['id'] . ',%';
			$req = $bdd->prepare("SELECT id FROM rss_serge WHERE owners LIKE :user OR owners LIKE :userDesactivated ORDER BY id");
			$req->execute(array(
				'user' => $userId,
				'userDesactivated' => $userIdDesactivated));
				$listAllSources = $req->fetchAll();
				$req->closeCursor();

			$sources = ',';
			foreach ($listAllSources as $allSources)
			{
				$sources = $sources . $allSources['id'] . ',';
			}

			$req = $bdd->prepare('INSERT INTO watch_pack_queries_serge (pack_id, query, source) VALUES (:pack_id, :query, :source)');
			$req->execute(array(
				'pack_id' => $result['id'],
				'query' => '[!source!]',
				'source' => $sources));
				$req->closeCursor();

			header('Location: watchPack?type=create&packId=' . $result['id']);
		}
		else
		{
			$ERRORMESSAGENEWPACKNAME = "A watch pack with this name already exist, please change the name";
		}
	}
	elseif (isset($_POST['addNewPack']) AND $_POST['watchPackList'] == 'NewPack' AND (empty($_POST['watchPackName']) OR empty($_POST['watchPackDescription'])))
	{
		$ERRORMESSAGEEMPTYNAMEORDESC = "You have to enter a name and a description for your watch pack";
	}
	elseif (!empty($_POST['watchPackList']))
	{
		preg_match("/[0-9]+/", $_POST['watchPackList'], $pack_idInUse);

		$req = $bdd->prepare('SELECT id FROM watch_pack_serge WHERE author = :pseudo AND id = :pack_idInUse');
		$req->execute(array(
			'pseudo' => $_SESSION['pseudo'],
			'pack_idInUse' => $pack_idInUse[0]));
			$result = $req->fetch();
			$req->closeCursor();

		header('Location: watchPack?type=create&packId=' . $pack_idInUse[0]);
	}

	# TODO Faire une fonction qui va relir toute les sources et les mots clefs
	if (!empty($_GET['packId']))
	{
		preg_match("/[0-9]+/", $_GET['packId'], $pack_idInUse);

		$req = $bdd->prepare('SELECT name, description, category, language FROM watch_pack_serge WHERE author = :pseudo AND id = :pack_idInUse');
		$req->execute(array(
			'pseudo' => $_SESSION['pseudo'],
			'pack_idInUse' => $pack_idInUse[0]));
			$packDetails = $req->fetch();
			$req->closeCursor();

		if (empty($packDetails))
		{
			header('Location: watchPack?type=create');
		}
		$reqReadPackSources = $bdd->prepare('SELECT source FROM watch_pack_queries_serge WHERE pack_id = :pack_id AND query = "[!source!]"');
		$reqReadPackSources->execute(array(
			'pack_id' => $pack_idInUse[0]));
			$reqReadPackSourcestmp = $reqReadPackSources->fetchAll();
			$reqReadPackSources->closeCursor();

			$packSource = array();
			foreach ($reqReadPackSourcestmp as $readPackSources)
			{
				if (preg_match("/^[,!0-9,]+$/", $readPackSources['source']))
				{
					$readPackSources['source'] = preg_replace("/!/", "", $readPackSources['source']);
					$packSource = array_merge(preg_split('/,/', $readPackSources['source'], -1, PREG_SPLIT_NO_EMPTY), $packSource);
				}
			}

			$sourcesIds = implode(',', $packSource);

			$req = $bdd->prepare("SELECT id, link, name, owners, active FROM rss_serge WHERE id IN ($sourcesIds) ORDER BY name");
			$req->execute(array(
				'user' => $userId,
				'userDesactivated' => $userIdDesactivated));
				$listAllSources = $req->fetchAll();
				$req->closeCursor();

			$reqReadPackSources = $bdd->prepare('SELECT source FROM watch_pack_queries_serge WHERE pack_id = :pack_id AND query <> "[!source!]"');
			$reqReadPackSources->execute(array(
				'pack_id' => $pack_idInUse[0]));
				$reqReadPackSourcestmp = $reqReadPackSources->fetchAll();
				$reqReadPackSources->closeCursor();

				$packSourceUsed = array("0");
				foreach ($reqReadPackSourcestmp as $readPackSources)
				{
					if (preg_match("/^[,!0-9,]+$/", $readPackSources['source']))
					{
						$readPackSources['source'] = preg_replace("/!/", "", $readPackSources['source']);
						$packSourceUsed = array_merge(preg_split('/,/', $readPackSources['source'], -1, PREG_SPLIT_NO_EMPTY), $packSourceUsed);
					}
				}

			$sourcesIdsUsed = implode(',', $packSourceUsed);

			$req = $bdd->prepare("SELECT id, link, name, owners, active FROM rss_serge WHERE id IN ($sourcesIdsUsed) ORDER BY name");
			$req->execute(array());
				$readPackSources = $req->fetchAll();
				$req->closeCursor();
	}
}
include_once('view/nav/nav.php');

include_once('view/body/watchPack.php');

include_once('view/footer/footer.php');

?>
