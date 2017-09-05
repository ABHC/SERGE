<?php
include_once('model/read.php');

preg_match("/[A-Fa-f0]{8}/", $_GET['token'], $token);

$checkCol = array(array("token", "=", $token[0], "AND"),
array("premium", "=" , 1, ""));
$user = read('users_table_serge', 'id, users', $checkCol, '', $bdd);
$user = $user[0];

if (!empty($user))
{
	// read available results
	$checkCol = array(array("owners", "l", ',' . $user['id'] . ',', ""));
	$results = read("result_news_serge", 'title, link, date', $checkCol, 'ORDER BY date DESC LIMIT 30',$bdd);

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
	<description></description>';

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
	echo "You need to be a premium user to access to this functionality";
}
?>
