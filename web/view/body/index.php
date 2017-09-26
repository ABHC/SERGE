<div class="background">
	<div class="subBackground"></div>
</div>
<div class="backgroundImage">
</div>
<div class="backgroundDetails">
	<h2><?php get_t('main_title_index', $bdd); ?></h2>
	<h3><?php get_t('sub_title_index', $bdd); ?></h3>
	<div class="buttonArea">
		<div class="line"></div>
		<a href="#signup" class="buttonTry"><?php get_t('try1_button_index', $bdd); ?></a>
		<div class="line"></div>
	</div>
</div>
<?php echo $ErrorMessageCheckMail; ?>
<div class="body">
	<div class="functionality">
		<div class="functionalityLine">
			<div>
				<div class="iconRSS"></div>
				<div class="functionalityText">
					<h5><?php get_t('functionality1_title_index', $bdd); ?></h5>
					<div><?php get_t('functionality1_text_index', $bdd); ?></div>
				</div>
			</div>
			<div>
				<div class="iconPatent"></div>
				<div class="functionalityText">
					<h5><?php get_t('functionality2_title_index', $bdd); ?></h5>
					<div><?php get_t('functionality2_text_index', $bdd); ?></div>
				</div>
			</div>
		</div>
		<div class="functionalityLine">
			<div>
				<div class="iconScience"></div>
				<div class="functionalityText">
					<h5><?php get_t('functionality3_title_index', $bdd); ?></h5>
					<div><?php get_t('functionality3_text_index', $bdd); ?></div>
				</div>
			</div>
			<div>
				<div class="iconMail"></div>
				<div class="functionalityText">
					<h5><?php get_t('functionality4_title_index', $bdd); ?></h5>
					<div><?php get_t('functionality4_text_index', $bdd); ?></div>
				</div>
			</div>
		</div>
		<div class="functionalityLine">
			<div>
				<div class="iconOption"></div>
				<div class="functionalityText">
					<h5><?php get_t('functionality5_title_index', $bdd); ?></h5>
					<div><?php get_t('functionality5_text_index', $bdd); ?></div>
				</div>
			</div>
			<div>
				<div class="iconHistory"></div>
				<div class="functionalityText">
					<h5><?php get_t('functionality6_title_index', $bdd); ?></h5>
					<div><?php get_t('functionality6_text_index', $bdd); ?></div>
				</div>
			</div>
		</div>
		<div class="functionalityLine">
			<div>
				<div class="iconRSS"></div>
				<div class="functionalityText">
					<h5><?php get_t('functionality7_title_index', $bdd); ?></h5>
					<div><?php get_t('functionality7_text_index', $bdd); ?></div>
				</div>
			</div>
		</div>
	</div>

	<div class="buttonArea">
		<div class="line"></div>
		<a href="#signup" class="buttonTry"><?php get_t('try2_button_index', $bdd); ?></a>
		<div class="line"></div>
	</div>

	<div class="titleButton">
		<?php get_t('comingSoon_button_index', $bdd); ?>
	</div>

	<div class="functionality">
		<div class="functionalityLine">
			<div>
				<div class="iconStats"></div>
				<div class="functionalityText">
					<h5><?php get_t('functionality11_title_index', $bdd); ?></h5>
					<div><?php get_t('functionality11_text_index', $bdd); ?></div>
				</div>
			</div>
			<div>
				<div class="iconTwitter"></div>
				<div class="functionalityText">
					<h5><?php get_t('functionality8_title_index', $bdd); ?></h5>
					<div><?php get_t('functionality8_text_index', $bdd); ?></div>
				</div>
			</div>
		</div>
		<div class="functionalityLine">
			<div>
				<div class="iconWiki"></div>
				<div class="functionalityText">
					<h5><?php get_t('functionality9_title_index', $bdd); ?></h5>
					<div><?php get_t('functionality9_text_index', $bdd); ?></div>
				</div>
			</div>
			<div>
				<div class="iconSMS"></div>
				<div class="functionalityText">
					<h5><?php get_t('functionality10_title_index', $bdd); ?></h5>
					<div><?php get_t('functionality10_text_index', $bdd); ?></div>
				</div>
			</div>
		</div>
	</div>

	<?php
	if (!isset($_SESSION['pseudo']))
	{?>
	<div id="signup">
		<div class="inscription">
			<p class="title_inscription" ><?php get_t('signup_title_index', $bdd); ?></p>

			<form method="post" action="index#signup" >
				<input type="hidden" name="nonce" value="<?php echo $nonce; ?>"/>
				<p class="title_form_inscription" ><?php get_t('input1_signup_index', $bdd); ?><br>
					<input class="inscription_field" type="text" name="reg_pseudo" id="Pseudo" value="" />
				</p>

				<p class="title_form_inscription" ><?php get_t('input2_signup_index', $bdd); ?><br>
					<input class="inscription_field" type="password" name="reg_password" id="reg_password" placeholder=""/>
				</p>

				<p class="title_form_inscription" ><?php get_t('input3_signup_index', $bdd); ?><br>
					<input class="inscription_field" type="password" autocomplete="off" name="reg_repassword" id="reg_repassword" placeholder=""/>
				</p>

				<p class="title_form_inscription" ><?php get_t('input4_signup_index', $bdd); ?><br>
					<input class="inscription_field" type="email" name="reg_mail" id="Mail" value=""/>
				</p>

				<div class="title_form_inscription" ><?php get_t('input5_signup_index', $bdd); ?>
					<div class="align">
						<input class="captcha_field" type="text" autocomplete="off" name="captcha" id="captcha" placeholder="" />
						<div class="captcha" >
							<div class="captcha1"></div>
							<div class="captcha2"></div>
							<div class="captcha3"></div>
							<div class="captcha4"></div>
						</div>
					</div>
				</div>

				<p class="error_inscription" ><?php echo $errorMessage; ?></p>
				<input class="submit_inscription" type="submit" value="<?php get_t('submit_signup_index', $bdd); ?>" />
			</form>
		</div>
	</div>
	<?php }?>
</div>
