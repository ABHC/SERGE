<?php
function del_accent(string $str, $encoding='utf-8')
{
	return preg_replace('#&[^;]+;#', '',
				 preg_replace('#&([A-za-z]{2})(?:lig);#', '\1',
				 preg_replace('#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1',
				 htmlentities($str, ENT_NOQUOTES, $encoding))));
}

$req = $bdd->prepare("SELECT id, title, link, $keywordQueryId FROM $tableName WHERE search_index IS NULL AND owners LIKE :user");
$req->execute(array(
	'user' => '%,' . $_SESSION['id'] . '%'));
	$result = $req->fetchAll();
	$req->closeCursor();

foreach ($result as $line)
{
	if(!empty($tableNameQuery))
	{
		# Add keyword in Index
		$keywordQueryIds = explode(',', $line[$keywordQueryId]);
		$keywordQueryIndex= '';

		foreach ($keywordQueryIds as $id)
		{
			$req = $bdd->prepare("SELECT $queryColumn FROM $tableNameQuery WHERE id = :id");
			$req->execute(array(
				'id' => $id));
				$result = $req->fetch();
				$req->closeCursor();

			if ($type === 'sciences')
			{
				$step1 = preg_replace("/(%28|%29|%22|AND\+|OR\+|NOTAND\+[^+]+(\+|$))/", "", $result[$queryColumn]);
				$step2 = preg_replace("/[^:\+]+:/", "", $step1);
				$result[$queryColumn] = preg_replace("/\+/", " ", $step2);
			}
			elseif ($type === 'patents')
			{
				$step1 = urldecode($result[$queryColumn]);
				$step2 = preg_replace("/(AND|OR)/", "", $step1);
				$result[$queryColumn] = preg_replace("/[^:\ ]+:/", "", $step2);
			}

			$keywordQueryIndex = $keywordQueryIndex. ' ' . $result[$queryColumn];
		}

		# Link studies
		$linkKeywords = preg_replace("/([^A-za-z0-9]|https)/", " ", $line['link']);
		preg_match_all("/\w{5,}/", $linkKeywords, $linkKeywords_array);
		$linkKeywords_array = array_unique($linkKeywords_array[0]);
		$linkKeywords = implode(' ', $linkKeywords_array);
		$title = $linkKeywords . ' ';
	}

	$title = $title . $line['title'] . ' ' . $keywordQueryIndex;

	$wordArray           = explode(' ', $title);
	$titleIndexLOWER     = '';
	$titleIndexSOUNDEX   = '';
	$titleIndexDELACCENT = '';
	$titleIndexPERMUTE   = '';

	foreach($wordArray as $word)
	{
		$titleIndexLOWER     = $titleIndexLOWER . ' ' . mb_strtolower($word);
		$titleIndexDELACCENT = $titleIndexDELACCENT . ' ' . mb_strtolower(del_accent($word));
		$titleIndexSOUNDEX   = $titleIndexSOUNDEX . ' ' . soundex($word);
	}

	$searchIndex = $titleIndexLOWER . ' ' . $titleIndexDELACCENT . ' ' . $titleIndexSOUNDEX;

	// Update search index
	$req = $bdd->prepare("UPDATE $tableName SET search_index = :search WHERE id = :id");
	$req->execute(array(
		'search' => $searchIndex,
		'id' => $line['id']));
		$req->closeCursor();
}
?>
