<?php

# Define variable
$resultTab             = '';
$wikiTab               = '';
$settingTab            = '';

include('controller/accessLimitedToSignInPeople.php');
include('model/get_text.php');
include('model/get_text_var.php');
include('model/read.php');
include('controller/generateNonce.php');


$unsafeData = array();
$unsafeData = array_merge($unsafeData, array(array('pseudo', 'conn_pseudo', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('password', 'conn_password', 'POST', 'str')));

include('controller/dataProcessing.php');

# Nonce
$nonceTime = $_SERVER['REQUEST_TIME'];
$nonce = getNonce($nonceTime);

if (isset($data['pseudo']) AND isset($data['password']))
{
	$ERRORMESSAGE = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px />' . var_get_t('badIdOrPass_error_connection', $bdd);
}

include('view/nav/nav.php');

include('view/body/purchase.php');

include('view/footer/footer.php');

?>
