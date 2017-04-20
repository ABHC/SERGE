<?php
function deleteLink($bdd, $resultId)
{
	$reqReadOwnersResultForId = $bdd->prepare('SELECT owners FROM result_news_serge WHERE id = :id');
	$reqReadOwnersResultForId->execute(array(
		'id' => $resultId));
		$ownersResult = $reqReadOwnersResultForId->fetch();
		$reqReadOwnersResultForId->closeCursor();

	if (!empty($ownersResult))
	{
		$userId = $_SESSION['id'];
		$OwnerNEW = preg_replace("/,$userId,/", ',', $ownersResult['owners']);

		$req = $bdd->prepare('UPDATE result_news_serge SET owners = :owners WHERE id = :id');
		$req->execute(array(
			'owners' => $OwnerNEW,
			'id' => $resultId));
			$req->closeCursor();
	}
}
?>
