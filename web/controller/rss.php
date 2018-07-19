<?php
include('model/read.php');


# Data processing
$unsafeData = array();
$unsafeData = array_merge($unsafeData, array(array('token', 'token', 'GET', 'str')));

include('controller/dataProcessing.php');


$data['token'] = preg_replace("/\.php/", '', $data['token']);
preg_match("/[A-Fa-f0]{8}/", $data['token'], $token);

if (!empty($token[0]))
{
	$checkCol = array(array('token', '=', $token[0], 'AND'),
	array('premium_expiration_date', '>', $_SERVER['REQUEST_TIME'], ''));
	$user     = read('users_table_serge', 'id, users', $checkCol, '', $bdd);
	$user     = $user[0] ?? '';
}

preg_match("/[sp]$/", $data['token'], $resultType);

if (!empty($user))
{
	if ($resultType[0] === 's')
	{
		// read available results
		$checkCol = array(array('owners', 'l', ',' . $user['id'] . ',', ''));
		$results = read('results_science_serge', 'title, link, date', $checkCol, 'ORDER BY date DESC LIMIT 30', $bdd);
		$description = 'Scientifique publication';
	}
	elseif ($resultType[0] === 'p')
	{
		// read available results
		$checkCol = array(array('owners', 'l', ',' . $user['id'] . ',', ''));
		$results = read('results_patents_serge', 'title, link, date', $checkCol, 'ORDER BY date DESC LIMIT 30', $bdd);
		$description = 'Patents';
	}
	elseif (empty($results))
	{
		// read available results
		$checkCol = array(array('owners', 'l', ',' . $user['id'] . ',', ''));
		$results = read('results_news_serge', 'title, link, date', $checkCol, 'ORDER BY date DESC LIMIT 30', $bdd);
		$description = 'General news';
	}

	if (!empty($recordLink))
	{
		$result['link'] = urlencode($result['link']);
	}

	echo
	'<?xml version="1.0" encoding="UTF-8" ?>
	<rss version="2.0">
	<channel>
	<title>Serge : RSS ' . $user['users'] . '</title>
	<link>http://serge.eu/</link>
	<description>' . $description . '</description>';

	foreach ($results as $result)
	{
		echo '
		<item>
			<title>' . htmlspecialchars($result['title']) . '</title>
			<link>' . htmlspecialchars($result['link']) . '</link>
			<description></description>
			<pubDate>' . date("D, d M Y H:i:s O", $result['date']) . '</pubDate>
			<guid>' . htmlspecialchars($result['link']) . '</guid>
		</item>';
	}

	echo '
	</channel>
	</rss>';
}
else
{
	echo 'You need to be a premium user to access to this functionality';
}
?>
