<?php
function update($tableName, $updateCol, $checkCol, $optional, $bdd)
{
	# USAGE
	/*
	$updateCol = array(array("ColumnName", "ColNewValue"),
										array("ColumnName", "ColNewValue");
	$checkCol = array(array("ColumnName", "=", "ColValue", "OR"),
										array("ColumnName", "l", "ColValue", "AND"),
										array("ColumnName", "=", "ColValue", ""));
	*/

	$SETvar      = '';
	$WHEREvar    = '';
	$arrayValues = array();
	$comma       = '';

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

		$WHEREvar    = $WHEREvar . $nameCol . $op . ":" . 'check' . $nameCol . $connector;
		$nameCol = 'check' . $nameCol;
		$arrayValues = array_merge($arrayValues, array($nameCol => $value));
	}

	foreach ($updateCol as $line)
	{
		$nameCol = $line[0];
		$value   = $line[1];

		$SETvar = $SETvar . $comma . $nameCol . " = :new" . $nameCol;
		$comma = ",";
		$nameCol = 'new' . $nameCol;
		$arrayValues = array_merge($arrayValues, array($nameCol => $value));
	}

	# SQL request
	try
	{
		$req = $bdd->prepare("UPDATE $tableName SET $SETvar WHERE $WHEREvar $optional");
		$req->execute($arrayValues);
		$req->closeCursor();
		$execution = TRUE;
	}
	catch (Exception $e)
	{
		$execution            = FALSE;
		$internalErrorMessage = $e->getMessage();
	}

	return $execution;
}
?>
