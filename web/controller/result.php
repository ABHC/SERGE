<?php
include_once('controller/accessLimitedToSignInPeople.php');

//include_once('model/get_text.php');

# Initialization of variables
$result             = 'active';
$wiki               = '';
$setting            = '';
$colOrder['read']   = 'Read';
$colOrder['send']   = 'Send';
$colOrder['DESC']   = '';
$colOrder['title']  = '';
$colOrder['source'] = '';
$colOrder['date']   = '';
$colOrder['OCDESC'] = '';
$recordLink         = '';
$search             = '';
$searchBoolean      = '';
$searchInLink       = '';
$searchSort         = '';
$orderBy            = '';
$ORDERBY            = '';
$OPTIONALCOND       = '';
$limit              = 15;
$base               = 0;
$page               = 0;
$actualPageLink     = '';


# Select results type
if (!empty($_GET['type']))
{
	$type = htmlspecialchars($_GET['type']);

	if ($type == 'news')
	{
		$type           = 'news';
		$newsActive     = 'class="active"';
		$tableName      = 'result_news_serge';
		$tableNameQuery = 'keyword_news_serge';
		$ownersColumn   = 'applicable_owners_sources';
		$userId        = '|' . $_SESSION['id'] . ':';
		$keywordQueryId = 'keyword_id';
		$queryColumn    = 'keyword';
		$specialColumn  = ', id_source, keyword_id ';
		$displayColumn  = 'Keyword';
	}
	elseif ($type == 'sciences')
	{
		$type           = 'sciences';
		$sciencesActive = 'class="active"';
		$tableName      = 'result_science_serge';
		$tableNameQuery = 'queries_science_serge';
		$ownersColumn   = 'owners';
		$userId        = ',' . $_SESSION['id'] . ',';
		$keywordQueryId = 'query_id';
		$queryColumn    = 'query_arxiv';
		$specialColumn  = ',query_id, id_source ';
		$displayColumn  = 'Query';
	}
	elseif ($type == 'patents')
	{
		$type           = 'patents';
		$patentsActive  = 'class="active"';
		$tableName      = 'result_patents_serge';
		$tableNameQuery = 'queries_wipo_serge';
		$ownersColumn   = 'owners';
		$userId        = ',' . $_SESSION['id'] . ',';
		$keywordQueryId = 'query_id';
		$queryColumn    = 'query';
		$displayColumn  = 'Query';
	}
	else
	{
		$type           = 'news';
		$newsActive     = 'class="active"';
		$tableName      = 'result_news_serge';
		$tableNameQuery = 'keyword_news_serge';
		$ownersColumn   = 'applicable_owners_sources';
		$userId         = '|' . $_SESSION['id'] . ':';
		$keywordQueryId = 'keyword_id';
		$queryColumn    = 'keyword';
		$specialColumn  = ', id_source, keyword_id ';
		$displayColumn  = 'Keyword';
	}
}
else
{
	$type           = 'news';
	$newsActive     = 'class="active"';
	$tableName      = 'result_news_serge';
	$tableNameQuery = 'keyword_news_serge';
	$ownersColumn   = 'applicable_owners_sources';
	$userId         = '|' . $_SESSION['id'] . ':';
	$keywordQueryId = 'keyword_id';
	$queryColumn    = 'keyword';
	$specialColumn  = ', id_source, keyword_id ';
	$displayColumn  = 'Keyword';
}

# Warning sensitive variables [SQLI]
$SELECTRESULT = '(SELECT id, title, link, send_status, read_status, `date`' . $specialColumn . 'FROM ' . $tableName . ' WHERE owners LIKE :user';

# Delete results
include_once('model/delResult.php');

if (isset($_POST['deleteLink']))
{
	foreach($_POST as $key => $val)
	{
		$key = htmlspecialchars($key);
		$val = htmlspecialchars($val);

		if (preg_match("/^delete[0-9]+$/", $key))
		{
				$resultId = preg_replace("/^delete/", "", $key);
				deleteLink($bdd, $resultId);
		}
	}
}

# Record when a link is click
include_once('model/readUserSettings.php');

$recordRead = readUserSettings('id, password, record_read', $bdd);

if ($recordRead['record_read'] == 1)
{
	$pass       = $recordRead['password'];
	$id         = $recordRead['id'];
	$pseudo     = $_SESSION['pseudo'];
	$salt       = 'blackSalt';
	$hash       =  hash('sha256', $salt . ':' . $pass . $pseudo . $id);
	$recordLink = 'redirect?id=' . $id . '&hash=' . $hash . '&link=';
}

