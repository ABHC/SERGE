<?php

include('permission/connection_sql.php');
include('web/model/read.php');
include('web/model/update.php');

//Reading database
$tableName = 'users_table_serge';
$fieldToRead = 'id,history_lifetime';
$readDatabase = read($tableName, $fieldToRead, array(), 'AND `history_lifetime` IS NOT NULL', $bdd);

$tableName = 'results_news_serge';
$fieldToRead = 'id,send_status,read_status,serge_date,owners';
$readDatabaseResultsNews = read($tableName, $fieldToRead, array(), '', $bdd);

foreach($readDatabase as $user)
{
  foreach($readDatabaseResultsNews as $key => $result)
  {
    $regex = '#,'.$user['id'].',#';

    if( ( preg_match($regex , $result['owners']) ) && ( time() - $result['serge_date'] > $user['history_lifetime'] ) )
    {
      $readDatabaseResultsNews[$key]['send_status'] = preg_replace($regex, ',', $result['send_status']);
      $readDatabaseResultsNews[$key]['read_status'] = preg_replace($regex, ',', $result['read_status']);
      $readDatabaseResultsNews[$key]['owners']      = preg_replace($regex, ',', $result['owners']);
    }
  }
}

foreach($readDatabaseResultsNews as $line)
{
  $tableToWrite = 'results_news_serge';
  $updateCol = array(array( 'send_status', $line['send_status'] ),
                     array( 'read_status', $line['read_status'] ),
                     array( 'owners',      $line['owners']      )
                    );
  $checkCol = array(array('id', '=', $line['id'], ''));
  update($tableToWrite, $updateCol, $checkCol, '', $bdd);
}

?>
