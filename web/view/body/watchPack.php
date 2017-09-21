<div class="background">
	<div class="subBackground">
	</div>
</div>
<div class="body">
	<div class="selectType">
		<a <?php echo $addActive; ?> href="watchPack?type=add">
			<div class="selectTypeAddPack"><?php get_t('name1_type_watchpack', $bdd); ?></div>
		</a>
		<a <?php echo $createActive; ?> href="watchPack?type=create">
			<div class="selectTypeCreatePack"><?php get_t('name2_type_watchpack', $bdd); ?></div>
		</a>
	</div>
	<?php
	if ($type === 'create')
	{
		$packIdURL = '';
		if (!empty($data['packId']))
		{
			$packIdURL = '&packId=' . $data['packId'];
		}
	?>
	<h1><?php get_t('title_window0_watchpack', $bdd); ?></h1>
	<form method="post" action="watchPack?type=create<?php echo $packIdURL; ?>">
		<input type="hidden" name="nonce" value="<?php echo $nonce; ?>"/>
		<input type="hidden" name="scrollPos" id="scrollPos" value="
		<?php
		if (!empty($data['scrollPos']))
		{
			echo $data['scrollPos'];
		}
		else
		{
			echo '0';
		}
		?>"/>
		<input type="hidden" name="scrollPos" id="scrollPos" value="0"/>
		<input type="hidden" name="delEditingScienceQuery" value="<?php echo htmlspecialchars($delEditingScienceQuery); ?>"/>
		<input type="hidden" name="delEditingPatentQuery" value="<?php echo htmlspecialchars($delEditingPatentQuery); ?>"/>

		<div class="dataPackManagement">
			<h2><?php get_t('input1_window0_watchpack', $bdd); ?></h2>
			<div>
				<input title="Add" class="submit" type="submit" name="addNewPack" value="add" />
				<select name="watchPackList" onchange="this.form.submit();">
					<option value="NewPack"><?php get_t('select1_window0_watchpack', $bdd); ?>&nbsp;&nbsp;</option>
					<?php
					# List here watch Pack own by current user


					$checkCol = array(array('author', '=', $_SESSION['pseudo'], ''));
					$ownerWatchPacks = read('watch_pack_serge', 'id, name', $checkCol, '', $bdd);

					foreach ($ownerWatchPacks as $ownerWatchPack)
					{
						if ($ownerWatchPack['id'] == $data['packId'])
						{
							echo '<option value="' . $ownerWatchPack['id'] . '" selected>Edit: ' . $ownerWatchPack['name'] . '&nbsp;&nbsp;</option>';
						}
						else
						{
							echo '<option value="' . $ownerWatchPack['id'] . '">Edit: ' . $ownerWatchPack['name'] . '&nbsp;&nbsp;</option>';
						}
					}
					 ?>
				</select>
				<span class="arrDownBorder">▾</span>
				<input type="text" name="watchPackName" placeholder="<?php get_t('input2_window0_watchpack', $bdd); ?>" value="<?php echo  htmlspecialchars($packDetails['name']); ?>"/>
			</div>
			<div>
				<?php echo $selectLanguage; ?>
				<span class="arrDownBorder">▾</span>
				<input type="text" name="watchPackCategory" placeholder="<?php get_t('input3_window0_watchpack', $bdd); ?>" value="<?php echo htmlspecialchars($packDetails['category']); ?>" list="watchPackCategory"/>
				<datalist id="watchPackCategory">
					<?php
					# List here watch Pack category


					$checkCol = array();
					$categoryWatchPacks = read('watch_pack_serge', 'id, category', $checkCol, 'GROUP BY category', $bdd);

					foreach ($categoryWatchPacks as $categoryWatchPack)
					{
							echo '<option value="' . htmlspecialchars($categoryWatchPack['category']) . '"></option>';
					}
					 ?>
				</datalist>
			</div>
			<?php echo htmlspecialchars($ERRORMESSAGENEWPACKNAME); ?>
			<h2><?php get_t('titleInput4_window0_watchpack', $bdd); ?></h2>
			<textarea name="watchPackDescription" minlength="50" maxlength="300" placeholder="<?php get_t('input4_window0_watchpack', $bdd); ?>"><?php echo htmlspecialchars($packDetails['description']); ?></textarea>
		</div>

		<div class="keywordManagement">
			<h2><?php get_t('window2_title_setting', $bdd); ?></h2>
			<div class="newsInput">
				<input title="Add new keyword" class="submit" type="submit" value="add" name="addNewKeyword"/>
				<select name="sourceKeyword" id="sourceKeyword">
				<?php
					foreach ($listAllSources as $sourcesList)
					{
						if ($sourcesList['id'] == $_SESSION['lastSourceUse'])
						{
							$amISelected = 'selected';
						}
						else
						{
							$amISelected = '';
						}

						$sourcesList['name'] = preg_replace("/\[!NEW!\]/", "", $sourcesList['name']);
						echo '<option value="source' . htmlspecialchars($sourcesList['id']) . '" ' . htmlspecialchars($amISelected) . '>' . htmlspecialchars($sourcesList['name']) . '</option>' . PHP_EOL;
					}
					?>
					<option value="source00"><?php get_t('select1_window2_setting', $bdd); ?></option>
				</select>
				<span class="arrDownBorder">▾</span>
				<input type="text" class="keywordInput" name="newKeyword" id="keyword" placeholder="Keyword,next keyword, ..." />
			</div>
			<div class="newsInput">
				<input title="Add" name="addNewSource" class="submit" type="submit" value="add" />
				<select name="sourceType" id="sourceType">
					<option value="inputSource"><?php get_t('select2_window2_setting', $bdd); ?></option>
				</select>
				<span class="arrDownBorder">▾</span>
				<input type="url" name="newSource" id="source" placeholder="Source" />
			</div>
			<?php echo htmlspecialchars($ERROR_MESSAGE); ?>

			<div>
				<?php
				$cptSource = 0;
				foreach ($readPackSources as $packSourcesList)
				{
					$packSourcesList['name'] = preg_replace("/\[!NEW!\]/", "", $packSourcesList['name']);
					preg_match("/./", ucfirst($packSourcesList['name']), $rssFirstLetter);

					if ($actualLetter != $rssFirstLetter[0])
					{
						$foldSourceName = 'radio-s' . $rssFirstLetter[0];
						$amICheckFoldSource = '';
						if (isset($_SESSION[$foldSourceName]))
						{
							if ($_SESSION[$foldSourceName] == $rssFirstLetter[0])
							{
								$amICheckFoldSource = 'checked';
							}
						}

						$actualLetter = $rssFirstLetter[0];

						echo '
						</div>
						<input type="checkbox" name="radio-s' . htmlspecialchars($rssFirstLetter[0]) . '" id="unfold-s' . htmlspecialchars($rssFirstLetter[0]) . '" value="' . htmlspecialchars($rssFirstLetter[0]) . '" ' . htmlspecialchars($amICheckFoldSource) . '/>'.
						'<div class="sourceList" >'.
							'<label for="unfold-s' . htmlspecialchars($rssFirstLetter[0]) . '" class="unfoldTag">'.
							 htmlspecialchars($rssFirstLetter[0]) . ' ▾'.
							'</label>'.
							'<label for="unfold-s' . htmlspecialchars($rssFirstLetter[0]) . '" class="foldTag">'.
							 htmlspecialchars($rssFirstLetter[0]) . ' ▴'.
							'</label>';
					}

					$foldKeywordName = 'radio-ks' . $packSourcesList['id'];
					$amICheckFoldKeyword = '';
					if (isset($_SESSION[$foldKeywordName]))
					{
						if ($_SESSION[$foldKeywordName] == $packSourcesList['id'])
						{
							$amICheckFoldKeyword = 'checked';
						}
					}



					$checkCol = array(array("query", "=", "[!source!]", "AND"),
														array("source", "=", "%,!" . $packSourcesList['id'] . ",%", "AND"),
														array("pack_id", "=", $data['packId'], ""),
				);
					$result = read('watch_pack_queries_serge', 'id', $checkCol, '', $bdd);
					$resultDesactivatedSource = $result[0];

					if (empty($resultDesactivatedSource))
					{
						echo
						'<div class="tagSource Tactive" id="ks' . htmlspecialchars($packSourcesList['id']) . '">'.
							'<input type="submit" title="Delete" name="delSource" value="source' . htmlspecialchars($packSourcesList['id']) . '&"/>'.
							'<input type="submit" title="Disable" name="disableSource" value="source' . htmlspecialchars($packSourcesList['id']) . '&"/>'.
							'<a href="' . htmlspecialchars($packSourcesList['link']) . '" target="_blank">'.
								 htmlspecialchars(ucfirst($packSourcesList['name'])).
							'</a>'.
						'</div>';
					}
					elseif (!empty($resultDesactivatedSource))
					{
						echo
						'<div class="tagSource Tdisable" id="ks' . htmlspecialchars($packSourcesList['id']) . '">'.
							'<input type="submit" title="Delete" name="delSource" value="source' . htmlspecialchars($packSourcesList['id']) . '&"/>'.
							'<input type="submit" title="Activate" name="activateSource" value="source' . htmlspecialchars($packSourcesList['id']) . '&"/>'.
							'<a href="' . htmlspecialchars($packSourcesList['link']) . '" target="_blank">'.
								 htmlspecialchars(ucfirst($packSourcesList['name'])).
							'</a>'.
						'</div>';
					}

					echo
					'<input type="checkbox" name="radio-ks' . htmlspecialchars($packSourcesList['id']) . '" id="unfold-ks' . htmlspecialchars($packSourcesList['id']) . '" value="' . htmlspecialchars($packSourcesList['id']) . '" ' . htmlspecialchars($amICheckFoldKeyword) . '/>'.
					'<div class="keywordList" id="keywordList' . htmlspecialchars($packSourcesList['id']) . '">'.
						'<label for="unfold-ks' . htmlspecialchars($packSourcesList['id']) . '" id="unfold' . htmlspecialchars($packSourcesList['id']) . '"  class="unfoldTag">'.
							'Unfold keyword list ▾'.
						'</label>'.
						'<label for="unfold-ks' . htmlspecialchars($packSourcesList['id']) . '" id="fold' . htmlspecialchars($packSourcesList['id']) . '" class="foldTag">'.
							'Fold keyword list ▴'.
						'</label>';

					# Keyword loop


					$checkCol = array(array("pack_id", "=", $data['packId'], "AND"),
														array("source", "nl", "[!%", "AND"),
														array("query", "nl", "[!%", ""));
					$reqKeywordList = read('watch_pack_queries_serge', 'id, query, source', $checkCol, 'ORDER BY query', $bdd);

					$cptKeyword = 0;
					foreach ($reqKeywordList as $ownerKeywordList)
					{
						$applicable_owners_sources = $ownerKeywordList['applicable_owners_sources'];
						$ownerKeywordList['query'] = preg_replace("/^:all@[0-9]+$/", ":All", $ownerKeywordList['query']);
						if (!empty($ownerKeywordList['source']))
						{
							$listSourceKeyword = array();
							if (preg_match("/^[,!0-9,]+$/", $ownerKeywordList['source']))
							{
								$listSourceKeyword = array_merge(preg_split('/,/', $ownerKeywordList['source'], -1, PREG_SPLIT_NO_EMPTY), $listSourceKeyword);
							}
						}

						$packSourcesListDesac = '!' . $packSourcesList['id'];

						if (in_array($packSourcesListDesac, $listSourceKeyword))
						{
							echo
							'<div class="tag Tdisable">'.
								'<input type="submit" title="Delete" name="delKeyword" value="source'. htmlspecialchars($packSourcesList['id']) . '&keyword' . htmlspecialchars($ownerKeywordList['id']) . '&"/>'.
								'<input type="submit" title="Activate" name="activateKeyword" value="source'. htmlspecialchars($packSourcesList['id']) . '&keyword' . htmlspecialchars($ownerKeywordList['id']) . '&"/>'.
								'<a href="setting?keyword=keyword' . htmlspecialchars($ownerKeywordList['id']) . '">'.
									 htmlspecialchars(ucfirst($ownerKeywordList['query'])).
								'</a>'.
							'</div>';
							$cptKeyword++;
						}
						elseif (in_array($packSourcesList['id'], $listSourceKeyword))
						{
							echo
							'<div class="tag Tactive">'.
								'<input type="submit" title="Delete" name="delKeyword" value="source'. htmlspecialchars($packSourcesList['id']) . '&keyword' . htmlspecialchars($ownerKeywordList['id']) . '&"/>'.
								'<input type="submit" title="Disable" name="disableKeyword" value="source'. htmlspecialchars($packSourcesList['id']) . '&keyword' . htmlspecialchars($ownerKeywordList['id']) . '&"/>'.
								'<a href="setting?keyword=keyword' . htmlspecialchars($ownerKeywordList['id']) . '">'.
									 htmlspecialchars(ucfirst($ownerKeywordList['query'])).
								'</a>'.
							'</div>';
							$cptKeyword++;
						}
					}
					echo '</div>' . PHP_EOL;

					if ($cptKeyword < 7)
					{
						$style = $style . PHP_EOL .
						'#unfold' . $packSourcesList['id'] . ',' . PHP_EOL .
						'#fold' . $packSourcesList['id'] . PHP_EOL .
						'{' . PHP_EOL .
						'	display: none;' . PHP_EOL .
						'}' . PHP_EOL .
						'#keywordList' . $packSourcesList['id'] . PHP_EOL .
						'{' . PHP_EOL .
						'	width: 96%;' . PHP_EOL .
						'	height: auto;' . PHP_EOL .
						'}' . PHP_EOL;
					}
					$cptSource++;
				}
				$_SESSION['additionalStyle'] = $style;
				?>
			</div>
		</div>

		<div class="scientificPublicationManagement">
			<h2><?php get_t('window3_title_setting', $bdd); ?></h2>
			<div class="newQueryContainer">
				<div class="lineQuery">
				<input title="Add new science query" class="submit" type="submit" name="scienceQuerySubmit" value="" />
				<?php
				$cpt = 0;
				$logicalConnector = '';

				while ($cpt < $_SESSION['cptScienceQuery'])
				{
					echo $logicalConnector;

					if (intval($cpt/3) === ($cpt/3) && $cpt != 0)
					{
						echo '</div>';
					}

					if (intval($cpt/3) === ($cpt/3) && $cpt != 0)
					{
						echo '<div class="lineQuery">';
					}

					if (intval($cpt/3) === ($cpt/3) && $cpt != 0)
					{
						echo '<div class="ghostSpace"></div>';
					}

					$selected['ti'] = '';
					$selected['au'] = '';
					$selected['abs'] = '';
					$selected['jr'] = '';
					$selected['cat'] = '';
					$selected['all'] = '';
					$selected[$data['scienceType' . $cpt]] = 'selected';


					if ($data['openParenthesis' . $cpt] === 'active')
					{
						$checked['openParenthesis' . $cpt] = 'checked';
					}
					echo '
					<input type="checkbox" id="openParenthesis' . htmlspecialchars($cpt) . '" name="openParenthesis' . htmlspecialchars($cpt) . '" value="active" ' .  htmlspecialchars($checked['openParenthesis' . $cpt]) . '/>
					<label class="queryParenthesis" for="openParenthesis' . htmlspecialchars($cpt) . '">(</label>
					<select title="Type" class="queryType" name="scienceType' . htmlspecialchars($cpt) . '" id="scienceType0' . htmlspecialchars($cpt) . '">
						<option value="ti" ' . htmlspecialchars($selected['ti']) . '>Title</option>
						<option value="au" ' . htmlspecialchars($selected['au']) . '>Author</option>
						<option value="abs" ' . htmlspecialchars($selected['abs']) . '>Abstract</option>
						<option value="jr" ' . htmlspecialchars($selected['jr']) . '>Reference</option>
						<option value="cat" ' . htmlspecialchars($selected['cat']) . '>Category</option>
						<option value="all" ' . htmlspecialchars($selected['all']) . '>All</option>
					</select>
					<span class="arrDownBorder">▾</span>
					<input type="text" class="query" name="scienceQuery' . htmlspecialchars($cpt) . '" id="scienceQuery0' . htmlspecialchars($cpt) . '" placeholder="Keyword" value="' .  htmlspecialchars($data['scienceQuery' . $cpt]) . '"/>';


					if ($data['closeParenthesis' . $cpt] === 'active')
					{
						$checked['closeParenthesis' . $cpt] = 'checked';
					}
					echo '
					<input type="checkbox" id="closeParenthesis' . htmlspecialchars($cpt) . '" name="closeParenthesis' . htmlspecialchars($cpt) . '" value="active" ' .  htmlspecialchars($checked['closeParenthesis' . $cpt]) . '/>
					<label class="queryParenthesis" for="closeParenthesis' . htmlspecialchars($cpt) . '">)</label>';

					$cpt++;

					$checked['OR'] = '';
					$checked['AND'] = '';
					$checked['NOTAND'] = '';
					$checked[$data['andOrAndnot' . $cpt]] = 'checked';

					if (empty($data['andOrAndnot' . $cpt]))
					{
						$checked['OR'] = 'checked';
					}

					$logicalConnector = '
					<div class="btnList">
						<input type="radio" id="andOrNotand_AND0' . htmlspecialchars($cpt) . '" name="andOrAndnot' . htmlspecialchars($cpt) . '" value="AND" ' . htmlspecialchars($checked['AND']) . '>
						<label class="ANDOrNotand" for="andOrNotand_AND0' . htmlspecialchars($cpt) . '"></label>
						<input type="radio" id="andOrNotand_OR0' . htmlspecialchars($cpt) . '" name="andOrAndnot' . htmlspecialchars($cpt) . '" value="OR" ' . htmlspecialchars($checked['OR']) . '>
						<label class="andORNotand" for="andOrNotand_OR0' . htmlspecialchars($cpt) . '"></label>
						<input type="radio" id="andOrNotand_NOTAND0' . htmlspecialchars($cpt) . '" name="andOrAndnot' . htmlspecialchars($cpt) . '" value="NOTAND" ' . htmlspecialchars($checked['NOTAND']) . '>
						<label class="andOrNOTAND" for="andOrNotand_NOTAND0' . htmlspecialchars($cpt) . '"></label>
					</div>';
				}
				?>
				<input title="Extend" class="extend" type="submit" id="extendScience" name="extendScience" value=">>" />
			</div>
			</div>
			<?php echo htmlspecialchars($ERROR_SCIENCEQUERY); ?>
			<?php
			// Read watchPack science query


			$checkCol = array(array("pack_id", "=", $data['packId'], "AND"),
												array("source", "=", "Science", "OR"),
												array("pack_id", "=", $data['packId'], "AND"),
												array("source", "=", "!Science", ""));
			$queries = read('watch_pack_queries_serge', 'id, query, source', $checkCol, '', $bdd);

			foreach ($queries as $query)
			{
				$queryDisplay = '';
				$Qdisable = '';
				$titleDisableActivate = 'Disable';
				$nameClassDisableActivate = 'disable';

				$pattern = '!Science';
				if (preg_match("/$pattern/", $query['source']))
				{
					$Qdisable = 'Qdisable';
					$titleDisableActivate = 'Activate';
					$nameClassDisableActivate = 'activate';
				}

				echo '
				<div class="queryContainer ' . htmlspecialchars($Qdisable) . '">
					<input type="submit" title="Delete" class="deleteQuery" name="delQueryScience" value="query' . htmlspecialchars($query['id']) . '"/>
					<input type="submit" title="' . htmlspecialchars($titleDisableActivate) . '" class="' . htmlspecialchars($nameClassDisableActivate) . 'Query" name="' . htmlspecialchars($nameClassDisableActivate) . 'QueryScience" value="query' . htmlspecialchars($query['id']) . '"/>
				';

				$queryId = $query['id'];

				$queryFieldsName['ti']  = 'Title';
				$queryFieldsName['au']  = 'Author';
				$queryFieldsName['abs'] = 'Abstract';
				$queryFieldsName['cat'] = 'Category';
				$queryFieldsName['jr']  = 'Reference';
				$queryFieldsName['all'] = 'All';

				$query = $query['query'];
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
						<a href="setting?action=editQueryScience&query=' . htmlspecialchars($queryId) . '" >
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
					<a href="setting?action=editQueryScience&query=' . htmlspecialchars($queryId) . '" >
						<div class="queryTypeView">' . htmlspecialchars($queryFieldsName[$fields]) . '</div>
					</a>
					<a href="setting?action=editQueryScience&query=' . htmlspecialchars($queryId) . '" >
						<div class="queryKeywordView">' . htmlspecialchars($fieldInput) . '</div>
					</a>';

					preg_match("/^\)/", $query, $closeParenthesisDisplay);
					if (!empty($closeParenthesisDisplay[0]))
					{
						$query = preg_replace("/^\)/", "", $query);
						$queryDisplay = $queryDisplay . '
						<a href="setting?action=editQueryScience&query=' . htmlspecialchars($queryId) . '" >
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
						<a href="setting?action=editQueryScience&query=' . htmlspecialchars($queryId) . '" >
						<div class="query' . ucfirst(strtolower($logicalConnector[0])) . 'View">' . $logicalConnector[0] . '</div>
						</a>
						';
					}
				}

				echo $queryDisplay . '</div>';
			}
			?>
		</div>
		<div class="patentManagement">
			<h2><?php get_t('window4_title_setting', $bdd); ?></h2>
			<div class="newQueryContainer">
				<div class="lineQuery">
				<input title="Add new patents query" class="submit" type="submit" name="patentQuerySubmit" value="" />
				<?php
				$cpt = 0;
				$logicalConnector = '';

				while ($cpt < $_SESSION['cptPatentQuery'])
				{
					echo $logicalConnector;

					if (intval($cpt/3) === ($cpt/3) && $cpt != 0)
					{
						echo '</div>';
					}

					if (intval($cpt/3) === ($cpt/3) && $cpt != 0)
					{
						echo '<div class="lineQuery">';
					}

					if (intval($cpt/3) === ($cpt/3) && $cpt != 0)
					{
						echo '<div class="ghostSpace"></div>';
					}

					$selected['ALLNAMES'] = '';
					$selected['ALLNUM'] = '';
					$selected['AAD'] = '';
					$selected['AADC'] = '';
					$selected['PAA'] = '';
					$selected['PA'] = '';
					$selected['ANA'] = '';
					$selected['ARE'] = '';
					$selected['AD'] = '';
					$selected['AN'] = '';
					$selected['CHEM'] = '';
					$selected['CTR'] = '';
					$selected['DS'] = '';
					$selected['EN_AB'] = '';
					$selected['EN_ALL'] = '';
					$selected['EN_CL'] = '';
					$selected['EN_DE'] = '';
					$selected['EN_ALLTXT'] = '';
					$selected['EN_TI'] = '';
					$selected['IC_EX'] = '';
					$selected['LGF'] = '';
					$selected['FP'] = '';
					$selected['GN'] = '';
					$selected['IC'] = '';
					$selected['ICI'] = '';
					$selected['ICN'] = '';
					$selected['IPE'] = '';
					$selected['ISA'] = '';
					$selected['ISR'] = '';
					$selected['INA'] = '';
					$selected['IN'] = '';
					$selected['IADC'] = '';
					$selected['RPA'] = '';
					$selected['RCN'] = '';
					$selected['RP'] = '';
					$selected['RAD'] = '';
					$selected['LI'] = '';
					$selected['PAF'] = '';
					$selected['ICF'] = '';
					$selected['INF'] = '';
					$selected['RPF'] = '';
					$selected['NPA'] = '';
					$selected['NPAN'] = '';
					$selected['NPED'] = '';
					$selected['NPET'] = '';
					$selected['PN'] = '';
					$selected['OF'] = '';
					$selected['NPCC'] = '';
					$selected['PRIORPCTAN'] = '';
					$selected['PRIORPCTWO'] = '';
					$selected['PI'] = '';
					$selected['PCN'] = '';
					$selected['PD'] = '';
					$selected['NP'] = '';
					$selected['DP'] = '';
					$selected['LGP'] = '';
					$selected['SIS'] = '';
					$selected['TPO'] = '';
					$selected['WO'] = '';
					$selected[$data['patentType' . $cpt]] = 'selected';

					echo '
				<select title="Type" class="queryType" name="patentType' . htmlspecialchars($cpt) . '" id="patentType' . htmlspecialchars($cpt) . '">
					<option value="ALLNAMES" ' . htmlspecialchars($selected['ALLNAMES']) . '>All Names</option>
					<option value="ALLNUM" ' . htmlspecialchars($selected['ALLNUM']) . '>All Numbers and IDs</option>
					<option value="AAD" ' . htmlspecialchars($selected['AAD']) . '>Applicant Address</option>
					<option value="AADC" ' . htmlspecialchars($selected['AADC']) . '>Applicant Address Country</option>
					<option value="PAA" ' . htmlspecialchars($selected['PAA']) . '>Applicant All Data</option>
					<option value="PA" ' . htmlspecialchars($selected['PA']) . '>Applicant Name</option>
					<option value="ANA" ' . htmlspecialchars($selected['ANA']) . '>Applicant Nationality</option>
					<option value="ARE" ' . htmlspecialchars($selected['ARE']) . '>Applicant Residence</option>
					<option value="AD" ' . htmlspecialchars($selected['AD']) . '>Application Date</option>
					<option value="AN" ' . htmlspecialchars($selected['AN']) . '>Application Number</option>
					<option value="CHEM" ' . htmlspecialchars($selected['CHEM']) . '>Chemical</option>
					<option value="CTR" ' . htmlspecialchars($selected['CTR']) . '>Country</option>
					<option value="DS" ' . htmlspecialchars($selected['DS']) . '>Designated States</option>
					<option value="EN_AB" ' . htmlspecialchars($selected['EN_AB']) . '>English Abstract</option>
					<option value="EN_ALL" ' . htmlspecialchars($selected['EN_ALL']) . '>English All</option>
					<option value="EN_CL" ' . htmlspecialchars($selected['EN_CL']) . '>English Claims</option>
					<option value="EN_DE" ' . htmlspecialchars($selected['EN_DE']) . '>English Description</option>
					<option value="EN_ALLTXT" ' . htmlspecialchars($selected['EN_ALLTXT']) . '>English Text</option>
					<option value="EN_TI" ' . htmlspecialchars($selected['EN_TI']) . '>English Title</option>
					<option value="EN_EX" ' . htmlspecialchars($selected['EN_EX']) . '>Exact IPC code</option>
					<option value="LGF" ' . htmlspecialchars($selected['LGF']) . '>Filing Language</option>
					<option value="FP" ' . htmlspecialchars($selected['FP']) . '>Front Page(FP)</option>
					<option value="GN" ' . htmlspecialchars($selected['GN']) . '>Grant Number</option>
					<option value="IC" ' . htmlspecialchars($selected['IC']) . '>International Class</option>
					<option value="ICI" ' . htmlspecialchars($selected['ICI']) . '>International Class Inventive</option>
					<option value="ICN" ' . htmlspecialchars($selected['ICN']) . '>International Class N-Inventive</option>
					<option value="IPE" ' . htmlspecialchars($selected['IPE']) . '>International Preliminary Examination</option>
					<option value="ISA" ' . htmlspecialchars($selected['ISA']) . '>International Search Authority</option>
					<option value="ISR" ' . htmlspecialchars($selected['ISR']) . '>International Search Report</option>
					<option value="INA" ' . htmlspecialchars($selected['INA']) . '>Inventor All Data</option>
					<option value="IN" ' . htmlspecialchars($selected['IN']) . '>Inventor Name</option>
					<option value="IADC" ' . htmlspecialchars($selected['IADC']) . '>Inventor Nationality</option>
					<option value="RPA" ' . htmlspecialchars($selected['RPA']) . '>Legal Representative All Data</option>
					<option value="RCN" ' . htmlspecialchars($selected['RCN']) . '>Legal Representative Country</option>
					<option value="RP" ' . htmlspecialchars($selected['RP']) . '>Legal Representative Name</option>
					<option value="RAD" ' . htmlspecialchars($selected['RAD']) . '>Legal Representative Address</option>
					<option value="LI" ' . htmlspecialchars($selected['LI']) . '>Licensing availability</option>
					<option value="PAF" ' . htmlspecialchars($selected['PAF']) . '>Main Applicant Name</option>
					<option value="ICF" ' . htmlspecialchars($selected['ICF']) . '>Main International Class</option>
					<option value="INF" ' . htmlspecialchars($selected['INF']) . '>Main Inventor Name</option>
					<option value="RPF" ' . htmlspecialchars($selected['RPF']) . '>Main Legal Rep Name</option>
					<option value="NPA" ' . htmlspecialchars($selected['NPA']) . '>National Phase All Data</option>
					<option value="NPAN" ' . htmlspecialchars($selected['NPAN']) . '>National Phase Application Number</option>
					<option value="NPED" ' . htmlspecialchars($selected['NPED']) . '>National Phase Entry Date</option>
					<option value="NPET" ' . htmlspecialchars($selected['NPET']) . '>National Phase Entry Type</option>
					<option value="PN" ' . htmlspecialchars($selected['PN']) . '>National Publication Number</option>
					<option value="OF" ' . htmlspecialchars($selected['OF']) . '>Office Code</option>
					<option value="NPCC" ' . htmlspecialchars($selected['NPCC']) . '>National Phase Office Code</option>
					<option value="PRIORPCTAN" ' . htmlspecialchars($selected['PRIORPCTAN']) . '>Prior PCT Application Number</option>
					<option value="PRIORPCTW" ' . htmlspecialchars($selected['PRIORPCTW']) . '>Prior PCT WO Number</option>
					<option value="PI" ' . htmlspecialchars($selected['PI']) . '>Priority All Data</option>
					<option value="PCN" ' . htmlspecialchars($selected['PCN']) . '>Priority Country</option>
					<option value="PD" ' . htmlspecialchars($selected['PD']) . '>Priority Date</option>
					<option value="NP" ' . htmlspecialchars($selected['NP']) . '>Priority Number</option>
					<option value="DP" ' . htmlspecialchars($selected['DP']) . '>Publication Date</option>
					<option value="LGP" ' . htmlspecialchars($selected['LGP']) . '>Publication Language</option>
					<option value="SIS" ' . htmlspecialchars($selected['SIS']) . '>Supplementary International Search</option>
					<option value="TPO" ' . htmlspecialchars($selected['TPO']) . '>Third Party Observation</option>
					<option value="WO" ' . htmlspecialchars($selected['WO']) . '>WIPO Publication Number</option>
				</select>
				<span class="arrDownBorder">▾</span>
				<input type="text" class="query" name="patentQuery' . htmlspecialchars($cpt) . '" id="patentQuery' . htmlspecialchars($cpt) . '" placeholder="Keyword" value="' . $data['patentQuery' . $cpt] . '" />';

				$cpt++;

				$checked = '';
				if ($data['andOrPatent' . $cpt] === 'OR')
				{
					$checked = 'checked';
				}

				$logicalConnector = '
				<input type="checkbox" id="patentAndOr' . htmlspecialchars($cpt) . '" name="andOrPatent' . htmlspecialchars($cpt) . '" value="OR" ' . htmlspecialchars($checked) . '>
				<label class="andOr" for="patentAndOr' . htmlspecialchars($cpt) . '"></label>';
				}
				?>
				<input title="Extend" class="extend" type="submit" id="extend" name="extendPatent" value=">>" />
			</div>
			</div>
			<?php echo htmlspecialchars($ERROR_PATENTQUERY); ?>
			<?php
			// Read watch pack patent query
			$checkCol = array(array("pack_id", "=", $data['packId'], "AND"),
												array("source", "=", "Patent", "OR"),
												array("pack_id", "=", $data['packId'], "AND"),
												array("source", "=", "!Patent", ""));
			$queries = read('watch_pack_queries_serge', 'id, query, source', $checkCol, '', $bdd);

			foreach ($queries as $query)
			{
				$queryDisplay = '';
				$Qdisable = '';
				$titleDisableActivate = 'Disable';
				$nameClassDisableActivate = 'disable';

				$pattern = '!Patent';
				if (preg_match("/$pattern/", $query['source']))
				{
					$Qdisable = 'Qdisable';
					$titleDisableActivate = 'Activate';
					$nameClassDisableActivate = 'activate';
				}

				echo '
				<div class="queryContainer ' . htmlspecialchars($Qdisable) . '">
					<input type="submit" title="Delete" class="deleteQuery" name="delQueryPatent" value="query' . htmlspecialchars($query['id']) . '"/>
					<input type="submit" title="' . htmlspecialchars($titleDisableActivate) . '" class="' . htmlspecialchars($nameClassDisableActivate) . 'Query" name="' . htmlspecialchars($nameClassDisableActivate) . 'QueryPatent" value="query' . htmlspecialchars($query['id']) . '"/>
				';

				$queryId = $query['id'];

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

				$query = $query['query'];

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
					<a href="setting?action=editQueryPatent&query=' . htmlspecialchars($queryId) . '" >
						<div class="queryTypeView">' . htmlspecialchars($queryFieldsName[$fields]) . '</div>
					</a>
					<a href="setting?action=editQueryPatent&query=' . htmlspecialchars($queryId) . '" >
						<div class="queryKeywordView">' . htmlspecialchars($fieldInput) . '</div>
					</a>';

					preg_match("/^(AND|OR)\+/", $query, $logicalConnector);
					if (!empty($logicalConnector[1]))
					{
						$query = preg_replace("/^(AND|OR)\+/", "", $query);
						preg_match("/.{1,3}/", $logicalConnector[1], $logicalConnector);
						$queryDisplay = $queryDisplay . '
						<a href="setting?action=editQueryPatent&query=' . htmlspecialchars($queryId) . '" >
						<div class="query' . ucfirst(strtolower($logicalConnector[0])) . 'View">' . $logicalConnector[0] . '</div>
						</a>
						';
					}
				}

				echo $queryDisplay . '</div>';
			}
			?>
		</div>
	</form>
	<?php
	}
	else
	{
	?>
		<h1>Community watch packs</h1>
		<form class="formSearch" method="get" action="watchPack">
			<input type="text" name="search" id="search" placeholder="Search Serge" value="<?php echo htmlspecialchars($search); ?>"/>
			<input type="hidden" name="orderBy" value="<?php echo  htmlspecialchars(preg_replace("/.*=/", "", $orderBy)); ?>"/>
			<input type="hidden" name="optionalCond" value="<?php echo $optionalCond; ?>"/>
		</form>


		<div class="tableContainer">
			<form class="table-header" method="get" action="watchPack">
				<table>
					<thead>
						<tr>
							<th>Add</th>
							<?php
							echo '
							<th><a href="?orderBy=name' . htmlspecialchars($colOrder['DESC']) . htmlspecialchars($searchSort) . htmlspecialchars($actualPageLink) . '&language=' . htmlspecialchars($selectedLanguageCode) . '&type=' . htmlspecialchars($type) . '">Name ' . htmlspecialchars($colOrder['name']) . '</a></th>
							<th><a href="?orderBy=author' . htmlspecialchars($colOrder['DESC']) . htmlspecialchars($searchSort) . htmlspecialchars($actualPageLink) . '&language=' . htmlspecialchars($selectedLanguageCode) . '&type=' . htmlspecialchars($type) . '">Author ' . htmlspecialchars($colOrder['author']) . '</a></th>
							<th><a href="?orderBy=category' . htmlspecialchars($colOrder['DESC']) . htmlspecialchars($searchSort) . htmlspecialchars($actualPageLink) . '&language=' . htmlspecialchars($selectedLanguageCode) . '&type=' . htmlspecialchars($type) . '">Category ' . htmlspecialchars($colOrder['category']) . '</a></th>
							<th><a href="?orderBy=date' . htmlspecialchars($colOrder['DESC']) . htmlspecialchars($searchSort) . htmlspecialchars($actualPageLink) . '&language=' . htmlspecialchars($selectedLanguageCode) . '&type=' . htmlspecialchars($type) . '">Date ' . htmlspecialchars($colOrder['date']) . '</a></th>
							<th>' . $colOrder['language'] . '</th>
							<th><a href="?orderBy=rate' . htmlspecialchars($colOrder['DESC']) . htmlspecialchars($searchSort) . '&language=' . htmlspecialchars($selectedLanguageCode) . '&type=' . htmlspecialchars($type) . '">Rate' . htmlspecialchars($colOrder['rate']) . '</a></th>';
							?>
						</tr>
					</thead>
				</table>
			</form>
			<div class="table-content">
				<table>
					<tbody>
						<form method="post" action="watchPack">
							<input type="hidden" name="nonce" value="<?php echo $nonce; ?>"/>
							<?php
							foreach ($watchPacks as $watchPack)
							{
								# Color stars
								$starTitle = 'Add a star';
								$colorStar = '';
								$pattern = ',' . $_SESSION['id'] . ',';
								if (preg_match("/$pattern/", $watchPack['rating']))
								{
									$colorStar = 'colorStar';
									$starTitle = 'Unstar';
								}
								echo '
								<tr>
									<td><input title="Add watch pack" name="addPack" class="icoAddPack" type="submit" value="' . $watchPack['id'] . '" /></td>
									<td title="' . htmlspecialchars($watchPack['description']) . '">' . htmlspecialchars($watchPack['name']) . '</td>
									<td>' . htmlspecialchars($watchPack['author']) . '</td>
									<td>' . htmlspecialchars($watchPack['category']) . '</td>
									<td>' . date("H:i d/m/o", $watchPack['update_date']) . '</td>
									<td>' . strtoupper($watchPack['language']) . '</td>
									<td>' . htmlspecialchars($watchPack['NumberOfStars']) . '<input title="' . htmlspecialchars($starTitle) . '" name="AddStar" class="star ' . htmlspecialchars($colorStar) . '" type="submit" value="&#9733; ' . htmlspecialchars($watchPack['id']) . '" /></td>
								</tr>';
							}
							?>
						</form>
					</tbody>
				</table>
			</div>
		</div>
		<div class="pages">
			<?php
			$nbPage = ceil(count($watchPacks) / $limit);
			$page   = $page + 1;
			$cpt    = 1;
			$dotBetweenPageNumber = FALSE;

			while ($cpt <= $nbPage)
			{
				if ($cpt === $page)
				{
					echo '
					<a href="result?page=' . $cpt . $searchSort . $optionalCond . $orderBy . '&type=' . $type . '" class="pageNumber current">
					' . $cpt . '
					</a>';
					$dotBetweenPageNumber = FALSE;
				}
				elseif (($cpt - 1) === $page || ($cpt + 1) === $page)
				{
					echo '
					<a href="result?page=' . $cpt . $searchSort . $optionalCond . $orderBy . '&type=' . $type . '" class="pageNumber">
					' . $cpt . '
					</a>';
					$dotBetweenPageNumber = FALSE;
				}
				elseif ($cpt <= 2 || $cpt === $nbPage || ($cpt + 1) === $nbPage)
				{
					echo '
					<a href="result?page=' . $cpt . $searchSort . $optionalCond . $orderBy . '&type=' . $type . '" class="pageNumber">
					' . $cpt . '
					</a>';
				}
				else
				{
					if ($dotBetweenPageNumber === FALSE)
					{
						echo '...';
						$dotBetweenPageNumber = TRUE;
					}
				}
				$cpt++;
			}
			?>
		</div>
		<?php
		}
		?>
	</div>
