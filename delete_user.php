<?php

include('permission/connection_sql.php');
include('web/model/read.php');
include('web/model/update.php');

$tableName = 'users_table_serge';
$fieldToRead = 'id,signup_date,email_validation,req_for_del';
$readDatabase = read($tableName, $fieldToRead, array(), '', $bdd);

foreach($readDatabase as $row)
{
  //48 hours * 3600 = 172800 seconds
  if(
      ( ($row['email_validation'] == 0) && (time() - $row['signup_date']) > 172800 ) ||
      ( ($row['req_for_del'] != NULL)   && (time() - $row['req_for_del']) > 172800 )
    )
  {

    $queryDeleteLine = "DELETE FROM $tableName WHERE id = ".$row['id'];

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
