<?php
function read(string $tableName, string $selectedCol, array $checkCol, string $optional, $bdd)
{
	$check       = FALSE;
	$arrayValues = array();
	$cpt         = 0;
	$WHEREvar    = 1;
	$op_array = array(
										"=" => " = ",
										"l" => " LIKE ",
										"nl" => " NOT LIKE ",
										">=" => " >= ",
										">" => " > ",
										"<" => " < ",
										"IN" => " IN ",
										"<>" => " <> ",
										"REGEXP" => " REGEXP ");

	if (!empty($checkCol))
	{
		$WHEREvar = '';
		foreach ($checkCol as $line)
		{
			$op        = $op_array[$line[1]];
			$nameCol   = $line[0];
			$value     = $line[2];
			$connector = ' ' . $line[3] . ' ';

			if (is_array($value))
			{
				$cptArray = 0;
				$comma    = '';
				$WHEREvar = $WHEREvar . $nameCol . $op . '(';

				foreach ($value as $arrayVariable)
				{
					$WHEREvar    = $WHEREvar . $comma . ':' . $nameCol . $cpt . $cptArray;
					$arrayValues = array_merge($arrayValues, array($nameCol . $cpt . $cptArray => $arrayVariable));
					$comma       = ',';

					$cptArray++;
				}
				$WHEREvar    = $WHEREvar . ')' . $connector;
			}
			else
			{
				$WHEREvar    = $WHEREvar . $nameCol . $op . '(:' . $nameCol . $cpt . ')' . $connector;
				$arrayValues = array_merge($arrayValues, array($nameCol . $cpt => $value));
			}
			$cpt++;
		}
	}

	if (empty($selectedCol))
	{
		$selectedCol = 'id';
		$check       = TRUE;
	}

	# SQL request
	try
	{
		$req = $bdd->prepare("SELECT $selectedCol FROM $tableName WHERE $WHEREvar $optional");
		$req->execute($arrayValues);
		$result = $req->fetchAll();
		$req->closeCursor();
	}
	catch (Exception $e)
	{
		// Error in log
		error_log($e->getMessage(), 0);
	}

	if (empty($result))
	{
		$result = array();
	}

	if (empty($result) && $check)
	{
		$result = FALSE;
	}
	elseif ($check)
	{
		$result = TRUE;
	}

	return $result;
}
?>
