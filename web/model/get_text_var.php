<?php
function var_get_t(string $name, $bdd)
{
	if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE']))
	{
		$language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		$language = strtoupper($language[0] . $language[1]);
		$language = preg_replace("/[^A-Z]/", "", $language);
	}

	if (!empty($_SESSION['lang']))
	{
		$language = $_SESSION['lang'];
	}

	if (empty($language) && $language != 'FR' && $language != 'EN')
	{
		$language = 'EN';
	}

	$req = $bdd->prepare("SELECT $language FROM text_content_serge WHERE index_name = :name");
	$req->execute(array(
		'name' => $name));
		$result = $req->fetch();
		$req->closeCursor();

	return $result[$language];
}
?>
