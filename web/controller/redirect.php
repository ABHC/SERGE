<?php
include('model/read.php');
include('model/update.php');

# Data processing
$unsafeData = array();
$unsafeData = array_merge($unsafeData, array(array('linkId', 'id', 'GET', '09')));
$unsafeData = array_merge($unsafeData, array(array('token', 'token', 'GET', 'str')));
$unsafeData = array_merge($unsafeData, array(array('type', 'type', 'GET', 'Az')));

include('controller/dataProcessing.php');

$redirect = 'error404';

if (!empty($data['linkId']) && !empty($data['token']) && !empty($data['type']))
{
	# Read id bound to the token
	$checkCol = array(array('token', '=', $data['token'], ''));
	$result   = read('users_table_serge', 'id', $checkCol, '', $bdd);
	$userId   = $result[0]['id'];

	if (!empty($userId))
	{
		if ($data['type'] === 'news')
		{
			$tableName = 'result_news_serge';
		}
		elseif ($data['type'] === 'sciences')
		{
			$tableName = 'result_science_serge';
		}
		elseif ($data['type'] === 'patents')
		{
			$tableName = 'result_patents_serge';
		}
		$checkCol = array(array('id', '=', $data['linkId'], 'AND'),
		array('owners', 'l', '%,' . $userId . ',%', ''));
		$result = read($tableName, 'link, read_status', $checkCol, '', $bdd);
		$link = $result[0]['link'];
		$readStatus = $result[0]['read_status'];

		if (!empty($link))
		{
			$arrayReadStatus = explode(',', $readStatus);
			if (!in_array($userId, $arrayReadStatus))
			{
				$updateCol = array(array('read_status', $readStatus . $userId . ','));
				$checkCol  = array(array('id', '=', $data['linkId'], ''));
				$execution = update($tableName, $updateCol, $checkCol, '', $bdd);
			}
			$redirect = $link;
		}
	}
}
header("Location: $redirect");
die();
?>
