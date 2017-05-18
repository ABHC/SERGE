<?php
function del_accent($str, $encoding='utf-8')
{
		$str = htmlentities($str, ENT_NOQUOTES, $encoding);
		$str = preg_replace('#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', $str);
		$str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
		$str = preg_replace('#&[^;]+;#', '', $str);

		return $str;
}

$req = $bdd->prepare('SELECT id, title FROM result_news_serge WHERE search_index IS NULL AND owners LIKE :user');
$req->execute(array(
	'user' => '%,' . $_SESSION['id'] . '%'));
	$result = $req->fetchAll();
	$req->closeCursor();

foreach ($result as $line)
{

$title = $line['title'];

$wordArray = explode(" ", $title);
$titleIndexLOWER = '';
$titleIndexSOUNDEX = '';
$titleIndexDELACCENT = '';
$titleIndexPERMUTE = '';

foreach($wordArray as $word)
{
		$titleIndexLOWER = $titleIndexLOWER . ' ' . mb_strtolower($word) ;
		$titleIndexDELACCENT = $titleIndexDELACCENT . ' ' . mb_strtolower(del_accent($word));
		$titleIndexSOUNDEX = $titleIndexSOUNDEX . ' ' . soundex($word) ;
}

$titleIndex = $titleIndexLOWER . ' ' . $titleIndexDELACCENT . ' ' . $titleIndexSOUNDEX;

// Update search index
$req = $bdd->prepare('UPDATE result_news_serge SET search_index = :title WHERE id = :id');
$req->execute(array(
	'title' => $titleIndex,
	'id' => $line['id']));
	$req->closeCursor();
}
?>
