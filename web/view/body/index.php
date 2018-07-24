<div class="background">
	<div class="sub-background"></div>
</div>
<div class="background-index-image">
</div>
<div class="background-index-details">
	<h1><?php get_t('Save time on your watch with our Serge platform', $bdd); ?></h1>
	<h4><?php get_t('sub_title_index', $bdd); ?></h4>
	<form method="post" class="input-single-field" action="index#signup" >
		<input type="hidden" name="nonce" value="<?php echo $nonce; ?>"/>
		<input type="text" class="single-field" name="reg_pseudo_alone" id="Pseudo" autocomplete="off" value="" placeholder="<?php get_t('input1_signup_index', $bdd); ?>"/>
		<input type="submit" class="single-field-submit-button" value="<?php get_t('try1_button_index', $bdd); ?>"/>
	</form>
</div>
<?php echo $ErrorMessageCheckMail; ?>
<div class="body">
	<div class="functionality-line">
		<div>
			<div class="icon-RSS"></div>
			<div class="functionality-text">
				<h5><?php get_t('functionality1_title_index', $bdd); ?></h5>
				<div><?php get_t('functionality1_text_index', $bdd); ?></div>
			</div>
		</div>
		<div>
			<div class="icon-patent"></div>
			<div class="functionality-text">
				<h5><?php get_t('functionality2_title_index', $bdd); ?></h5>
				<div><?php get_t('functionality2_text_index', $bdd); ?></div>
			</div>
		</div>
	</div>
	<div class="functionality-line">
		<div>
			<div class="icon-science"></div>
			<div class="functionality-text">
				<h5><?php get_t('functionality3_title_index', $bdd); ?></h5>
				<div><?php get_t('functionality3_text_index', $bdd); ?></div>
			</div>
		</div>
		<div>
			<div class="icon-mail"></div>
			<div class="functionality-text">
				<h5><?php get_t('functionality4_title_index', $bdd); ?></h5>
				<div><?php get_t('functionality4_text_index', $bdd); ?></div>
			</div>
		</div>
	</div>
	<div class="functionality-line">
		<div>
			<div class="icon-option"></div>
			<div class="functionality-text">
				<h5><?php get_t('functionality5_title_index', $bdd); ?></h5>
				<div><?php get_t('functionality5_text_index', $bdd); ?></div>
			</div>
		</div>
		<div>
			<div class="icon-history"></div>
			<div class="functionality-text">
				<h5><?php get_t('functionality6_title_index', $bdd); ?></h5>
				<div><?php get_t('functionality6_text_index', $bdd); ?></div>
			</div>
		</div>
	</div>
	<div class="functionality-line">
		<div>
			<div class="icon-RSS"></div>
			<div class="functionality-text">
				<h5><?php get_t('functionality7_title_index', $bdd); ?></h5>
				<div><?php get_t('functionality7_text_index', $bdd); ?></div>
			</div>
		</div>
	</div>

	<div class="center-area">
		<div class="line"></div>
		<a href="#signup" class="button-internal-link"><?php get_t('try2_button_index', $bdd); ?></a>
		<div class="line"></div>
	</div>

	<div class="title-button">
		<?php get_t('comingSoon_button_index', $bdd); ?>
	</div>

	<div class="functionality-line">
		<div>
			<div class="icon-stats"></div>
			<div class="functionality-text">
				<h5><?php get_t('functionality11_title_index', $bdd); ?></h5>
				<div><?php get_t('functionality11_text_index', $bdd); ?></div>
			</div>
		</div>
		<div>
			<div class="icon-twitter"></div>
			<div class="functionality-text">
				<h5><?php get_t('functionality8_title_index', $bdd); ?></h5>
				<div><?php get_t('functionality8_text_index', $bdd); ?></div>
			</div>
		</div>
	</div>
	<div class="functionality-line">
		<div>
			<div class="icon-wiki"></div>
			<div class="functionality-text">
				<h5><?php get_t('functionality9_title_index', $bdd); ?></h5>
				<div><?php get_t('functionality9_text_index', $bdd); ?></div>
			</div>
		</div>
		<div>
			<div class="icon-SMS"></div>
			<div class="functionality-text">
				<h5><?php get_t('functionality10_title_index', $bdd); ?></h5>
				<div><?php get_t('functionality10_text_index', $bdd); ?></div>
			</div>
		</div>
	</div>

	<?php
	if (!isset($_SESSION['pseudo']))
	{?>
	<div id="signup" class="space"></div>
	<div class="form-window">
		<h3><?php get_t('signup_title_index', $bdd); ?></h3>

		<form method="post" action="index#signup" >
			<input type="hidden" name="nonce" value="<?php echo $nonce; ?>"/>
			<p class="title_form_inscription" ><?php get_t('input1_signup_index', $bdd); ?><br>
				<input class="inscription_field" type="text" name="reg_pseudo" id="Pseudo" value="<?php echo $pseudoValue; ?>" />
			</p>

			<p class="title_form_inscription" ><?php get_t('input2_signup_index', $bdd); ?><br>
				<input class="inscription_field" type="password" name="reg_password" id="reg_password" placeholder=""/>
			</p>

			<p class="title_form_inscription" ><?php get_t('input3_signup_index', $bdd); ?><br>
				<input class="inscription_field" type="password" autocomplete="off" name="reg_repassword" id="reg_repassword" placeholder=""/>
			</p>

			<p class="title_form_inscription" ><?php get_t('input4_signup_index', $bdd); ?><br>
				<input class="inscription_field" type="email" name="reg_mail" id="Mail" value="<?php echo $emailValue ?? ''; ?>"/>
			</p>

			<div class="title_form_inscription" ><?php get_t('input5_signup_index', $bdd); ?>
				<div class="align">
					<input class="captcha-field" type="text" autocomplete="off" name="captcha" id="captcha" placeholder="" />
					<div class="captcha" >
						<div class="captcha1"></div>
						<div class="captcha2"></div>
						<div class="captcha3"></div>
						<div class="captcha4"></div>
					</div>
				</div>
			</div>

			<p class="error_inscription" ><?php echo $errorMessage; ?></p>
			<input class="submit-button" type="submit" value="<?php get_t('submit_signup_index', $bdd); ?>" />
		</form>
	</div>
	<?php }?>
</div>
