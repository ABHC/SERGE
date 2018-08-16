<?php

include('permission/connection_sql.php');
include('web/model/read.php');
include('web/model/update.php');

//Reading database
$tableName = 'users_table_serge';
$fieldToRead = 'id,history_lifetime';
$readDatabase = read($tableName, $fieldToRead, array(), '', $bdd);

$tableName = 'results_news_serge';
$fieldToRead = 'id,send_status,read_status,serge_date,owners';
$readDatabaseResultsNews = read($tableName, $fieldToRead, array(), 'LIMIT 5', $bdd);

foreach($readDatabase as $user)
{
  if( $user['history_lifetime'] != NULL )
  {
    foreach($readDatabaseResultsNews as $result)
    {
        $afterReplaceOwner = preg_replace('#,'.$user['id'].',#', ',', $result['owners']);

        if( ( $afterReplaceOwner != $result['owners'] ) && ( time() - $result['serge_date'] > $user['history_lifetime'] ) )
        {
          $afterReplaceSend = preg_replace('#,'.$user['id'].',#', ',', $result['send_status']);
          $afterReplaceRead = preg_replace('#,'.$user['id'].',#', ',', $result['read_status']);

          echo "line changed\n";
        }
        else
        {
          echo "line not changed\n";
        }
    }
  }
  else
  {
    echo "line not changed because history lifetime = NULL\n";
  }
}

?>
