<div class="background">
	<div class="subBackground">
	</div>
</div>
<div class="body">
	<h1>Watch result</h1>
	<form class="formSearch" method="get" action="result">
		<input type="text" name="search" id="search" placeholder="Search Serge" value="<?php echo $search; ?>"/>
		<input type="hidden" name="orderBy" value="<?php echo preg_replace("/.*=/", "", $orderBy); ?>"/>
		<input type="hidden" name="optionalCond" value="<?php echo $optionalCond; ?>"/>
		<input type="hidden" name="type" value="<?php echo $type; ?>"/>

	</form>
	<div class="selectResultsType">
		<a <?php echo $newsActive; ?> href="result">
			<div class="selectResultsTypeNews"><?php get_t('title1_type_results', $bdd); ?></div>
		</a>
		<a <?php echo $sciencesActive; ?> href="result?type=sciences">
			<div class="selectResultsTypeSciences"><?php get_t('title2_type_results', $bdd); ?></div>
		</a>
		<a <?php echo $patentsActive; ?> href="result?type=patents">
			<div class="selectResultsTypePatents"><?php get_t('title3_type_results', $bdd); ?></div>
		</a>
	</div>

	<form class="tableContainer" method="post" action="result">
		<input type="hidden" name="nonce" value="<?php echo $nonce; ?>"/>
		<div class="table-header">
			<table>
				<thead>
					<tr>
						<th><input title="Delete selected links" name="deleteLink" class="submit" type="submit" value="delete" /></th>
						<?php
						echo '
						<th><a href="?orderBy=title' . $colOrder['DESC'] . $searchSort . $optionalCond . $actualPageLink . '&type=' . $type . '">'; get_t('title1_table_results', $bdd);
						echo ' ' . $colOrder['title'] . '</a></th>
						<th>' . $displayColumn . '</th>
						<th><a href="?orderBy=source' . $colOrder['DESC'] . $searchSort . $optionalCond . $actualPageLink . '&type=' . $type . '">';
						get_t('title3_table_results', $bdd);
						echo ' ' . $colOrder['source'] . '</a></th>
						<th><a href="?orderBy=date' . $colOrder['DESC'] . $searchSort . $optionalCond . $actualPageLink . '&type=' . $type . '">';
						get_t('title4_table_results', $bdd);
						echo ' ' . $colOrder['date'] . '</a></th>
						<th><a href="?optionalCond=send' . $colOrder['OCDESC'] . $searchSort . $orderBy . '&type=' . $type . '">' . $colOrder['send'] . '</a></th>
						<th><a href="?optionalCond=read' . $colOrder['OCDESC'] . $searchSort . $orderBy . '&type=' . $type . '">' . $colOrder['read'] . '</a></th>
						<th><a href="wiki">' . var_get_t('title7_table_results', $bdd) .  '</a></th>';
						?>
					</tr>
				</thead>
			</table>
		</div>
		<div class="table-content">
			<table>
				<tbody>
					<?php
					$readOwnerResults = new ArrayIterator($readOwnerResults);
					foreach (new LimitIterator($readOwnerResults, $base, $limit) as $result)
					{
						# Read keyword for current result
						$breaker = FALSE;
						$keywordIds = $result[$keywordQueryId];
						preg_match_all("/[0-9]+,/", $keywordIds, $keywordIds_array);
						foreach ($readOwnerKeyword as $OwnerKeyword)
						{
							foreach ($keywordIds_array[0] as $id)
							{
								$idK = preg_replace("/,/", "", $id);
								$keywordId = $idK;

								if ($idK == $OwnerKeyword['id'])
								{
									$keywordId = $idK;
									$breaker   = TRUE;
									break;
								}
							}

							if ($breaker)
							{
								break;
							}
						}

						$checkCol = array(array("id", "=", $keywordId, ""));
						$keyword  = read($tableNameQuery, $queryColumn, $checkCol, '', $bdd);
						$keyword  = preg_replace("/^:all@[0-9]+$/", var_get_t('all_specialKeyword_results', $bdd), $keyword[0][$queryColumn]);

						# Read source for current result
						if ($type == 'sciences')
						{
							$queryDisplay = '';
							preg_match("/,[0-9]+,/", $result[$keywordQueryId], $queryId);
							$queryId = preg_replace("/,/", "", $queryId[0]);

							$queryFieldsName['ti']  = 'Title';
							$queryFieldsName['au']  = 'Author';
							$queryFieldsName['abs'] = 'Abstract';
							$queryFieldsName['cat'] = 'Category';
							$queryFieldsName['jr']  = 'Reference';
							$queryFieldsName['all'] = 'All';

							$query = $keyword;
							$query = preg_replace("/%22/", "`", $query);
							$query = preg_replace("/%28/", "(", $query);
							$query = preg_replace("/%29/", ")", $query);

							preg_match_all("/[a-z]+:/", $query, $queryFields);
							foreach ($queryFields[0] as $fields)
							{
								preg_match("/^\(/", $query, $openParenthesisDisplay);
								if (!empty($openParenthesisDisplay[0]))
								{
									$query = preg_replace("/^\(/", "", $query);
									$queryDisplay = $queryDisplay . '
									<a href="setting?action=editQueryScience&query=' . $queryId . '" >
										<div class="queryParenthesisView">(</div>
									</a>
									';
								}

								preg_match("/$fields`[^`]*`/", $query, $fieldInput);
								$fieldInputPURE = preg_replace("/\+/", "\+", $fieldInput[0]);
								$query = preg_replace("/$fieldInputPURE/", "", $query);
								$fieldInput = preg_replace("/(.+:|`)/", "", $fieldInput[0]);
								$fieldInput = preg_replace("/\+/", " ", $fieldInput);
								$fields = preg_replace("/(:|`)/", "", $fields);
								$queryDisplay = $queryDisplay . '
								<a href="setting?action=editQueryScience&query=' . $queryId . '" >
									<div class="queryTypeView">' . $queryFieldsName[$fields] . '</div>
								</a>
								<a href="setting?action=editQueryScience&query=' . $queryId . '" >
									<div class="queryKeywordView">' . $fieldInput . '</div>
								</a>';

								preg_match("/^\)/", $query, $closeParenthesisDisplay);
								if (!empty($closeParenthesisDisplay[0]))
								{
									$query = preg_replace("/^\)/", "", $query);
									$queryDisplay = $queryDisplay . '
									<a href="setting?action=editQueryScience&query=' . $queryId . '" >
										<div class="queryParenthesisView">)</div>
									</a>
									';
								}

								preg_match("/^\+(AND|OR|NOTAND)\+/", $query, $logicalConnector);
								if (!empty($logicalConnector[1]))
								{
									$query = preg_replace("/^\+(AND|OR|NOTAND)\+/", "", $query);
									preg_match("/.{1,3}/", $logicalConnector[1], $logicalConnector);
									$queryDisplay = $queryDisplay . '
									<a href="setting?action=editQueryScience&query=' . $queryId . '" >
									<div class="query' . ucfirst(strtolower($logicalConnector[0])) . 'View">' . $logicalConnector[0] . '</div>
									</a>
									';
								}
							}

							$keyword = '<div class="queryContainer">' . $queryDisplay . '</div>';
						}
						elseif ($type == 'patents')
						{
							$queryDisplay = '';
							preg_match("/,[0-9]+,/", $result[$keywordQueryId], $queryId);
							$queryId = preg_replace("/,/", "", $queryId[0]);

							$queryFieldsName['ALLNAMES'] = 'All Names';
							$queryFieldsName['ALLNUM'] = 'All Numbers and IDs';
							$queryFieldsName['AAD'] = 'Applicant Address';
							$queryFieldsName['AADC'] = 'Applicant Address Country';
							$queryFieldsName['PAA'] = 'Applicant All Data';
							$queryFieldsName['PA'] = 'Applicant Name';
							$queryFieldsName['ANA'] = 'Applicant Nationality';
							$queryFieldsName['ARE'] = 'Applicant Residence';
							$queryFieldsName['AD'] = 'Application Date';
							$queryFieldsName['AN'] = 'Application Number';
							$queryFieldsName['CHEM'] = 'Chemical';
							$queryFieldsName['CTR'] = 'Country';
							$queryFieldsName['DS'] = 'Designated States';
							$queryFieldsName['EN_AB'] = 'English Abstract';
							$queryFieldsName['EN_ALL'] = 'English All';
							$queryFieldsName['EN_CL'] = 'English Claims';
							$queryFieldsName['EN_DE'] = 'English Description';
							$queryFieldsName['EN_ALLTXT'] = 'English Text';
							$queryFieldsName['EN_TI'] = 'English Title';
							$queryFieldsName['IC_EX'] = 'Exact IPC code';
							$queryFieldsName['LGF'] = 'Filing Language';
							$queryFieldsName['FP'] = 'Front Page(FP)';
							$queryFieldsName['GN'] = 'Grant Number';
							$queryFieldsName['IC'] = 'International Class';
							$queryFieldsName['ICI'] = 'International Class Inventive';
							$queryFieldsName['ICN'] = 'International Class N-Inventive';
							$queryFieldsName['IPE'] = 'International Preliminary Examination';
							$queryFieldsName['ISA'] = 'International Search Authority';
							$queryFieldsName['ISR'] = 'International Search Report';
							$queryFieldsName['INA'] = 'Inventor All Data';
							$queryFieldsName['IN'] = 'Inventor Name';
							$queryFieldsName['IADC'] = 'Inventor Nationality';
							$queryFieldsName['RPA'] = 'Legal Representative All Data';
							$queryFieldsName['RCN'] = 'Legal Representative Country';
							$queryFieldsName['RP'] = 'Legal Representative Name';
							$queryFieldsName['RAD'] = 'Legal Representative Address';
							$queryFieldsName['LI'] = 'Licensing availability';
							$queryFieldsName['PAF'] = 'Main Applicant Name';
							$queryFieldsName['ICF'] = 'Main International Class';
							$queryFieldsName['INF'] = 'Main Inventor Name';
							$queryFieldsName['RPF'] = 'Main Legal Rep Name';
							$queryFieldsName['NPA'] = 'National Phase All Data';
							$queryFieldsName['NPAN'] = 'National Phase Application Number';
							$queryFieldsName['NPED'] = 'National Phase Entry Date';
							$queryFieldsName['NPET'] = 'National Phase Entry Type';
							$queryFieldsName['PN'] = 'National Publication Number';
							$queryFieldsName['OF'] = 'Office Code';
							$queryFieldsName['NPCC'] = 'National Phase Office Code';
							$queryFieldsName['PRIORPCTAN'] = 'Prior PCT Application Number';
							$queryFieldsName['PRIORPCTWO'] = 'Prior PCT WO Number';
							$queryFieldsName['PI'] = 'Priority All Data';
							$queryFieldsName['PCN'] = 'Priority Country';
							$queryFieldsName['PD'] = 'Priority Date';
							$queryFieldsName['NP'] = 'Priority Number';
							$queryFieldsName['DP'] = 'Publication Date';
							$queryFieldsName['LGP'] = 'Publication Language';
							$queryFieldsName['SIS'] = 'Supplementary International Search';
							$queryFieldsName['TPO'] = 'Third Party Observation';
							$queryFieldsName['WO'] = 'WIPO Publication Number';

							$query = $keyword;

							preg_match_all("/[A-Z_]+\%3A/", $query, $queryFields);
							foreach ($queryFields[0] as $fields)
							{
								preg_match("/$fields\ *[^\+]+\+/", $query, $fieldInput);
								$fieldInputPURE = preg_replace("/\+/", "\+", $fieldInput[0]);
								$query = preg_replace("/$fieldInputPURE/", "", $query);
								$fieldInput = preg_replace("/(.+\%3A|`)/", "", $fieldInput[0]);
								$fieldInput = preg_replace("/\+/", " ", $fieldInput);
								$fields = preg_replace("/(\%3A|`)/", "", $fields);
								$queryDisplay = $queryDisplay . '
								<a href="setting?action=editQueryPatent&query=' . $queryId . '" >
									<div class="queryTypeView">' . $queryFieldsName[$fields] . '</div>
								</a>
								<a href="setting?action=editQueryPatent&query=' . $queryId . '" >
									<div class="queryKeywordView">' . $fieldInput . '</div>
								</a>';

								preg_match("/^(AND|OR)\+/", $query, $logicalConnector);
								if (!empty($logicalConnector[1]))
								{
									$query = preg_replace("/^(AND|OR)\+/", "", $query);
									preg_match("/.{1,3}/", $logicalConnector[1], $logicalConnector);
									$queryDisplay = $queryDisplay . '
									<a href="setting?action=editQueryPatent&query=' . $queryId . '" >
									<div class="query' . ucfirst(strtolower($logicalConnector[0])) . 'View">' . $logicalConnector[0] . '</div>
									</a>
									';
								}
							}

							$keyword = '<div class="queryContainer">' . $queryDisplay . '</div>';
						}

						$checkCol = array(array("id", "l", $result['id_source'], ""));
						$source   = read($tableNameSource, 'link, name', $checkCol, '', $bdd);
						$source   = $source[0];

						preg_match("/^https?:\/\/[^\/]*\//", $source['link'], $sourceLink);

						$date = $result['date']; #TODO Créer Option timezone et Adapter à la time zone de l'utilisateur

						$userIdComma = ',' . $_SESSION['id'] . ',';
						if (preg_match("/$userIdComma/", $result['send_status']))
						{
							$amISend = '<img alt="Send" src="images/iconSend.png" />';
						}
						else
						{
							$amISend = '<img alt="Not Send" src="images/iconNotSend.png" />';
						}

						if (preg_match("/$userIdComma/", $result['read_status']))
						{
							$amIRead = '<img alt="Read" src="images/iconRead.png" />';
						}
						else
						{
							$amIRead = '<img alt="Unread" src="images/iconUnread.png" />';
						}

						if (!empty($recordLink))
						{
							$result['link'] = $recordLink . $result['id'];
						}

						echo '
						<tr>
							<td><input type="checkbox" name="delete' . $result['id'] . '" id="delete0' . $result['id'] . '" /><label class="checkbox" for="delete0' . $result['id'] . '"></label></td>
							<td><a href="' . $result['link'] . '" target="_blank">' . $result['title'] . '</a></td>
							<td>' . $keyword . '</td>
							<td><a href="' .  $sourceLink[0] . '">' . $source['name'] . '</a></td>
							<td>' . date("H:i d/m/o", $date) . '</td>
							<td>' . $amISend . '</td>
							<td>' . $amIRead . '</td>
							<td>
								<a href="link?link" class="wikiLogo">
									<img alt="Add in wiki" title="Add in wiki" src="../images/iconWikiLight.png"/>
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
				<a href="result?page=' . $cpt . $searchSort . $optionalCond . $orderBy . '&type=' . $type . '" class="pageNumber current">
				' . $cpt . '
				</a>';
				$dotBetweenPageNumber = FALSE;
			}
			elseif (($cpt - 1) == $page || ($cpt + 1) == $page)
			{
				echo '
				<a href="result?page=' . $cpt . $searchSort . $optionalCond . $orderBy . '&type=' . $type . '" class="pageNumber">
				' . $cpt . '
				</a>';
				$dotBetweenPageNumber = FALSE;
			}
			elseif ($cpt <= 2 || $cpt == $nbPage || ($cpt + 1) == $nbPage)
			{
				echo '
				<a href="result?page=' . $cpt . $searchSort . $optionalCond . $orderBy . '&type=' . $type . '" class="pageNumber">
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
