<div class="background">
	<div class="subBackground">
	</div>
</div>
<div class="bodyResult">
	<div>
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
			<form class="selectExportType" method="post" action="result?page=<?php echo $data['page'] . $searchSort . $data['optionalCond'] . $data['orderBy'] . '&type=' . $type;?>" target="_blank" onsubmit="window.location.reload();" >
				<input type="hidden" name="nonce" value="<?php echo $nonce; ?>"/>
				<input type="submit" class="exportSpace" title="Export results" value=""/>
				<select name="export">
					<option value="csv">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CSV
					</option>
					<option value="txt">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TXT
					</option>
					<option value="xml">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;XML
					</option>
					<option value="sql">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SQL
					</option>
				</select>
			</form>
		</div>
	</div>
<div class="body">
	<h1><?php get_t('title_window0_result', $bdd); ?></h1>
	<form class="formSearch" method="get" action="result">
		<input type="text" name="search" id="search" placeholder="<?php get_t('Search', $bdd); ?>" value="<?php echo $search; ?>"/>
		<input type="hidden" name="orderBy" value="<?php echo preg_replace("/.*=/", "", $data['orderBy']); ?>"/>
		<input type="hidden" name="optionalCond" value="<?php echo $data['optionalCond']; ?>"/>
		<input type="hidden" name="type" value="<?php echo $type; ?>"/>
	</form>

	<form class="tableContainer" method="post" action="result?page=<?php echo $data['page'] . $searchSort . $data['optionalCond'] . $data['orderBy'] . '&type=' . $type;?>">
		<input type="hidden" name="nonce" value="<?php echo $nonce; ?>"/>
		<div class="table-header">
			<table>
				<thead>
					<tr>
						<th><input type="checkbox" name="selectAll" id="checkAllPage" /><label class="checkbox" for="checkAllPage" onmouseup="checkAllPage();"></label><input title="<?php get_t('Delete selected links', $bdd); ?>" name="deleteLink" class="submit" type="submit" value="delete" /></th>
						<?php
						echo '
						<th><a href="?orderBy=title' . $colOrder['DESC'] . $searchSort . $data['optionalCond'] . $actualPageLink . '&type=' . $type . '">'; get_t('title1_table_results', $bdd);
						echo ' ' . $colOrder['title'] . '</a></th>
						<th>' . $displayColumn . '</th>
						<th><a href="?orderBy=source' . $colOrder['DESC'] . $searchSort . $data['optionalCond'] . $actualPageLink . '&type=' . $type . '">';
						get_t('title3_table_results', $bdd);
						echo ' ' . $colOrder['source'] . '</a></th>
						<th><a href="?orderBy=date' . $colOrder['DESC'] . $searchSort . $data['optionalCond'] . $actualPageLink . '&type=' . $type . '">';
						get_t('title4_table_results', $bdd);
						echo ' ' . $colOrder['date'] . '</a></th>
						<th><a href="?optionalCond=send' . $colOrder['OCDESC'] . $searchSort . $data['orderBy'] . '&type=' . $type . '">' . $colOrder['send'] . '</a></th>';
						echo $readStatusColumn;
						echo '<th><a href="wiki">' . var_get_t('title7_table_results', $bdd) .  '</a></th>';
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

							# Read science search fields
							include_once('model/readColumns.php');

							$nextColumnName = FALSE;
							foreach ($columnsNames as $columnsName)
							{
								if ($nextColumnName && $columnsName['Field'] != 'active')
								{
									$queryFieldsName[$columnsName['Field']] = $columnsName['Field'];
								}

								if ($columnsName['Field'] === 'quote')
								{
									$nextColumnName = TRUE;
								}
							}

							$query = urldecode($keyword);

							preg_match_all("/([^\|]+)\|*/", $query, $queryFields);
							foreach ($queryFields[1] as $fields)
							{
								preg_match("/^\(/", $fields, $openParenthesisDisplay);
								if (!empty($openParenthesisDisplay[0]))
								{
									$queryDisplay = $queryDisplay . '
									<a href="setting?action=editQueryScience&query=' . $queryId . '" >
										<div class="queryParenthesisView">(</div>
									</a>
									';
								}

								if (!empty($queryFieldsName[$fields]))
								{
									$queryDisplay = $queryDisplay . '
									<a href="setting?action=editQueryScience&query=' . $queryId . '" >
									<div class="queryTypeView">' . ucfirst($queryFieldsName[$fields]) . '</div>
									</a>';
								}

								preg_match("/#.+/", $fields, $fieldInput);
								if (!empty($fieldInput[0]))
								{
									$fieldInput = preg_replace("/#/", "", $fieldInput[0]);
									$queryDisplay = $queryDisplay . '
									<a href="setting?action=editQueryScience&query=' . $queryId . '" >
									<div class="queryKeywordView">' . $fieldInput . '</div>
									</a>';
								}

								preg_match("/^\)/", $fields, $closeParenthesisDisplay);
								if (!empty($closeParenthesisDisplay[0]))
								{
									$queryDisplay = $queryDisplay . '
									<a href="setting?action=editQueryScience&query=' . $queryId . '" >
									<div class="queryParenthesisView">)</div>
									</a>
									';
								}

								preg_match("/^(AND|OR|NOT)$/", $fields, $logicalConnector);
								if (!empty($logicalConnector[1]))
								{
									preg_match("/.{2,3}/", $logicalConnector[1], $logicalConnector);
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

						$checkCol = array(array("id", "l", $result['source_id'], ""));
						$source   = read($tableNameSource, 'link, name', $checkCol, '', $bdd);
						$source   = $source[0];

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

						if ($optionReadStatus && preg_match("/$userIdComma/", $result['read_status']))
						{
							$amIRead = '<td><img alt="Read" src="images/iconRead.png" /></td>';
						}
						elseif ($optionReadStatus)
						{
							$amIRead = '<td><img id="readStatus' . $result['id'] . '" alt="Unread" src="images/iconUnread.png" /></td>';
						}
						else
						{
							$amIRead = '';
						}

						if (!empty($recordLink))
						{
							$result['link'] = $recordLink . $result['id'];
							$updatePage = 'onmouseup="updateReadStatus(' . $result['id'] . ', ' . $type . ');"';
						}

						echo '
						<tr>
							<td><input type="checkbox" name="delete' . $result['id'] . '" id="delete0' . $result['id'] . '" /><label class="checkbox" for="delete0' . $result['id'] . '"></label></td>
							<td><a href="' . $result['link'] . '" target="_blank" ' . $updatePage . '>' . $result['title'] . '</a></td>
							<td>' . $keyword . '</td>
							<td><a href="' .  $source['link'] . '">' . $source['name'] . '</a></td>
							<td>' . date("H:i d/m/o", $date) . '</td>
							<td>' . $amISend . '</td>'
							 . $amIRead .
							'<td>
								<a href="addLinkInWiki?link" class="wikiLogo">
									<img alt="'. var_get_t('Add in wiki', $bdd) . '" title="'. var_get_t('Add in wiki', $bdd) . '" src="../images/iconWikiLight.png"/>
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
		$cpt    = 0;
		$dotBetweenPageNumber = FALSE;

		while ($cpt <= ($nbPage + 1))
		{
			if ($cpt == 0 && $page >= 5)
			{
				echo '
				<a href="result?page=' . ($page - 5) . $searchSort . $data['optionalCond'] . $data['orderBy'] . '&type=' . $type . '" class="pageNumber speedPage">
				&lt;&lt; 5&nbsp;
				</a>';
			}
			elseif ($cpt == ($nbPage + 1) && $page <= ($nbPage - 5))
			{
				echo '
				<a href="result?page=' . ($page + 5) . $searchSort . $data['optionalCond'] . $data['orderBy'] . '&type=' . $type . '" class="pageNumber speedPage">
				&nbsp;5 &gt&gt;
				</a>';
			}
			elseif ($cpt == $page)
			{
				echo '
				<a href="result?page=' . $cpt . $searchSort . $data['optionalCond'] . $data['orderBy'] . '&type=' . $type . '" class="pageNumber current">
				' . $cpt . '
				</a>';
				$dotBetweenPageNumber = FALSE;
			}
			elseif ((($cpt - 1) == $page || ($cpt + 1) == $page) && $cpt > 0 && $cpt <= $nbPage)
			{
				echo '
				<a href="result?page=' . $cpt . $searchSort . $data['optionalCond'] . $data['orderBy'] . '&type=' . $type . '" class="pageNumber">
				' . $cpt . '
				</a>';
				$dotBetweenPageNumber = FALSE;
			}
			elseif (($cpt <= 2 || $cpt == $nbPage || ($cpt + 1) == $nbPage) && $cpt > 0)
			{
				echo '
				<a href="result?page=' . $cpt . $searchSort . $data['optionalCond'] . $data['orderBy'] . '&type=' . $type . '" class="pageNumber">
				' . $cpt . '
				</a>';
			}
			else
			{
				if (!$dotBetweenPageNumber && $cpt > 0 && $cpt < $nbPage)
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
</div>
