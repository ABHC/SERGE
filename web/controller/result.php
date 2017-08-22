<?php
include_once('controller/accessLimitedToSignInPeople.php');
include_once('model/get_text_var.php');
include_once('model/get_text.php');
include_once('model/read.php');
include_once('model/update.php');

# Initialization of variables
$result             = 'active';
$wiki               = '';
$setting            = '';
$colOrder['read']   = var_get_t('title6Read_table_results', $bdd);
$colOrder['send']   = var_get_t('title5Send_table_results', $bdd);
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
		$sciencesActive = '';
		$patentsActive  = '';
		$tableName      = 'result_news_serge';
		$tableNameQuery = 'keyword_news_serge';
		$tableNameSource = 'rss_serge';
		$ownersColumn   = 'applicable_owners_sources';
		$userId        = '|' . $_SESSION['id'] . ':';
		$keywordQueryId = 'keyword_id';
		$queryColumn    = 'keyword';
		$specialColumn  = ', id_source, keyword_id ';
		$displayColumn  = var_get_t('title2News_table_results', $bdd);
	}
	elseif ($type == 'sciences')
	{
		$type           = 'sciences';
		$sciencesActive = 'class="active"';
		$patentsActive  = '';
		$newsActive     = '';
		$tableName      = 'result_science_serge';
		$tableNameQuery = 'queries_science_serge';
		$tableNameSource = 'science_sources_serge';
		$ownersColumn   = 'owners';
		$userId         = ',' . $_SESSION['id'] . ',';
		$keywordQueryId = 'query_id';
		$queryColumn    = 'query_arxiv';
		$specialColumn  = ',query_id, id_source ';
		$displayColumn  = var_get_t('title2Science_table_results', $bdd);
	}
	elseif ($type == 'patents')
	{
		$type           = 'patents';
		$patentsActive  = 'class="active"';
		$sciencesActive = '';
		$newsActive     = '';
		$tableName      = 'result_patents_serge';
		$tableNameQuery = 'queries_wipo_serge';
		$tableNameSource = 'patents_sources_serge';
		$ownersColumn   = 'owners';
		$userId        = ',' . $_SESSION['id'] . ',';
		$keywordQueryId = 'id_query_wipo';
		$queryColumn    = 'query';
		$specialColumn  = ', id_query_wipo, id_source ';
		$displayColumn  = var_get_t('title2Patents_table_results', $bdd);
	}
	else
	{
		$type           = 'news';
		$newsActive     = 'class="active"';
		$patentsActive  = '';
		$sciencesActive = '';
		$tableName      = 'result_news_serge';
		$tableNameQuery = 'keyword_news_serge';
		$tableNameSource = 'rss_serge';
		$ownersColumn   = 'applicable_owners_sources';
		$userId         = '|' . $_SESSION['id'] . ':';
		$keywordQueryId = 'keyword_id';
		$queryColumn    = 'keyword';
		$specialColumn  = ', id_source, keyword_id ';
		$displayColumn  = var_get_t('title2News_table_results', $bdd);
	}
}
else
{
	$type           = 'news';
	$newsActive     = 'class="active"';
	$patentsActive  = '';
	$sciencesActive = '';
	$tableName      = 'result_news_serge';
	$tableNameQuery = 'keyword_news_serge';
	$tableNameSource = 'rss_serge';
	$ownersColumn   = 'applicable_owners_sources';
	$userId         = '|' . $_SESSION['id'] . ':';
	$keywordQueryId = 'keyword_id';
	$queryColumn    = 'keyword';
	$specialColumn  = ', id_source, keyword_id ';
	$displayColumn  = var_get_t('title2News_table_results', $bdd);
}

# Warning sensitive variables [SQLI]
$SELECTRESULT = '(SELECT id, title, link, send_status, read_status, `date`' . $specialColumn . 'FROM ' . $tableName . ' WHERE owners LIKE :user';

# Delete results
#include_once('model/delResult.php');

if (isset($_POST['deleteLink']))
{
	foreach($_POST as $key => $val)
	{
		$key = htmlspecialchars($key);
		#$val = htmlspecialchars($val);
		$pureID = $_SESSION['id'];

		if (preg_match("/^delete[0-9]+$/", $key))
		{
			$checkCol = array(array("id", "=", preg_replace("/^delete/", "", $key), ""));
			$ownersResult = read('result_news_serge', 'owners', $checkCol, '', $bdd);

			if (!empty($ownersResult))
			{
				$updateCol = array(array("owners", preg_replace("/,$pureID,/", ',', $ownersResult[0]['owners'])));
				$checkCol = array(array("id", "=", preg_replace("/^delete/", "", $key), ""));
				$execution = update('result_news_serge', $updateCol, $checkCol, '', $bdd);
			}
		}
	}
}

# Record when a link is click
#include_once('model/readUserSettings.php');
$checkCol = array(array("users", "=", $_SESSION['pseudo'], ""));
$recordRead = read('users_table_serge', 'id, password, record_read', $checkCol, '', $bdd);

if ($recordRead[0]['record_read'] == 1)
{
	$pass       = $recordRead[0]['password'];
	$id         = $recordRead[0]['id'];
	$pseudo     = $_SESSION['pseudo'];
	$salt       = 'blackSalt';
	$hash       =  hash('sha256', $salt . ':' . $pass . $pseudo . $id);
	$recordLink = 'redirect?id=' . $id . '&type=' . $type .'&hash=' . $hash . '&link=';
}

#include_once('model/readOwnerKeyword.php');
$checkCol = array(array($ownersColumn, "l", '%' . $userId . '%', ""));
$readOwnerKeyword = read($tableNameQuery, 'id', $checkCol, '', $bdd);

#include_once('model/readResultKeywordName.php');

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
		$colOrder['read'] = var_get_t('title6Read_table_results', $bdd);
		$colOrder['OCDESC'] = 'DESC';

		# WARNING sensitive variable [SQLI]
		$OPTIONALCOND = ' AND read_status LIKE \'%' . $_SESSION['id'] .'%\'';
	}
	elseif ($optionalCond == 'readDESC')
	{
		$colOrder['read'] = var_get_t('title6Unread_table_results', $bdd);
		$colOrder['OCDESC'] = '';

		# WARNING sensitive variable [SQLI]
		$OPTIONALCOND = ' AND read_status NOT LIKE \'%' . $_SESSION['id'] . '%\'';
	}
	elseif ($optionalCond == 'send')
	{
		$colOrder['send'] = var_get_t('title5Send_table_results', $bdd);
		$colOrder['OCDESC'] = 'DESC';

		# WARNING sensitive variable [SQLI]
		$OPTIONALCOND = ' AND send_status LIKE \'%' . $_SESSION['id'] .'%\'';
	}
	elseif ($optionalCond == 'sendDESC')
	{
		$colOrder['send'] = var_get_t('title5NotSend_table_results', $bdd);
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