include_once('model/readOwnerKeyword.php');

include_once('model/readResultKeywordName.php');

# Page number
if (isset($_GET['page']) AND preg_match("/^[0-9]+$/", htmlspecialchars($_GET['page'])))
{
	$actualPageLink = '&page=' . htmlspecialchars($_GET['page']);
	$limit          = 15;
	$page           = $_GET['page'] - 1;
	$base           = $limit * $page;
}

# Order results
if (!empty($_GET['orderBy']))
{
	$orderBy = htmlspecialchars($_GET['orderBy']);
	if ($orderBy == 'title')
	{
		$colOrder['title'] = '▾';
		$colOrder['DESC'] = 'DESC';

		# WARNING sensitive variable [SQLI]
		$ORDERBY = 'ORDER BY title';
	}
	elseif ($orderBy == 'titleDESC')
	{
		$colOrder['title'] = '▴';
		$colOrder['DESC'] = '';

		# WARNING sensitive variable [SQLI]
		$ORDERBY = 'ORDER BY title DESC';
	}
	elseif ($orderBy == 'source')
	{
		$colOrder['source'] = '▾';
		$colOrder['DESC'] = 'DESC';

		# WARNING sensitive variable [SQLI]
		$ORDERBY = 'ORDER BY id_source';
	}
	elseif ($orderBy == 'sourceDESC')
	{
		$colOrder['source'] = '▴';
		$colOrder['DESC'] = '';

		# WARNING sensitive variable [SQLI]
		$ORDERBY = 'ORDER BY id_source DESC';
	}
	elseif ($orderBy == 'date')
	{
		$colOrder['date'] = '▾';
		$colOrder['DESC'] = 'DESC';

		# WARNING sensitive variable [SQLI]
		$ORDERBY = 'ORDER BY date';
	}
	elseif ($orderBy == 'dateDESC')
	{
		$colOrder['date'] = '▴';
		$colOrder['DESC'] = '';

		# WARNING sensitive variable [SQLI]
		$ORDERBY = 'ORDER BY date DESC';
	}
	else
	{
		$colOrder['date'] = '▴';
		$colOrder['DESC'] = '';

		# WARNING sensitive variable [SQLI]
		$ORDERBY = 'ORDER BY date DESC';
	}

	$orderBy = '&orderBy=' . $orderBy;
}
elseif (empty($_GET['search']))
{
	$colOrder['date'] = '▴';
	$colOrder['DESC'] = '';

	# WARNING sensitive variable [SQLI]
	$ORDERBY = 'ORDER BY date DESC';
}

if (!empty($_GET['optionalCond']))
{
	$optionalCond = htmlspecialchars($_GET['optionalCond']);
	if ($optionalCond == 'read')
	{
		$colOrder['read'] = 'Read';
		$colOrder['OCDESC'] = 'DESC';

		# WARNING sensitive variable [SQLI]
		$OPTIONALCOND = ' AND read_status LIKE \'%' . $_SESSION['id'] .'%\'';
	}
	elseif ($optionalCond == 'readDESC')
	{
		$colOrder['read'] = 'Unread';
		$colOrder['OCDESC'] = '';

		# WARNING sensitive variable [SQLI]
		$OPTIONALCOND = ' AND read_status NOT LIKE \'%' . $_SESSION['id'] . '%\'';
	}
	elseif ($optionalCond == 'send')
	{
		$colOrder['send'] = 'Send';
		$colOrder['OCDESC'] = 'DESC';

		# WARNING sensitive variable [SQLI]
		$OPTIONALCOND = ' AND send_status LIKE \'%' . $_SESSION['id'] .'%\'';
	}
	elseif ($optionalCond == 'sendDESC')
	{
		$colOrder['send'] = 'Not send';
		$colOrder['OCDESC'] = '';

		# WARNING sensitive variable [SQLI]
		$OPTIONALCOND = ' AND send_status NOT LIKE \'%' . $_SESSION['id'] .'%\'';
	}

	$optionalCond = '&optionalCond=' . $optionalCond;
}
else
{
	$optionalCond = '';
}

include_once('controller/searchEngine.php');

include_once('model/readOwnerResult.php');

include_once('view/nav/nav.php');

include_once('view/body/result.php');

include_once('view/footer/footer.php');

?>
