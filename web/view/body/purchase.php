<div class="background"></div>
<div class="body">
	<?php echo $priceMessageSuccess; ?>
	<?php
	if ($data['type'] === 'SMS')
	{
		?>
		<div class="window">
			<div class="emoticon-sad"></div>
			<?php get_t('Sorry, SMS purchase is not available in beta version', $bdd); ?>
		</div>
		<?php
	}
	else
	{
		?>
		<h3><?php get_t('title0_title_purchase', $bdd); ?></h3>
		<h4><?php get_t('title1_title_purchase', $bdd); ?></h4>
		<div class="functionality-line">
			<div>
				<div class="icon-mail"></div>
				<div class="functionality-text">
					<h5><?php get_t('functionality4_title_index', $bdd); ?></h5>
					<div><?php get_t('functionality4_text_index', $bdd); ?></div>
				</div>
			</div>
			<div>
				<div class="icon-RSS"></div>
				<div class="functionality-text">
					<h5><?php get_t('functionality7_title_index', $bdd); ?></h5>
					<div><?php get_t('functionality7_text_index', $bdd); ?></div>
				</div>
			</div>
		</div>
		<div class="functionality-line">
			<div>
				<div class="icon-twitter"></div>
				<div class="functionality-text">
					<h5><?php get_t('functionality8_title_index', $bdd); ?></h5>
					<div><?php get_t('functionality8_text_index', $bdd); ?></div>
				</div>
			</div>
			<div>
				<div class="icon-SMS"></div>
				<div class="functionality-text">
					<h5><?php get_t('functionality10_title_index', $bdd); ?></h5>
					<div><?php get_t('functionality10_text_index', $bdd); ?></div>
				</div>
			</div>
		</div>

		<?php
		if ($needToPay === TRUE)
		{
			?>
			<div id="purchase" class="form-window">
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
			<div class="form-window">
				<h3><?php get_t('title_text_purchase', $bdd); ?></h3>
				<h6 id="price"><?php echo $monthPrice; ?> €</h6>
				<form method="post" action="purchase#purchase">
					<input type="hidden" name="nonce" value="<?php echo $nonce; ?>"/>
					<div><?php get_t('input1_purchase_purchase', $bdd); ?><br><input class="number alpha" type="number" name="months" min="1" max="30" value="1" onchange="updatePrice(this.value,<?php echo $monthPrice; ?>);"/><?php get_t('input1_text_purchase', $bdd); ?></div>
					<div><?php get_t('input2_purchase_purchase', $bdd); ?><input class="purchase_field" type="text" name="premiumCode" id="code" /></div>
					<?php echo $ERRORMESSAGE ?? ''; ?>
					<div>
						<input type="checkbox" name="readCGS" id="CGS" value="true" required/>
						<label class="checkbox" for="CGS"></label>&nbsp;&nbsp;&nbsp;
						<a href="legal" target="_blank"><?php get_t('input3_purchase_purchase', $bdd); ?></a>
					</div>
					<input class="submit-button" type="submit" value="<?php get_t('submit_button_purchase', $bdd); ?>" name="submitPurchase"/>
				</form>
			</div>
			<?php
		}
	}
	?>
</div>
