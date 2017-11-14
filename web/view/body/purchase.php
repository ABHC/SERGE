<div class="background"></div>
<div class="body">
	<?php echo $priceMessageSuccess; ?>
	<h2>Become premium</h2>
	<h3>Access to all Serge's features</h3>
	<div class="functionality">
		<div class="premiumFunctionalityLine">
			<div>
				<div class="iconMail"></div>
				<div class="functionalityText">
					<h5><?php get_t('functionality4_title_index', $bdd); ?></h5>
					<div><?php get_t('functionality4_text_index', $bdd); ?></div>
				</div>
			</div>
			<div>
				<div class="iconRSS"></div>
				<div class="functionalityText">
					<h5><?php get_t('functionality7_title_index', $bdd); ?></h5>
					<div><?php get_t('functionality7_text_index', $bdd); ?></div>
				</div>
			</div>
		</div>
		<div class="premiumFunctionalityLine">
			<div>
				<div class="iconTwitter"></div>
				<div class="functionalityText">
					<h5><?php get_t('functionality8_title_index', $bdd); ?></h5>
					<div><?php get_t('functionality8_text_index', $bdd); ?></div>
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
		if ($needToPay === TRUE)
		{
	?>
	<div id="purchase" class="purchase">
		<h3>Payment system choice :</h3><br>
		<form action="purchase" method="POST">
			<input type="hidden" name="nonce" value="<?php echo $nonce; ?>"/>
			<input type="hidden" name="stripeAccess" value="true"/>
			Stripe payment system &nbsp; <script
			src="https://checkout.stripe.com/checkout.js" class="stripe-button"
			data-key="<?php echo $stripe['publishable_key']; ?>"
			data-amount="<?php echo $price; ?>"
			data-name="Cairn Devices"
			data-description="Serge premium for <?php echo $data['months']; ?> month"
			data-image="images/SERGE_logo_norm.png"
			data-locale="auto"
			data-zip-code="true"
			data-currency="eur">
			</script>
		</form>
	</div>
	<?php
		}
		else
		{
	 ?>
	<div class="purchase">
		<span class="title_purchase"><?php get_t('title_text_purchase', $bdd); ?></span>
		<div class="price" id="price"><?php echo $monthPrice; ?> €</div>
		<form method="post" action="purchase#purchase">
			<input type="hidden" name="nonce" value="<?php echo $nonce; ?>"/>
			<p class="title_form_purchase"><?php get_t('input1_purchase_purchase', $bdd); ?></p> <p><input class="number alpha" type="number" name="months" min="1" max="30" value="1" onchange="updatePrice(this.value,<?php echo $monthPrice; ?>);"/><?php get_t('input1_text_purchase', $bdd); ?></p>
			<p class="title_form_purchase"><?php get_t('input2_purchase_purchase', $bdd); ?></p> <p><input class="purchase_field" type="text" name="premiumCode" id="code" /></p>
			<?php echo $ERRORMESSAGE ?? ''; ?>
			<p><input type="checkbox" name="readCGS" id="CGS" value="true" required/><label class="checkbox" for="CGS"></label><a href="legal" ><?php get_t('input3_purchase_purchase', $bdd); ?></a></p>
			<input class="submit_purchase" type="submit" value="<?php get_t('submit_button_purchase', $bdd); ?>" name="submitPurchase"/>
		</form>
	</div>
	<?php
	}
	?>
</div>
