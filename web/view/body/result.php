<div class="background">
	<div class="subBackground">
	</div>
</div>
<div class="body">
	<h1>Watch result</h1>
	<form class="tableContainer" method="post" action="result">
		<div class="table-header">
			<table cellpadding="0" cellspacing="0" border="0">
				<thead>
					<tr>
						<th><input alt="Delete" title="Delete selected links" name="deleteLink" class="submit" type="submit" value="delete" /></th>
						<?php
						echo '
						<th><a href="?orderBy=title' . $colOrder['DESC'] . '">Title ' . $colOrder['title'] . '</a></th>
						<th>Keyword</th>
						<th><a href="?orderBy=source' . $colOrder['DESC'] . '">Source ' . $colOrder['source'] . '</a></th>
						<th><a href="?orderBy=date' . $colOrder['DESC'] . '">Date ' . $colOrder['date'] . '</a></th>
						<th><a href="?orderBy=send' . $colOrder['DESC'] . '">' . $colOrder['send'] . '</a></th>
						<th><a href="?orderBy=read' . $colOrder['DESC'] . '">' . $colOrder['read'] . '</a></th>
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
					foreach ($readOwnerResults as $result)
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
							<td><a href="' . $source['link'] . '">' . $source['name'] . '</a></td>
							<td>' . $date . '</td>
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
		<a href="result?page=1" class="pageNumber">
			1
		</a>
		<a href="result?page=2" class="pageNumber">
			2
		</a>
		<a href="result?page=3" class="pageNumber">
			3
		</a>
		<a href="result?page=4" class="pageNumber">
			4
		</a>
	</div>
</div>
