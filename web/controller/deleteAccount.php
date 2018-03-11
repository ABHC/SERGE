<?php

# Define variable
$resultTab             = '';
$wikiTab               = '';
$settingTab            = '';
$errorMessage          = '';
$checkYourEmails       = FALSE;
$unvalidLink           = FALSE;
$forgotPassphraseStep0 = FALSE;
$forgotPassphraseStep1 = FALSE;

include('controller/accessLimitedToSignInPeople.php');
include('model/get_text.php');
include('model/get_text_var.php');
include('model/read.php');
include('model/update.php');
include('controller/generateNonce.php');


$unsafeData = array();
$unsafeData = array_merge($unsafeData, array(array('token', 'token', 'GET', 'str')));

include('controller/dataProcessing.php');


if (!empty($data['token']))
{
	# Check if checker is good
	$checkCol   = array(array('token', '=', $data['token'], ''));
	$rightUser  = read('users_table_serge', 'id, email', $checkCol, '', $bdd);
	$userId     = $rightUser[0]['id'];
	$email      = $rightUser[0]['email'];

	if ($userId === $_SESSION['id'])
	{
		# Send email to admin
		$checkCol     = array(array('name', '=', 'support_email', ''));
		$supportEmail = read('miscellaneous_serge', 'value', $checkCol, '', $bdd);

		$to      = $supportEmail[0]['value'];
		$subject = 'Serge : Delete user';
		$body    = 'Delete user number : ' . $_SESSION['id'] . ' with this email : ' . $email;

		include('controller/sendmail.php');
	}
	else
	{
		header('Location: connection');
		die();
	}
}


include('view/nav/nav.php');

include('view/body/deleteAccount.php');

include('view/footer/footer.php');

?>
