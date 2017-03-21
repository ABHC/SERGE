<?php
// Check if keyword is already in bdd
$req = $bdd->prepare('SELECT id, applicable_owners_sources, active FROM keyword_news_serge WHERE LOWER(keyword) = LOWER(:newKeyword)');
$req->execute(array(
	'newKeyword' => $newKeyword));
	$result = $req->fetch();
	$req->closeCursor();

if (!$result)
{
	// Adding new source
	$applicable_owners_sources = '|' . $_SESSION['id'] . ':,' . $sourceId . ',|';
	$active = 1;
	$req = $bdd->prepare('INSERT INTO keyword_news_serge (keyword, applicable_owners_sources, active) VALUES (:newKeyword, :applicable_owners_sources, :active)');
	$req->execute(array(
		'newKeyword' => $newKeyword,
		'applicable_owners_sources' => $applicable_owners_sources,
		'active' => $active));
		$req->closeCursor();
}
else
{
	// Update applicable_owners_sources
	// Search in applicable_owners_sources if idUser: exist
	$applicable_owners_sources = $result['applicable_owners_sources'];
	$findme = '|' . $_SESSION['id'] . ':';
	$pos = strpos($applicable_owners_sources, $findme);

	if ($pos !== false)
	{
		preg_match("/\|" . $_SESSION['id'] . ":[,0-9,]*,\|/", $applicable_owners_sources, $userApplicable_owners_sources);

		if (preg_match("/\|" . $_SESSION['id'] . ":[,0-9,]*," . $sourceId . ",[,0-9,]*\|/", $applicable_owners_sources))
		{
			$ERROR_MESSAGE = 'The keyword for this source was already in the database';
		}
		else
		{
			// Add source in the end of source list for current user
			$newSourceForAdding = ',' . $sourceId . ',|';
			$userApplicable_owners_sourcesNEW = preg_replace("/,*\|$/", $newSourceForAdding, 		$userApplicable_owners_sources[0]);
			$applicable_owners_sources = preg_replace("/\|" . $_SESSION['id'] . ":[,0-9,]*,\|/", 		$userApplicable_owners_sourcesNEW, $applicable_owners_sources);
		}
	}
	else
	{
		// Add user and source in applicable_owners_sources
		$userApplicable_owners_sourcesNEW = '|' . $_SESSION['id'] . ':,' . $sourceId . ',|';
		$applicable_owners_sources = preg_replace("/\|$/", $userApplicable_owners_sourcesNEW,$applicable_owners_sources);
	}

	$active = $result['active'] + 1;
	$req = $bdd->prepare('UPDATE keyword_news_serge SET applicable_owners_sources = :applicable_owners_sources, active = :active WHERE id = :id');
	$req->execute(array(
		'applicable_owners_sources' => $applicable_owners_sources,
		'active' => $active,
		'id' => $result['id']));
		$req->closeCursor();
}
?>
