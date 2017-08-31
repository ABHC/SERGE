<?php
$result = $bdd->query('SELECT * FROM captcha_serge');
$cpt=0;
while ($donnees = $result->fetch())
{
		$captcha[$cpt]=$donnees['name'];
		$cpt++;
}
$result->closeCursor();
?>
