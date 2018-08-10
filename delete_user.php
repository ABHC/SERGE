<?php

include('permission/connection_sql.php');
include('web/model/read.php');
include('web/model/update.php');

$tableName = 'users_table_serge';
$fieldToRead = 'id,signup_date,email_validation,req_for_del';
$readDatabase = read($tableName, $fieldToRead, array(), '', $bdd);

foreach($readDatabase as $value)
{
  //48 hours * 3600 = 172800 seconds
  if( ( (time() - $value['signup_date'] > 172800) &&
      $value['email_validation'] == 0 ) ||
      (time() - $value['req_for_del'] > 172800)
    )
  {
    $queryDeleteLine = "DELETE FROM $tableName WHERE id = ".$value['id'];

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

    echo "line deleted\n";
  }
  else
  {
    echo "no line deleted\n";
  }
}

?>
