<?php

# Define variable
$resultTab             = '';
$wikiTab               = '';
$settingTab            = '';

include_once('controller/accessLimitedToSignInPeople.php');
include_once('model/get_text.php');
include_once('model/get_text_var.php');
include_once('model/read.php');
include_once('controller/generateNonce.php');


$unsafeData = array();
$unsafeData = array_merge($unsafeData, array(array('pseudo', 'conn_pseudo', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('password', 'conn_password', 'POST', 'str')));

include_once('controller/dataProcessing.php');

# Nonce
$nonceTime = time();
$nonce = getNonce($nonceTime);

if (isset($data['pseudo']) AND isset($data['password']))
{
	$ERRORMESSAGE = '<img src="images/pictogrammes/redcross.png" alt="error" width=15px />' . var_get_t('badIdOrPass_error_connection', $bdd);
}

include_once('view/nav/nav.php');

include_once('view/body/purchase.php');

include_once('view/footer/footer.php');

?>
