<?php
function addNewKeyword($sourceId, $newKeyword, $ERROR_MESSAGE, $reqReadOwnerSourcestmp, $bdd)
{
$updateBDD = TRUE;

# Special keyword :all

// Check if keyword is already in bdd
$req = $bdd->prepare('SELECT id, applicable_owners_sources, active FROM keyword_news_serge WHERE LOWER(keyword) = LOWER(:newKeyword)');
$req->execute(array(
	'newKeyword' => $newKeyword));
	$result = $req->fetch();
	$req->closeCursor();

if (!$result)
{
	if ($sourceId == '00')
	{
		$applicable_owners_sources = '|' . $_SESSION['id'] . ':,';
		foreach ($reqReadOwnerSourcestmp as $ownerSourcesList)
		{
			$applicable_owners_sources = $applicable_owners_sources . $ownerSourcesList['id'] . ',';
		}
		$applicable_owners_sources = $applicable_owners_sources . '|';
	}
	else
	{
		# TODO Test if sourceId is an existing sources
		$applicable_owners_sources = '|' . $_SESSION['id'] . ':,' . $sourceId . ',|';
	}
	// Adding new keyword
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

		if (preg_match("/\|" . $_SESSION['id'] . ":[,0-9,]*," . $sourceId . ",[,0-9,]*\|/", $applicable_owners_sources) AND $sourceId != '00')
		{
			$updateBDD     = FALSE;
			$ERROR_MESSAGE = 'The keyword: "' . $newKeyword . '" for this source was already in the database<br>' . $ERROR_MESSAGE;
		}
		else
		{
			// Add source in the end of source list for current user
			if ($sourceId == '00')
			{
				$newSourceForAdding = ',';
				foreach ($reqReadOwnerSourcestmp as $ownerSourcesList)
				{
					if (!preg_match("/\|" . $_SESSION['id'] . ":[,0-9,]*," . $ownerSourcesList['id'] . ",[,0-9,]*\|/", $applicable_owners_sources))
					{
						$newSourceForAdding = $newSourceForAdding . $ownerSourcesList['id'] . ',';
					}
				}
				$applicable_owners_sources = $applicable_owners_sources . '|';
			}
			else
			{
				$newSourceForAdding = ',' . $sourceId . ',|';
			}
			$userApplicable_owners_sourcesNEW = preg_replace("/,*\|$/", $newSourceForAdding, 		$userApplicable_owners_sources[0]);
			$applicable_owners_sources = preg_replace("/\|" . $_SESSION['id'] . ":[,0-9,]*,\|/", 		$userApplicable_owners_sourcesNEW, $applicable_owners_sources);
		}
	}
	else
	{
		// Add user and source in applicable_owners_sources
		if ($sourceId == '00')
		{
			$userApplicable_owners_sourcesNEW = '|' . $_SESSION['id'] . ':,';
			foreach ($reqReadOwnerSourcestmp as $ownerSourcesList)
			{
				$userApplicable_owners_sourcesNEW = $userApplicable_owners_sourcesNEW . $ownerSourcesList['id'] . ',';
			}
			$userApplicable_owners_sourcesNEW = $userApplicable_owners_sourcesNEW . '|';
		}
		else
		{
			$userApplicable_owners_sourcesNEW = '|' . $_SESSION['id'] . ':,' . $sourceId . ',|';
		}
		$applicable_owners_sources = preg_replace("/\|$/", $userApplicable_owners_sourcesNEW,$applicable_owners_sources);
	}

	if ($updateBDD)
	{
		$active = $result['active'] + 1;
		$req = $bdd->prepare('UPDATE keyword_news_serge SET applicable_owners_sources = :applicable_owners_sources, active = :active WHERE id = :id');
		$req->execute(array(
			'applicable_owners_sources' => $applicable_owners_sources,
			'active' => $active,
			'id' => $result['id']));
			$req->closeCursor();
	}
}

return $ERROR_MESSAGE;
}
?>
