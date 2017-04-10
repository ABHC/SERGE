<?php
// Read in BDD the results for current owner
$userId = '%,' . $_SESSION['id'] . ',%';
$reqReadOwnerResults = $bdd->prepare('SELECT id, title, link, send_status, read_status, `date`, id_source, keyword_id FROM result_news_serge WHERE owners LIKE :user ORDER BY `date` DESC');
$reqReadOwnerResults->execute(array(
	'user' => $userId));
	$readOwnerResults = $reqReadOwnerResults->fetchAll();
	$reqReadOwnerResults->closeCursor();
?>
