<?php
if ($type == 'news')
{
	$tableName = 'result_news_serge';
}
elseif ($type == 'sciences')
{
	$tableName = 'result_science_serge';
}
elseif ($type == 'patents')
{
	$tableName = 'result_patents_serge';
}

// Change record read
$req = $bdd->prepare("UPDATE $tableName SET read_status = :userId WHERE link = :link");
$req->execute(array(
	'userId' => $userId,
	'link' => $link));
	$req->closeCursor();
?>
