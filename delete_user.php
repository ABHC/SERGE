<?php

include('permission/connection_sql.php');
include('web/model/read.php');
include('web/model/update.php');

//Reading database
$tableName = 'users_table_serge';
$fieldToRead = 'id,signup_date,email_validation,req_for_del';
$readDatabase = read($tableName, $fieldToRead, array(array('email_validation', '=', 0, '')), 'OR `req_for_del` IS NOT NULL', $bdd);

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
