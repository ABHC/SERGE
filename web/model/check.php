<?php
function check($tableName, $checkCol, $bdd)
{
	# USAGE
	/*$checkCol = array(array("ColumnName", "=", "ColValue", "OR"),
										array("ColumnName", "l", "ColValue", "AND"),
										array("ColumnName", "=", "ColValue", ""));*/

	$WHEREvar    = '';
	$arrayValues = array();

	foreach ($checkCol as $line)
	{
		if ($line[1] == "l")
		{
			$op = " LIKE ";
		}
		elseif ($line[1] == "=")
		{
			$op = " = ";
		}
		else
		{
			$op = " = ";
		}

		$nameCol   = $line[0];
		$value     = $line[2];
		$connector = " " . $line[3] . " ";

		$WHEREvar    = $WHEREvar . $nameCol . $op . ":" . $nameCol . $connector;
		$arrayValues = array_merge($arrayValues, array($nameCol => $value));
	}

	# SQL request
	$req = $bdd->prepare("SELECT id FROM $tableName WHERE $WHEREvar");
	$req->execute($arrayValues);
	$result = $req->fetch();
	$req->closeCursor();


	if (empty($result))
	{
		$check = FALSE;
	}
	else
	{
		$check = TRUE;
	}

	return $check;
}
?>
