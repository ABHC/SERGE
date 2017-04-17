<?php
# User need to be connected to access to this page
if (!isset($_SESSION['pseudo']))
{
	$_SESSION['redirectFrom'] = 'result';
	header('Location: connection');
}

//include_once('model/get_text.php');

$result  = 'active';
$wiki    = '';
$setting = '';
$colOrder['read'] = 'Read';
$colOrder['send'] = 'Send';
$colOrder['DESC'] = '';
$colOrder['title'] = '';
$colOrder['source'] = '';
$colOrder['date'] = '';
$recordLink = '';
$search = '';
$searchBoolean = '';
$ORDERBY = '';

$SELECTRESULT = '(SELECT id, title, link, send_status, read_status, `date`, id_source, keyword_id FROM result_news_serge WHERE owners LIKE :user';

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

include_once('model/readUserSettings.php');
$recordRead = $userSettings['record_read'];

if ($recordRead == 0)
{
	$pass   = $userSettings['password'];
	$id     = $userSettings['id'];
	$pseudo = $_SESSION['pseudo'];
	$salt   = 'blackSalt';
	$hash   =  hash('sha256', $salt . ':' . $pass . $pseudo . $id);
	$recordLink = 'redirect?id=' . $id . '&hash=' . $hash . '&link=';
}

include_once('model/readOwnerKeyword.php');

include_once('model/readResultKeywordName.php');

$resultBase  = 0;
$resultLimit = 500;

# Page number
if (isset($_GET['page']) AND preg_match("/^[0-9]+$/", htmlspecialchars($_GET['page'])))
{
	$page       = htmlspecialchars($_GET['page']);
	$page       = $page - 1;
	$resultBase = 15 * $page;
}

# Order results
if (isset($_GET['orderBy']))
{
	$ORDERBY = htmlspecialchars($_GET['orderBy']);
	if ($ORDERBY == 'title')
	{
		$colOrder['title'] = '▾';
		$colOrder['DESC'] = 'DESC';

		# WARNING sensitive variable [SQLI]
		$ORDERBY = 'ORDER BY title';
	}
	elseif ($ORDERBY == 'titleDESC')
	{
		$colOrder['title'] = '▴';
		$colOrder['DESC'] = '';

		# WARNING sensitive variable [SQLI]
		$ORDERBY = 'ORDER BY title DESC';
	}
	elseif ($ORDERBY == 'source')
	{
		$colOrder['source'] = '▾';
		$colOrder['DESC'] = 'DESC';

		# WARNING sensitive variable [SQLI]
		$ORDERBY = 'ORDER BY id_source';
	}
	elseif ($ORDERBY == 'sourceDESC')
	{
		$colOrder['source'] = '▴';
		$colOrder['DESC'] = '';

		# WARNING sensitive variable [SQLI]
		$ORDERBY = 'ORDER BY id_source DESC';
	}
	elseif ($ORDERBY == 'date')
	{
		$colOrder['date'] = '▾';
		$colOrder['DESC'] = 'DESC';

		# WARNING sensitive variable [SQLI]
		$ORDERBY = 'ORDER BY date';
	}
	elseif ($ORDERBY == 'dateDESC')
	{
		$colOrder['date'] = '▴';
		$colOrder['DESC'] = '';

		# WARNING sensitive variable [SQLI]
		$ORDERBY = 'ORDER BY date DESC';
	}
	elseif ($ORDERBY == 'read')
	{
		$colOrder['read'] = 'Read';
		$colOrder['DESC'] = 'DESC';

		# WARNING sensitive variable [SQLI]
		$ORDERBY = 'AND read_status LIKE \'%' . $_SESSION['id'] .'%\' ORDER BY date DESC';
	}
	elseif ($ORDERBY == 'readDESC')
	{
		$colOrder['read'] = 'Unread';
		$colOrder['DESC'] = '';

		# WARNING sensitive variable [SQLI]
		$ORDERBY = 'AND read_status NOT LIKE \'%' . $_SESSION['id'] . '%\' ORDER BY date DESC';
	}
	elseif ($ORDERBY == 'send')
	{
		$colOrder['send'] = 'Send';
		$colOrder['DESC'] = 'DESC';

		# WARNING sensitive variable [SQLI]
		$ORDERBY = 'AND send_status LIKE \'%' . $_SESSION['id'] .'%\' ORDER BY date DESC';
	}
	elseif ($ORDERBY == 'sendDESC')
	{
		$colOrder['send'] = 'Not send';
		$colOrder['DESC'] = '';

		# WARNING sensitive variable [SQLI]
		$ORDERBY = 'AND send_status NOT LIKE \'%' . $_SESSION['id'] .'%\' ORDER BY date DESC';
	}
	else
	{
		$colOrder['date'] = '▴';
		$colOrder['DESC'] = '';

		# WARNING sensitive variable [SQLI]
		$ORDERBY = 'ORDER BY date DESC';
	}
}
elseif (empty($_GET['search']))
{
	$colOrder['date'] = '▴';
	$colOrder['DESC'] = '';

	# WARNING sensitive variable [SQLI]
	$ORDERBY = 'ORDER BY date DESC';
}

# Search in result
if (!empty($_GET['search']))
{
	$search = htmlspecialchars($_GET['search']);
	$searchBoolean = preg_replace("/(^|\ )[a-zA-Z]{1,3}\ /", " ", $search);
	$searchBoolean = preg_replace("/\ /", "* ", $searchBoolean);
	$searchBoolean = preg_replace("/.$/", "$0*", $searchBoolean);
	$searchBoolean = preg_replace("/^..*.$/", "($0$1)", $searchBoolean);

	# WARNING sensitive variable [SQLI]
	$QUERYRESULT =
	$SELECTRESULT . ' AND MATCH(title, link) AGAINST (:search))
	 UNION ' .
	 $SELECTRESULT . ' AND MATCH(title, link) AGAINST (:searchBoolean IN BOOLEAN MODE)  LIMIT 10)
	 UNION ' .
	 $SELECTRESULT . ' AND MATCH(title, link) AGAINST (:search WITH QUERY EXPANSION) LIMIT 10)' .
	 $ORDERBY;
}
else
{
	# WARNING sensitive variable [SQLI]
	$QUERYRESULT = $SELECTRESULT . ' AND title NOT LIKE :search AND title NOT LIKE :searchBoolean) ' . $ORDERBY;
}

include_once('model/readOwnerResult.php');

include_once('view/nav/nav.php');

include_once('view/body/result.php');

include_once('view/footer/footer.php');

?>
