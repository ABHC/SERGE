<div class="nav">
	<div class="navlogo">
		<a class="navCairnDevicesLogo" href="index">
		</a>
		<a class="navTitle" href="index">
			<?php get_t('name_title_nav', $bdd); ?>
		</a>
	</div>

	<div class="navBlockContainer">
		<a class="navBlock <?php echo $result; ?>" href="result">
			<div class="navResultSergeLogo">
			</div>
			<div class="navTitle" >
				<?php get_t('tab1_title_nav', $bdd); ?>
			</div>
		</a>

		<a class="navBlock <?php echo $wiki; ?>" href="wiki">
			<div class="navWikiLogo">
			</div>
			<div class="navTitle" >
				<?php get_t('tab2_title_nav', $bdd); ?>
			</div>
		</a>

		<a class="navBlock <?php echo $setting; ?>" href="setting">
			<div class="navSergeSettingLogo">
			</div>
			<div class="navTitle" >
				<?php get_t('tab3_title_nav', $bdd); ?>
			</div>
		</a>
	</div>

</div>
