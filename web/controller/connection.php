<?php

//include_once('model/get_text.php');

include_once('view/nav/nav.php');

include_once('view/body/connection.php');

include_once('view/footer/footer.php');

if (isset($_POST['conn_pseudo']) && isset($_POST['conn_password']))
{
	$pseudo   = htmlspecialchars($_POST['conn_pseudo']);
	$password = hash('sha256', $_POST['conn_password']);

	include_once('model/connection.php');

	if (!$result)
	{
		echo 'Mauvais identifiant ou mot de passe !';
	}
	else
	{
		session_start();
		$_SESSION['pseudo']            = $pseudo;
		$_SESSION['id']                = $result['id'];
		$_SESSION['email']             = $result['email'];
		$_SESSION['send_condition']    = $result['send_condition'];
		$_SESSION['frequency']         = $result['frequency'];
		$_SESSION['link_limit']        = $result['link_limit'];
		$_SESSION['selected_days']     = $result['selected_days'];
		$_SESSION['selected_hour']     = $result['selected_hour'];
		$_SESSION['mail_design']       = $result['mail_design'];
		$_SESSION['language']          = $result['language'];
		$_SESSION['background_result'] = $result['background_result'];
		$redirect                      = $_SESSION['redirectFrom'];
		header("Location: $redirect");
	}
}

?>
