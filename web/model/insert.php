<?php
function insert(string $tableName, array $insertCol, string $optional, string $redirectPage, $bdd)
{
	# USAGE
	/*
	$insertCol = array(array('ColumnName', 'ColValue'),
										array('ColumnName', 'ColValue');
	*/

	$INSERTvar   = '';
	$VALUESvar   = '';
	$arrayValues = array();
	$comma       = '';

	foreach ($insertCol as $line)
	{
		$nameCol = $line[0];
		$value   = $line[1];

		$INSERTvar = $INSERTvar . $comma . $nameCol;
		$VALUESvar = $VALUESvar . $comma . ':' . $nameCol;
		$comma     = ',';

		$arrayValues = array_merge($arrayValues, array($nameCol => $value));
	}

	# SQL request
	try
	{
		$req = $bdd->prepare("INSERT INTO $tableName ($INSERTvar) VALUES ($VALUESvar) $optional");
		$req->execute($arrayValues);
		$req->closeCursor();
		$execution = TRUE;

		if (!empty($redirectPage))
		{
			header("Location: $redirectPage");
			die();
		}
	}
	catch (Exception $e)
	{
		$execution = FALSE;
		// Error in log $internalErrorMessage = $e->getMessage();
		error_log($e->getMessage(), 0);
	}

	return $execution;
}
?>
