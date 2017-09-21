<?php
function get_t($name, $bdd)
{
	$language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
	$language = strtoupper($language[0] . $language[1]);
	$language = preg_replace("/[^A-Z]/", "", $language);

	if (!empty($_SESSION['lang']))
	{
		$language = $_SESSION['lang'];
	}

	if ($language != 'FR' && $language != 'EN')
	{
		$language = 'EN';
	}

	$req = $bdd->prepare("SELECT $language FROM text_content_serge WHERE index_name = :name");
	$req->execute(array(
		'name' => $name));
		$result = $req->fetch();
		$req->closeCursor();

	echo htmlspecialchars($result[$language]);

	return 0;
}
?>
