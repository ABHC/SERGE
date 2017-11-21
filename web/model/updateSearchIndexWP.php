<?php
function del_accent(string $str, $encoding='utf-8')
{
	return preg_replace('#&[^;]+;#', '',
				 preg_replace('#&([A-za-z]{2})(?:lig);#', '\1',
				 preg_replace('#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1',
				 htmlentities($str, ENT_NOQUOTES, $encoding))));
}

$req = $bdd->prepare("SELECT id, name, description, author, category, language FROM $tableName WHERE search_index IS NULL");
$req->execute();
	$result = $req->fetchAll();
	$req->closeCursor();

foreach ($result as $line)
{
	$word = $line['name'] . ' ' . $line['description'] . ' ' . $line['author'] . ' ' . $line['category'] . ' ' . $line['language'];

	$soundexWord = '';
	$soundexWord_array = explode(' ', $word);
	foreach ($soundexWord_array as $wordSplit)
	{
		$soundexWord = $soundexWord . ' ' . soundex($wordSplit);
	}

	$searchIndex = mb_strtolower($word) . ' ' . mb_strtolower(del_accent($word)) . ' ' . $soundexWord;

	// Update search index
	$req = $bdd->prepare("UPDATE $tableName SET search_index = :search WHERE id = :id");
	$req->execute(array(
		'search' => $searchIndex,
		'id' => $line['id']));
		$req->closeCursor();
}
?>
