<?php
// Check if source is already in bdd
/*$req = $bdd->prepare('SELECT owners FROM rss_serge WHERE link = :link');
$req->execute(array(
	'link' => $source));
	$result = $req->fetch();
	$req->closeCursor();*/

$checkCol = array(array("link", "=", $source, ""));
$result = read('rss_serge', 'owners', $checkCol, '', $bdd);
$result = $result[0];

if (!$result)
{
	// Adding new source
	#$owners = ',' . $_SESSION['id'] . ',';
	#$active = 1;
	preg_match('@^(?:http.*://[www.]*)?([^/]+)@i', $source, $matches);
	#$name = ucfirst($matches[1] . '[!NEW!]');

	$insertCol = array(array("link", $source),
										array("owners", ',' . $_SESSION['id'] . ','),
										array("name", ucfirst($matches[1] . '[!NEW!]')),
										array("active", 1));
	$execution = insert('rss_serge', $insertCol, '', 'setting', $bdd);

	/*$req = $bdd->prepare('INSERT INTO rss_serge (link, owners, name, active) VALUES
	(:link, :owners, :name, :active)');
	$req->execute(array(
		'link' => $source,
		'owners' => $owners,
		'name' => $name,
		'active' => $active));
		$req->closeCursor();*/
}
else
{
	/*$actualOwner ='%,' . $_SESSION['id'] . ',%';
	$req = $bdd->prepare('SELECT owners, active FROM rss_serge WHERE owners LIKE :owners AND link = :link');
	$req->execute(array(
		'owners' => $actualOwner,
		'link' => $source));
		$resultActualOwner = $req->fetch();
		$req->closeCursor();*/

		$checkCol = array(array("owners", "l", '%,' . $_SESSION['id'] . ',%', "AND"),
											array("link", "=", $source, ""));
		$result = read('rss_serge', 'owners, active', $checkCol, '', $bdd);
		$resultActualOwner = $result[0];

		if (!$resultActualOwner)
		{
			// Update owners of existing source with the new onwer
			/*$newOwners = $resultActualOwner['owners'] . $_SESSION['id'] . ',';
			$active    = $resultActualOwner['active'] + 1;
			$req = $bdd->prepare('UPDATE rss_serge SET owners = :owners, active = :active WHERE link = :link');
			$req->execute(array(
				'owners' => $newOwners,
				'link' => $source,
				'active' => $active));
				$req->closeCursor();*/

			$updateCol = array(array("owners", $resultActualOwner['owners'] . $_SESSION['id'] . ','),
												array("active", $resultActualOwner['active'] + 1));
			$checkCol  = array(array("link", "=", $source, ""));
			$execution = update('rss_serge', $updateCol, $checkCol, '', $bdd);
		}
		else
		{
			$_SESSION['ERROR_MESSAGE'] = 'This source is already in the database';
		}
}

?>
