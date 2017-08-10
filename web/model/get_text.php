<?php
function get_t($name, $bdd)
{
	if (!empty($_SESSION['lang']))
	{
		$language = $_SESSION['lang'];
	}
	else
	{
		$language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		$language = strtoupper($language[0] . $language[1]);

		if ($language != 'FR' AND $language != 'EN')
		{
			$language = 'EN';
		}
	}

	$req = $bdd->prepare("SELECT $language FROM text_content_serge WHERE index_name = :name");
	$req->execute(array(
		'name' => $name));
		$result = $req->fetch();
		$req->closeCursor();

	echo $result[$language];

	return 0;
}
?>
