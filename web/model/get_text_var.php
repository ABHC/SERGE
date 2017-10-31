<?php
function var_get_t(string $name, $bdd)
{
	$language = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
	$language = strtoupper($language[0] . $language[1]);

	if ($language != 'FR' && $language != 'EN')
	{
		$language = 'EN';
	}

	if (!empty($_SESSION['lang']))
	{
		$language = $_SESSION['lang'];
	}

	$req = $bdd->prepare("SELECT $language FROM text_content_serge WHERE index_name = :name");
	$req->execute(array(
		'name' => $name));
		$result = $req->fetch();
		$req->closeCursor();

	return $result[$language];
}
?>
