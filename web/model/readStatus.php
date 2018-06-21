<?php
include('controller/accessLimitedToSignInPeople.php');
include('model/read.php');

# Data processing
$unsafeData = array();
$unsafeData = array_merge($unsafeData, array(array('articleId', 'articleId', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('type', 'type', 'POST', 'Az')));

include('controller/dataProcessing.php');

# Select table name for article type
switch ($data['type'])
{
  case "news":
    $tableName = 'result_news_serge';
    break;
  case "sciences":
    $tableName = 'result_science_serge';
    break;
  case "patents":
    $tableName = 'result_patents_serge';
    break;
}

# Read if article is mark as read
$checkCol = array(array('id', '=', $data['articleId'], ''));
$amIRead  = read($tableName, 'read_status', $checkCol, '', $bdd);

# Return result
$userIdComma = ',' . $_SESSION['id'] . ',';
if (preg_match("/$userIdComma/", $amIRead['read_status']))
{
  echo 'read';
}
?>
