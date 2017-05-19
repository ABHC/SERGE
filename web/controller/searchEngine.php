<?php
# Search in result
if (!empty($_GET['search']))
{
	include_once('model/updateSearchIndex.php');

	$search        = htmlspecialchars($_GET['search']);
	$searchBoolean = preg_replace("/(^|\ )[a-zA-Z]{1,3}(\ |$)/", " ", $search);
	$searchBoolean = preg_replace("/[^ ]+/", '\'$0\'', $searchBoolean);
	$searchBoolean = preg_replace("/[^ ]+'/", "$0*", $searchBoolean);
	$searchBoolean = preg_replace("/^..*.$/", "($0$1)", $searchBoolean);

	# Search with soundex
	$searchArray = explode(" ", $search);
	$searchSOUNDEX = '';
	foreach($searchArray as $word)
	{
			$searchSOUNDEX = $searchSOUNDEX . ' ' . soundex($word);
	}


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
		$CHECKLINK = '(SELECT id, title, link, send_status, read_status, `date`' . $specialColumn . 'FROM ' . $tableName . ' WHERE owners LIKE :user AND (' . $SEARCHINLINK . '))';
	}
	else
	{
		# WARNING sensitive variable [SQLI]
		$CHECKLINK = '(SELECT id, title, link, send_status, read_status, `date`' . $specialColumn . 'FROM ' . $tableName . ' WHERE id = 0)';
	}

	# WARNING sensitive variable [SQLI]
	$SELECTRESULT = $SELECTRESULT . $OPTIONALCOND;
	$QUERYRESULT =
	 $SELECTRESULT . ' AND MATCH(search_index) AGAINST (:search))
	 UNION ' .
	 $SELECTRESULT . ' AND MATCH(search_index) AGAINST (:searchBoolean IN BOOLEAN MODE)  LIMIT 15)
	 UNION ' .
	 $SELECTRESULT . ' AND match(search_index) AGAINST (:searchSOUNDEX))
	 UNION ' .
	 $CHECKLINK    . '
	 UNION ' .
	 $SELECTRESULT . ' AND MATCH(search_index) AGAINST (:search WITH QUERY EXPANSION) LIMIT 15) ' .
	 $ORDERBY;

	$searchSort = '&search=' . $search;
}
else
{
	$search = '';
	$searchBoolean = '';
	$searchSOUNDEX = '';

	# WARNING sensitive variable [SQLI]
	$SELECTRESULT = $SELECTRESULT . $OPTIONALCOND;
	$QUERYRESULT  = $SELECTRESULT . ' AND title NOT LIKE :search AND title NOT LIKE :searchBoolean AND title NOT LIKE :searchSOUNDEX) ' . $ORDERBY;
}
?>
