<?php
# TODO Gestion des erreurs
$reponse_get_text = $bdd->query('SELECT * FROM Text_content');

$cpt=0;

while($donnes_get_text = $reponse_get_text->fetch())
{
	$get_text[$donnes_get_text['Name']] = $donnes_get_text['English'];
}

$reponse_get_text->closeCursor();
?>
