<?php
set_time_limit(25);
session_start();
include('connection_sql.php');
include('../controller/accessLimitedToSignInPeople.php');
include('read.php');


# Data processing
$unsafeData = array();
$unsafeData = array_merge($unsafeData, array(array('count', 'count', 'GET', '09')));
$unsafeData = array_merge($unsafeData, array(array('articleId', 'articleId', 'GET', '09')));
$unsafeData = array_merge($unsafeData, array(array('type', 'type', 'GET', 'Az')));

include('../controller/dataProcessing.php');


if (!empty($data['articleId']) && !empty($data['type']))
{
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
	$userIdComma = '%,' . $_SESSION['id'] . ',%';
	$checkCol = array(array('id', '=', $data['articleId'], 'AND'),
	array('read_status', 'l', $userIdComma, ''));
	$amIRead = read($tableName, '', $checkCol, '', $bdd);

	# Return result
	if ($amIRead)
	{
		echo 'read';
	}
	elseif ($data['count'] < 20)
	{
		$data['count']++;
		header('Location: readStatus?count=' . $data['count'] . '&articleId=' . $data['articleId'] . '&type=' . $data['type']);
	}
}
?>
