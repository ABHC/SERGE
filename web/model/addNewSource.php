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
	$active = 0;
	$req = $bdd->prepare('INSERT INTO rss_serge (link, owners, active) VALUES
	(:link, :owners, :active)');
	$req->execute(array(
		'link' => $source,
		'owners' => $owners,
		'active' => $active));
		$req->closeCursor();
}
else
{
	// Update owners of existing source with the new onwer
	$newsOwners = $result[0] . $_SESSION['id'] . ',';
	$req = $bdd->prepare('UPDATE users_table_serge SET owners = :owners WHERE link = :link');
	$req->execute(array(
		'owners' => $newOwners,
		'link' => $source));
		$req->closeCursor();
}

?>
