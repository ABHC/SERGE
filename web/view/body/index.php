<div class="background">
	<div class="subBackground"></div>
</div>
<div class="backgroundImage">
</div>
<h2><?php get_t('main_title_index', $bdd); ?></h2>
<h3><?php get_t('sub_title_index', $bdd); ?></h3>
<div class="buttonArea">
	<span class="line"></span>
	<a href="#signup" class="buttonTry">Try for free</a>
	<span class="line"></span>
</div>

<div class="body">
	<div class="functionality">
		<div class="functionalityLine">
			<span>
				<div class="iconRSS"></div>
				<div class="functionalityText">
					<h5>Track RSS</h5>
					<div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla dignissim metus sit amet metus pharetra sagittis. Suspendisse sit amet rhoncus erat.</div>
				</div>
			</span>
			<span>
				<div class="iconPatent"></div>
				<div class="functionalityText">
					<h5>Track Patents</h5>
					<div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla dignissim metus sit amet metus pharetra sagittis. Suspendisse sit amet rhoncus erat.</div>
				</div>
			</span>
		</div>
		<div class="functionalityLine">
			<span>
				<div class="iconScience"></div>
				<div class="functionalityText">
					<h5>Track Science</h5>
					<div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla dignissim metus sit amet metus pharetra sagittis. Suspendisse sit amet rhoncus erat.</div>
				</div>
			</span>
			<span>
				<div class="iconMail"></div>
				<div class="functionalityText">
					<h5>Result by email</h5>
					<div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla dignissim metus sit amet metus pharetra sagittis. Suspendisse sit amet rhoncus erat.</div>
				</div>
			</span>
		</div>
		<div class="functionalityLine">
			<span>
				<div class="iconOption"></div>
				<div class="functionalityText">
					<h5>Hight customization</h5>
					<div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla dignissim metus sit amet metus pharetra sagittis. Suspendisse sit amet rhoncus erat.</div>
				</div>
			</span>
			<span>
				<div class="iconHistory"></div>
				<div class="functionalityText">
					<h5>Effective history</h5>
					<div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla dignissim metus sit amet metus pharetra sagittis. Suspendisse sit amet rhoncus erat.</div>
				</div>
			</span>
		</div>
	</div>

	<div class="buttonArea">
		<span class="line"></span>
		<a href="#signup" class="buttonTry">Let me try !</a>
		<span class="line"></span>
	</div>

	<div class="titleButton">
		Coming soon
	</div>

	<div class="functionality">
		<div class="functionalityLine">
			<span>
				<div class="iconRSS"></div>
				<div class="functionalityText">
					<h5>Result by RSS</h5>
					<div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla dignissim metus sit amet metus pharetra sagittis. Suspendisse sit amet rhoncus erat.</div>
				</div>
			</span>
			<span>
				<div class="iconTwitter"></div>
				<div class="functionalityText">
					<h5>Track Twitter</h5>
					<div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla dignissim metus sit amet metus pharetra sagittis. Suspendisse sit amet rhoncus erat.</div>
				</div>
			</span>
		</div>
		<div class="functionalityLine">
			<span>
				<div class="iconWiki"></div>
				<div class="functionalityText">
					<h5>Wiki</h5>
					<div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla dignissim metus sit amet metus pharetra sagittis. Suspendisse sit amet rhoncus erat.</div>
				</div>
			</span>
			<span>
				<div class="iconSMS"></div>
				<div class="functionalityText">
					<h5>Alert by SMS</h5>
					<div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla dignissim metus sit amet metus pharetra sagittis. Suspendisse sit amet rhoncus erat.</div>
				</div>
			</span>
		</div>
		<div class="functionalityLine">
			<span>
				<div class="iconStats"></div>
				<div class="functionalityText">
					<h5>Statistics</h5>
					<div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla dignissim metus sit amet metus pharetra sagittis. Suspendisse sit amet rhoncus erat.</div>
				</div>
			</span>
		</div>
	</div>

	<div id="signup">
		<div class="inscription">
			<p class="title_inscription" >Sign up</p>

			<form method="post" action="index#signup" >
				<p class="title_form_inscription" >Pseudo<label for="Pseudo"></label><br>
					<input class="inscription_field" type="text" name="reg_pseudo" id="Pseudo" value="" />
				</p>

				<p class="title_form_inscription" >Passphrase<label for="Mot de passe"></label><br>
					<input class="inscription_field" type="password" name="reg_password" id="reg_password" placeholder=""/>
				</p>

				<p class="title_form_inscription" >Re passphrase<label for="ReMot de passe"></label><br>
					<input class="inscription_field" type="password" name="reg_repassword" id="reg_repassword" placeholder=""/>
				</p>

				<p class="title_form_inscription" >Email<label for="Mail"></label><br>
					<input class="inscription_field" type="email" name="reg_mail" id="Mail" value=""/>
				</p>

				<p class="title_form_inscription" >Captcha<label for="captcha"></label><br>
					<div class="align">
						<input class="captcha_field" type="text" name="captcha" id="captcha" placeholder="" />
						<div class="captcha" >
							<div class="captcha1"></div><div class="captcha2"></div><div class="captcha3"></div><div class="captcha4"></div>
						</div>
					</div>
				</p>

				<p class="error_inscription" ><?php echo $error_pass_length;
				echo $error_bad_email;
				echo $error_existing_pseudo;
				echo $error_existing_email;
				echo $error_pass_doesnt_match;
				echo $error_bad_captcha; ?></p>
				<input class="submit_inscription" type="submit" value="Sign up" />
			</form>
		</div>
	</div>
</div>
