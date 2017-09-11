<?php
function read($tableName, $selectedCol, $checkCol, $optional, $bdd)
{
	# USAGE
	/*
	$checkCol = array(array('ColumnName', '=', 'ColValue', 'OR'),
										array('ColumnName', 'l', 'ColValue', 'AND'),
										array('ColumnName', '=', 'ColValue', ''));
	*/

	$check       = FALSE;
	$arrayValues = array();
	$cpt = 0;
	$WHEREvar = 1;

	if (!empty($checkCol))
	{
		$WHEREvar = '';
		foreach ($checkCol as $line)
		{
			$op = ' = ';
			if ($line[1] == 'l')
			{
				$op = ' LIKE ';
			}
			elseif ($line[1] == '>=')
			{
				$op = ' >= ';
			}
			elseif ($line[1] == 'IN')
			{
				$op = ' IN ';
			}
			elseif ($line[1] == '<>')
			{
				$op = ' <> ';
			}

			$nameCol   = $line[0];
			$value     = $line[2];
			$connector = ' ' . $line[3] . ' ';

			$WHEREvar    = $WHEREvar . $nameCol . $op . '(:' . $nameCol . $cpt . ')' . $connector;
			$arrayValues = array_merge($arrayValues, array($nameCol . $cpt => $value));
			$cpt++;
		}
	}

	if (empty($selectedCol))
	{
		$selectedCol = 'id';
		$check = TRUE;
		$result = TRUE;
	}

	# SQL request
	$req = $bdd->prepare("SELECT $selectedCol FROM $tableName WHERE $WHEREvar $optional");
	$req->execute($arrayValues);
	$result = $req->fetchAll();
	$req->closeCursor();

	if (empty($result) AND $check)
	{
		$result = FALSE;
	}

	return $result;
}
?>
