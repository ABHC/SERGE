<?php
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
	}
	else
	{
		# WARNING sensitive variable [SQLI]
		$CHECKLINK = '(SELECT id, title, link, send_status, read_status, `date`, id_source, keyword_id FROM result_news_serge WHERE id = 0)';
	}


	# Search in keyword
	include_once('model/readKeywordId.php');
	$OR                = '';
	$SEARCHKEYWORD     = '';
	$searchInKeyword   = preg_replace("/(^|\ )[a-zA-Z]{1,3}(\ |$)/", " ", $search);
	$searchKeywordList = explode(" ", $searchInKeyword);

	foreach ($searchKeywordList as $searchKeyword)
	{
		//unset($searchOwnerKeyword);
		if (strlen($searchKeyword) > 3)
		{
			$userId        = '%|' . $_SESSION['id'] . ':%';
			$searchKeyword = '%' . $searchKeyword . '%';

			$searchOwnerKeyword = readKeywordId($userId, $searchKeyword, $bdd);

			if (!empty($searchOwnerKeyword))
			{
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
	$QUERYRESULT  = $SELECTRESULT . ' AND title NOT LIKE :search AND title NOT LIKE :searchBoolean) ' . $ORDERBY;
}
?>
