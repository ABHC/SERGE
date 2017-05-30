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
		if ($_POST['language'] == $code)
		{
			$colOrder['language'] = $colOrder['language'] . PHP_EOL . '<option value="' . $code . '" selected>' . $code . ' &nbsp;&nbsp;' . $languageName . '</option>';
		}
		else
		{
			$colOrder['language'] = $colOrder['language'] . PHP_EOL . '<option value="' . $code . '">' . $code . ' &nbsp;&nbsp;' . $languageName . '</option>';
		}
	}

	$colOrder['language'] = $colOrder['language'] . PHP_EOL . '</select>';
}

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
}

# Read watchPack
$req = $bdd->prepare("SELECT id, name, description, author, users, category, language, update_date, rating, ((LENGTH(`rating`) - LENGTH(REPLACE(`rating`, ',', '')))-1) AS `NumberOfStars` FROM `watch_pack_serge` WHERE 1 ORDER BY `NumberOfStars` DESC;");
$req->execute();
	$watchPacks = $req->fetchAll();
	$req->closeCursor();

include_once('view/nav/nav.php');

include_once('view/body/watchPack.php');

include_once('view/footer/footer.php');

?>
