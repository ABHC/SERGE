<div class="background"></div>
<div class="body">
	<div class="connection">
		<span class="title_connection">Connection</span>

		<form method="post" action="connection">
			<p class="title_form_connection">Pseudo</p> <p><input class="connection_field" type="text" name="conn_pseudo" id="Pseudo" value="<?php echo htmlspecialchars($_POST['conn_pseudo']);?>" /></p>
			<p class="title_form_connection">Password</p> <p><input class="connection_field" type="password" name="conn_password" id="conn_password" /></p>
			<a class="text_connection" href="forgot_passphrase">Forgot your passphrase ?</a><br>
			<a class="text_connection" href="index#signup">Don't have an account ? Sign Up</a>
			<input class="submit_connection" type="submit" value="Submit"/>
		</form>
	</div>
</div>
