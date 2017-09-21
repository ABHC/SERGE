<?php
function addNewKeyword($sourceId, $newKeyword, $ERROR_MESSAGE, $ownerSources, $bdd)
{
	$updateBDD = TRUE;

	// Check if keyword is already in bdd
	$checkCol = array(array("keyword", "=", mb_strtolower($newKeyword), ""));
	$result   = read('keyword_news_serge', 'id, applicable_owners_sources, active', $checkCol, '', $bdd);
	$result   = $result[0];

	if (empty($result))
	{
		$active = 0;
		if ($sourceId === '00')
		{
			$applicableOwners = '|' . $_SESSION['id'] . ':,';
			foreach ($ownerSources as $ownerSourcesList)
			{
				$applicableOwners = $applicableOwners . $ownerSourcesList['id'] . ',';
				$active = $active + 1;
			}
			$applicableOwners = $applicableOwners . '|';
		}
		else
		{
			# TODO Test if sourceId is an existing sources
			$applicableOwners = '|' . $_SESSION['id'] . ':,' . $sourceId . ',|';
			$active = $active + 1;
		}
		// Adding new keyword
		$insertCol = array(array("keyword", strtolower($newKeyword)),
											array("applicable_owners_sources", '|' . $_SESSION['id'] . ':,' . $sourceId . ',|'),
											array("active", $active + 1));
		$execution = insert('keyword_news_serge', $insertCol, '', 'setting', $bdd);
	}
	else
	{
		$active = $result['active'];
		// Update applicable_owners_sources
		// Search in applicable_owners_sources if idUser: exist
		$applicableOwners = $result['applicable_owners_sources'];
		$findme           = '|' . $_SESSION['id'] . ':';
		$pos              = strpos($applicableOwners, $findme);

		if ($pos !== false)
		{
			preg_match("/\|" . $_SESSION['id'] . ":[,0-9,]*,\|/", $applicableOwners, $userApplicable_owners_sources);

			if (preg_match("/\|" . $_SESSION['id'] . ":[,0-9,]*," . $sourceId . ",[,0-9,]*\|/", $applicableOwners) && $sourceId != '00')
			{
				$updateBDD     = FALSE;
				$ERROR_MESSAGE = 'The keyword: "' . $newKeyword . '" for this source was already in the database<br>' . $ERROR_MESSAGE;
			}
			else
			{
				// Add source in the end of source list for current user
				if ($sourceId === '00')
				{
					$newSourceForAdding = ',';
					foreach ($ownerSources as $ownerSourcesList)
					{
						if (!preg_match("/\|" . $_SESSION['id'] . ":[,0-9,]*," . $ownerSourcesList['id'] . ",[,0-9,]*\|/", $applicableOwners))
						{
							$newSourceForAdding = $newSourceForAdding . $ownerSourcesList['id'] . ',';
							$active = $active + 1;
						}
					}
					$applicableOwners = $applicableOwners . '|';
				}
				else
				{
					$newSourceForAdding = ',' . $sourceId . ',|';
					$active = $active + 1;
				}
				$newOwner = preg_replace("/,*\|$/", $newSourceForAdding, 		$userApplicable_owners_sources[0]);
				$applicableOwners = preg_replace("/\|" . $_SESSION['id'] . ":[,0-9,]*,\|/", 		$newOwner, $applicableOwners);
			}
		}
		else
		{
			// Add user and source in applicable_owners_sources
			if ($sourceId === '00')
			{
				$newOwner = '|' . $_SESSION['id'] . ':,';
				foreach ($ownerSources as $ownerSourcesList)
				{
					$newOwner = $newOwner . $ownerSourcesList['id'] . ',';
					$active   = $active + 1;
				}
				$newOwner = $newOwner . '|';
			}
			else
			{
				$newOwner = '|' . $_SESSION['id'] . ':,' . $sourceId . ',|';
				$active = $active + 1;
			}
			$applicableOwners = preg_replace("/\|$/", $newOwner,$applicableOwners);
		}

		if ($updateBDD)
		{
			$updateCol = array(array("applicable_owners_sources", $applicableOwners),
													array("active", $active));
			$checkCol  = array(array("id", "=", $result['id'], ""));
			$execution = update('keyword_news_serge', $updateCol, $checkCol, '', $bdd);
		}
	}

	return $ERROR_MESSAGE;
}
?>
