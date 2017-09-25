<?php

# Define variable
$resultTab     = '';
$wikiTab       = '';
$settingTab    = '';
$premiumCodeId = 0;
$needToPay     = FALSE;

include('controller/accessLimitedToSignInPeople.php');
include('model/get_text.php');
include('model/get_text_var.php');
include('model/read.php');
include('model/update.php');
include('model/insert.php');
include('controller/generateNonce.php');


$unsafeData = array();
$unsafeData = array_merge($unsafeData, array(array('months', 'months', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('premiumCode', 'premiumCode', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('readCGS', 'readCGS', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('submitPurchase', 'submitPurchase', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('stripeAccess', 'stripeAccess', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('stripeToken', 'stripeToken', 'POST', 'str')));

include('controller/dataProcessing.php');

# Nonce
$nonceTime = $_SERVER['REQUEST_TIME'];
$nonce     = getNonce($nonceTime);

# Read user details
$checkCol    = array(array('id', '=', $_SESSION['id'], ''));
$userDetails = read('users_table_serge', 'premium_expiration_date', $checkCol, '', $bdd);
$userDetails = $userDetails[0];

if ($userDetails['premium_expiration_date'] < $_SERVER['REQUEST_TIME'])
{
	$userDetails['premium_expiration_date'] = $_SERVER['REQUEST_TIME'] - 1;
}

if (!empty($data['submitPurchase']) && !empty($data['readCGS']) && !empty($data['months']))
{
	// Read month price
	$checkCol   = array(array('type', '=', 'month', 'AND'),
											array('currency', '=', 'EUR', ''));
	$result     = read('price_table_serge', 'price', $checkCol, '',$bdd);
	$monthPrice = $result[0]['price'];

	// Check if $data['month'] is min 1 and max 30 and int
	if ($data['months'] < 1 || $data['months'] > 30)
	{
		$data['months'] = 1;
	}

	$price           = $monthPrice * $data['months'];
	$premiumDuration = $data['months'] * 30 *24 * 3600;

	if (!empty($data['premiumCode']))
	{
		// Check if premium code exist
		$checkCol = array(array('code', '=', $data['premiumCode'], 'AND'),
											array('users', 'nl', '%,' . $_SESSION['id'] . ',%', 'AND'),
											array('expiration_date', '>', $_SERVER['REQUEST_TIME'], ''));
		$result   = read('premium_code_table_serge', 'id, duration_premium, users', $checkCol, '',$bdd);

		$premiumCodeId       = $result[0]['id'];
		$premiumCodeDuration = $result[0]['duration_premium'];

		if (!empty($premiumCodeDuration))
		{
			// Update premium code entry with the new user
			$updateCol = array(array('users', $result[0]['users'] . $_SESSION['id'] . ','));
			$checkCol  = array(array('id', '=', $premiumCodeId, ''));
			$execution = update('premium_code_table_serge', $updateCol, $checkCol, '', $bdd);

			$price = $price - (($premiumCodeDuration/3600*24*30) * $monthPrice);

			if ($price < 0)
			{
				$price = 0;
			}
		}
	}

	if ($price > 0)
	{
		$needToPay = TRUE;

		require_once('vendor/autoload.php');

		// Read stripe keys
		$checkCol = array(array('account_name', '=', 'Cairn Devices Serge TEST', ''));
		$result   = read('stripe_table_serge', 'secret_key, publishable_key', $checkCol, '',$bdd);
		$result   = $result[0];

		$stripe = array(
			'secret_key'      => $result['secret_key'],
			'publishable_key' => $result['publishable_key']
		);

		\Stripe\Stripe::setApiKey($stripe['secret_key']);

		$_SESSION['price']           = $price;
		$_SESSION['premiumDuration'] = $premiumDuration;
		$_SESSION['currency']        = 'eur';
	}
	elseif ($price === 0)
	{
		// Set user to premium
		$updateCol = array(array('premium_expiration_date', $userDetails['premium_expiration_date'] + $premiumDuration));
		$checkCol  = array(array('id', '=', $_SESSION['id'], ''));
		$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);

		$checkCol = array();
		$result   = read('purchase_table_serge', 'id', $checkCol, 'ORDER BY id DESC LIMIT 1',$bdd);

		if (empty($result))
		{
			$result[0]['id'] = 0;
		}

		$invoiceId = $result[0]['id'] + 1;

		// Add details in purchase table
		$insertCol = array(array('user_id', $_SESSION['id']),
											array('purchase_date', $_SERVER['REQUEST_TIME']),
											array('duration_premium', $premiumDuration),
											array('invoice_number', 'FA' . $invoiceId . '-user-' . $_SESSION['id']),
											array('price', 0),
											array('premium_code_id', $premiumCodeId),
											array('bank_details', 'none'));
		$execution = insert('purchase_table_serge', $insertCol, '', '', $bdd);

		header('Location: setting');
		die();
	}
}
elseif (!empty($data['stripeAccess']) && $data['stripeAccess'] === 'true')
{
	require_once('vendor/autoload.php');

	// Read stripe keys
	$checkCol = array(array('account_name', '=', 'Cairn Devices Serge TEST', '')); // TODO Add in installation script
	$result   = read('stripe_table_serge', 'secret_key, publishable_key', $checkCol, '',$bdd);
	$result = $result[0];

	$stripe = array(
		'secret_key'      => $result['secret_key'],// TODO Add in installation script
		'publishable_key' => $result['publishable_key']// TODO Add in installation script
	);

	\Stripe\Stripe::setApiKey($stripe['secret_key']);

	$token = $data['stripeToken'];

	$customer = \Stripe\Customer::create(array(
			'email' => $_SESSION['email'],
			'source'  => $token
	));

	$charge = \Stripe\Charge::create(array(
			'customer' => $customer->id,
			'amount'   => $_SESSION['price'],
			'currency' => $_SESSION['currency']
	));

	// Set user to premium
	$updateCol = array(array('premium_expiration_date', $userDetails['premium_expiration_date'] + $_SESSION['premiumDuration']));
	$checkCol  = array(array('id', '=', $_SESSION['id'], ''));
	$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);

	$checkCol = array();
	$result   = read('purchase_table_serge', 'id', $checkCol, 'ORDER BY id DESC LIMIT 1',$bdd);

	if (empty($result))
	{
		$result[0]['id'] = 0;
	}

	$invoiceId = $result[0]['id'] + 1;

	$insertCol = array(array('user_id', $_SESSION['id']),
										array('purchase_date', $_SERVER['REQUEST_TIME']),
										array('duration_premium', $_SESSION['premiumDuration']),
										array('invoice_number', 'FA' . $invoiceId . '-user-' . $_SESSION['id']),
										array('price', $_SESSION['price']),
										array('premium_code_id', $premiumCodeId),
										array('bank_details', 'none'));
	$execution = insert('purchase_table_serge', $insertCol, '', '', $bdd);

	$price = $_SESSION['price'] / 100;
	unset($_SESSION['price']);
	unset($_SESSION['premiumDuration']);
	echo "<h1>Successfully charged $price € !</h1>";
}

include('view/nav/nav.php');

include('view/body/purchase.php');

include('view/footer/footer.php');

?>
