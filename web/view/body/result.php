<div class="background">
	<div class="subBackground">
	</div>
</div>
<div class="body">
	<h1>Watch result</h1>
	<form class="formSearch" method="get" action="result">
		<input type="text" name="search" id="search" placeholder="Search Serge" value="<?php echo $search; ?>"/>
		<input type="hidden" name="orderBy" value="<?php echo $orderBy; ?>"/>
		<input type="hidden" name="optionalCond" value="<?php echo $optionalCond; ?>"/>
		<input type="hidden" name="type" value="<?php echo $type; ?>"/>

	</form>
	<div class="selectResultsType">
		<a <?php echo $newsActive; ?> href="result?type=news">
			<div class="selectResultsTypeNews">News</div>
		</a>
		<a <?php echo $sciencesActive; ?> href="result?type=sciences">
			<div class="selectResultsTypeSciences">Sciences</div>
		</a>
		<a <?php echo $patentsActive; ?> href="result?type=patents">
			<div class="selectResultsTypePatents">Patents</div>
		</a>
	</div>

	<form class="tableContainer" method="post" action="result">
		<div class="table-header">
			<table cellpadding="0" cellspacing="0" border="0">
				<thead>
					<tr>
						<th><input alt="Delete" title="Delete selected links" name="deleteLink" class="submit" type="submit" value="delete" /></th>
						<?php
						echo '
						<th><a href="?orderBy=title' . $colOrder['DESC'] . $searchSort . $optionalCond . $actualPageLink . '">Title ' . $colOrder['title'] . '</a></th>
						<th>' . $displayColumn . '</th>
						<th><a href="?orderBy=source' . $colOrder['DESC'] . $searchSort . $optionalCond . $actualPageLink . '">Source ' . $colOrder['source'] . '</a></th>
						<th><a href="?orderBy=date' . $colOrder['DESC'] . $searchSort . $optionalCond . $actualPageLink . '">Date ' . $colOrder['date'] . '</a></th>
						<th><a href="?optionalCond=send' . $colOrder['OCDESC'] . $searchSort . $orderBy . '">' . $colOrder['send'] . '</a></th>
						<th><a href="?optionalCond=read' . $colOrder['OCDESC'] . $searchSort . $orderBy . '">' . $colOrder['read'] . '</a></th>
						<th><a href="wiki">Wiki</a></th>';
						?>
					</tr>
				</thead>
			</table>
		</div>
		<div class="table-content">
			<table cellpadding="0" cellspacing="0" border="0">
				<tbody>
					<?php
					$readOwnerResults = new ArrayIterator($readOwnerResults);
					foreach (new LimitIterator($readOwnerResults, $base, $limit) as $result)
					{
						# Read keyword for current result
						$keyword = readResultKeyword($result['keyword_id'], $readOwnerKeyword, $bdd);
						$keyword = preg_replace("/^:all@[0-9]+$/", ":All", $keyword);

						# Read source for current result
						$reqSourceResults = $bdd->prepare('SELECT link, name FROM rss_serge WHERE id LIKE :id');
						$reqSourceResults->execute(array(
							'id' => $result['id_source']));
							$source = $reqSourceResults->fetch();
							$reqSourceResults->closeCursor();

						preg_match("/^https?:\/\/[^\/]*\//", $source['link'], $sourceLink);

						$date = $result['date']; #TODO Créer Option timezone et Adapter à la time zone de l'utilisateur

						$userIdComma = ',' . $_SESSION['id'] . ',';
						if (preg_match("/$userIdComma/", $result['send_status']))
						{
							$amISend = '<img src="images/iconSend.png" />';
						}
						else
						{
							$amISend = '<img src="images/iconNotSend.png" />';
						}

						if (preg_match("/$userIdComma/", $result['read_status']))
						{
							$amIRead = '<img src="images/iconRead.png" />';
						}
						else
						{
							$amIRead = '<img src="images/iconUnread.png" />';
						}

						echo '
						<tr>
							<td><input type="checkbox" name="delete' . $result['id'] . '" id="delete0' . $result['id'] . '" /><label class="checkbox" for="delete0' . $result['id'] . '"></label></td>
							<td><a href="' . $recordLink . $result['link'] . '" target="_blank">' . $result['title'] . '</a></td>
							<td>' . $keyword . '</td>
							<td><a href="' .  $sourceLink[0] . '">' . $source['name'] . '</a></td>
							<td>' . date("H:i d/m/o", $date) . '</td>
							<td>' . $amISend . '</td>
							<td>' . $amIRead . '</td>
							<td>
								<a href="link?link" class="wikiLogo">
									<img src="../images/iconWikiWB.png"/>
								</a>
							</td>
						</tr>';
					}
					?>
				</tbody>
			</table>
		</div>
	</form>
	<div class="pages">
		<?php
		$nbPage = ceil(count($readOwnerResults) / $limit);
		$page   = $page + 1;
		$cpt    = 1;
		$dotBetweenPageNumber = FALSE;

		while ($cpt <= $nbPage)
		{
			if ($cpt == $page)
			{
				echo '
				<a href="result?page=' . $cpt . $searchSort . $optionalCond . $orderBy . '" class="pageNumber current">
				' . $cpt . '
				</a>';
				$dotBetweenPageNumber = FALSE;
			}
			elseif ($cpt <= 2)
			{
				echo '
				<a href="result?page=' . $cpt . $searchSort . $optionalCond . $orderBy . '" class="pageNumber">
				' . $cpt . '
				</a>';
			}
			elseif (($cpt - 1) == $page OR ($cpt + 1) == $page)
			{
				echo '
				<a href="result?page=' . $cpt . $searchSort . $optionalCond . $orderBy . '" class="pageNumber">
				' . $cpt . '
				</a>';
				$dotBetweenPageNumber = FALSE;
			}
			elseif ($cpt == $nbPage OR ($cpt + 1) == $nbPage)
			{
				echo '
				<a href="result?page=' . $cpt . $searchSort . $optionalCond . $orderBy . '" class="pageNumber">
				' . $cpt . '
				</a>';
			}
			else
			{
				if ($dotBetweenPageNumber == FALSE)
				{
					echo '...';
					$dotBetweenPageNumber = TRUE;
				}
			}
			$cpt++;
		}
		?>
	</div>
</div>
