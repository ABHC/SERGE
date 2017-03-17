<?php

include_once('model/get_text.php');

include_once('view/nav/nav.php');

include_once('view/body/connexion.php');

include_once('view/footer/footer.php');

if (isset($_POST['conn_pseudo']) && isset($_POST['conn_password']))
{
	$pseudo= htmlspecialchars($_POST['conn_pseudo']);
	$password = hash('sha256', $_POST['conn_password']);

	include_once('model/connexion.php');

	if (!$result)
	{
	    echo 'Mauvais identifiant ou mot de passe !';
	}
	else
	{
	    session_start();
	    $_SESSION['pass']   = $password;
	    $_SESSION['pseudo'] = $pseudo;
	    $redirect           = htmlspecialchars($_GET['redirectFrom']);
	    header("Location: $redirect");
	}
}

?>
