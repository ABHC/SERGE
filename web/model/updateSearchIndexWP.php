<?php
function del_accent(string $str, $encoding='utf-8')
{
		$str = htmlentities($str, ENT_NOQUOTES, $encoding);
		$str = preg_replace('#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', $str);
		$str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
		$str = preg_replace('#&[^;]+;#', '', $str);

		return $str;
}

$req = $bdd->prepare("SELECT id, name, description, author, category, language FROM $tableName WHERE search_index IS NULL");
$req->execute(array(
	'user' => '%,' . $_SESSION['id'] . '%'));
	$result = $req->fetchAll();
	$req->closeCursor();

foreach ($result as $line)
{
	$titleIndexLOWER = '';
	$titleIndexSOUNDEX = '';
	$titleIndexDELACCENT = '';
	$titleIndexPERMUTE = '';

	$word = $line['name'] . ' ' . $line['description'] . ' ' . $line['author'] . ' ' . $line['category'] . ' ' . $line['language'];

	$titleIndexLOWER = $titleIndexLOWER . ' ' . mb_strtolower($word) ;
	$titleIndexDELACCENT = $titleIndexDELACCENT . ' ' . mb_strtolower(del_accent($word));
	$titleIndexSOUNDEX = $titleIndexSOUNDEX . ' ' . soundex($word) ;

	$searchIndex = $titleIndexLOWER . ' ' . $titleIndexDELACCENT . ' ' . $titleIndexSOUNDEX;

	// Update search index
	$req = $bdd->prepare("UPDATE $tableName SET search_index = :search WHERE id = :id");
	$req->execute(array(
		'search' => $searchIndex,
		'id' => $line['id']));
		$req->closeCursor();
}
?>
