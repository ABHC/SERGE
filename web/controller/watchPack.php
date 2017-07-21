<?php

//include_once('model/get_text.php');

include_once('controller/accessLimitedToSignInPeople.php');

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
	# Language list
	$language['aa'] = 'Afar';
	$language['ab'] = 'Abkhazian';
	$language['af'] = 'Afrikaans';
	$language['ak'] = 'Akan';
	$language['am'] = 'Amharic';
	$language['ar'] = 'Arabic';
	$language['as'] = 'Assamese';
	$language['ay'] = 'Aymara';
	$language['az'] = 'Azerbaijani';
	$language['ba'] = 'Bashkir';
	$language['be'] = 'Belarusian';
	$language['bg'] = 'Bulgarian';
	$language['bm'] = 'Bambara';
	$language['bn'] = 'Bengali';
	$language['bo'] = 'Tibetan';
	$language['br'] = 'Breton';
	$language['bs'] = 'Bosnian';
	$language['ca'] = 'Catalan';
	$language['ce'] = 'Chechen';
	$language['co'] = 'Corsican';
	$language['cs'] = 'Czech';
	$language['cv'] = 'Chuvash';
	$language['cy'] = 'Welsh';
	$language['da'] = 'Danish';
	$language['de'] = 'German';
	$language['dv'] = 'Divehi';
	$language['dz'] = 'Dzongkha';
	$language['ee'] = 'Ewe';
	$language['el'] = 'Greek';
	$language['en'] = 'English';
	$language['eo'] = 'Esperanto';
	$language['es'] = 'Spanish';
	$language['et'] = 'Estonian';
	$language['eu'] = 'Basque';
	$language['fa'] = 'Persian';
	$language['ff'] = 'Fulah';
	$language['fi'] = 'Finnish';
	$language['fj'] = 'Fijian';
	$language['fo'] = 'Faroese';
	$language['fr'] = 'French';
	$language['fy'] = 'Western Frisian';
	$language['ga'] = 'Irish';
	$language['gd'] = 'Scottish Gaelic';
	$language['gl'] = 'Galician';
	$language['gn'] = 'Guarani';
	$language['gu'] = 'Gujarati';
	$language['ha'] = 'Hausa';
	$language['he'] = 'Hebrew';
	$language['hi'] = 'Hindi';
	$language['hr'] = 'Croatian';
	$language['ht'] = 'Haitian';
	$language['hu'] = 'Hungarian';
	$language['hy'] = 'Armenian';
	$language['ia'] = 'Interlingua';
	$language['id'] = 'Indonesian';
	$language['ie'] = 'Interlingue';
	$language['ig'] = 'Igbo';
	$language['ii'] = 'Sichuan Yi';
	$language['is'] = 'Icelandic';
	$language['it'] = 'Italian';
	$language['iu'] = 'Inuktitut';
	$language['ja'] = 'Japanese';
	$language['jv'] = 'Javanese';
	$language['ka'] = 'Georgian';
	$language['kg'] = 'Kongo';
	$language['kk'] = 'Kazakh';
	$language['kl'] = 'Kalaallisut';
	$language['km'] = 'Khmer';
	$language['kn'] = 'Kannada';
	$language['ko'] = 'Korean';
	$language['ks'] = 'Kashmiri';
	$language['ku'] = 'Kurdish';
	$language['kv'] = 'Komi';
	$language['kw'] = 'Cornish';
	$language['ky'] = 'Kirghiz';
	$language['la'] = 'Latin';
	$language['lb'] = 'Luxembourgish';
	$language['lg'] = 'Ganda';
	$language['li'] = 'Limburgish';
	$language['ln'] = 'Lingala';
	$language['lo'] = 'Lao';
	$language['lt'] = 'Lithuanian';
	$language['lu'] = 'Luba-Katanga';
	$language['lv'] = 'Latvian';
	$language['mh'] = 'Marshallese';
	$language['mi'] = 'Māori';
	$language['mk'] = 'Macedonian';
	$language['ml'] = 'Malayalam';
	$language['mn'] = 'Mongolian';
	$language['mr'] = 'Marathi';
	$language['ms'] = 'Malay';
	$language['mt'] = 'Maltese';
	$language['my'] = 'Burmese';
	$language['nb'] = 'Norwegian Bokmål';
	$language['nd'] = 'North Ndebele';
	$language['ne'] = 'Nepali';
	$language['nl'] = 'Dutch';
	$language['nn'] = 'Norwegian Nynorsk';
	$language['no'] = 'Norwegian';
	$language['oc'] = 'Occitan';
	$language['oj'] = 'Ojibwa';
	$language['or'] = 'Oriya';
	$language['os'] = 'Ossetian';
	$language['pa'] = 'Panjabi';
	$language['pi'] = 'Pāli';
	$language['pl'] = 'Polish';
	$language['pt'] = 'Portuguese';
	$language['rm'] = 'Romansh';
	$language['ro'] = 'Romanian';
	$language['ru'] = 'Russian';
	$language['rw'] = 'Kinyarwanda';
	$language['sa'] = 'Sanskrit';
	$language['sh'] = 'Serbo-croate';
	$language['si'] = 'Sinhalese';
	$language['sk'] = 'Slovak';
	$language['sl'] = 'Slovene';
	$language['sm'] = 'Samoan';
	$language['sn'] = 'Shona';
	$language['so'] = 'Somali';
	$language['sq'] = 'Albanian';
	$language['sr'] = 'Serbian';
	$language['ss'] = 'Swati';
	$language['su'] = 'Sundanese';
	$language['sv'] = 'Swedish';
	$language['sw'] = 'Swahili';
	$language['ta'] = 'Tamil';
	$language['th'] = 'Thai';
	$language['ti'] = 'Tigrinya';
	$language['tk'] = 'Turkmen';
	$language['tl'] = 'Tagalog';
	$language['tn'] = 'Tswana';
	$language['to'] = 'Tonga';
	$language['tr'] = 'Turkish';
	$language['ts'] = 'Tsonga';
	$language['tt'] = 'Tatar';
	$language['tw'] = 'Twi';
	$language['ug'] = 'Uighur';
	$language['uk'] = 'Ukrainian';
	$language['uz'] = 'Uzbek';
	$language['vi'] = 'Viêt Namese';
	$language['yi'] = 'Yiddish';
	$language['yo'] = 'Yoruba';
	$language['za'] = 'Zhuang';
	$language['zh'] = 'Chinese';
	$language['zu'] = 'Zulu';

	$colOrder['language'] = '<select name="language" onchange="this.form.submit();">';
	$colOrder['language'] = $colOrder['language'] . PHP_EOL . '<option value="all" selected>All languages</option>';

	foreach ($language as $code => $languageName)
	{
		if ($_GET['language'] == $code)
		{
			$colOrder['language'] = $colOrder['language'] . PHP_EOL . '<option value="' . $code . '" selected>' . $code . ' &nbsp;&nbsp;' . $languageName . '</option>';
			$selectedLanguageCode = $code;
		}
		else
		{
			$colOrder['language'] = $colOrder['language'] . PHP_EOL . '<option value="' . $code . '">' . $code . ' &nbsp;&nbsp;' . $languageName . '</option>';
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
		$req = $bdd->prepare('SELECT language FROM users_table_serge WHERE id = :userId');
		$req->execute(array(
			'userId' => $_SESSION['id']));
			$packDetails = $req->fetch();
			$req->closeCursor();
	}

	# Language list
	$language['aa'] = 'Afar';
	$language['ab'] = 'Abkhazian';
	$language['af'] = 'Afrikaans';
	$language['ak'] = 'Akan';
	$language['am'] = 'Amharic';
	$language['ar'] = 'Arabic';
	$language['as'] = 'Assamese';
	$language['ay'] = 'Aymara';
	$language['az'] = 'Azerbaijani';
	$language['ba'] = 'Bashkir';
	$language['be'] = 'Belarusian';
	$language['bg'] = 'Bulgarian';
	$language['bm'] = 'Bambara';
	$language['bn'] = 'Bengali';
	$language['bo'] = 'Tibetan';
	$language['br'] = 'Breton';
	$language['bs'] = 'Bosnian';
	$language['ca'] = 'Catalan';
	$language['ce'] = 'Chechen';
	$language['co'] = 'Corsican';
	$language['cs'] = 'Czech';
	$language['cv'] = 'Chuvash';
	$language['cy'] = 'Welsh';
	$language['da'] = 'Danish';
	$language['de'] = 'German';
	$language['dv'] = 'Divehi';
	$language['dz'] = 'Dzongkha';
	$language['ee'] = 'Ewe';
	$language['el'] = 'Greek';
	$language['en'] = 'English';
	$language['eo'] = 'Esperanto';
	$language['es'] = 'Spanish';
	$language['et'] = 'Estonian';
	$language['eu'] = 'Basque';
	$language['fa'] = 'Persian';
	$language['ff'] = 'Fulah';
	$language['fi'] = 'Finnish';
	$language['fj'] = 'Fijian';
	$language['fo'] = 'Faroese';
	$language['fr'] = 'French';
	$language['fy'] = 'Western Frisian';
	$language['ga'] = 'Irish';
	$language['gd'] = 'Scottish Gaelic';
	$language['gl'] = 'Galician';
	$language['gn'] = 'Guarani';
	$language['gu'] = 'Gujarati';
	$language['ha'] = 'Hausa';
	$language['he'] = 'Hebrew';
	$language['hi'] = 'Hindi';
	$language['hr'] = 'Croatian';
	$language['ht'] = 'Haitian';
	$language['hu'] = 'Hungarian';
	$language['hy'] = 'Armenian';
	$language['ia'] = 'Interlingua';
	$language['id'] = 'Indonesian';
	$language['ie'] = 'Interlingue';
	$language['ig'] = 'Igbo';
	$language['ii'] = 'Sichuan Yi';
	$language['is'] = 'Icelandic';
	$language['it'] = 'Italian';
	$language['iu'] = 'Inuktitut';
	$language['ja'] = 'Japanese';
	$language['jv'] = 'Javanese';
	$language['ka'] = 'Georgian';
	$language['kg'] = 'Kongo';
	$language['kk'] = 'Kazakh';
	$language['kl'] = 'Kalaallisut';
	$language['km'] = 'Khmer';
	$language['kn'] = 'Kannada';
	$language['ko'] = 'Korean';
	$language['ks'] = 'Kashmiri';
	$language['ku'] = 'Kurdish';
	$language['kv'] = 'Komi';
	$language['kw'] = 'Cornish';
	$language['ky'] = 'Kirghiz';
	$language['la'] = 'Latin';
	$language['lb'] = 'Luxembourgish';
	$language['lg'] = 'Ganda';
	$language['li'] = 'Limburgish';
	$language['ln'] = 'Lingala';
	$language['lo'] = 'Lao';
	$language['lt'] = 'Lithuanian';
	$language['lu'] = 'Luba-Katanga';
	$language['lv'] = 'Latvian';
	$language['mh'] = 'Marshallese';
	$language['mi'] = 'Māori';
	$language['mk'] = 'Macedonian';
	$language['ml'] = 'Malayalam';
	$language['mn'] = 'Mongolian';
	$language['mr'] = 'Marathi';
	$language['ms'] = 'Malay';
	$language['mt'] = 'Maltese';
	$language['my'] = 'Burmese';
	$language['nb'] = 'Norwegian Bokmål';
	$language['nd'] = 'North Ndebele';
	$language['ne'] = 'Nepali';
	$language['nl'] = 'Dutch';
	$language['nn'] = 'Norwegian Nynorsk';
	$language['no'] = 'Norwegian';
	$language['oc'] = 'Occitan';
	$language['oj'] = 'Ojibwa';
	$language['or'] = 'Oriya';
	$language['os'] = 'Ossetian';
	$language['pa'] = 'Panjabi';
	$language['pi'] = 'Pāli';
	$language['pl'] = 'Polish';
	$language['pt'] = 'Portuguese';
	$language['rm'] = 'Romansh';
	$language['ro'] = 'Romanian';
	$language['ru'] = 'Russian';
	$language['rw'] = 'Kinyarwanda';
	$language['sa'] = 'Sanskrit';
	$language['sh'] = 'Serbo-croate';
	$language['si'] = 'Sinhalese';
	$language['sk'] = 'Slovak';
	$language['sl'] = 'Slovene';
	$language['sm'] = 'Samoan';
	$language['sn'] = 'Shona';
	$language['so'] = 'Somali';
	$language['sq'] = 'Albanian';
	$language['sr'] = 'Serbian';
	$language['ss'] = 'Swati';
	$language['su'] = 'Sundanese';
	$language['sv'] = 'Swedish';
	$language['sw'] = 'Swahili';
	$language['ta'] = 'Tamil';
	$language['th'] = 'Thai';
	$language['ti'] = 'Tigrinya';
	$language['tk'] = 'Turkmen';
	$language['tl'] = 'Tagalog';
	$language['tn'] = 'Tswana';
	$language['to'] = 'Tonga';
	$language['tr'] = 'Turkish';
	$language['ts'] = 'Tsonga';
	$language['tt'] = 'Tatar';
	$language['tw'] = 'Twi';
	$language['ug'] = 'Uighur';
	$language['uk'] = 'Ukrainian';
	$language['uz'] = 'Uzbek';
	$language['vi'] = 'Viêt Namese';
	$language['yi'] = 'Yiddish';
	$language['yo'] = 'Yoruba';
	$language['za'] = 'Zhuang';
	$language['zh'] = 'Chinese';
	$language['zu'] = 'Zulu';

	$userLang = strtolower($packDetails['language']);

	$selectLanguage = '<select class="shortSelect" name="language">' . PHP_EOL;

	foreach ($language as $code => $languageName)
	{
		if ($userLang == $code)
		{
			$selectLanguage = $selectLanguage . PHP_EOL . '<option value="' . $code . '" selected>' . $code . ' &nbsp;&nbsp;' . $languageName . '</option>';
			$selectedLanguageCode = $code;
		}
		else
		{
			$selectLanguage = $selectLanguage . PHP_EOL . '<option value="' . $code . '">' . $code . ' &nbsp;&nbsp;' . $languageName . '</option>';
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

}
include_once('view/nav/nav.php');

include_once('view/body/watchPack.php');

include_once('view/footer/footer.php');

?>
