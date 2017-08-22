<div class="background"></div>
<div class="body">
	<div class="connection">
		<span class="title_connection"><?php get_t('title_text_connection', $bdd); ?></span>

		<form method="post" action="connection">
			<p class="title_form_connection"><?php get_t('input1_signin_connection', $bdd); ?></p> <p><input class="connection_field" type="text" name="conn_pseudo" id="Pseudo" value="<?php echo htmlspecialchars($_POST['conn_pseudo']);?>" /></p>
			<p class="title_form_connection"><?php get_t('input2_signin_connection', $bdd); ?></p> <p><input class="connection_field" type="password" name="conn_password" id="conn_password" /></p>
			<a class="text_connection" href="forgot_passphrase"><?php get_t('forgotPass_link_connection', $bdd); ?></a><br>
			<a class="text_connection" href="index#signup"><?php get_t('noAccount_link_connection', $bdd); ?></a>
			<input class="submit_connection" type="submit" value="<?php get_t('submit_signin_connection', $bdd); ?>"/>
		</form>
	</div>
</div>
