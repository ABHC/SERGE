<div class="background"></div>
<div class="body">
	<?php
	if ($mailSent)
	{
		?>
		<div class="bug">
			<p class="center"><?php get_t('Your bug report has been sent successfull !', $bdd); ?></div>
		</div>
		<?php
	}
	else
	{
		?>
		<div class="form-window">
			<h3><?php get_t('Bug report', $bdd); ?></h3>

			<form method="post" action="bug">
				<input type="hidden" name="nonce" value="<?php echo $nonce; ?>"/>
				<div>
					<?php get_t('input4_signup_index', $bdd); ?>
					<input class="bug_field" type="email" name="forg_email" id="Email" value="<?php echo $email ?? ''; ?>" />
				</div>
				<div>
					<?php get_t('Description of the problem', $bdd); ?>
					<textarea name="bugDescription" minlength="50" placeholder="<?php get_t('Indicate the severity of the bug', $bdd); ?>"><?php echo $data['bugDescription'] ?? ''; ?></textarea>
				</div>
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
				<?php echo $errorMessage; ?> <br>
				<a href="https://github.com/ABHC/SERGE/issues/new" target="_blank"><?php get_t('I prefer open an issue on GitHub', $bdd); ?></a>
				<input class="submit-button" type="submit" value="<?php get_t('Submit bug report', $bdd); ?>"/>
			</form>
		</div>
		<?php
	}
	?>
</div>
