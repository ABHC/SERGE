<?php
function readResultKeyword($keywordIds, $readOwnerKeyword, $bdd, $queryColumn, $tableNameQuery)
{
	$breaker = FALSE;
	preg_match_all("/[0-9]+,/", $keywordIds, $keywordIds_array);
	foreach ($readOwnerKeyword as $OwnerKeyword)
	{
		foreach ($keywordIds_array[0] as $id)
		{
			$idK = preg_replace("/,/", "", $id);

			if ($idK == $OwnerKeyword['id'])
			{
				$keywordId = $idK;
				$breaker   = TRUE;
			}
		}

		if ($breaker)
		{
			break;
		}
	}

	$reqKeywordResults = $bdd->prepare('SELECT ' . $queryColumn . ' FROM ' . $tableNameQuery . ' WHERE id = :id');
	$reqKeywordResults->execute(array(
		'id' => $keywordId));
		$keyword = $reqKeywordResults->fetch();
		$reqKeywordResults->closeCursor();

	return $keyword[$queryColumn];
}
?>
