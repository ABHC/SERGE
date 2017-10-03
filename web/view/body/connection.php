<div class="background"></div>
<div class="body">
	<?php
	if ($forgotPassphraseStep0)
	{
		?>
		<div class="connection">
			<span class="title_connection">Forgot passphrase</span>

			<form method="post" action="connection?action=forgotPassphraseProcessing">
				<input type="hidden" name="nonce" value="<?php echo $nonce; ?>"/>

				<p class="title_form_connection">Email</p> <p><input class="connection_field" type="email" name="forg_email" id="Email" value="" /></p>

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

				<?php echo $errorMessage; ?>
				<a class="text_connection" href="index#signup"><?php get_t('noAccount_link_connection', $bdd); ?></a>
				<input class="submit_connection" type="submit" value="Submit"/>
			</form>
		</div>
		<?php
	}
	elseif ($forgotPassphraseStep1)
	{
		?>
		<div class="connection">
			<span class="title_connection">Reset passphrase</span>

			<form method="post" action="connection?action=resetPassphraseProcessing&token=<?php echo $data['token'];?>&checker=<?php echo $data['checker'];?>">
				<input type="hidden" name="nonce" value="<?php echo $nonce; ?>"/>

				<p class="title_form_connection"><?php get_t('input2_signin_connection', $bdd); ?></p> <p><input class="connection_field" type="password" name="reset_password" id="reset_password" /></p>
				<p class="title_form_connection"><?php get_t('input2_signin_connection', $bdd); ?></p> <p><input class="connection_field" type="password" name="reset_repassword" id="reset_repassword" /></p>

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

				<?php echo $errorMessage; ?>
				<input class="submit_connection" type="submit" value="Reset passphrase"/>
			</form>
		</div>
		<?php
	}
	elseif ($checkYourEmails)
	{
		?>
		<div class="connection">
			Your request was succesfully send, please check yours emails, in order to reset your password.
		</div>
		<?php
	}
	elseif ($unvalidLink)
	{
		?>
		<div class="connection">
			Your request is unvalid or too old.
		</div>
		<?php
	}
	else
	{
		?>
		<div class="connection">
			<span class="title_connection"><?php get_t('title_text_connection', $bdd); ?></span>

			<form method="post" action="connection">
				<input type="hidden" name="nonce" value="<?php echo $nonce; ?>"/>
				<p class="title_form_connection"><?php get_t('input1_signin_connection', $bdd); ?></p> <p><input class="connection_field" type="text" name="conn_pseudo" id="Pseudo" value="<?php echo $data['pseudo'];?>" /></p>
				<p class="title_form_connection"><?php get_t('input2_signin_connection', $bdd); ?></p> <p><input class="connection_field" type="password" name="conn_password" id="conn_password" /></p>
				<?php echo $errorMessage; ?>
				<a class="text_connection" href="?action=forgotPassphrase"><?php get_t('forgotPass_link_connection', $bdd); ?></a><br>
				<a class="text_connection" href="index#signup"><?php get_t('noAccount_link_connection', $bdd); ?></a>
				<input class="submit_connection" type="submit" value="<?php get_t('submit_signin_connection', $bdd); ?>"/>
			</form>
		</div>
		<?php
	}
	?>
</div>
