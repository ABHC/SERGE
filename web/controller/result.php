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
$searchInLink = '';
$searchSort = '';
$orderBy = '';
$ORDERBY = '';
$OPTIONALCOND = '';
$limit = 15;
$base = 0;
$page = 0;

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
	$pass       = $userSettings['password'];
	$id         = $userSettings['id'];
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
	$page           = htmlspecialchars($_GET['page']);
	$page           = $page - 1;
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

# Search in result
if (!empty($_GET['search']))
{
	$search        = htmlspecialchars($_GET['search']);
	$searchBoolean = preg_replace("/(^|\ )[a-zA-Z]{1,3}(\ |$)/", " ", $search);
	$searchBoolean = preg_replace("/[^ ]+/", '\'$0\'', $searchBoolean);
	$searchBoolean = preg_replace("/[^ ]+'/", "$0*", $searchBoolean);
	$searchBoolean = preg_replace("/^..*.$/", "($0$1)", $searchBoolean);

	# Search in link
	$SEARCHINLINK     = '';
	$searchInLink     = preg_replace("/(^|\ )[a-zA-Z]{1,2}(\ |$)/", " ", $search);
	$searchInLinkList = explode(" ", $searchInLink);
	$OR = '';

	foreach ($searchInLinkList as $searchInLink)
	{
		$searchInLink = preg_replace("/[^a-zA-Z0-9]+/", "%", $searchInLink);
		if (strlen($searchInLink) > 2)
		{
			$searchInLink = '%' . $searchInLink . '%';

			# WARNING sensitive variable [SQLI]
			$SEARCHINLINK = $SEARCHINLINK . $OR . 'LOWER(link) LIKE LOWER("' . $searchInLink . '")';
			$OR = ' OR ';
		}
	}

	if (!empty($SEARCHINLINK))
	{
		# WARNING sensitive variable [SQLI]
		$CHECKLINK = '(SELECT id, title, link, send_status, read_status, `date`, id_source, keyword_id FROM result_news_serge WHERE owners LIKE :user AND (' . $SEARCHINLINK . '))';
		echo $CHECKLINK;
	}
	else
	{
		# WARNING sensitive variable [SQLI]
		$CHECKLINK = '(SELECT id, title, link, send_status, read_status, `date`, id_source, keyword_id FROM result_news_serge WHERE id = 0)';
	}


	# Search in keyword
	$SEARCHKEYWORD   = '';
	$searchInKeyword = preg_replace("/(^|\ )[a-zA-Z]{1,3}(\ |$)/", " ", $search);
	$searchKeywordList = explode(" ", $searchInKeyword);

	foreach ($searchKeywordList as $searchKeyword)
	{
		if (strlen($searchKeyword) > 3)
		{
			$searchKeyword = '%' . $searchKeyword . '%';
			$userId = '%|' . $_SESSION['id'] . ':%';
			$reqSearchOwnerKeyword = $bdd->prepare('SELECT id FROM keyword_news_serge WHERE applicable_owners_sources LIKE :user AND LOWER(keyword) LIKE LOWER(:searchKeyword)');
			$reqSearchOwnerKeyword->execute(array(
				'user' => $userId,
				'searchKeyword' => $searchKeyword));
				$searchOwnerKeyword = $reqSearchOwnerKeyword->fetchAll();
				$reqSearchOwnerKeyword->closeCursor();

			if (!empty($searchOwnerKeyword))
			{
				$OR = '';
				foreach ($searchOwnerKeyword as $searchKeyword)
				{
					$keywordIdSearch = '\'%,' . $searchKeyword['id'] . ',%\'';

					# WARNING sensitive variable [SQLI]
					$SEARCHKEYWORD = $SEARCHKEYWORD . $OR . 'keyword_id LIKE ' . $keywordIdSearch;
					$OR = ' OR ';
				}
			}
		}
	}

	if (!empty($SEARCHKEYWORD))
	{
		# WARNING sensitive variable [SQLI]
		$CHECKKEYWORD = '(SELECT id, title, link, send_status, read_status, `date`, id_source, keyword_id FROM result_news_serge WHERE owners LIKE :user AND (' . $SEARCHKEYWORD . '))';
	}
	else
	{
		# WARNING sensitive variable [SQLI]
		$CHECKKEYWORD = '(SELECT id, title, link, send_status, read_status, `date`, id_source, keyword_id FROM result_news_serge WHERE id = 0)';
	}

	# WARNING sensitive variable [SQLI]
	$SELECTRESULT = $SELECTRESULT . $OPTIONALCOND;
	$QUERYRESULT =
	 $SELECTRESULT . ' AND MATCH(title) AGAINST (":search"))
	 UNION ' .
	 $SELECTRESULT . ' AND MATCH(title) AGAINST (":searchBoolean" IN BOOLEAN MODE)  LIMIT 15)
	 UNION ' .
	 $CHECKKEYWORD . '
	 UNION ' .
	 $CHECKLINK    . '
	 UNION ' .
	 $SELECTRESULT . ' AND MATCH(title) AGAINST (":search" WITH QUERY EXPANSION) LIMIT 3)' .
	 $ORDERBY;

	$searchSort = '&search=' . $search;
}
else
{
	# WARNING sensitive variable [SQLI]
	$SELECTRESULT = $SELECTRESULT . $OPTIONALCOND;
	$QUERYRESULT = $SELECTRESULT . ' AND title NOT LIKE :search AND title NOT LIKE :searchBoolean) ' . $ORDERBY;
}

include_once('model/readOwnerResult.php');

include_once('view/nav/nav.php');

include_once('view/body/result.php');

include_once('view/footer/footer.php');

?>
