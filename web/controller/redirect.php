<?php
include_once('model/read.php');
include_once('model/update.php');

if (isset($_GET['link']) AND isset($_GET['id']) AND isset($_GET['hash']))
{
	$link = urldecode($_GET['link']);
	$id   = htmlspecialchars($_GET['id']);
	$type = htmlspecialchars($_GET['type']);
	$hash = htmlspecialchars($_GET['hash']);

	# Read hash password and pseudo for user with this id
	$checkCol = array(array("id", "=", $id, ""));
	$result = read('users_table_serge', 'users, password', $checkCol, '', $bdd);
	$passwordPseudoWithId = $result[0];

	if (!empty($passwordPseudoWithId))
	{
		$pass   = $passwordPseudoWithId['password'];
		$pseudo = $passwordPseudoWithId['users'];
		$salt   = 'blackSalt';

		$checkHash = hash('sha256', $salt . ':' . $pass . $pseudo . $id);

		if ($hash === $checkHash)
		{
			$userId = ',' . $id . ',';
			if ($type == 'news')
			{
				$tableName = 'result_news_serge';
			}
			elseif ($type == 'sciences')
			{
				$tableName = 'result_science_serge';
			}
			elseif ($type == 'patents')
			{
				$tableName = 'result_patents_serge';
			}
			$updateCol = array(array("read_status", $userId));
			$checkCol  = array(array("link", "=", $link, ""));
			$execution = update($tableName, $updateCol, $checkCol, '', $bdd);
		}

		header("Location: $link");
	}
	else
	{
		header("Location: error404");
	}
}
else
{
	header("Location: error404");
}


?>
