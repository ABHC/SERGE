<?php
include('controller/accessLimitedToSignInPeople.php');
include('model/get_text_var.php');
include('model/get_text.php');
include('model/read.php');
include('model/update.php');
include('controller/generateNonce.php');

# Data processing
$unsafeData = array();
$unsafeData = array_merge($unsafeData, array(array('resultType', 'type', 'GET', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('page', 'page', 'GET', '09')));
$unsafeData = array_merge($unsafeData, array(array('orderBy', 'orderBy', 'GET', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('search', 'search', 'GET', 'str')));
$unsafeData = array_merge($unsafeData, array(array('optionalCond', 'optionalCond', 'GET', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('deleteLink', 'deleteLink', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('export', 'export', 'POST', 'Az')));
foreach($_POST as $key => $val)
{
	if (preg_match("/^delete[0-9]+$/", $key, $name))
	{
		$unsafeData = array_merge($unsafeData, array(array(htmlspecialchars($name[0]), $name[0], 'POST', '09')));
	}
}

include('controller/dataProcessing.php');

# Nonce
$nonceTime = $_SERVER['REQUEST_TIME'];
$nonce     = getNonce($nonceTime);

# Initialization of variables
$resultTab          = 'active';
$wikiTab            = '';
$settingTab         = '';
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
$ORDERBY            = '';
$OPTIONALCOND       = '';
$limit              = 15;
$base               = 0;
$page               = 0;
$actualPageLink     = '';
$type               = 'news';
$newsActive         = 'class="active"';
$patentsActive      = '';
$sciencesActive     = '';
$tableName          = 'results_news_serge';
$tableNameQuery     = 'inquiries_news_serge';
$tableNameSource    = 'sources_news_serge';
$ownersColumn       = 'applicable_owners_sources';
$userId             = '|' . $_SESSION['id'] . ':';
$keywordQueryId     = 'inquiry_id';
$queryColumn        = 'keyword';
$specialColumn      = ', source_id, inquiry_id ';
$displayColumn      = var_get_t('title2News_table_results', $bdd);

# Select results type
if (!empty($data['resultType']))
{
	if ($data['resultType'] === 'sciences')
	{
		$type            = 'sciences';
		$sciencesActive  = 'class="active"';
		$patentsActive   = '';
		$newsActive      = '';
		$tableName       = 'results_science_serge';
		$tableNameQuery  = 'inquiries_sciences_serge';
		$tableNameSource = 'sources_sciences_serge';
		$ownersColumn    = 'owners';
		$userId          = ',' . $_SESSION['id'] . ',';
		$keywordQueryId  = 'inquiry_id';
		$queryColumn     = 'query_serge';
		$specialColumn   = ',inquiry_id, source_id ';
		$displayColumn   = var_get_t('Query', $bdd);
	}
	elseif ($data['resultType'] === 'patents')
	{
		$type            = 'patents';
		$patentsActive   = 'class="active"';
		$sciencesActive  = '';
		$newsActive      = '';
		$tableName       = 'results_patents_serge';
		$tableNameQuery  = 'inquiries_patents_serge';
		$tableNameSource = 'sources_patents_serge';
		$ownersColumn    = 'owners';
		$userId          = ',' . $_SESSION['id'] . ',';
		$keywordQueryId  = 'inquiry_id';
		$queryColumn     = 'query';
		$specialColumn   = ', inquiry_id, source_id ';
		$displayColumn   = var_get_t('Query', $bdd);
	}
}

# Delete results
if (!empty($data['deleteLink']))
{
	foreach($data as $key => $val)
	{
		$pureID = $_SESSION['id'];

		if (preg_match("/^delete[0-9]+$/", $key))
		{
			$checkCol     = array(array('id', '=', preg_replace("/^delete/", '', $key), ''));
			$ownersResult = read($tableName, 'owners', $checkCol, '', $bdd);

			if (!empty($ownersResult))
			{
				$updateCol = array(array('owners', preg_replace("/,$pureID,/", ',', $ownersResult[0]['owners'])));
				$checkCol  = array(array('id', '=', preg_replace("/^delete/", '', $key), ''));
				$execution = update($tableName, $updateCol, $checkCol, '', $bdd);
			}
		}
	}
}

# Record when a link is click
$checkCol   = array(array('users', '=', $_SESSION['pseudo'], ''));
$recordRead = read('users_table_serge', 'record_read, token', $checkCol, '', $bdd);

if ($recordRead[0]['record_read'] == 1)
{
	$recordLink = 'redirect?type=' . $type .'&token=' . $recordRead[0]['token'] . '&id=';
}

$checkCol = array(array($ownersColumn, 'l', '%' . $userId . '%', ''));
$readOwnerKeyword = read($tableNameQuery, 'id', $checkCol, '', $bdd);

# Order results
$colOrder['date'] = '▴';
$colOrder['DESC'] = '';
# WARNING sensitive variable [SQLI]
$ORDERBY = 'ORDER BY date DESC';
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
	if ($data['orderBy'] === 'title')
	{
		$colOrder['title'] = '▾';
		$colOrder['DESC']  = 'DESC';

		# WARNING sensitive variable [SQLI]
		$ORDERBY = 'ORDER BY title';
	}
	elseif ($data['orderBy'] === 'titleDESC')
	{
		$colOrder['title'] = '▴';
		$colOrder['DESC']  = '';

		# WARNING sensitive variable [SQLI]
		$ORDERBY = 'ORDER BY title DESC';
	}
	elseif ($data['orderBy'] === 'source')
	{
		$colOrder['source'] = '▾';
		$colOrder['DESC']   = 'DESC';

		# WARNING sensitive variable [SQLI]
		$ORDERBY = 'ORDER BY source_id';
	}
	elseif ($data['orderBy'] === 'sourceDESC')
	{
		$colOrder['source'] = '▴';
		$colOrder['DESC']   = '';

		# WARNING sensitive variable [SQLI]
		$ORDERBY = 'ORDER BY source_id DESC';
	}
	elseif ($data['orderBy'] === 'date')
	{
		$colOrder['date'] = '▾';
		$colOrder['DESC'] = 'DESC';

		# WARNING sensitive variable [SQLI]
		$ORDERBY = 'ORDER BY date';
	}
	elseif ($data['orderBy'] === 'dateDESC')
	{
		$colOrder['date'] = '▴';
		$colOrder['DESC'] = '';

		# WARNING sensitive variable [SQLI]
		$ORDERBY = 'ORDER BY date DESC';
	}
	$data['orderBy'] = '&orderBy=' . $data['orderBy'];
}

if (!empty($data['optionalCond']))
{
	if ($data['optionalCond'] === 'read')
	{
		$colOrder['read']   = var_get_t('title6Read_table_results', $bdd);
		$colOrder['OCDESC'] = 'DESC';

		# WARNING sensitive variable [SQLI]
		$OPTIONALCOND = ' AND read_status LIKE \'%' . $_SESSION['id'] .'%\'';
	}
	elseif ($data['optionalCond'] === 'readDESC')
	{
		$colOrder['read']   = var_get_t('title6Unread_table_results', $bdd);
		$colOrder['OCDESC'] = '';

		# WARNING sensitive variable [SQLI]
		$OPTIONALCOND = ' AND read_status NOT LIKE \'%' . $_SESSION['id'] . '%\'';
	}
	elseif ($data['optionalCond'] === 'send')
	{
		$colOrder['send']   = var_get_t('title5Send_table_results', $bdd);
		$colOrder['OCDESC'] = 'DESC';

		# WARNING sensitive variable [SQLI]
		$OPTIONALCOND = ' AND send_status LIKE \'%' . $_SESSION['id'] .'%\'';
	}
	elseif ($data['optionalCond'] === 'sendDESC')
	{
		$colOrder['send']   = var_get_t('title5NotSend_table_results', $bdd);
		$colOrder['OCDESC'] = '';

		# WARNING sensitive variable [SQLI]
		$OPTIONALCOND = ' AND send_status NOT LIKE \'%' . $_SESSION['id'] .'%\'';
	}

	$data['optionalCond'] = '&optionalCond=' . $data['optionalCond'];
}

# Export result
if (!empty($data['export']))
{
	if ($data['export'] == 'csv')
	{
		$ext = 'csv';
	}
	elseif ($data['export'] == 'txt')
	{
		$ext = 'txt';
	}
	elseif ($data['export'] == 'xml')
	{
		$ext = 'xml';
	}
	elseif ($data['export'] == 'sql')
	{
		$ext = 'sql';
	}

	# Warning sensitive variables [SQLI]
	$SELECTRESULT = '(SELECT title, link, `date` FROM ' . $tableName . ' WHERE owners LIKE :user';
	$WP = '';
	$arrayValues = array('user' => '%,' . $_SESSION['id'] . ',%');
	include('controller/searchEngine.php');

	include('model/readOwnerResult.php');

	$exportFile = fopen('export_' . $_SESSION['id'] . '.php', 'a');

	$codeExportPage = '
<?php
set_time_limit(25);
session_start();
include(\'controller/accessLimitedToSignInPeople.php\');

$ownerId = ' . $_SESSION['id'] . ';

if ($_SESSION[\'id\'] != $ownerId)
{
	die();
}

if ($_GET[\'ext\'] == \'csv\')
{
	$ext = \'.csv\';
	$typeMIME = \'text/csv\';
}
elseif ($_GET[\'ext\'] == \'txt\')
{
	$ext = \'.txt\';
	$typeMIME = \'text/plain\';
}
elseif ($_GET[\'ext\'] == \'xml\')
{
	$ext = \'.xml\';
	$typeMIME = \'application/xml\';
}
elseif ($_GET[\'ext\'] == \'sql\')
{
	$ext = \'.sql\';
	$typeMIME = \'text/sql\';
}

header("Content-Description: File Transfer");
header("Content-Type: $typeMIME\n");
header("Content-Transfer-Encoding: binary");
header("Content-disposition: attachment; filename=export_' . $_SESSION['id'] . '$ext");
header("Content-Length: ".filesize( \'export_' . $_SESSION['id'] . '.php\'));
header("Cache-Control: no-cache, must-revalidate");

unlink(__FILE__);?>';

	fputs($exportFile, $codeExportPage);
	$xml = new SimpleXMLElement('<root/>');

	foreach ($readOwnerResults as $lineResult)
	{
		if ($data['export'] == 'csv')
		{
			unset($lineResult[0]);
			unset($lineResult[1]);
			unset($lineResult[2]);
			fputcsv($exportFile, $lineResult);
		}
		elseif ($data['export'] == 'txt')
		{
			unset($lineResult[0]);
			unset($lineResult[1]);
			unset($lineResult[2]);
			fputcsv($exportFile, $lineResult, ' ');
		}
		elseif ($data['export'] == 'xml')
		{
			unset($lineResult[0]);
			unset($lineResult[1]);
			unset($lineResult[2]);
			$lineResult = array_flip($lineResult);
			array_walk_recursive($lineResult, array($xml, 'addChild'));
		}
	}

	if ($data['export'] == 'sql')
	{
		$resultSQL = 'INSERT INTO `results_news_serge` (`title`, `link`, `date`) VALUES' . PHP_EOL;
		foreach ($readOwnerResults as $lineResult)
		{
			$resultSQL .= '(\'' . addslashes($lineResult['title']) . '\',\'' . addslashes($lineResult['link']) . '\',\'' . addslashes($lineResult['date']) . '\'),' . PHP_EOL;
		}
		$resultSQL = substr($resultSQL, 0, -2);
		$resultSQL .= ';';
		fputs($exportFile, $resultSQL);
	}

	if ($data['export'] == 'xml')
	{
		fputs($exportFile, $xml->asXML());
	}

	fclose($exportFile);

	$filename = 'export_' . $_SESSION['id'] . '?ext=' . $ext;
	header("Location: $filename");
	die();
}
else
{
	# Warning sensitive variables [SQLI]
	$SELECTRESULT = '(SELECT id, title, link, send_status, read_status, `date`' . $specialColumn . 'FROM ' . $tableName . ' WHERE owners LIKE :user';
	$WP = '';
	$arrayValues = array('user' => '%,' . $_SESSION['id'] . ',%');
	include('controller/searchEngine.php');

	include('model/readOwnerResult.php');
}

# Display read status column

## Read if option read status is activated
$checkCol         = array(array('id', '=', $_SESSION['id']));
$readStatusColumn = read('users_table_serge', 'record_read', $checkCol, '', $bdd);

$readStatusColumn = '';
if ($optionReadStatus)
{
	$readStatusColumn = '<th><a href="?optionalCond=read' . $colOrder['OCDESC'] . $searchSort . $data['orderBy'] . '&type=' . $type . '">' . $colOrder['read'] . '</a></th>';
}


# Page number
if (!empty($data['page']))
{
	$numberResults = new ArrayIterator($readOwnerResults);
	$limit  = 15;
	$nbPage = ceil(count($numberResults) / $limit);

	if ($data['page'] > $nbPage || $data['page'] < 1)
	{
		$data['page'] = 1;
	}

	$actualPageLink = '&page=' . $data['page'];
	$page           = $data['page'] - 1;
	$base           = $limit * $page;
}

include('view/nav/nav.php');

include('view/body/result.php');

include('view/footer/footer.php');

?>
