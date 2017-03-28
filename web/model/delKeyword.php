<?php
	$applicable_owners_sourceForCurrentUserNEW = preg_replace("/,$sourceIdAction,/", ',', $applicable_owners_sourcesCurrentKeywordAndUser);

	$applicable_owners_sourceForCurrentUserNEW = preg_replace("/\|/", '', $applicable_owners_sourceForCurrentUserNEW);

	$applicable_owners_sources = preg_replace($applicable_owners_sourcesCurrentKeywordAndUser, $applicable_owners_sourceForCurrentUserNEW, $applicable_owners_sources);

	$active = $activeForCurrentKeyword - 1;

	$req = $bdd->prepare('UPDATE keyword_news_serge SET applicable_owners_sources = :applicable_owners_sources, active = :active WHERE id = :id');
	$req->execute(array(
		'applicable_owners_sources' => $applicable_owners_sources,
		'active' => $active,
		'id' => $keywordIdAction));
		$req->closeCursor();
?>
