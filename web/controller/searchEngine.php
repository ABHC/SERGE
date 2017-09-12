<?php
# Search in result
include("model/updateSearchIndex$WP.php");
if (!empty($data['search']))
{
	$search        = $data['search'];
	$searchBoolean = preg_replace("/(^|\ )[a-zA-Z]{1,3}(\ |$)/", ' ', $search);
	$searchBoolean = preg_replace("/[^ ]+/", '\'$0\'', $searchBoolean);
	$searchBoolean = preg_replace("/[^ ]+'/", "$0*", $searchBoolean);
	$searchBoolean = preg_replace("/^..*.$/", "($0$1)", $searchBoolean);

	# Search with soundex
	$searchArray = explode(' ', $search);
	$searchSOUNDEX = '';
	foreach($searchArray as $word)
	{
			$searchSOUNDEX = $searchSOUNDEX . ' ' . soundex($word);
	}

	# WARNING sensitive variable [SQLI]
	$SELECTRESULT = $SELECTRESULT . $OPTIONALCOND;
	$QUERYRESULT =
	 $SELECTRESULT . ' AND MATCH(search_index) AGAINST (:search))
	 UNION ' .
	 $SELECTRESULT . ' AND MATCH(search_index) AGAINST (:searchBoolean IN BOOLEAN MODE)  LIMIT 15)
	 UNION ' .
	 $SELECTRESULT . ' AND MATCH(search_index) AGAINST (:searchSOUNDEX))
	 UNION ' .
	 $SELECTRESULT . ' AND MATCH(search_index) AGAINST (:search WITH QUERY EXPANSION) LIMIT 5) ' .
	 $ORDERBY;

	$arrayValues = array_merge($arrayValues, array(
		'search' => $search,
		'searchBoolean' => $searchBoolean,
		'searchSOUNDEX' => $searchSOUNDEX));

	$searchSort = '&search=' . $search;
}
else
{
	$search = '';
	$searchBoolean = '';
	$searchSOUNDEX = '';

	# WARNING sensitive variable [SQLI]
	$SELECTRESULT = $SELECTRESULT . $OPTIONALCOND;
	$QUERYRESULT  = $SELECTRESULT . ') ' . $ORDERBY;
}
?>
