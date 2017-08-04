<?php
$reponse = $bdd->query('SELECT * FROM captcha_serge');
$cpt=0;
while ($donnees = $reponse->fetch())
{
		$captcha[$cpt]=$donnees['name'];
		$cpt++;
}
$reponse->closeCursor();
?>
