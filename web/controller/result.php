<?php
# User need to be connected to access to this page
if (!isset($_SESSION['pseudo']))
{
	$_SESSION['redirectFrom'] = 'result';
	header('Location: connection');
}

//include_once('model/get_text.php');

$result  = 'active';
$wiki    = '';
$setting = '';
$colOrder['read'] = 'Read';
$colOrder['send'] = 'Send';
$colOrder['DESC'] = '';
$recordLink = '';
$search = '';
$ORDERBYorSEARCH = '';

include_once('model/delResult.php');

if (isset($_POST['deleteLink']))
{
	foreach($_POST as $key => $val)
	{
		$key = htmlspecialchars($key);
		$val = htmlspecialchars($val);
		if (preg_match("/^delete[0-9]+$/", $key))
		{
				$resultId = preg_replace("/^delete/", "", $key);
				deleteLink($bdd, $resultId);
		}
	}
}

include_once('model/readUserSettings.php');
$recordRead = $userSettings['record_read'];

if ($recordRead == 0)
{
	$pass   = $userSettings['password'];
	$id     = $userSettings['id'];
	$pseudo = $_SESSION['pseudo'];
	$salt   = 'blackSalt';
	$hash   =  hash('sha256', $salt . ':' . $pass . $pseudo . $id);
	$recordLink = 'redirect?id=' . $id . '&hash=' . $hash . '&link=';
}

include_once('model/readOwnerKeyword.php');

include_once('model/readResultKeywordName.php');

$resultBase  = 0;
$resultLimit = 15;

# Page number
if (isset($_GET['page']) AND preg_match("/^[0-9]+$/", htmlspecialchars($_GET['page'])))
{
	$page       = htmlspecialchars($_GET['page']);
	$page       = $page - 1;
	$resultBase = 15 * $page;
}

# Order results
if (isset($_GET['orderBy']))
{
	$ORDERBYorSEARCH = htmlspecialchars($_GET['orderBy']);
	if ($ORDERBYorSEARCH == 'title')
	{
		$colOrder['title'] = '▾';
		$colOrder['DESC'] = 'DESC';

		# WARNING sensitive variable [SQLI]
		$ORDERBYorSEARCH = 'ORDER BY title';
	}
	elseif ($ORDERBYorSEARCH == 'titleDESC')
	{
		$colOrder['title'] = '▴';
		$colOrder['DESC'] = '';

		# WARNING sensitive variable [SQLI]
		$ORDERBYorSEARCH = 'ORDER BY title DESC';
	}
	elseif ($ORDERBYorSEARCH == 'source')
	{
		$colOrder['source'] = '▾';
		$colOrder['DESC'] = 'DESC';

		# WARNING sensitive variable [SQLI]
		$ORDERBYorSEARCH = 'ORDER BY id_source';
	}
	elseif ($ORDERBYorSEARCH == 'sourceDESC')
	{
		$colOrder['source'] = '▴';
		$colOrder['DESC'] = '';

		# WARNING sensitive variable [SQLI]
		$ORDERBYorSEARCH = 'ORDER BY id_source DESC';
	}
	elseif ($ORDERBYorSEARCH == 'date')
	{
		$colOrder['date'] = '▾';
		$colOrder['DESC'] = 'DESC';

		# WARNING sensitive variable [SQLI]
		$ORDERBYorSEARCH = 'ORDER BY date';
	}
	elseif ($ORDERBYorSEARCH == 'dateDESC')
	{
		$colOrder['date'] = '▴';
		$colOrder['DESC'] = '';

		# WARNING sensitive variable [SQLI]
		$ORDERBYorSEARCH = 'ORDER BY date DESC';
	}
	elseif ($ORDERBYorSEARCH == 'read')
	{
		$colOrder['read'] = 'Read';
		$colOrder['DESC'] = 'DESC';

		# WARNING sensitive variable [SQLI]
		$ORDERBYorSEARCH = 'AND read_status LIKE \'%' . $_SESSION['id'] .'%\' ORDER BY date DESC';
	}
	elseif ($ORDERBYorSEARCH == 'readDESC')
	{
		$colOrder['read'] = 'Unread';
		$colOrder['DESC'] = '';

		# WARNING sensitive variable [SQLI]
		$ORDERBYorSEARCH = 'AND read_status NOT LIKE \'%' . $_SESSION['id'] . '%\' ORDER BY date DESC';
	}
	elseif ($ORDERBYorSEARCH == 'send')
	{
		$colOrder['send'] = 'Send';
		$colOrder['DESC'] = 'DESC';

		# WARNING sensitive variable [SQLI]
		$ORDERBYorSEARCH = 'AND send_status LIKE \'%' . $_SESSION['id'] .'%\' ORDER BY date DESC';
	}
	elseif ($ORDERBYorSEARCH == 'sendDESC')
	{
		$colOrder['send'] = 'Not send';
		$colOrder['DESC'] = '';

		# WARNING sensitive variable [SQLI]
		$ORDERBYorSEARCH = 'AND send_status NOT LIKE \'%' . $_SESSION['id'] .'%\' ORDER BY date DESC';
	}
	else
	{
		$colOrder['date'] = '▴';
		$colOrder['DESC'] = '';

		# WARNING sensitive variable [SQLI]
		$ORDERBYorSEARCH = 'ORDER BY date DESC';
	}
}
else
{
	$colOrder['date'] = '▴';
	$colOrder['DESC'] = '';

	# WARNING sensitive variable [SQLI]
	$ORDERBYorSEARCH = 'ORDER BY date DESC';
}

# Search in result
if (!empty($_GET['search']))
{
	$search = htmlspecialchars($_GET['search']);

	# WARNING sensitive variable [SQLI]
	$ORDERBYorSEARCH = 'AND (MATCH(title, link) AGAINST (:search)) ' . $ORDERBYorSEARCH;
}
else
{
	$ORDERBYorSEARCH = 'AND title NOT LIKE :search ' . $ORDERBYorSEARCH;
}

include_once('model/readOwnerResult.php');

include_once('view/nav/nav.php');

include_once('view/body/result.php');

include_once('view/footer/footer.php');

?>
