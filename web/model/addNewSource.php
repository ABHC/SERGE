<?php
// Check if source is already in bdd
$req = $bdd->prepare('SELECT owners FROM rss_serge WHERE link = :link');
$req->execute(array(
	'link' => $source));
	$result = $req->fetch();
	$req->closeCursor();

if (!$result)
{
	// Adding new source
	$owners = ',' . $_SESSION['id'] . ',';
	$active = 1;
	preg_match('@^(?:http.*://[www.]*)?([^/]+)@i', $source, $matches);
	$name = ucfirst($matches[1] . '[!NEW!]');
	$req = $bdd->prepare('INSERT INTO rss_serge (link, owners, name, active) VALUES
	(:link, :owners, :name, :active)');
	$req->execute(array(
		'link' => $source,
		'owners' => $owners,
		'name' => $name,
		'active' => $active));
		$req->closeCursor();
}
else
{
	$actualOwner ='%,' . $_SESSION['id'] . ',%';
	$req = $bdd->prepare('SELECT owners, active FROM rss_serge WHERE owners LIKE :owners AND link = :link');
	$req->execute(array(
		'owners' => $actualOwner,
		'link' => $source));
		$resultActualOwner = $req->fetch();
		$req->closeCursor();

		if (!$resultActualOwner)
		{
			// Update owners of existing source with the new onwer
			$newOwners = $resultActualOwner['owners'] . $_SESSION['id'] . ',';
			$active    = $resultActualOwner['active'] + 1;
			$req = $bdd->prepare('UPDATE rss_serge SET owners = :owners, active = :active WHERE link = :link');
			$req->execute(array(
				'owners' => $newOwners,
				'link' => $source,
				'active' => $active));
				$req->closeCursor();
		}
		else
		{
			$_SESSION['ERROR_MESSAGE'] = 'This source is already in the database';
		}
}

?>
