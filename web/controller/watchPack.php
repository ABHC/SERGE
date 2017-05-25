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

# Ttype
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
		$_SESSION['type'] = 'add';
	}
}
else
{
	$type           = 'add';
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
	$_SESSION['type'] = 'add';
}

include_once('view/nav/nav.php');

include_once('view/body/watchPack.php');

include_once('view/footer/footer.php');

?>
