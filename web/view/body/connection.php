<div class="background"></div>
<div class="body">
	<?php
	if ($changePassphraseStep0)
	{
		?>
		<div class="form-window">
			<h3><?php get_t('title0_changePass_connection', $bdd); ?></h3>

			<form method="post" action="connection?action=changePassphraseProcessing">
				<input type="hidden" name="nonce" value="<?php echo $nonce; ?>"/>

				<div><?php get_t('input4_signup_index', $bdd); ?><input class="connection_field" type="email" name="forg_email" id="Email" value="" /></div>

				<div>
					<?php get_t('input5_signup_index', $bdd); ?>
					<div class="align">
						<input class="captcha-field" type="text" autocomplete="off" name="captcha" id="captcha" placeholder="" />
						<div class="captcha">
							<div class="captcha1"></div>
							<div class="captcha2"></div>
							<div class="captcha3"></div>
							<div class="captcha4"></div>
						</div>
					</div>
				</div>

				<?php echo $errorMessage; ?>
				<a href="index#signup"><?php get_t('noAccount_link_connection', $bdd); ?></a>
				<input class="submit-button" type="submit" value="<?php get_t('submit0_changePass_connection', $bdd); ?>"/>
			</form>
		</div>
		<?php
	}
	elseif ($changePassphraseStep1)
	{
		?>
		<div class="form-window">
			<h3><?php get_t('title1_changePass_connection', $bdd); ?></h3>

			<form method="post" action="connection?action=resetPassphraseProcessing&token=<?php echo $data['token'];?>&checker=<?php echo $data['checker'];?>">
				<input type="hidden" name="nonce" value="<?php echo $nonce; ?>"/>

				<div><?php get_t('input2_signin_connection', $bdd); ?><input class="connection_field" type="password" name="reset_password" id="reset_password" /></div>
				<div><?php get_t('input2_signin_connection', $bdd); ?><input class="connection_field" type="password" name="reset_repassword" id="reset_repassword" /></div>

				<div><?php get_t('input5_signup_index', $bdd); ?>
					<div class="align">
						<input class="captcha-field" type="text" autocomplete="off" name="captcha" id="captcha" placeholder="" />
						<div class="captcha">
							<div class="captcha1"></div>
							<div class="captcha2"></div>
							<div class="captcha3"></div>
							<div class="captcha4"></div>
						</div>
					</div>
				</div>

				<?php echo $errorMessage; ?>
				<input class="submit-button" type="submit" value="<?php get_t('title1_changePass_connection', $bdd); ?>"/>
			</form>
		</div>
		<?php
	}
	elseif ($checkYourEmails)
	{
		?>
		<div class="form-window">
			<?php get_t('checkMail_changePass_connection', $bdd); ?>
		</div>
		<?php
	}
	elseif ($unvalidLink)
	{
		?>
		<div class="form-window">
			<?php get_t('invalidRequest_changePass_connection', $bdd); ?>
		</div>
		<?php
	}
	else
	{
		?>
		<div class="form-window">
			<h3><?php get_t('title_text_connection', $bdd); ?></h3>

			<form method="post" action="connection">
				<input type="hidden" name="nonce" value="<?php echo $nonce; ?>"/>
				<div><?php get_t('input1_signin_connection', $bdd); ?><input class="connection_field" type="text" name="conn_pseudo" id="Pseudo" value="<?php echo $data['pseudo'] ?? '';?>" /></div>
				<div><?php get_t('input2_signin_connection', $bdd); ?><input class="connection_field" type="password" name="conn_password" id="conn_password" /></div>
				<?php echo $errorMessage; ?>
				<a href="?action=changePassphrase"><?php get_t('forgotPass_link_connection', $bdd); ?></a><br>
				<a href="index#signup"><?php get_t('noAccount_link_connection', $bdd); ?></a>
				<input class="submit-button" type="submit" value="<?php get_t('submit_signin_connection', $bdd); ?>"/>
			</form>
		</div>
		<?php
	}
	?>
</div>
