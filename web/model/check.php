<?php
function check($tableName, $checkCol, $bdd)
{
	# USAGE
	$checkCol = array(array("ColumnName", "=", "ColValue", "OR"),
										array("ColumnName", "l", "ColValue", "AND"),
										array("ColumnName", "=", "ColValue", ""));

	$WHEREvar    = '';
	$arrayValues = '';

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

		# Sanitize
		$nameCol   = " " . $bdd->quote($line[0]);
		$value     = $bdd->quote($line[2]) . " ";
		$connector = $bdd->quote($line[3]);

		$WHEREvar    = $WHEREvar . " " . $nameCol . $op . $nameCol . $connector;
		$arrayValues = $arrayValues . array('nameCol' => $value);
	}

	# Sanitize
	$tableName = $bdd->quote($tableName);

	# SQL request
	$req = $bdd->prepare("SELECT id FROM $tableName WHERE $WHEREvar");
	$req->execute($arrayValues);
	$result = $req->fetch();
	$req->closeCursor();


	if (!empty($result))
	{
		$checkResult = TRUE;
	}
	else {
		$checkResult = FALSE;
	}

	return $checkResult;
}
?>
