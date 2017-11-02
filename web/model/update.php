<?php
function update(string $tableName, array $updateCol, array $checkCol, string $optional, $bdd)
{
	$SETvar      = '';
	$WHEREvar    = '';
	$arrayValues = array();
	$comma       = '';
	$cpt         = 0;

	foreach ($checkCol as $line)
	{
		$op = ' = ';
		if ($line[1] == 'l')
		{
			$op = ' LIKE ';
		}

		$nameCol   = $line[0];
		$value     = $line[2];
		$connector = ' ' . $line[3] . ' ';

		$WHEREvar    = $WHEREvar . $nameCol . $op . ':' . 'check' . $nameCol . $cpt . $connector;
		$nameCol = 'check' . $nameCol . $cpt;
		$arrayValues = array_merge($arrayValues, array($nameCol => $value));
		$cpt++;
	}

	foreach ($updateCol as $line)
	{
		$nameCol = $line[0];
		$value   = $line[1];

		$SETvar = $SETvar . $comma . $nameCol . ' = :new' . $nameCol . $cpt;
		$comma = ', ';
		$nameCol = 'new' . $nameCol . $cpt;
		$arrayValues = array_merge($arrayValues, array($nameCol => $value));
		$cpt++;
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
		$execution = FALSE;
		// Error in log
		error_log($e->getMessage(), 0);
	}

	return $execution;
}
?>
