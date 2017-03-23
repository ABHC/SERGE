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


		# Check if keyword need to be delete
		$req = $bdd->prepare('SELECT applicable_owners_sources FROM keyword_news_serge WHERE id = :id');
		$req->execute(array(
			'id' => $keywordIdAction));
			$applicable_owners_sources = $req->fetch();
			$req->closeCursor();

			if (preg_match("/(\|[0-9]*:,)*\|/", $applicable_owners_sources['applicable_owners_sources']))
			{
				$req = $bdd->prepare('DELETE FROM keyword_news_serge WHERE id = :id');
				$req->execute(array(
					'id' => $keywordIdAction));
					$req->closeCursor();

				# Rebuild index primary key
				$bdd->query('SET  @num := 0');
				$bdd->query('UPDATE keyword_news_serge SET id = @num := (@num+1)');
				$bdd->query('ALTER TABLE keyword_news_serge AUTO_INCREMENT = 1');
			}
?>
