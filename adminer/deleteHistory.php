<?php
//must be included after delete_user.php file

//Reading database
$tableName = 'users_table_serge';
$fieldToRead = 'id,history_lifetime';
$readDatabase = read($tableName, $fieldToRead, array(), 'AND `history_lifetime` IS NOT NULL', $bdd);

$tableName = 'results_news_serge';
$fieldToRead = 'id,send_status,read_status,serge_date,owners';
$resultsNews = read($tableName, $fieldToRead, array(), 'LIMIT 25', $bdd);

$deleteHistory = array();

foreach($readDatabase as $user)
{
  foreach($resultsNews as $key => $result)
  {
    $regex = '#,'.$user['id'].',#';

    if( ( preg_match($regex , $result['owners']) ) && ( time() - $result['serge_date'] > $user['history_lifetime'] ) )
    {
      $deleteHistory[] = $result['id'];

      $resultsNews[$key]['send_status'] = preg_replace($regex, ',', $resultsNews[$key]['send_status']);
      $resultsNews[$key]['read_status'] = preg_replace($regex, ',', $resultsNews[$key]['read_status']);
      $resultsNews[$key]['owners']      = preg_replace($regex, ',', $resultsNews[$key]['owners']);
    }
  }
}

foreach($userDeleted as $user)
{
  foreach($resultsNews as $key => $result)
  {
    $regex = '#,'.$user.',#';

    if( preg_match($regex , $result['owners']) )
    {
      $deleteHistory[] = $result['id'];

      $resultsNews[$key]['send_status'] = preg_replace($regex, ',', $resultsNews[$key]['send_status']);
      $resultsNews[$key]['read_status'] = preg_replace($regex, ',', $resultsNews[$key]['read_status']);
      $resultsNews[$key]['owners']      = preg_replace($regex, ',', $resultsNews[$key]['owners']);
    }
  }
}

foreach($deleteHistory as $line)
{
  $key = array_search($line, array_column($resultsNews, 'id'));

  $tableToWrite = 'results_news_serge';
  $updateCol = array(array( 'send_status', $resultsNews[$key]['send_status'] ),
                     array( 'read_status', $resultsNews[$key]['read_status'] ),
                     array( 'owners',      $resultsNews[$key]['owners']      )
                    );
  $checkCol = array(array('id', '=', $line, ''));
  update($tableToWrite, $updateCol, $checkCol, '', $bdd);
}

?>
