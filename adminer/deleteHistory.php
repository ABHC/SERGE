<?php

/*
This script delete the history of a user if he wants to
To remove a result from someone history,
remove the user id in fields send_status,read_status,owners of a result
*/

//IMPORTANT must be included after delete_user.php file

//Reading database
$tableName    = 'users_table_serge';
$fieldToRead  = 'id,history_lifetime';
$readDatabase = read($tableName, $fieldToRead, array(), 'AND `history_lifetime` IS NOT NULL', $bdd);

$tableName    = 'results_news_serge';
$fieldToRead  = 'id,send_status,read_status,serge_date,owners';
$resultsNews  = read($tableName, $fieldToRead, array(), '', $bdd);

//In order to collect all id to update
$deleteHistory = array();

//Check all users and all results
//If a user is found in a result and the date of the result is bigger than the history lifetime then
//Get the id of the result and update the value
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

//Check all deleted users and all results
//If a user is found in a result then
//Get the id of the result and update the value
//The variable userDeleted is defined in the file delete_user.php
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

//Foreach id, update with the new value
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
