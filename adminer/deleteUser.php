<?php

/*
This script will delete all users that correspond to one of these two conditions
either the user didn't validate his account within 2 days after his registration
either the user requested to delete his account and didn't cancel his request within 2 days
*/

//Reading database
$tableName    = 'users_table_serge';
$fieldToRead  = 'id,signup_date,email_validation,req_for_del';
$readDatabase = read($tableName, $fieldToRead, array(array('email_validation', '=', 0, '')), 'OR `req_for_del` IS NOT NULL', $bdd);

//In order to collect all users deleted to delete their history later
$userDeleted  = array();

foreach($readDatabase as $line)
{
  //48 hours * 3600 = 172800 seconds
  //If no email validation more than 48 hours OR request for deletion more than 48 hours
  //Then delete
  if(
      ( ($line['email_validation'] == 0) && (time() - $line['signup_date']) > 172800 ) ||
      ( ($line['req_for_del'] != NULL)   && (time() - $line['req_for_del']) > 172800 )
    )
  {
    $userDeleted[] = $line['id'];

    //Building query
    $queryDeleteLine = "DELETE FROM $tableName WHERE id = ".$line['id'];

    //Executing query
    try
    {
      $req = $bdd->prepare($queryDeleteLine);
      $req->execute();
      $req->closeCursor();
    }
    catch (Exception $e)
    {
      error_log($e->getMessage(), 0);
    }

  }
}

?>
