<div class="background">
	<div class="sub-background">
	</div>
</div>
<form method="post" action="setting">
	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>"/>
	<input type="hidden" name="scrollPos" id="scrollPos" value="<?php
	if (!empty($data['scrollPos']))
	{
		echo $data['scrollPos'];
		$_SESSION['posBeforeRefresh'] = $data['scrollPos'];
	}
	elseif (empty($data['scrollPos']) && !empty($_SESSION['posBeforeRefresh']))
	{
		echo $_SESSION['posBeforeRefresh'];
		unset($_SESSION['posBeforeRefresh']);
	}
	?>"/>
	<input type="hidden" name="delEditingScienceQuery" value="<?php echo $delEditingScienceQuery; ?>"/>
	<input type="hidden" name="delEditingPatentQuery" value="<?php echo $delEditingPatentQuery; ?>"/>
	<div class="body-row">
		<div>
			<div class="aside-sticky-nav">
				<a href="#premium">
					<div class="aside-nav-background selectConfigTypePremium"><?php get_t('Premium', $bdd); ?></div>
				</a>
				<a href="#setting">
					<div class="aside-nav-background selectConfigTypeSetting"><?php get_t('Settings', $bdd); ?></div>
				</a>
				<a href="#watchPack">
					<div class="aside-nav-background selectConfigTypeWatchPack"><?php get_t('WatchPack', $bdd); ?></div>
				</a>
				<a href="#news">
					<div class="aside-nav-background selectConfigTypeNews"><?php get_t('News', $bdd); ?></div>
				</a>
			</div>
		</div>

		<div class="body">
			<div class="board">
				<h2><?php get_t('Account information', $bdd); ?></h2>
				<?php get_t('Hello', $bdd); ?>&nbsp;<?php echo $_SESSION['pseudo']; ?>,
				<div>
					<div>
						<div>
							<input type="checkbox" id="modal-controller" class="modal-open" hidden>
							<label for="modal-controller"><img alt="Premium" src="/images/pictogrammes/pictoPremium.png" class="icoText"/>&nbsp;<?php get_t('Premium presentation', $bdd); ?></label>
							<div class="modal-wrap">
								<label for="modal-controller" class="modal-overlay"></label>
								<div class="modal-body">

									<label for="modal-controller" class="modal-close">&times;</label>

									<input type="radio" name="content-nav" id="modal-content-1" class="modal-radio" checked hidden/>
									<input type="radio" name="content-nav" id="modal-content-2" class="modal-radio" hidden/>
									<input type="radio" name="content-nav" id="modal-content-3" class="modal-radio" hidden/>

									<div class="modal-slide content-1">
										<div class="modal-content">
											<h2><?php get_t('Premium feature - RSS Feed', $bdd); ?></h2>
											<div>
												<div>
													<?php get_t('functionality7_text_index', $bdd); ?>
												</div>
												<div>
													<h3 title="<?php get_t('Premium functionality', $bdd); ?>"><img alt="Premium" src="../images/pictogrammes/pictoPremium.png" class="icoTextSmall"/>&nbsp;<?php get_t('subtitle6_window1_setting', $bdd); ?></h3>
													<?php
													$rssLink = 'https://' . $_SERVER['HTTP_HOST'] . '/rss/' . $token[0]['token'];
													?>
													<?php get_t('subsubtitle1_window1_setting', $bdd); ?>
													<div class="align">
														<textarea class="falseInput" id="toCopy"><?php echo $rssLink; ?></textarea>
														<button class="copyButton" id="copy" title="<?php get_t('Copy RSS feed', $bdd); ?>" type="button" onclick="copyToClipboard('');"></button>
													</div><br>
													<?php get_t('subsubtitle2_window1_setting', $bdd); ?>
													<div class="align">
														<textarea class="falseInput" id="toCopyS"><?php echo $rssLink; ?>s</textarea>
														<button class="copyButton" id="copyS" title="<?php get_t('Copy RSS feed', $bdd); ?>" type="button" onclick="copyToClipboard('S');"></button>
													</div><br>
													<?php get_t('subsubtitle3_window1_setting', $bdd); ?>
													<div class="align">
														<textarea class="falseInput" id="toCopyP"><?php echo $rssLink; ?>p</textarea>
														<button class="copyButton" id="copyP" title="<?php get_t('Copy RSS feed', $bdd); ?>" type="button" onclick="copyToClipboard('P');"></button>
													</div>
												</div>
												<div class="modal-nav">
													<label for="modal-content-2" class="next-slide">&#8250;</label>
												</div>
											</div>
										</div>
									</div>

									<div class="modal-slide content-2">
										<div class="modal-content">
											<h2><?php get_t('Premium feature - Email', $bdd); ?></h2>
											<div>
												<div class="modal-nav">
													<label for="modal-content-1" class="prev-slide">&#8249;</label>
												</div>
												<div>
													<?php get_t('functionality4_text_index', $bdd); ?>
												</div>
												<div class="imgPrem imgEmail">
												</div>
												<div class="modal-nav">
													<label for="modal-content-3" class="next-slide">&#8250;</label>
												</div>
											</div>
										</div>
									</div>

									<div class="modal-slide content-3">
										<div class="modal-content">
											<h2><?php get_t('Premium feature - SMS', $bdd); ?></h2>
											<div>
												<div class="modal-nav">
													<label for="modal-content-2" class="prev-slide">&#8249;</label>
												</div>
												<div>
													<?php get_t('functionality10_text_index', $bdd); ?>
												</div>
												<div class="imgPrem imgSms">
												</div>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>
						<div>
							<a class="helpMe" href="<?php get_t('https://github.com/ABHC/SERGE/wiki/User\'s-Guide', $bdd); ?>" target="_blank">?</a>&nbsp;<a href="<?php get_t('https://github.com/ABHC/SERGE/wiki/User\'s-Guide', $bdd); ?>" target="_blank"><?php get_t('User guide', $bdd); ?></a>
						</div>
						<?php
						if (!$emailIsCheck)
						{
							?>
							<div class="redAlert">
								<div class="redAlertPicto"></div>&nbsp;&nbsp;&nbsp;<?php get_t('title2_premium_setting', $bdd); ?>
							</div>
							<?php
						}
						?>
					</div>
					<span class="Vseparator"></span>
					<div <?php echo $classNoPremium; ?>>
						<h3 title="<?php get_t('Premium functionality', $bdd); ?>"><img alt="Premium" src="../images/pictogrammes/pictoPremium.png" class="icoTextSmall"/>&nbsp;<?php get_t('subtitle6_window1_setting', $bdd); ?></h3>
						<?php
						$rssLink = 'https://' . $_SERVER['HTTP_HOST'] . '/rss/' . $token[0]['token'];
						?>
						<?php get_t('subsubtitle1_window1_setting', $bdd); ?>
						<div class="align">
							<textarea class="falseInput" id="toCopy"><?php echo $rssLink; ?></textarea>
							<button class="copyButton" id="copy" title="<?php get_t('Copy RSS feed', $bdd); ?>" type="button" onclick="copyToClipboard('');"></button>
						</div><br>
						<?php get_t('subsubtitle2_window1_setting', $bdd); ?>
						<div class="align">
							<textarea class="falseInput" id="toCopyS"><?php echo $rssLink; ?>s</textarea>
							<button class="copyButton" id="copyS" title="<?php get_t('Copy RSS feed', $bdd); ?>" type="button" onclick="copyToClipboard('S');"></button>
						</div><br>
						<?php get_t('subsubtitle3_window1_setting', $bdd); ?>
						<div class="align">
							<textarea class="falseInput" id="toCopyP"><?php echo $rssLink; ?>p</textarea>
							<button class="copyButton" id="copyP" title="<?php get_t('Copy RSS feed', $bdd); ?>" type="button" onclick="copyToClipboard('P');"></button>
						</div>
					</div>
				</div>
			</div>

			<div class="board" id="premium">
				<div>
					<a class="helpMe" title="<?php get_t('User guide', $bdd); ?>"  href="<?php get_t('https://github.com/ABHC/SERGE/wiki/User\'s-Guide#premium-information', $bdd); ?>" target="_blank">?</a>
					<h2><?php get_t('Premium', $bdd); ?></h2>
				</div>
				<div>
					<div>
						<?php
						if ($userIsPremium)
						{
							get_t('title0_premium_setting', $bdd); echo ' : ' . date("d/m/o", $userSettings['premium_expiration_date']); ?><br>

							<a href="purchase" class="purchaseButton"><?php get_t('button0_premium_setting', $bdd); ?></a>
							<?php get_t('title1_premium_setting', $bdd); ?> :<br>
							<?php
						}
						else
						{
							?>
							<a href="purchase" class="purchaseButton"><?php get_t('button1_premium_setting', $bdd); ?></a>
							<?php
							if (!empty($paymentList))
							{
								get_t('title1_premium_setting', $bdd); echo ' :<br>';
							}
						}
						?>
						<div class="boxScroll">
							<?php
							foreach ($paymentList as $payment)
							{
								$numberOfMonths = $payment['duration_premium'] / (30*24*3600);
								$price = $payment['price'] / 100;

								echo '<div><span>' . date("H:i d/m/o", $payment['purchase_date']) . '</span><span>' . $numberOfMonths . ' months' . '</span><span>' . $price . '€</span></div>';
							}
							?>
						</div>
					</div>
					<span class="Vseparator"></span>
					<div>
						<?php get_t('Remaining number of SMS', $bdd); ?>&nbsp;:&nbsp; <?php echo $userSettings['sms_credits']; ?>
						<a href="purchase?type=SMS" class="purchaseButton"><?php get_t('Add SMS in your account', $bdd); ?></a>
						<?php get_t('Your SMS payment history', $bdd); ?> :<br>
						<span class="boxScroll">
							<?php
							/*
							foreach ($smsPaymentList as $payment)
							{
							$numberOfSMS = $payment['number_sms'] / (30*24*3600);
							$price = $payment['price'] / 100;

							echo '<div><span>' . date("d/m/o H:i", $payment['purchase_date']) . '</span><span>' . $numberOfMonths . ' months ' . '</span><span>' . $price . '€</span></div>';
						}*/
						?>
					</span>
				</div>
			</div>
		</div>

		<div class="board" id="setting">
			<div class="titleBoard">
				<a class="helpMe" title="<?php get_t('User guide', $bdd); ?>"  href="<?php get_t('https://github.com/ABHC/SERGE/wiki/User\'s-Guide#settings', $bdd); ?>" target="_blank">?</a>
				<h2><?php get_t('Settings', $bdd); ?></h2>
			</div>
			<span class="mainSetting">
				<span class="mainSettingOption">
					<a class="text_connection" href="connection?action=changePassphrase"><img alt="Passphrase" src="../images/pictogrammes/pictoMdp.png" class="icoText"/>&nbsp;<?php get_t('Change your passphrase', $bdd); ?></a>
				</span>
				<span class="mainSettingOption">
					<img alt="Language" src="../images/pictogrammes/pictoLanguage.png" class="icoText"/>&nbsp;<?php get_t('Change your language', $bdd); ?>&nbsp;<select class="shortSelect" name="selectLanguage" onchange="autoSubmit(this.form);">
						<option value="FR" <?php echo $selectLanguageFR ?? ''; ?>>
							FR&nbsp;
						</option>
						<option value="EN" <?php echo $selectLanguageEN ?? ''; ?>>
							EN&nbsp;
						</option>
					</select>
					<span class="arrDownBorder">▾</span>
					<?php
					#TODO List language
					?>
				</span>

				<span class="mainSettingOption">
					<img alt="Wallpaper" src="../images/pictogrammes/pictoWallpaper.png" class="icoText"/>&nbsp;<?php get_t('selectTitle_window1_setting', $bdd); ?>&nbsp;<select size="<?php echo count($backgroundList); ?>" id="selectBackgroundPreview" class="selectBackground" name="backgroundResult" onchange="autoSubmit(this.form);">
						<?php
						$random['name'] = 'random';
						$random['filename'] = 'random.png';
						$backgroundList = array_merge($backgroundList, array($random));
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

							echo '<option value="' . $backgroundName['name'] . '" ' . $backgroundSelected . ' id="/images/background_preview/' . $backgroundName['filename'] . '">' . var_get_t($backgroundName['name'], $bdd) . '</option>' . PHP_EOL;
						}
						?>
					</select>
					<?php
					foreach ($backgroundList as $backgroundName)
					{
						echo '<img alt="' . $backgroundName['name'] . '" src=/images/background_preview/' . $backgroundName['filename'] . ' style="display: none;" />';
					}
					?>
				</span>
				<span class="mainSettingOption">
					<span class="Hseparator red"></span>
				</span>
				<span class="mainSettingOption">
					<img alt="Alert" src="../images/pictogrammes/redcross.png" class="icoText"/>&nbsp;<span class="fakeLink" onclick="confirmDAccount('<?php get_t('Are you sure you want to delete your account ?', $bdd); ?>', '<?php echo $token[0]['token']; ?>');"><?php get_t('Delete my account', $bdd); ?></span>
				</span>
			</span>
			<span class="optionFold">
				<input type="checkbox" name="radio-optionMail" id="unfold-optionMail" value="mail" <?php echo $foldingStateMail ?? ''; ?>/>
				<div class="optionList">
					<label for="unfold-optionMail" class="unfoldOption" title="<?php get_t('Premium functionality', $bdd); ?>">
						<img alt="Premium" src="../images/pictogrammes/pictoPremium.png" class="icoTextSmall"/>&nbsp;<?php get_t('Mail configuration', $bdd); ?>&nbsp;▾
					</label>
					<label for="unfold-optionMail" class="foldOption" title="<?php get_t('Premium functionality', $bdd); ?>">
						<img alt="Premium" src="../images/pictogrammes/pictoPremium.png" class="icoTextSmall"/>&nbsp;<?php get_t('Mail configuration', $bdd); ?>&nbsp;▴
					</label>
					<div class="option">
						<div <?php echo $classNoPremium; ?>>
							<div>
								<h3><?php get_t('input1_window1_setting', $bdd); ?></h3>
								<div class="align">
									<input type="email" name="email" id="email" value="<?php echo $userSettings['email']; ?>"/>
									<input title="<?php get_t('Update email', $bdd); ?>" class="submit" type="submit" value="" />
								</div>
							</div>
							<div>
								<h3><?php get_t('subtitle2_window1_setting', $bdd); ?></h3>
								<div>
									<input type="radio" id="condNbLink" name="cond" onchange="autoSubmit(this.form);" value="link_limit" <?php echo $condNbLink; ?>>
									<label class="radio dot" for="condNbLink"></label>
									<?php get_t('input2_window1_setting', $bdd); ?>
									<input class="number alpha" type="number" name="numberLinks" onchange="autoSubmit(this.form);" min="5" placeholder="50" value="<?php echo $userSettings['link_limit']; ?>"/>
								</div>
								<div>
									<input type="radio" id="condFreq" name="cond" onchange="autoSubmit(this.form);" value="freq" <?php echo $condFreq; ?>>
									<label class="radio dot" for="condFreq"></label>
									<?php get_t('input3_window1_setting', $bdd); ?>
									<input class="number alpha" type="number" name="freq" onchange="autoSubmit(this.form);" min="2" max="500" step="2" placeholder="24" value="<?php echo $userSettings['frequency'] ?? '2'; ?>"/><?php get_t('input4_window1_setting', $bdd); ?>
								</div>
								<div>
									<input type="radio" id="condDate" name="cond" onchange="autoSubmit(this.form);" value="deadline" <?php echo $condDate; ?>>
									<label class="radio dot" for="condDate"></label>
									<?php get_t('input5_window1_setting', $bdd); ?>
									<input class="number alpha" type="number" name="hours" onchange="autoSubmit(this.form);" min="0" max="23" step="2" placeholder="19" value="<?php echo $userSettings['selected_hour']; ?>"/>h
									<?php get_t('input6_window1_setting', $bdd); ?>
									<select class="selectCommResult" name="days" onchange="autoSubmit(this.form);">
										<option value=",1,2,3,4,5," <?php echo $day[0] ?? ''; ?>>
											<?php get_t('select1_window1_setting', $bdd); ?>
										</option>
										<option value=",1,3,5," <?php echo $day[8] ?? ''; ?>>
											<?php get_t('select2_window1_setting', $bdd); ?>
										</option>
										<option value=",1,2,3,4,5,6,7," <?php echo $day[9] ?? ''; ?>>
											<?php get_t('select3_window1_setting', $bdd); ?>
										</option>
										<option value=",1," <?php echo $day[1] ?? ''; ?>>
											<?php get_t('select4_window1_setting', $bdd); ?>
										</option>
										<option value=",2," <?php echo $day[2] ?? ''; ?>>
											<?php get_t('select5_window1_setting', $bdd); ?>
										</option>
										<option value=",3," <?php echo $day[3] ?? ''; ?>>
											<?php get_t('select6_window1_setting', $bdd); ?>
										</option>
										<option value=",4," <?php echo $day[4] ?? ''; ?>>
											<?php get_t('select7_window1_setting', $bdd); ?>
										</option>
										<option value=",5," <?php echo $day[5] ?? ''; ?>>
											<?php get_t('select8_window1_setting', $bdd); ?>
										</option>
										<option value=",6," <?php echo $day[6] ?? ''; ?>>
											<?php get_t('select9_window1_setting', $bdd); ?>
										</option>
										<option value=",7," <?php echo $day[7] ?? ''; ?>>
											<?php get_t('select10_window1_setting', $bdd); ?>
										</option>
									</select>
									<span class="arrDown">▾</span>
									<?php get_t('input6.1_window1_setting', $bdd); ?>
									<select class="selectCommResult" name="secondDay" onchange="autoSubmit(this.form);">
										<option value=""><?php get_t('select10.1_window1_setting', $bdd); ?></option>
										<option value="1," <?php echo $day2[1] ?? ''; ?>>
											<?php get_t('select4_window1_setting', $bdd); ?>
										</option>
										<option value="2," <?php echo $day2[2] ?? ''; ?>>
											<?php get_t('select5_window1_setting', $bdd); ?>
										</option>
										<option value="3," <?php echo $day2[3] ?? ''; ?>>
											<?php get_t('select6_window1_setting', $bdd); ?>
										</option>
										<option value="4," <?php echo $day2[4] ?? ''; ?>>
											<?php get_t('select7_window1_setting', $bdd); ?>
										</option>
										<option value="5," <?php echo $day2[5] ?? ''; ?>>
											<?php get_t('select8_window1_setting', $bdd); ?>
										</option>
										<option value="6," <?php echo $day2[6] ?? ''; ?>>
											<?php get_t('select9_window1_setting', $bdd); ?>
										</option>
										<option value="7," <?php echo $day2[7] ?? ''; ?>>
											<?php get_t('select10_window1_setting', $bdd); ?>
										</option>
									</select>
									<span class="arrDown">▾</span>
								</div>
							</div>
						</div>
						<span class="Vseparator"></span>
						<div <?php echo $classNoPremium; ?>>
							<div>
								<h3><?php get_t('Communication of results', $bdd); ?></h3>
								<div class="align">
									<input type="checkbox" name="resultByEmail" id="resultByEmail" onchange="autoSubmit(this.form);" value="email" <?php echo $checkResultByMail ?? ''; ?>/><label class="checkbox" for="resultByEmail"></label><?php get_t('Receive your results by email', $bdd); ?>
								</div>
							</div>
							<div>
								<h3><?php get_t('subtitle3_window1_setting', $bdd); ?></h3>
								<div>
									<input type="radio" id="orderByKeyword" name="orderBy" onchange="autoSubmit(this.form);" value="masterword" <?php echo $orderByKeyword; ?>>
									<label class="radio dot" for="orderByKeyword"></label>
									<?php get_t('input7_window1_setting', $bdd); ?>
								</div>
								<div>
									<input type="radio" id="orderBySource" name="orderBy" onchange="autoSubmit(this.form);" value="origin" <?php echo $orderBySource; ?>>
									<label class="radio dot" for="orderBySource"></label>
									<?php get_t('input8_window1_setting', $bdd); ?>
								</div>
								<div>
									<input type="radio" id="orderByAlpha" name="orderBy" onchange="autoSubmit(this.form);" value="type" <?php echo $orderByType; ?>>
									<label class="radio dot" for="orderByAlpha"></label>
									<?php get_t('input9_window1_setting', $bdd); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<input type="checkbox" name="radio-optionSMS" id="unfold-optionSMS" value="sms" <?php echo $foldingStateSMS ?? ''; ?>/>
				<div class="optionList">
					<label for="unfold-optionSMS" class="unfoldOption" title="<?php get_t('Premium functionality', $bdd); ?>">
						<img alt="Premium" src="../images/pictogrammes/pictoPremium.png" class="icoTextSmall"/>&nbsp;<?php get_t('SMS configuration', $bdd); ?>&nbsp;▾
					</label>
					<label for="unfold-optionSMS" class="foldOption" title="<?php get_t('Premium functionality', $bdd); ?>">
						<img alt="Premium" src="../images/pictogrammes/pictoPremium.png" class="icoTextSmall"/>&nbsp;<?php get_t('SMS configuration', $bdd); ?>&nbsp;▴
					</label>
					<div class="option">
						<div <?php echo $classNoPremium; ?>>
							<div>
								<h3><?php get_t('Your phone number', $bdd); ?></h3>
								<div class="align">
									<input type="tel" name="tel" id="tel" value="<?php echo $userSettings['phone_number']; ?>"/>
									<input title="<?php get_t('Update phone number', $bdd); ?>" class="submit" type="submit" value="" />
								</div>
							</div>
						</div>
						<span class="Vseparator"></span>
						<div <?php echo $classNoPremium; ?>>
							<div>
								<h3><?php get_t('Communication of results', $bdd); ?></h3>
								<div class="align">
									<input type="checkbox" name="resultBySMS" id="resultBySMS" onchange="autoSubmit(this.form);" value="sms" <?php echo $checkResultBySMS ?? ''; ?>/><label class="checkbox" for="resultBySMS"></label><?php get_t('Receive your alerts by SMS', $bdd); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<input type="checkbox" name="radio-optionPrivacy" id="unfold-optionPrivacy" value="privacy" <?php echo $foldingStatePrivacy ?? ''; ?>/>
				<div class="optionList">
					<label for="unfold-optionPrivacy" class="unfoldOption">
						<?php get_t('Privacy configuration', $bdd); ?>&nbsp;▾
					</label>
					<label for="unfold-optionPrivacy" class="foldOption">
						<?php get_t('Privacy configuration', $bdd); ?>&nbsp;▴
					</label>
					<div class="option">
						<div>
							<h3><?php get_t('Read monitoring', $bdd); ?></h3>
							<div class="align">
								<div class="switch">
									<input type="checkbox" id="recordRead" name="recordRead" onchange="autoSubmit(this.form);" value="active" <?php echo $recordRead; ?>>
									<label for="recordRead" class="slider"></label>
								</div>
								<?php get_t('input10_window1_setting', $bdd); ?>
							</div>
						</div>
						<span class="Vseparator"></span>
						<div>
							<h3><?php get_t('Delete history', $bdd); ?></h3>
							<div class="deleteContainer">
								<div class="deleteLogo"></div>
								<input title="<?php get_t('Delete', $bdd); ?>" class="deleteButton" type="submit" name="buttonDeleteHistory" value="<?php get_t('input11_window1_setting', $bdd); ?>" />
								<?php get_t('input11.1_window1_setting', $bdd); ?>
								<input class="number alpha" name="deleteHistoryValue" type="number" min="1" value="1"/>
								<select class="selectCommResult Unit" name="deleteHistoryUnit">
									<option value="hour">
										<?php get_t('select11_window1_setting', $bdd); ?>
									</option>
									<option value="day">
										<?php get_t('select12_window1_setting', $bdd); ?>
									</option>
									<option value="week">
										<?php get_t('select13_window1_setting', $bdd); ?>
									</option>
									<option value="month">
										<?php get_t('select14_window1_setting', $bdd); ?>
									</option>
									<option value="year">
										<?php get_t('select15_window1_setting', $bdd); ?>
									</option>
								</select>
								<span class="arrDown">▾</span>
							</div>
						</div>
					</div>
				</div>
			</span>
		</div>

		<div class="board" id="watchPack">
			<div class="titleBoard">
				<a class="helpMe" title="<?php get_t('User guide', $bdd); ?>"  href="<?php get_t('https://github.com/ABHC/SERGE/wiki/User\'s-Guide#watchpacks-management', $bdd); ?>" target="_blank">?</a>
				<h2><?php get_t('Watchpacks management', $bdd); ?></h2>
			</div>
			<span class="inlineButton">
				<a class="buttonCreatesourcePack" href="watchPack?type=create"><?php get_t('Button1_window2_setting', $bdd); ?></a>
				<a class="buttonVisiteCommunitySourcePack" href="watchPack?type=add"><?php get_t('Button2_window2_setting', $bdd); ?></a>
			</span>
			<?php
			foreach ($watchPackUsedList as $watchPackUsed)
			{
				$Qdisable = '';
				$titleDisableActivate = var_get_t('Disable', $bdd);
				$nameClassDisableActivate = 'disable';

				$pattern = ',!' . $_SESSION['id'] . ',';
				if (preg_match("/$pattern/", $watchPackUsed['users']))
				{
					$Qdisable = 'Qdisable';
					$titleDisableActivate = var_get_t('Activate', $bdd);
					$nameClassDisableActivate = 'activate';
				}

				echo '
				<span class="queryContainer ' . $Qdisable . '">
				<input type="submit" title="' . var_get_t('Delete', $bdd) . '" class="deleteQuery" name="removePack" value="' . $watchPackUsed['id'] . '"/>
				';

				echo '<span title="' . $watchPackUsed['description'] . '">' . $watchPackUsed['name'] . '</span></span>';
			}
			?>
		</div>

		<div class="keywordManagement" id="news">
			<div class="titleBoard">
				<a class="helpMe" title="<?php get_t('User guide', $bdd); ?>"  href="<?php get_t('https://github.com/ABHC/SERGE/wiki/User\'s-Guide#news', $bdd); ?>" target="_blank">?</a>
				<h2><?php get_t('window2_title_setting', $bdd); ?></h2>
			</div>
			<div class="newsInput">
				<select name="sourceType" id="sourceType">
					<option value="inputSource"><?php get_t('select2_window2_setting', $bdd); ?></option>
				</select>
				<span class="arrDownBorder">▾</span>
				<input type="url" name="newSource" id="source" title="<?php get_t('Any web page, Serge is in charge of finding RSS feeds and diary', $bdd); ?>" placeholder="<?php get_t('Source link', $bdd); ?>" />
				<input title="<?php get_t('Add new source', $bdd); ?>" class="submit" type="submit" value="" />
			</div>
			<?php #echo $ERROR_MESSAGE . '<br>'; ?>
			<div class="newsInput">
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
					<option value="0"><?php get_t('select1_window2_setting', $bdd); ?></option>
				</select>
				<span class="arrDownBorder">▾</span>
				<input type="text" class="keywordInput" name="newKeyword" id="keyword" title="<?php get_t('Special keywords -- :all to retrieve all links; :alert to receive the result directly overriding the sending conditions', $bdd); ?>" placeholder="<?php get_t('Keyword, next keyword, ...', $bdd); ?>" />
				<input title="<?php get_t('Add new keyword', $bdd); ?>" class="submit" type="submit" value="" />
			</div>
			<div>
				<div id="sourceAdditionStatus">
					<?php  echo $sourceAdditionStatus ?? ''; ?>
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
							if (isset($_SESSION[$foldSourceName]) && $_SESSION[$foldSourceName] == $rssFirstLetter[0])
							{
								$amICheckFoldSource = 'checked';
							}

							$actualLetter = $rssFirstLetter[0];

							echo '
							</div>
							<input type="checkbox" name="radio-s' . $rssFirstLetter[0] . '" id="unfold-s' . $rssFirstLetter[0] . '" value="' . $rssFirstLetter[0] . '" ' . $amICheckFoldSource . '/>' . PHP_EOL.
							'<div class="sourceList">' . PHP_EOL.
							'<label for="unfold-s' . $rssFirstLetter[0] . '" class="unfoldTag">' . PHP_EOL.
							$rssFirstLetter[0] . ' ▾'.
							'</label>' . PHP_EOL.
							'<label for="unfold-s' . $rssFirstLetter[0] . '" class="foldTag">' . PHP_EOL.
							$rssFirstLetter[0] . ' ▴'.
							'</label>' . PHP_EOL;
						}

						$foldKeywordName = 'radio-ks' . $ownerSourcesList['id'];
						$amICheckFoldKeyword = '';
						if (isset($_SESSION[$foldKeywordName]) && $_SESSION[$foldKeywordName] == $ownerSourcesList['id'])
						{
							$amICheckFoldKeyword = 'checked';
						}

						if (preg_match("/," . $_SESSION['id'] . ",/", $ownerSourcesList['owners']))
						{
							echo
							'<div class="tagSource Tactive" id="ks' . $ownerSourcesList['id'] . '">' . PHP_EOL.
							'<input type="submit" title="' . var_get_t('Delete', $bdd) . '" name="delSource" value="source' . $ownerSourcesList['id'] . '&"/>' . PHP_EOL.
							'<input type="submit" title="' . var_get_t('Disable', $bdd) . '" name="disableSource" value="source' . $ownerSourcesList['id']. '&"/>' . PHP_EOL.
							'<a href="' . $ownerSourcesList['link']. '" target="_blank">'.
							ucfirst($ownerSourcesList['name']).
							'</a>' . PHP_EOL.
							'</div>' . PHP_EOL;
						}
						elseif (preg_match("/,!" . $_SESSION['id'] . ",/", $ownerSourcesList['owners']))
						{
							echo
							'<div class="tagSource Tdisable" id="ks' . $ownerSourcesList['id'] . '">' . PHP_EOL.
							'<input type="submit" title="' . var_get_t('Delete', $bdd) . '" name="delSource" value="source' . $ownerSourcesList['id'] . '&"/>' . PHP_EOL.
							'<input type="submit" title="' . var_get_t('Activate', $bdd) . '" name="activateSource" value="source' . $ownerSourcesList['id']. '&"/>' . PHP_EOL.
							'<a href="' . $ownerSourcesList['link']. '" target="_blank">'.
							ucfirst($ownerSourcesList['name']).
							'</a>' . PHP_EOL.
							'</div>' . PHP_EOL;
						}

						echo
						'<input type="checkbox" name="radio-ks' . $ownerSourcesList['id'] . '" id="unfold-ks' . $ownerSourcesList['id'] . '" value="' . $ownerSourcesList['id'] . '" ' . $amICheckFoldKeyword . '/>' . PHP_EOL.
						'<div class="keywordList" id="keywordList' . $ownerSourcesList['id'] . '">' . PHP_EOL.
						'<label for="unfold-ks' . $ownerSourcesList['id'] . '" id="unfold' . $ownerSourcesList['id'] . '" class="unfoldTag">'.
						var_get_t('Unfold keyword list', $bdd) . ' ▾'.
						'</label>' . PHP_EOL.
						'<label for="unfold-ks' . $ownerSourcesList['id'] . '" id="fold' . $ownerSourcesList['id'] . '" class="foldTag">'.
						var_get_t('Fold keyword list', $bdd) . ' ▴'.
						'</label>' . PHP_EOL;

						# Keyword loop
						$cptKeyword = 0;
						foreach ($reqReadOwnerSourcesKeywordtmp as $ownerKeywordList)
						{
							$applicable_owners_sources = $ownerKeywordList['applicable_owners_sources'];
							$ownerKeywordList['keyword'] = preg_replace("/^:all@[0-9]+$/", ":All", $ownerKeywordList['keyword']);
							$ownerKeywordList['keyword'] = preg_replace("/^\[!alert!\]/", "&#9888; ", $ownerKeywordList['keyword']);
							if (preg_match("/\|" . $_SESSION['id'] . ":[,!0-9,]*," . $ownerSourcesList['id'] . ",[,!0-9,]*\|/", $applicable_owners_sources))
							{
								echo
								'<div class="tag Tactive">' . PHP_EOL.
								'<input type="submit" title="' . var_get_t('Delete', $bdd) . '" name="delKeyword" value="source'. $ownerSourcesList['id'] . '&keyword' . $ownerKeywordList['id'] . '&"/>' . PHP_EOL.
								'<input type="submit" title="' . var_get_t('Disable', $bdd) . '" name="disableKeyword" value="source'. $ownerSourcesList['id'] . '&keyword' . $ownerKeywordList['id'] . '&"/>' . PHP_EOL.
								'<a href="setting?keyword=keyword' . $ownerKeywordList['id'] . '">'.
								ucfirst($ownerKeywordList['keyword']) . '<span class="hiddenFont">,</span>'.
								'</a>' . PHP_EOL.
								'</div>' . PHP_EOL;
								$cptKeyword++;
							}
							elseif (preg_match("/\|" . $_SESSION['id'] . ":[,!0-9,]*,!" . $ownerSourcesList['id'] . ",[,!0-9,]*\|/", $applicable_owners_sources))
							{
								echo
								'<div class="tag Tdisable">' . PHP_EOL.
								'<input type="submit" title="' . var_get_t('Delete', $bdd) . '" name="delKeyword" value="source'. $ownerSourcesList['id'] . '&keyword' . $ownerKeywordList['id'] . '&"/>' . PHP_EOL.
								'<input type="submit" title="' . var_get_t('Activate', $bdd) . '" name="activateKeyword" value="source'. $ownerSourcesList['id'] . '&keyword' . $ownerKeywordList['id'] . '&"/>' . PHP_EOL.
								'<a href="setting?keyword=keyword' . $ownerKeywordList['id'] . '">'.
								ucfirst($ownerKeywordList['keyword']) . '<span class="hiddenFont">,</span>'.
								'</a>' . PHP_EOL.
								'</div>' . PHP_EOL;
								$cptKeyword++;
							}
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

		<div class="scientificPublicationManagement" id="science">
			<div class="titleBoard">
				<a class="helpMe" title="<?php get_t('User guide', $bdd); ?>"  href="<?php get_t('https://github.com/ABHC/SERGE/wiki/User\'s-Guide#scientific', $bdd); ?>" target="_blank">?</a>
				<h2><?php get_t('window3_title_setting', $bdd); ?></h2>
			</div>
			<div class="newQueryContainer">
				<div class="lineQuery">
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

					if (!empty($data['scienceType' . $cpt]))
					{
						$selected[$data['scienceType' . $cpt]] = 'selected';
					}

					if (!empty($data['openParenthesis' . $cpt]) && $data['openParenthesis' . $cpt] === 'active')
					{
						$checked['openParenthesis' . $cpt] = 'checked';
					}
					else
					{
						$checked['openParenthesis' . $cpt] = '';
					}

					$data['scienceQuery' . $cpt] = $data['scienceQuery' . $cpt] ?? '';
					echo '
					<input type="checkbox" id="openParenthesis' . $cpt . '" name="openParenthesis' . $cpt . '" value="active" ' . $checked['openParenthesis' . $cpt] . '/>
					<label class="queryParenthesis" for="openParenthesis' . $cpt . '">(</label>
					<select title="' . var_get_t('Type', $bdd) . '" class="queryType" name="scienceType' . $cpt . '" id="scienceType0' . $cpt . '">';

					foreach ($selected as $searchField => $selectedSearchField)
					{
						echo '<option value="' . $searchField . '" ' . $selectedSearchField . '>' . var_get_t($searchField, $bdd) . '</option>' . PHP_EOL;
						$selected[$searchField] = '';
					}

					echo '
					</select>
					<span class="arrDownBorder">▾</span>
					<input type="text" class="query" name="scienceQuery' . $cpt . '" id="scienceQuery0' . $cpt . '" placeholder="' . var_get_t('Keyword', $bdd) . '" value="' . $data['scienceQuery' . $cpt] . '"/>';

					if (!empty($data['closeParenthesis' . $cpt]) && $data['closeParenthesis' . $cpt] === 'active')
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
					$checked['NOT'] = '';
					if (empty($data['andOrNot' . $cpt]))
					{
						$checked['OR'] = 'checked';
					}
					else
					{
						$checked[$data['andOrNot' . $cpt]] = 'checked';
					}

					$logicalConnector = '
					<div class="btnList">
						<input type="radio" id="andOrNot_AND0' . $cpt . '" name="andOrNot' . $cpt . '" value="AND" ' . $checked['AND'] . '>
						<label class="ANDOrNot" for="andOrNot_AND0' . $cpt . '"></label>
						<input type="radio" id="andOrNot_OR0' . $cpt . '" name="andOrNot' . $cpt . '" value="OR" ' . $checked['OR'] . '>
						<label class="andORNot" for="andOrNot_OR0' . $cpt . '"></label>
						<input type="radio" id="andOrNot_NOT0' . $cpt . '" name="andOrNot' . $cpt . '" value="NOT" ' . $checked['NOT'] . '>
						<label class="andOrNOT" for="andOrNot_NOT0' . $cpt . '"></label>
					</div>';
				}
				?>
				<input title="<?php get_t('Extend', $bdd); ?>" class="extend" type="submit" id="extendScience" name="extendScience" value=">>" />
				<input title="<?php get_t('Add new science query', $bdd); ?>" class="submit" type="submit" name="scienceQuerySubmit" value="add" />
			</div>
			</div>
			<?php echo $ERROR_SCIENCEQUERY ?? ''; ?>
			<?php

			// Read owner science query
			$checkCol = array(array("owners", "l", '%,' . $_SESSION['id'] . ',%', "OR"),
												array("owners", "l", '%,!' . $_SESSION['id'] . ',%', ""));
			$queries = read('inquiries_sciences_serge', 'id, query_serge, owners', $checkCol, 'ORDER BY id DESC', $bdd);

			foreach ($queries as $query)
			{
				$queryDisplay = '';
				$Qdisable = '';
				$titleDisableActivate = var_get_t('Disable', $bdd);
				$nameClassDisableActivate = 'disable';

				$pattern = ',!' . $_SESSION['id'] . ',';
				if (preg_match("/$pattern/", $query['owners']))
				{
					$Qdisable = 'Qdisable';
					$titleDisableActivate = var_get_t('Activate', $bdd);
					$nameClassDisableActivate = 'activate';
				}

				echo '
				<div class="queryContainer ' . $Qdisable . '">
					<input type="submit" title="' . var_get_t('Delete', $bdd) . '" class="deleteQuery" name="delQueryScience" value="query' . $query['id'] . '"/>
					<input type="submit" title="' . $titleDisableActivate . '" class="' . $nameClassDisableActivate . 'Query" name="' . $nameClassDisableActivate . 'QueryScience" value="query' . $query['id'] . '"/>
				';

				$queryId = $query['id'];

				# Input submit for query edit
				echo '<input type="submit" class="noDisplay" id="editQueryScience' . $queryId . '" name="editQueryScience" value="' . $queryId . '"/>';
						echo '</div>' . PHP_EOL;
			<?php echo $ERROR_PATENTQUERY ?? ''; ?>
			<?php

			// Read owner patents queries
			$checkCol = array(array("owners", "l", '%,' . $_SESSION['id'] . ',%', "OR"),
												array("owners", "l", '%,!' . $_SESSION['id'] . ',%', ""));
			$queries = read('inquiries_patents_serge', 'id, query, owners', $checkCol, 'ORDER BY id DESC', $bdd);

			foreach ($queries as $query)
			{
				$queryDisplay = '';
				$Qdisable = '';
				$titleDisableActivate = var_get_t('Disable', $bdd);
				$nameClassDisableActivate = 'disable';

				$pattern = ',!' . $_SESSION['id'] . ',';
				if (preg_match("/$pattern/", $query['owners']))
				{
					$Qdisable = 'Qdisable';
					$titleDisableActivate = var_get_t('Activate', $bdd);
					$nameClassDisableActivate = 'activate';
				}

				echo '
				<div class="queryContainer ' . $Qdisable . '">
					<input type="submit" title="' . var_get_t('Delete', $bdd) . '" class="deleteQuery" name="delQueryPatent" value="query' . $query['id'] . '"/>
					<input type="submit" title="' . $titleDisableActivate . '" class="' . $nameClassDisableActivate . 'Query" name="' . $nameClassDisableActivate . 'QueryPatent" value="query' . $query['id'] . '"/>
				';

				$queryId = $query['id'];

				# input submit for query edit
				echo '<input type="submit" class="noDisplay" id="editQueryPatent' . $queryId . '" name="editQueryPatent" value="' . $queryId . '"/>';

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
					preg_match("/$fields\ *([^\+]+\+?[^(AND|OR)]+)(\+AND\+|\+OR\+|\+$)/", $query, $fieldInput);
					$fieldInputPURE = preg_replace("/\+/", "\+", $fieldInput[1]);
					$query = preg_replace("/$fields$fieldInputPURE/", "", $query);
					$fieldInput = preg_replace("/(.+\%3A|`)/", "", $fieldInput[1]);
					$fieldInput = preg_replace("/\+/", " ", $fieldInput);
					$fields = preg_replace("/(\%3A|`)/", "", $fields);
					$queryDisplay = $queryDisplay . '
					<label title="' . var_get_t('Edit query', $bdd) . '" for="editQueryPatent' . $queryId . '" class="queryTypeView">' . $queryFieldsName[$fields] . '
					</label>
					<label title="' . var_get_t('Edit query', $bdd) . '" for="editQueryPatent' . $queryId . '" class="queryKeywordView">' . $fieldInput . '
					</label>';

					preg_match("/^\+(AND|OR)\+/", $query, $logicalConnector);
					if (!empty($logicalConnector[1]))
					{
						$query = preg_replace("/^\+(AND|OR)\+/", "", $query);
						preg_match("/.{1,3}/", $logicalConnector[1], $logicalConnector);
						$queryDisplay = $queryDisplay . '
						<label title="' . var_get_t('Edit query', $bdd) . '" for="editQueryPatent' . $queryId . '" class="query' . ucfirst(strtolower($logicalConnector[0])) . 'View">' . $logicalConnector[0] . '
						</label>
						';
					}
				}

				echo $queryDisplay . '</div>';
			}
			?>
		</div>
	</form>
