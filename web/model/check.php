<?php
function check($tableName, $checkCol, $bdd)
{
	$WHEREvar = '';

	foreach ($checkCol as $key => $value)
	{

		# Sanitize
		$nameCol = $bdd->quote($nameCol);
		$op = $bdd->quote($op);
		$value = $bdd->quote($value);

		$WHEREvar = $WHEREvar . $nameCol . $op . $value ;
	}


	# Sanitize
	$tableName = $bdd->quote($tableName);

	$req = $bdd->prepare("SELECT id FROM users_table_serge WHERE users = :pseudo");
	$req->execute(array(
		'pseudo' => $_SESSION['pseudo']));
		$userSettings = $req->fetch();
		$req->closeCursor();

	return $userSettings;
}
?>
