<div class="nav">
	<div class="nav-logo">
		<a class="nav-cairn-devices-logo" href="index">
		</a>
		<a class="nav-title" href="index">
			<?php get_t('name_title_nav', $bdd); ?>
		</a>
	</div>

	<div class="nav-block-container">
		<a class="nav-block <?php echo $resultTab; ?>" href="result">
			<div class="nav-result-serge-logo">
			</div>
			<div class="nav-title" >
				<?php get_t('tab1_title_nav', $bdd); ?>
			</div>
		</a>

		<a class="nav-block <?php echo $wikiTab; ?>" href="addLinkInWiki">
			<div class="nav-wiki-logo">
			</div>
			<div class="nav-title" >
				<?php get_t('tab2_title_nav', $bdd); ?>
			</div>
		</a>

		<a class="nav-block <?php echo $settingTab; ?>" href="setting">
			<div class="nav-serge-setting-logo">
			</div>
			<div class="nav-title" >
				<?php get_t('tab3_title_nav', $bdd); ?>
			</div>
		</a>
	</div>
</div>
