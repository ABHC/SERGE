<div class="background"></div>
<div class="body">
	<div class="connexion">
		<p class="title_connexion">Connection</p>

                <form method="post" >
                        <p class="title_form_connexion">Email</p> <p><input class="connexion_field" type="text" name="conn_pseudo" id="Pseudo" value="<?php echo htmlspecialchars($_POST['conn_pseudo']);?>" /></p>
                        <p class="title_form_connexion">Password</p> <input class="connexion_field" type="password" name="conn_password" id="Mot de passe" /></p>
			<a class="text_connexion" href="forgot_passphrase.php">Forgot your passphrase ?</a>
		        <input class="submit_connexion" type="submit" value="Submit"/>
		</form>
	</div>
</div>
