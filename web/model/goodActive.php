<?php
		# Check if keyword need to be delete
		$req = $bdd->query('SELECT id, applicable_owners_sources FROM inquiries_news_serge WHERE 1');
			$applicable_owners_sources = $req->fetchAll();
			$req->closeCursor();

			foreach ($applicable_owners_sources as $input_lines)
			{
				# Regarder nb source
				preg_match_all("/,[0-9]+/", $input_lines['applicable_owners_sources'], $output_array);

				$cpt = 0;
				while (isset($output_array[0][$cpt]))
				{
					$cpt ++;
				}
				# Update la BDD avec active = nb sources
				$req = $bdd->prepare('UPDATE inquiries_news_serge SET active = :active WHERE id = :id');
				$req->execute(array(
					'active' => $cpt,
					'id' => $input_lines['id']));
					$req->closeCursor();
			}
?>
