<?php
	# SQL request
	try
	{
		$req = $bdd->prepare("SHOW columns FROM `equivalence_science_serge`");
		$req->execute();
		$columnsNames = $req->fetchAll();
		$req->closeCursor();
	}
	catch (Exception $e)
	{
		// Error in log
		error_log($e->getMessage(), 0);
	}
?>
