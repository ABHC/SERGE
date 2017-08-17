<?php
function insert($tableName, $insertCol, $optional, $redirectPage, $bdd)
{
	# USAGE
	/*
	$insertCol = array(array("ColumnName", "ColValue"),
										array("ColumnName", "ColValue");
	*/

	$INSERTvar      = '';
	$arrayValues = array();
	$comma       = '';

	foreach ($insertCol as $line)
	{
		$nameCol = $line[0];
		$value   = $line[1];

		$INSERTvar = $INSERTvar . $comma . $nameCol;
		$VALUESvar = $VALUESvar . $comma . ':' . $nameCol;
		$comma = ",";
		$arrayValues = array_merge($arrayValues, array($nameCol => $value));
	}

	# SQL request
	try
	{
		$req = $bdd->prepare("INSERT INTO $tableName ($INSERTvar) VALUES ($VALUESvar) $optional");
		$req->execute($arrayValues);
		$req->closeCursor();

		if (!empty($redirectPage))
		{
			header("Location: $redirectPage");
		}
	}
	catch (Exception $e)
	{
		$execution            = FALSE;
		$internalErrorMessage = $e->getMessage();
	}

	return $execution;
}
?>
