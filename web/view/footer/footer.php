<footer>
	<div class="footerContainer1">
		<div class="copyright">
			<a href="github"><?php get_t('copyright_title_footer', $bdd); ?></a>
		</div>

		<div class="bugFooter">
			<a class="bugFooterLogo" href="bug"></a>
			<a href="bug"><?php get_t('Bug ? Report here !', $bdd); ?></a>
		</div>

		<div class="links">
			<div class="roundLinks">
				<a href="workinprogress"><img alt="CairnGit" src="images/CairnGit_logo_norm.png" title="CairnGit"/></a>
				<a href="workinprogress"><img alt="Cairn Devices" src="images/Cairn_Devices_logo_norm.png" title="Cairn Devices"/></a>
				<a href="https://vigiserge.eu/"><img alt="Serge" src="images/SERGE_logo_norm.png" title="Serge"/></a>
			</div>

			<div class="subLinksContainer">
				<div class="subLinks">
					<a href="workinprogress"><?php get_t('link1_center_footer', $bdd); ?></a>
					<a href="legal"><?php get_t('link2_center_footer', $bdd); ?></a>
					<a href="legal#privacy"><?php get_t('link3_center_footer', $bdd); ?></a>
				</div>
				<div class="subLinks">
					<a href="index#signup"><?php get_t('link4_center_footer', $bdd); ?></a>
					<a href="connection"><?php get_t('link5_center_footer', $bdd); ?></a>
					<a href="mailto:support@cairn-devices.eu"><?php get_t('link6_center_footer', $bdd); ?></a>
				</div>
				<div class="subLinks">
					<a href="logout"><?php get_t('link7_center_footer', $bdd); ?></a>
					<a href="mailto:contact@cairn-devices.eu"><?php get_t('link8_center_footer', $bdd); ?></a>
					<a href="mailto:press@cairn-devices.eu"><?php get_t('link9_center_footer', $bdd); ?></a>
				</div>
			</div>
		</div>
		<div class="social">
			<a href="diaspora"><img alt="Diaspora" src="../images/Diaspora.png"/></a>
			<a href="https://twitter.com/SergeAutomaton"><img alt="Twitter" src="../images/Twitter.png"/></a>
			<a href="facebook"><img alt="Facebook" src="../images/Facebook.png"/></a>
			<a href="rss"><img alt="RSS" src="../images/RSS.png"/></a>
		</div>
	</div>

	<div class="footerContainer2">
		<div class="legal">
			<?php get_t('legal_text_footer', $bdd); ?>
		</div>
	</div>

</footer>
