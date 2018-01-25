<div class="background"></div>
<div class="body">
	<?php
	if ($mailSent)
	{
		?>
		<div class="bug">
			<p class="center"><?php get_t('Your bug report has been sent successfull !', $bdd); ?></p>
		</div>
		<?php
	}
	else
	{
	?>
	<div class="bug">
		<span class="title_bug"><?php get_t('Bug report', $bdd); ?></span>

		<form method="post" action="bug">
			<input type="hidden" name="nonce" value="<?php echo $nonce; ?>"/>
			<p class="title_form_bug"><?php get_t('input4_signup_index', $bdd); ?></p> <p><input class="bug_field" type="email" name="forg_email" id="Email" value="<?php echo $email ?? ''; ?>" /></p>

			<p class="title_form_bug"><?php get_t('Description of the problem', $bdd); ?></p>
			<textarea name="bugDescription" minlength="50" maxlength="300" placeholder="<?php get_t('Indicate the severity of the bug', $bdd); ?>"><?php echo $data['bugDescription'] ?? ''; ?></textarea>

			<p class="title_form_bug" ><?php get_t('input5_signup_index', $bdd); ?></p>
				<div class="align">
					<input class="captcha_field" type="text" autocomplete="off" name="captcha" id="captcha" placeholder="" />
					<div class="captcha" >
						<div class="captcha1"></div>
						<div class="captcha2"></div>
						<div class="captcha3"></div>
						<div class="captcha4"></div>
					</div>
				</div>
			<?php echo $errorMessage; ?> <br>
			<a class="text_bug" href="https://github.com/ABHC/SERGE/issues/new" target="_blank"><?php get_t('I prefer open an issue on GitHub', $bdd); ?></a>
			<a class="text_bug" href="index#signup"><?php get_t('noAccount_link_bug', $bdd); ?></a>
			<input class="submit_bug" type="submit" value="<?php get_t('Submit bug report', $bdd); ?>"/>
		</form>
	</div>
	<?php
	}
	?>
</div>
