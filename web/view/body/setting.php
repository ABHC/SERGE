<div class="background">
	<div class="subBackground">
	</div>
</div>
<form method="post" action="setting">
	<input type="hidden" name="scrollPos" id="scrollPos" value="
	<?php
	if (isset($_POST['scrollPos']))
	{
		echo htmlspecialchars($_POST['scrollPos']);
	}
	else
	{
		echo '0';
	}
	?>"/>
	<input type="hidden" name="delEditingScienceQuery" value="<?php echo $delEditingScienceQuery; ?>"/>
	<input type="hidden" name="delEditingPatentQuery" value="<?php echo $delEditingPatentQuery; ?>"/>
	<div class="body">
		<h1>Settings</h1>
		<div class="graphContainer">
			<div class="graphSubContainer graphSubContainer00">
				<input type="radio" name="radio-btn00" id="graph-0_00" checked />
				<div id="graph_00_00" class="graph">
					<div class="prev"><label for="graph-2_00" class="arrow">&#10096;</label></div>
					<div class="containerChart">
						<div class="titleChart">Results by keyword</div>
						<div class="bar-chart">
							<ul class="axis-y">
								<li data-axisy="100"></li>
								<li data-axisy="75"></li>
								<li data-axisy="50"></li>
								<li data-axisy="25"></li>
								<li data-axisy="0"></li>
							</ul>
							<ul class="axis-x">
								<li title="Ours : &#10;500 results"><i>Ours</i></li>
								<li title="Neutron : &#10;445 results"><i>Neutron</i></li>
								<li title="Chien Vert : &#10;670 results"><i>Chien Vert</i></li>
								<li title="wikileaks : &#10;205 results"><i>wikileaks</i></li>
								<li title="nucléaire : &#10;450 results"><i>nucléaire</i></li>
								<li title="Test : &#10;825 results"><i>Test</i></li>
								<li title=":All : &#10;420 results"><i>:All</i></li>
								<li title="Rat-thon : &#10;600 results"><i>Rat-thon</i></li>
							</ul>
						</div>
					</div>
					<div class="next"><label for="graph-1_00" class="arrow">&#10097;</label></div>
				</div>
				<input type="radio" name="radio-btn00" id="graph-1_00" />
				<div id="graph_00_01" class="graph">
					<div class="prev"><label for="graph-0_00" class="arrow">&#10096;</label></div>
					<div class="containerChart">
						<div class="titleChart">Distribution of results by source</div>
						<div class="torChart">
							<div class="circle">
								<div class="source1">
									<div title="Nextinpact :&#10;65%" class="s1"></div>
								</div>
								<div class="source2">
									<div title="Les crises :&#10;35%" class="s2"></div>
								</div>
								<div class="source3">
									<div title="Numerama :&#10;15%" class="s3"></div>
								</div>
								<div class="source4">
									<div title="Le monde :&#10;20%" class="s4"></div>
								</div>
							</div>
							<div class="center"></div>
						</div>
					</div>
					<div class="next"><label for="graph-2_00" class="arrow">&#10097;</label></div>
				</div>
				<input type="radio" name="radio-btn00" id="graph-2_00" />
				<div id="graph_00_02" class="graph">
					<div class="prev"><label for="graph-1_00" class="arrow">&#10096;</label></div>
					<div class="containerChart">
						<div class="titleChart">Results by query</div>
						<div class="bar-chart">
							<ul class="axis-y">
								<li data-axisy="100"></li>
								<li data-axisy="75"></li>
								<li data-axisy="50"></li>
								<li data-axisy="25"></li>
								<li data-axisy="0"></li>
							</ul>
							<ul class="axis-x">
								<li title="Query00 : &#10;570 results"><i>Query00</i></li>
								<li title="Query01 : &#10;885 results"><i>Query01</i></li>
								<li title="Query02 : &#10;400 results"><i>Query02</i></li>
								<li title="Query03 : &#10;290 results"><i>Query03</i></li>
								<li title="Query04 : &#10;680 results"><i>Query04</i></li>
								<li title="Query05 : &#10;885 results"><i>Query05</i></li>
								<li title="Query06 : &#10;520 results"><i>Query06</i></li>
								<li title="Query07 : &#10;620 results"><i>Query07</i></li>
							</ul>
						</div>
					</div>
					<div class="next"><label for="graph-0_00" class="arrow">&#10097;</label></div>
				</div>
			</div>
			<div class="graphSubContainer graphSubContainer01">
				<input type="radio" name="radio-btn01" id="graph-0_01" checked />
				<div id="graph_01_00" class="graph">
					<div class="prev"><label for="graph-2_01" class="arrow">&#10096;</label></div>
					<div class="containerChart">
						<div class="titleChart">Results by query</div>
						<div class="bar-chart">
							<ul class="axis-y">
								<li data-axisy="100"></li>
								<li data-axisy="75"></li>
								<li data-axisy="50"></li>
								<li data-axisy="25"></li>
								<li data-axisy="0"></li>
							</ul>
							<ul class="axis-x">
								<li title="Query00 : &#10;570 results"><i>Query00</i></li>
								<li title="Query01 : &#10;885 results"><i>Query01</i></li>
								<li title="Query02 : &#10;400 results"><i>Query02</i></li>
								<li title="Query03 : &#10;290 results"><i>Query03</i></li>
								<li title="Query04 : &#10;680 results"><i>Query04</i></li>
								<li title="Query05 : &#10;885 results"><i>Query05</i></li>
								<li title="Query06 : &#10;520 results"><i>Query06</i></li>
								<li title="Query07 : &#10;620 results"><i>Query07</i></li>
							</ul>
						</div>
					</div>
					<div class="next"><label for="graph-1_01" class="arrow">&#10097;</label></div>
				</div>
				<input type="radio" name="radio-btn01" id="graph-1_01" />
				<div id="graph_01_01" class="graph">
					<div class="prev"><label for="graph-0_01" class="arrow">&#10096;</label></div>
					<div class="containerChart">
						<div class="titleChart">Number of results per query</div>
						<div class="backChart">
							<ul class="axis-y">
								<li data-axisy="100"></li>
								<li data-axisy="75"></li>
								<li data-axisy="50"></li>
								<li data-axisy="25"></li>
								<li data-axisy="0"></li>
							</ul>
							<ul class="axis-time">
								<li data-time="Mars"></li>
								<li data-time="Avril"></li>
								<li data-time="Mai"></li>
								<li data-time="Juin"></li>
								<li data-time="Juillet"></li>
								<li data-time="Août"></li>
							</ul>
							<div class="curveChart">
								<div class="curveChartPart">
									<div title="Query01 : 15 results in march" class="query01">
									</div>
									<div title="Query02" class="query02">
									</div>
									<div title="Query03" class="query03">
									</div>
								</div>
								<div class="curveChartPart">
									<div title="" class="query01">
									</div>
									<div title="" class="query02">
									</div>
									<div title="" class="query03">
									</div>
								</div>
								<div class="curveChartPart">
									<div title="" class="query01">
									</div>
									<div title="" class="query02">
									</div>
									<div title="" class="query03">
									</div>
								</div>
								<div class="curveChartPart">
									<div title="" class="query01">
									</div>
									<div title="" class="query02">
									</div>
									<div title="" class="query03">
									</div>
								</div>
								<div class="curveChartPart">
									<div title="" class="query01">
									</div>
									<div title="" class="query02">
									</div>
									<div title="" class="query03">
									</div>
								</div>
								<div class="curveChartPart">
									<div title="" class="query01">
									</div>
									<div title="" class="query02">
									</div>
									<div title="" class="query03">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="next"><label for="graph-2_01" class="arrow">&#10097;</label></div>
				</div>
				<input type="radio" name="radio-btn01" id="graph-2_01" />
				<div id="graph_01_02" class="graph">
					<div class="prev"><label for="graph-1_01" class="arrow">&#10096;</label></div>
					<div class="containerChart">
						<div class="titleChart">Results read per query</div>
						<div class="torChart">
							<div class="circle">
								<div class="source1">
									<div title="Query01 :&#10;65% read" class="s1"></div>
								</div>
								<div class="source2">
									<div title="Query02 :&#10;35% read" class="s2"></div>
								</div>
								<div class="source3">
									<div title="Query03 :&#10;15% read" class="s3"></div>
								</div>
								<div class="source4">
									<div title="Query04 :&#10;20% read" class="s4"></div>
								</div>
							</div>
							<div class="center"></div>
						</div>
					</div>
					<div class="next"><label for="graph-0_01" class="arrow">&#10097;</label></div>
				</div>
			</div>
			<div class="graphSubContainer graphSubContainer02">
				<input type="radio" name="radio-btn02" id="graph-0_02" checked />
				<div id="graph_02_00" class="graph">
					<div class="prev"><label for="graph-2_02" class="arrow">&#10096;</label></div>
					<div class="containerChart">
						<div class="titleChart">Results by query</div>
						<div class="bar-chart">
							<ul class="axis-y">
								<li data-axisy="100"></li>
								<li data-axisy="75"></li>
								<li data-axisy="50"></li>
								<li data-axisy="25"></li>
								<li data-axisy="0"></li>
							</ul>
							<ul class="axis-x">
								<li title="Query00 : &#10;500 results"><i>Query00</i></li>
								<li title="Query01 : &#10;445 results"><i>Query01</i></li>
								<li title="Query02 : &#10;670 results"><i>Query02</i></li>
								<li title="Query03 : &#10;205 results"><i>Query03</i></li>
								<li title="Query04 : &#10;450 results"><i>Query04</i></li>
								<li title="Query05 : &#10;825 results"><i>Query05</i></li>
								<li title="Query06 : &#10;420 results"><i>Query06</i></li>
								<li title="Query07 : &#10;600 results"><i>Query07</i></li>
							</ul>
						</div>
					</div>
					<div class="next"><label for="graph-1_02" class="arrow">&#10097;</label></div>
				</div>
				<input type="radio" name="radio-btn02" id="graph-1_02" />
				<div id="graph_02_01" class="graph">
					<div class="prev"><label for="graph-0_02" class="arrow">&#10096;</label></div>
					<div class="containerChart">
						<div class="titleChart">Number of results per query</div>
						<div class="backChart">
							<ul class="axis-y">
								<li data-axisy="100"></li>
								<li data-axisy="75"></li>
								<li data-axisy="50"></li>
								<li data-axisy="25"></li>
								<li data-axisy="0"></li>
							</ul>
							<ul class="axis-time">
								<li data-time="Mars"></li>
								<li data-time="Avril"></li>
								<li data-time="Mai"></li>
								<li data-time="Juin"></li>
								<li data-time="Juillet"></li>
								<li data-time="Août"></li>
							</ul>
							<div class="curveChart">
								<div class="curveChartPart">
									<div title="Query01 : 15 results in march" class="query01">
									</div>
									<div title="Query02" class="query02">
									</div>
									<div title="Query03" class="query03">
									</div>
								</div>
								<div class="curveChartPart">
									<div title="" class="query01">
									</div>
									<div title="" class="query02">
									</div>
									<div title="" class="query03">
									</div>
								</div>
								<div class="curveChartPart">
									<div title="" class="query01">
									</div>
									<div title="" class="query02">
									</div>
									<div title="" class="query03">
									</div>
								</div>
								<div class="curveChartPart">
									<div title="" class="query01">
									</div>
									<div title="" class="query02">
									</div>
									<div title="" class="query03">
									</div>
								</div>
								<div class="curveChartPart">
									<div title="" class="query01">
									</div>
									<div title="" class="query02">
									</div>
									<div title="" class="query03">
									</div>
								</div>
								<div class="curveChartPart">
									<div title="" class="query01">
									</div>
									<div title="" class="query02">
									</div>
									<div title="" class="query03">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="next"><label for="graph-2_02" class="arrow">&#10097;</label></div>
				</div>
				<input type="radio" name="radio-btn02" id="graph-2_02" />
				<div id="graph_02_02" class="graph">
					<div class="prev"><label for="graph-1_02" class="arrow">&#10096;</label></div>
					<div class="containerChart">
						<div class="titleChart">Results read per query</div>
						<div class="torChart">
							<div class="circle">
								<div class="source1">
									<div title="Query01 :&#10;65% read" class="s1"></div>
								</div>
								<div class="source2">
									<div title="Query02 :&#10;35% read" class="s2"></div>
								</div>
								<div class="source3">
									<div title="Query03 :&#10;15% read" class="s3"></div>
								</div>
								<div class="source4">
									<div title="Query04 :&#10;20% read" class="s4"></div>
								</div>
							</div>
							<div class="center"></div>
						</div>
					</div>
					<div class="next"><label for="graph-0_02" class="arrow">&#10097;</label></div>
				</div>
			</div>
		</div>

		<div class="communicationResults">
			<h2>Communication of results</h2>
			<div class="divRow">
				<div>
					<div>
						<h3>Your email</h3>
						<input type="email" name="email" id="email" value="<?php echo $userSettings['email']; ?>"/>
					</div>
					<h3 >Result page</h3>
					<p>
						Background <select size="<?php echo count($backgroundList); ?>" id="selectBackgroundPreview" class="selectBackground" name="backgroundResult">
						<?php
						foreach ($backgroundList as $backgroundName)
						{
							if ($userSettings['background_result'] == $backgroundName['name'])
							{
								$backgroundSelected = 'selected';
							}
							else
							{
								$backgroundSelected = '';
							}

							echo '<option value="' . $backgroundName['name'] . '" ' . $backgroundSelected . ' id="../images/background/' . $backgroundName['filename'] . '">' . $backgroundName['name'] . '</option>'.PHP_EOL;
						}
						?>
							<!--<option value="random">Random</option>-->
						</select>
						<?php
						foreach ($backgroundList as $backgroundName)
						{
							echo '<img alt="' . $backgroundName['name'] . '" src=../images/background/' . $backgroundName['filename'] . ' style="display: none;" />';
						}
						?>
					</p>
				</div>
				<div>
					<h3>Sending condition</h3>
					<p>
						<input type="radio" id="condNbLink" name="cond" value="link_limit" <?php echo $condNbLink; ?>>
						<label class="radio" for="condNbLink"></label>
						by number of links :
						<input class="number alpha" type="number" name="numberLinks" min="5" placeholder="50" value="<?php echo $userSettings['link_limit']; ?>"/>
					</p>
					<p>
						<input type="radio" id="condFreq" name="cond" value="freq" <?php echo $condFreq; ?>>
						<label class="radio" for="condFreq"></label>
						by frequency, every
						<input class="number alpha" type="number" name="freq" min="2" max="500" step="2" placeholder="24" value="<?php echo $userSettings['frequency']; ?>"/> hours
					</p>
					<p>
						<input type="radio" id="condDate" name="cond" value="deadline" <?php echo $condDate; ?>>
						<label class="radio" for="condDate"></label>
						at
						<input class="number alpha" type="number" name="hours" min="0" max="23" step="2" placeholder="19" value="<?php echo $userSettings['selected_hour']; ?>"/> h
						, every
						<select class="selectCommResult" name="days">
							<option value=",1,2,3,4,5," <?php echo isset($day[0]) ? $day[0]:''; ?>>business day</option>
							<option value=",1,3,5," <?php echo isset($day[8]) ? $day[8]:''; ?>>second business day</option>
							<option value=",1,2,3,4,5,6,7," <?php echo isset($day[9]) ? $day[9]:''; ?>>day</option>
							<option value=",1," <?php echo isset($day[1]) ? $day[1]:''; ?>>monday</option>
							<option value=",2," <?php echo isset($day[2]) ? $day[2]:''; ?>>tuesday</option>
							<option value=",3," <?php echo isset($day[3]) ? $day[3]:''; ?>>wednesday</option>
							<option value=",4," <?php echo isset($day[4]) ? $day[4]:''; ?>>thursday</option>
							<option value=",5," <?php echo isset($day[5]) ? $day[5]:''; ?>>friday</option>
							<option value=",6," <?php echo isset($day[6]) ? $day[6]:''; ?>>saturday</option>
							<option value=",7," <?php echo isset($day[7]) ? $day[7]:''; ?>>sunday</option>
						</select>
						<span class="arrDown">▾</span>
						and
						<select class="selectCommResult" name="secondDay">
							<option value="">that's all</option>
							<option value="1," <?php echo isset($day2[1]) ? $day2[1]:''; ?>>monday</option>
							<option value="2," <?php echo isset($day2[2]) ? $day2[2]:''; ?>>tuesday</option>
							<option value="3," <?php echo isset($day2[3]) ? $day2[3]:''; ?>>wednesday</option>
							<option value="4," <?php echo isset($day2[4]) ? $day2[4]:''; ?>>thursday</option>
							<option value="5," <?php echo isset($day2[5]) ? $day2[5]:''; ?>>friday</option>
							<option value="6," <?php echo isset($day2[6]) ? $day2[6]:''; ?>>saturday</option>
							<option value="7," <?php echo isset($day2[7]) ? $day2[7]:''; ?>>sunday</option>
						</select>
						<span class="arrDown">▾</span>
					</p>
				</div>
				<div>
					<h3>Sorting for links in email</h3>
					<p>
						<input type="radio" id="orderByKeyword" name="orderBy" value="masterword" <?php echo $orderByKeyword; ?>>
						<label class="radio" for="orderByKeyword"></label>
						by keyword
					</p>
					<p>
						<input type="radio" id="orderBySource" name="orderBy" value="origin" <?php echo $orderBySource; ?>>
						<label class="radio" for="orderBySource"></label>
						by source
					</p>
					<p>
						<input type="radio" id="orderByAlpha" name="orderBy" value="type" <?php echo $orderByType; ?>>
						<label class="radio" for="orderByAlpha"></label>
						by alphabetical order
					</p>
				</div>
				<div>
					<h3>Privacy</h3>
					<div class="align">
						<div class="switch">
							<label for="recordRead">
								<input type="checkbox" id="recordRead" name="recordRead" value="active" <?php echo $recordRead; ?>>
								<div class="slider"></div>
							</label>
						</div>
						Record when a link is clicked
					</div>
					<div>
						<div class="deleteContainer">
							<div class="deleteLogo"></div>
							<input title="Delete" class="deleteButton" type="submit" name="buttonDeleteHistory" value="deleteHistory" />
							the last&nbsp;
							<input class="number alpha" name="deleteHistoryValue" type="number" min="1" value="1"/>
							&nbsp;
							<select class="selectCommResult Unit" name="deleteHistoryUnit">
								<option value="hour">Hour</option>
								<option value="day">Day</option>
								<option value="week">Week</option>
								<option value="month">Month</option>
								<option value="year">Year</option>
							</select>
							<span class="arrDown">▾</span>
						</div>
					</div>
				</div>
			</div>
			<div class="centerSubmit">
				<input title="Submit settings" class="submit" type="submit" name="settings" value="ChangeSettings" />
			</div>
		</div>

		<div class="keywordManagement">
			<h2>News management</h2>
			<a href="#helpNews" class="helpModalWindow"></a>
			<div id="helpNews">Help</div>
			<div class="newsInput">
				<input title="Add new keyword" class="submit" type="submit" value="" />
				<select name="sourceKeyword" id="sourceKeyword">
					<?php
					foreach ($reqReadOwnerSourcestmp as $ownerSourcesList)
					{
						if ($ownerSourcesList['id'] == $_SESSION['lastSourceUse'])
						{
							$amISelected = 'selected';
						}
						else
						{
							$amISelected = '';
						}

						$ownerSourcesList['name'] = preg_replace("/\[!NEW!\]/", "", $ownerSourcesList['name']);
						if (empty($ownerSourcesList['name']))
						{
							preg_match("/^https?:\/\/[^\/]*\//", $ownerSourcesList['link'], $sourceNameBaseLink);
							$ownerSourcesList['name'] = preg_replace("/(https?|\/|:)/", "", $sourceNameBaseLink[0]);
						}
						echo '<option value="source' . $ownerSourcesList['id'] . '" ' . $amISelected . '>' . $ownerSourcesList['name'] . '</option>' . PHP_EOL;
					}
					?>
					<option value="source00">All sources</option>
				</select>
				<span class="arrDownBorder">▾</span>
				<input type="text" class="keywordInput" name="newKeyword" id="keyword" placeholder="Keyword,next keyword, ..." />
			</div>
			<div class="newsInput">
				<input title="Add new source" class="submit" type="submit" value="" />
				<select name="sourceType" id="sourceType">
					<option value="inputSource">Add my own source</option>
					<option value="sourceGeneralEN">General news source pack [English]</option>
					<option value="sourceTechnologyEN">Technology source pack [English]</option>
					<option value="sourceSpatialEN">Spatial source pack [English]</option>
					<option value="sourceGeneralFR">General news source pack [French]</option>
					<option value="sourceTechnologyFR">Technology source pack [French]</option>
					<option value="sourceSpacialFR">Spatial source pack [French]</option>
				</select>
				<span class="arrDownBorder">▾</span>
				<input type="url" name="newSource" id="source" placeholder="Source" />
			</div>
			<?php echo $ERROR_MESSAGE; ?>

			<div class="inlineButton">
				<a class="buttonCreatesourcePack" href="watchPack?type=create">Create my own source pack</a>
				<a class="buttonVisiteCommunitySourcePack" href="watchPack?type=add">Add community source pack</a>
			</div>

			<div>
				<?php
				$cptSource = 0;
				foreach ($reqReadOwnerSourcestmp as $ownerSourcesList)
				{
					$ownerSourcesList['name'] = preg_replace("/\[!NEW!\]/", "", $ownerSourcesList['name']);
					if (empty($ownerSourcesList['name']))
					{
						preg_match("/^https?:\/\/[^\/]*\//", $ownerSourcesList['link'], $sourceNameBaseLink);
						$ownerSourcesList['name'] = preg_replace("/(https?|\/|:)/", "", $sourceNameBaseLink[0]);
					}
					preg_match("/./", ucfirst($ownerSourcesList['name']), $rssFirstLetter);

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
						<input type="checkbox" name="radio-s' . $rssFirstLetter[0] . '" id="unfold-s' . $rssFirstLetter[0] . '" value="' . $rssFirstLetter[0] . '" ' . $amICheckFoldSource . '/>'.
						'<div class="sourceList" >'.
							'<label for="unfold-s' . $rssFirstLetter[0] . '" class="unfoldTag">'.
								$rssFirstLetter[0] . ' ▾'.
							'</label>'.
							'<label for="unfold-s' . $rssFirstLetter[0] . '" class="foldTag">'.
								$rssFirstLetter[0] . ' ▴'.
							'</label>';
					}

					$foldKeywordName = 'radio-ks' . $ownerSourcesList['id'];
					$amICheckFoldKeyword = '';
					if (isset($_SESSION[$foldKeywordName]))
					{
						if ($_SESSION[$foldKeywordName] == $ownerSourcesList['id'])
						{
							$amICheckFoldKeyword = 'checked';
						}
					}

					if (preg_match("/," . $_SESSION['id'] . ",/", $ownerSourcesList['owners']))
					{
						echo
						'<div class="tagSource Tactive" id="ks' . $ownerSourcesList['id'] . '">'.
							'<input type="submit" title="Delete" name="delSource" value="source' . $ownerSourcesList['id'] . '&"/>'.
							'<input type="submit" title="Disable" name="disableSource" value="source' . $ownerSourcesList['id']. '&"/>'.
							'<a href="' . $ownerSourcesList['link']. '" target="_blank">'.
								ucfirst($ownerSourcesList['name']).
							'</a>'.
						'</div>';
					}
					elseif (preg_match("/,!" . $_SESSION['id'] . ",/", $ownerSourcesList['owners']))
					{
						echo
						'<div class="tagSource Tdisable" id="ks' . $ownerSourcesList['id'] . '">'.
							'<input type="submit" title="Delete" name="delSource" value="source' . $ownerSourcesList['id'] . '&"/>'.
							'<input type="submit" title="Activate" name="activateSource" value="source' . $ownerSourcesList['id']. '&"/>'.
							'<a href="' . $ownerSourcesList['link']. '" target="_blank">'.
								ucfirst($ownerSourcesList['name']).
							'</a>'.
						'</div>';
					}

					echo
					'<input type="checkbox" name="radio-ks' . $ownerSourcesList['id'] . '" id="unfold-ks' . $ownerSourcesList['id'] . '" value="' . $ownerSourcesList['id'] . '" ' . $amICheckFoldKeyword . '/>'.
					'<div class="keywordList" id="keywordList' . $ownerSourcesList['id'] . '">'.
						'<label for="unfold-ks' . $ownerSourcesList['id'] . '" id="unfold' . $ownerSourcesList['id'] . '"  class="unfoldTag">'.
							'Unfold keyword list ▾'.
						'</label>'.
						'<label for="unfold-ks' . $ownerSourcesList['id'] . '" id="fold' . $ownerSourcesList['id'] . '" class="foldTag">'.
							'Fold keyword list ▴'.
						'</label>';

					# Keyword loop
					$cptKeyword = 0;
					foreach ($reqReadOwnerSourcesKeywordtmp as $ownerKeywordList)
					{
						$applicable_owners_sources = $ownerKeywordList['applicable_owners_sources'];
						$ownerKeywordList['keyword'] = preg_replace("/^:all@[0-9]+$/", ":All", $ownerKeywordList['keyword']);
						$ownerKeywordList['keyword'] = preg_replace("/^\[!ALERT!\]/", "&#9888; ", $ownerKeywordList['keyword']);
						if (preg_match("/\|" . $_SESSION['id'] . ":[,!0-9,]*," . $ownerSourcesList['id'] . ",[,!0-9,]*\|/", $applicable_owners_sources))
						{
							echo
							'<div class="tag Tactive">'.
								'<input type="submit" title="Delete" name="delKeyword" value="source'. $ownerSourcesList['id'] . '&keyword' . $ownerKeywordList['id'] . '&"/>'.
								'<input type="submit" title="Disable" name="disableKeyword" value="source'. $ownerSourcesList['id'] . '&keyword' . $ownerKeywordList['id'] . '&"/>'.
								'<a href="setting?keyword=keyword' . $ownerKeywordList['id'] . '">'.
									ucfirst($ownerKeywordList['keyword']).
								'</a>'.
							'</div>';
							$cptKeyword++;
						}
						elseif (preg_match("/\|" . $_SESSION['id'] . ":[,!0-9,]*,!" . $ownerSourcesList['id'] . ",[,!0-9,]*\|/", $applicable_owners_sources))
						{
							echo
							'<div class="tag Tdisable">'.
								'<input type="submit" title="Delete" name="delKeyword" value="source'. $ownerSourcesList['id'] . '&keyword' . $ownerKeywordList['id'] . '&"/>'.
								'<input type="submit" title="Activate" name="activateKeyword" value="source'. $ownerSourcesList['id'] . '&keyword' . $ownerKeywordList['id'] . '&"/>'.
								'<a href="setting?keyword=keyword' . $ownerKeywordList['id'] . '">'.
									ucfirst($ownerKeywordList['keyword']).
								'</a>'.
							'</div>';
							$cptKeyword++;
						}
					}
					echo '</div>' . PHP_EOL;

					if ($cptKeyword < 7)
					{
						$style = $style . PHP_EOL .
						'#unfold' . $ownerSourcesList['id'] . ',' . PHP_EOL .
						'#fold' . $ownerSourcesList['id'] . PHP_EOL .
						'{' . PHP_EOL .
						'	display: none;' . PHP_EOL .
						'}' . PHP_EOL .
						'#keywordList' . $ownerSourcesList['id'] . PHP_EOL .
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
			<h2>Science watch management</h2>
			<div class="newQueryContainer">
				<div class="lineQuery">
				<input title="Add new science query" class="submit" type="submit" name="scienceQuerySubmit" value="add" />
				<?php
				$cpt = 0;
				$logicalConnector = '';

				while ($cpt < $_SESSION['cptScienceQuery'])
				{
					echo $logicalConnector;

					if (intval($cpt/3) == ($cpt/3) AND $cpt != 0)
					{
						echo '</div>';
					}

					if (intval($cpt/3) == ($cpt/3) AND $cpt != 0)
					{
						echo '<div class="lineQuery">';
					}

					if (intval($cpt/3) == ($cpt/3) AND $cpt != 0)
					{
						echo '<div class="ghostSpace"></div>';
					}

					$selected['ti'] = '';
					$selected['au'] = '';
					$selected['abs'] = '';
					$selected['jr'] = '';
					$selected['cat'] = '';
					$selected['all'] = '';

					if (isset($_POST['scienceType' . $cpt]))
					{
						$selected[$_POST['scienceType' . $cpt]] = 'selected';
					}

					if (isset($_POST['openParenthesis' . $cpt]) AND $_POST['openParenthesis' . $cpt] == "active")
					{
						$checked['openParenthesis' . $cpt] = 'checked';
					}
					else
					{
						$checked['openParenthesis' . $cpt] = '';
					}

					if (isset($_POST['scienceQuery' . $cpt]))
					{
						$_POST['scienceQuery' . $cpt] = htmlspecialchars($_POST['scienceQuery' . $cpt]);
					}
					else
					{
						$_POST['scienceQuery' . $cpt] = '';
					}

					echo '
					<input type="checkbox" id="openParenthesis' . $cpt . '" name="openParenthesis' . $cpt . '" value="active" ' . $checked['openParenthesis' . $cpt] . '/>
					<label class="queryParenthesis" for="openParenthesis' . $cpt . '">(</label>
					<select title="Type" class="queryType" name="scienceType' . $cpt . '" id="scienceType0' . $cpt . '">
						<option value="ti" ' . $selected['ti'] . '>Title</option>
						<option value="au" ' . $selected['au'] . '>Author</option>
						<option value="abs" ' . $selected['abs'] . '>Abstract</option>
						<option value="jr" ' . $selected['jr'] . '>Reference</option>
						<option value="cat" ' . $selected['cat'] . '>Category</option>
						<option value="all" ' . $selected['all'] . '>All</option>
					</select>
					<span class="arrDownBorder">▾</span>
					<input type="text" class="query" name="scienceQuery' . $cpt . '" id="scienceQuery0' . $cpt . '" placeholder="Keyword" value="' . $_POST['scienceQuery' . $cpt] . '"/>';


					if (isset($_POST['closeParenthesis' . $cpt]) AND $_POST['closeParenthesis' . $cpt] == "active")
					{
						$checked['closeParenthesis' . $cpt] = 'checked';
					}
					else
					{
						$checked['closeParenthesis' . $cpt] = '';
					}
					echo '
					<input type="checkbox" id="closeParenthesis' . $cpt . '" name="closeParenthesis' . $cpt . '" value="active" ' . $checked['closeParenthesis' . $cpt] . '/>
					<label class="queryParenthesis" for="closeParenthesis' . $cpt . '">)</label>';

					$cpt++;

					$checked['OR'] = '';
					$checked['AND'] = '';
					$checked['NOTAND'] = '';
					if (empty($_POST['andOrAndnot' . $cpt]))
					{
						$checked['OR'] = 'checked';
					}
					else
					{
						$checked[$_POST['andOrAndnot' . $cpt]] = 'checked';
					}

					$logicalConnector = '
					<div class="btnList">
						<input type="radio" id="andOrNotand_AND0' . $cpt . '" name="andOrAndnot' . $cpt . '" value="AND" ' . $checked['AND'] . '>
						<label class="ANDOrNotand" for="andOrNotand_AND0' . $cpt . '"></label>
						<input type="radio" id="andOrNotand_OR0' . $cpt . '" name="andOrAndnot' . $cpt . '" value="OR" ' . $checked['OR'] . '>
						<label class="andORNotand" for="andOrNotand_OR0' . $cpt . '"></label>
						<input type="radio" id="andOrNotand_NOTAND0' . $cpt . '" name="andOrAndnot' . $cpt . '" value="NOTAND" ' . $checked['NOTAND'] . '>
						<label class="andOrNOTAND" for="andOrNotand_NOTAND0' . $cpt . '"></label>
					</div>';
				}
				?>
				<input title="Extend" class="extend" type="submit" id="extendScience" name="extendScience" value=">>" />
			</div>
			</div>
			<?php echo isset($ERROR_SCIENCEQUERY) ? $ERROR_SCIENCEQUERY:''; ?>
			<?php
			include_once('model/readOwnerScienceQuery.php');
			foreach ($queries as $query)
			{
				$queryDisplay = '';
				$Qdisable = '';
				$titleDisableActivate = "Disable";
				$nameClassDisableActivate = "disable";

				$pattern = ',!' . $_SESSION['id'] . ',';
				if (preg_match("/$pattern/", $query['owners']))
				{
					$Qdisable = 'Qdisable';
					$titleDisableActivate = "Activate";
					$nameClassDisableActivate = "activate";
				}

				echo '
				<div class="queryContainer ' . $Qdisable . '">
					<input type="submit" title="Delete" class="deleteQuery" name="delQueryScience" value="query' . $query['id'] . '"/>
					<input type="submit" title="' . $titleDisableActivate . '" class="' . $nameClassDisableActivate . 'Query" name="' . $nameClassDisableActivate . 'QueryScience" value="query' . $query['id'] . '"/>
				';

				$queryId = $query['id'];

				$queryFieldsName['ti']  = 'Title';
				$queryFieldsName['au']  = 'Author';
				$queryFieldsName['abs'] = 'Abstract';
				$queryFieldsName['cat'] = 'Category';
				$queryFieldsName['jr']  = 'Reference';
				$queryFieldsName['all'] = 'All';

				$query = $query['query_arxiv'];
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

				echo $queryDisplay . '</div>';
			}
			?>
		</div>
		<div class="patentManagement">
			<h2>Patent watch management</h2>
			<div class="newQueryContainer">
				<div class="lineQuery">
				<input title="Add new patents query" class="submit" type="submit" name="patentQuerySubmit" value="add" />
				<?php
				$cpt = 0;
				$logicalConnector = '';

				while ($cpt < $_SESSION['cptPatentQuery'])
				{
					echo $logicalConnector;

					if (intval($cpt/3) == ($cpt/3) AND $cpt != 0)
					{
						echo '</div>';
					}

					if (intval($cpt/3) == ($cpt/3) AND $cpt != 0)
					{
						echo '<div class="lineQuery">';
					}

					if (intval($cpt/3) == ($cpt/3) AND $cpt != 0)
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
					if (isset($_POST['patentType' . $cpt]))
					{
						$selected[$_POST['patentType' . $cpt]] = 'selected';
					}

					if (isset($_POST['patentQuery' . $cpt]))
					{
						$_POST['patentQuery' . $cpt] = htmlspecialchars($_POST['patentQuery' . $cpt]);
					}
					else
					{
						$_POST['patentQuery' . $cpt] = '';
					}

					echo '
				<select title="Type" class="queryType" name="patentType' . $cpt . '" id="patentType' . $cpt . '">
					<option value="ALLNAMES" ' . $selected['ALLNAMES'] . '>All Names</option>
					<option value="ALLNUM" ' . $selected['ALLNUM'] . '>All Numbers and IDs</option>
					<option value="AAD" ' . $selected['AAD'] . '>Applicant Address</option>
					<option value="AADC" ' . $selected['AADC'] . '>Applicant Address Country</option>
					<option value="PAA" ' . $selected['PAA'] . '>Applicant All Data</option>
					<option value="PA" ' . $selected['PA'] . '>Applicant Name</option>
					<option value="ANA" ' . $selected['ANA'] . '>Applicant Nationality</option>
					<option value="ARE" ' . $selected['ARE'] . '>Applicant Residence</option>
					<option value="AD" ' . $selected['AD'] . '>Application Date</option>
					<option value="AN" ' . $selected['AN'] . '>Application Number</option>
					<option value="CHEM" ' . $selected['CHEM'] . '>Chemical</option>
					<option value="CTR" ' . $selected['CTR'] . '>Country</option>
					<option value="DS" ' . $selected['DS'] . '>Designated States</option>
					<option value="EN_AB" ' . $selected['EN_AB'] . '>English Abstract</option>
					<option value="EN_ALL" ' . $selected['EN_ALL'] . '>English All</option>
					<option value="EN_CL" ' . $selected['EN_CL'] . '>English Claims</option>
					<option value="EN_DE" ' . $selected['EN_DE'] . '>English Description</option>
					<option value="EN_ALLTXT" ' . $selected['EN_ALLTXT'] . '>English Text</option>
					<option value="EN_TI" ' . $selected['EN_TI'] . '>English Title</option>
					<option value="EN_EX" ' . $selected['IC_EX'] . '>Exact IPC code</option>
					<option value="LGF" ' . $selected['LGF'] . '>Filing Language</option>
					<option value="FP" ' . $selected['FP'] . '>Front Page(FP)</option>
					<option value="GN" ' . $selected['GN'] . '>Grant Number</option>
					<option value="IC" ' . $selected['IC'] . '>International Class</option>
					<option value="ICI" ' . $selected['ICI'] . '>International Class Inventive</option>
					<option value="ICN" ' . $selected['ICN'] . '>International Class N-Inventive</option>
					<option value="IPE" ' . $selected['IPE'] . '>International Preliminary Examination</option>
					<option value="ISA" ' . $selected['ISA'] . '>International Search Authority</option>
					<option value="ISR" ' . $selected['ISR'] . '>International Search Report</option>
					<option value="INA" ' . $selected['INA'] . '>Inventor All Data</option>
					<option value="IN" ' . $selected['IN'] . '>Inventor Name</option>
					<option value="IADC" ' . $selected['IADC'] . '>Inventor Nationality</option>
					<option value="RPA" ' . $selected['RPA'] . '>Legal Representative All Data</option>
					<option value="RCN" ' . $selected['RCN'] . '>Legal Representative Country</option>
					<option value="RP" ' . $selected['RP'] . '>Legal Representative Name</option>
					<option value="RAD" ' . $selected['RAD'] . '>Legal Representative Address</option>
					<option value="LI" ' . $selected['LI'] . '>Licensing availability</option>
					<option value="PAF" ' . $selected['PAF'] . '>Main Applicant Name</option>
					<option value="ICF" ' . $selected['ICF'] . '>Main International Class</option>
					<option value="INF" ' . $selected['INF'] . '>Main Inventor Name</option>
					<option value="RPF" ' . $selected['RPF'] . '>Main Legal Rep Name</option>
					<option value="NPA" ' . $selected['NPA'] . '>National Phase All Data</option>
					<option value="NPAN" ' . $selected['NPAN'] . '>National Phase Application Number</option>
					<option value="NPED" ' . $selected['NPED'] . '>National Phase Entry Date</option>
					<option value="NPET" ' . $selected['NPET'] . '>National Phase Entry Type</option>
					<option value="PN" ' . $selected['PN'] . '>National Publication Number</option>
					<option value="OF" ' . $selected['OF'] . '>Office Code</option>
					<option value="NPCC" ' . $selected['NPCC'] . '>National Phase Office Code</option>
					<option value="PRIORPCTAN" ' . $selected['PRIORPCTAN'] . '>Prior PCT Application Number</option>
					<option value="PRIORPCTWO" ' . $selected['PRIORPCTWO'] . '>Prior PCT WO Number</option>
					<option value="PI" ' . $selected['PI'] . '>Priority All Data</option>
					<option value="PCN" ' . $selected['PCN'] . '>Priority Country</option>
					<option value="PD" ' . $selected['PD'] . '>Priority Date</option>
					<option value="NP" ' . $selected['NP'] . '>Priority Number</option>
					<option value="DP" ' . $selected['DP'] . '>Publication Date</option>
					<option value="LGP" ' . $selected['LGP'] . '>Publication Language</option>
					<option value="SIS" ' . $selected['SIS'] . '>Supplementary International Search</option>
					<option value="TPO" ' . $selected['TPO'] . '>Third Party Observation</option>
					<option value="WO" ' . $selected['WO'] . '>WIPO Publication Number</option>
				</select>
				<span class="arrDownBorder">▾</span>
				<input type="text" class="query" name="patentQuery' . $cpt . '" id="patentQuery' . $cpt . '" placeholder="Keyword" value="' . $_POST['patentQuery' . $cpt] . '" />';

				$cpt++;

				$checked = '';
				if (isset($_POST['andOrPatent' . $cpt]) AND $_POST['andOrPatent' . $cpt] == 'OR')
				{
					$checked = 'checked';
				}

				$logicalConnector = '
				<input type="checkbox" id="patentAndOr' . $cpt . '" name="andOrPatent' . $cpt . '" value="OR" ' . $checked . '>
				<label class="andOr" for="patentAndOr' . $cpt . '"></label>';
				}
				?>
				<input title="Extend" class="extend" type="submit" id="extend" name="extendPatent" value=">>" />
			</div>
			</div>
			<?php echo isset($ERROR_PATENTQUERY) ? $ERROR_PATENTQUERY:''; ?>
			<?php
			include_once('model/readOwnerPatentQuery.php');
			foreach ($queries as $query)
			{
				$queryDisplay = '';
				$Qdisable = '';
				$titleDisableActivate = "Disable";
				$nameClassDisableActivate = "disable";

				$pattern = ',!' . $_SESSION['id'] . ',';
				if (preg_match("/$pattern/", $query['owners']))
				{
					$Qdisable = 'Qdisable';
					$titleDisableActivate = "Activate";
					$nameClassDisableActivate = "activate";
				}

				echo '
				<div class="queryContainer ' . $Qdisable . '">
					<input type="submit" title="Delete" class="deleteQuery" name="delQueryPatent" value="query' . $query['id'] . '"/>
					<input type="submit" title="' . $titleDisableActivate . '" class="' . $nameClassDisableActivate . 'Query" name="' . $nameClassDisableActivate . 'QueryPatent" value="query' . $query['id'] . '"/>
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

				echo $queryDisplay . '</div>';
			}
			?>
		</div>
	</div>
</form>
