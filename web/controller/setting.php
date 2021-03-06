<?php

include('controller/accessLimitedToSignInPeople.php');
include('model/get_text.php');
include('model/get_text_var.php');
include('model/read.php');
include('model/update.php');
include('model/insert.php');
include('controller/generateNonce.php');

// Define variables
$actualLetter           = '';
$style                  = '';
$orderByKeyword         = '';
$orderBySource          = '';
$orderByType            = '';
$delEditingScienceQuery = '';
$delEditingPatentQuery  = '';
$userId                 = $_SESSION['id'];
$classNoPremium         = '';
$formPostSubmit         = FALSE;

# Data processing
$unsafeData = array();
$unsafeData = array_merge($unsafeData, array(array('scrollPos', 'scrollPos', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('editQueryScience', 'editQueryScience', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('editQueryPatent', 'editQueryPatent', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('sourceType', 'sourceType', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('settings', 'settings', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('email', 'email', 'POST', 'email')));
$unsafeData = array_merge($unsafeData, array(array('backgroundResult', 'backgroundResult', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('resultByEmail', 'resultByEmail', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('cond', 'cond', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('numberLinks', 'numberLinks', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('freq', 'freq', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('days', 'days', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('hours', 'hours', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('secondDay', 'secondDay', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('orderBy', 'orderBy', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('recordRead', 'recordRead', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('historyLifetime', 'historyLifetime', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('buttonDeleteHistory', 'buttonDeleteHistory', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('deleteHistoryValue', 'deleteHistoryValue', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('deleteHistoryUnit', 'deleteHistoryUnit', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('newSource', 'newSource', 'POST', 'url')));
$unsafeData = array_merge($unsafeData, array(array('sourceKeyword', 'sourceKeyword', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('newKeyword', 'newKeyword', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('delKeyword', 'delKeyword', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('disableKeyword', 'disableKeyword', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('activateKeyword', 'activateKeyword', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('delSource', 'delSource', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('disableSource', 'disableSource', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('activateSource', 'activateSource', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('delEditingScienceQuery', 'delEditingScienceQuery', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('extendScience', 'extendScience', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('scienceQuerySubmit', 'scienceQuerySubmit', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('delQueryScience', 'delQueryScience', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('disableQueryScience', 'disableQueryScience', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('activateQueryScience', 'activateQueryScience', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('delEditingPatentQuery', 'delEditingPatentQuery', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('extendPatent', 'extendPatent', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('patentQuerySubmit', 'patentQuerySubmit', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('delQueryPatent', 'delQueryPatent', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('disableQueryPatent', 'disableQueryPatent', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('activateQueryPatent', 'activateQueryPatent', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('removePack', 'removePack', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('resultByEmail', 'resultByEmail', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('tel', 'tel', 'POST', '09')));
$unsafeData = array_merge($unsafeData, array(array('resultBySMS', 'resultBySMS', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('selectLanguage', 'selectLanguage', 'POST', 'Az')));
$unsafeData = array_merge($unsafeData, array(array('radio-optionMail', 'radio-optionMail', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('radio-optionSMS', 'radio-optionSMS', 'POST', 'str')));
$unsafeData = array_merge($unsafeData, array(array('radio-optionPrivacy', 'radio-optionPrivacy', 'POST', 'str')));

foreach($_POST as $key => $val)
{
		$key = htmlspecialchars($key);
		if (preg_match("/radio-s./", $key, $name) ||
		preg_match("/radio-ks[0-9]+/", $key, $name) ||
		preg_match("/andOrNot[0-9]+/", $key, $name) ||
		preg_match("/openParenthesis[0-9]+/", $key, $name) ||
		preg_match("/closeParenthesis[0-9]+/", $key, $name) ||
		preg_match("/scienceType[0-9]+/", $key, $name) ||
		preg_match("/scienceQuery[0-9]+/", $key, $name) ||
		preg_match("/andOrPatent[0-9]+/", $key, $name) ||
		preg_match("/patentType[0-9]+/", $key, $name) ||
		preg_match("/patentQuery[0-9]+/", $key, $name))
		{
			$unsafeData = array_merge($unsafeData, array(array($name[0], $name[0], 'POST', 'str')));
		}
}

include('controller/dataProcessing.php');

# Nonce
$nonceTime = $_SERVER['REQUEST_TIME'];
$nonce     = getNonce($nonceTime);

if (empty($_SESSION['cptScienceQuery']))
{
	$_SESSION['cptScienceQuery'] = 3;
}

if (empty($_SESSION['cptPatentQuery']))
{
	$_SESSION['cptPatentQuery'] = 3;
}

if (isset($_SESSION['ERROR_MESSAGE']))
{
	$ERROR_MESSAGE = $_SESSION['ERROR_MESSAGE'];
	$_SESSION['ERROR_MESSAGE'] = '';
}
else
{
	$ERROR_MESSAGE = '';
}


# Nav activation for this page
$resultTab  = '';
$wikiTab    = '';
$settingTab = 'active';

# Save folding state
if (!empty($data['sourceType']))
{
	foreach($_SESSION as $key => $val)
	{
		if (preg_match("/radio-s./", $key) || preg_match("/radio-ks[0-9]+/", $key))
		{
			$_SESSION[$key] = '';
		}
	}
	foreach($data as $key => $val)
	{
		$key = htmlspecialchars($key);
		$val = htmlspecialchars($val);
		if (preg_match("/radio-s./", $key) || preg_match("/radio-ks[0-9]+/", $key))
		{
			$_SESSION[$key] = $val;
		}
	}
}

if (!empty($data['radio-optionMail']))
{
	$foldingStateMail = 'checked';
}

if (!empty($data['radio-optionSMS']))
{
	$foldingStateSMS = 'checked';
}

if (!empty($data['radio-optionPrivacy']))
{
	$foldingStatePrivacy = 'checked';
}

# Read science search fields
include_once('model/readColumns.php');

$nextColumnName = FALSE;
foreach ($columnsNames as $columnsName)
{
	if ($nextColumnName && $columnsName['Field'] != 'active')
	{
		$selected[$columnsName['Field']] = '';
	}

	if ($columnsName['Field'] === 'quote')
	{
		$nextColumnName = TRUE;
	}
}

# Read watchPack used
$checkCol          = array(array('users', 'l', '%,' . $_SESSION['id'] . ',%', ''));
$watchPackUsedList = read('watch_pack_serge', 'id, name, description, users', $checkCol, 'ORDER BY name', $bdd);

# Read background list
$type           = 'result';
$checkCol       = array(array('type', '=', $type, ''));
$backgroundList = read('background_serge', 'id, name, filename', $checkCol, 'ORDER BY name', $bdd);

# Read token
$checkCol = array(array('id', '=', $_SESSION['id'], ''));
$token    = read('users_table_serge', 'token', $checkCol, '', $bdd);

# Read if user is premium
$checkCol      = array(array('id', '=', $_SESSION['id'], 'AND'),
											array('premium_expiration_date', '>', $_SERVER['REQUEST_TIME'], ''));
$userIsPremium = read('users_table_serge', '', $checkCol, '', $bdd);

if (!$userIsPremium)
{
	$classNoPremium = 'class="noPremium"';
}

# Read user payments history
$checkCol    = array(array('user_id', '=', $_SESSION['id'], ''));
$paymentList = read('purchase_table_serge', 'purchase_date, duration_premium, price', $checkCol, '', $bdd);

# Read if user mail is check
$checkCol     = array(array('email_validation', '=', 1, 'AND'),
											array('id', '=', $_SESSION['id'], ''));
$emailIsCheck = read('users_table_serge', '', $checkCol, '', $bdd);

if (!$emailIsCheck)
{
	# Javascript message if the user has not checked his email address
	echo '<script>alert("Your email is not verified, you will not be able to use Serge")</script>';
}

# Read owner sources
$checkCol  = array(array('owners', 'l', '%,' . $_SESSION['id'] . ',%', 'OR'),
									array('owners', 'l', '%,!' . $_SESSION['id'] . ',%', ''));
$reqReadOwnerSourcestmp = read('rss_serge', 'id, link, name, owners, active', $checkCol, 'ORDER BY name', $bdd);

$checkCol                      = array(array('applicable_owners_sources', 'l', '%|' . $_SESSION['id'] . ':%', ''));
$reqReadOwnerSourcesKeywordtmp = read('keyword_news_serge', 'id, keyword, applicable_owners_sources, active', $checkCol, 'ORDER BY keyword', $bdd);

# Read user settings
$checkCol     = array(array('users', '=', $_SESSION['pseudo'], ''));
$userSettings = read('users_table_serge', 'id, email, phone_number, password, result_by_email, send_condition, frequency, link_limit, selected_days, selected_hour, mail_design, language, record_read, history_lifetime, background_result, record_read, premium_expiration_date, alert_by_sms, sms_credits', $checkCol, '', $bdd);
$userSettings = $userSettings[0];

# Remove watchPack
if ($emailIsCheck && !empty($data['removePack']))
{
	include('model/removeWatchPackForAnUser.php');
}

if ($emailIsCheck && $userIsPremium && !empty($data['resultByEmail']))
{
	$updateCol = array(array('result_by_email', 1));
	$checkCol  = array(array('id', '=', $_SESSION['id'], ''));
	$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);
}

if ($emailIsCheck)
{
	# Change language
	if (!empty($data['selectLanguage']) && ($data['selectLanguage'] == 'EN' || $data['selectLanguage'] == 'FR'))
	{
		$updateCol = array(array('language', $data['selectLanguage']));
		$checkCol  = array(array('id', '=', $_SESSION['id'], ''));
		$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);

		$_SESSION['lang'] = $data['selectLanguage'];
	}

	# Change recieve alert by SMS
	if (!empty($data['resultBySMS']))
	{
		$updateCol = array(array('alert_by_sms', TRUE));
		$checkCol  = array(array('id', '=', $_SESSION['id'], ''));
		$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);
	}
	elseif(!empty($data['email']))
	{
		$updateCol = array(array('alert_by_sms', 0));
		$checkCol  = array(array('id', '=', $_SESSION['id'], ''));
		$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);
	}

	# Change recieve result by mail
	if (!empty($data['resultByEmail']))
	{
		$updateCol = array(array('result_by_email', TRUE));
		$checkCol  = array(array('id', '=', $_SESSION['id'], ''));
		$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);
	}
	elseif(!empty($data['email']))
	{
		$updateCol = array(array('result_by_email', 0));
		$checkCol  = array(array('id', '=', $_SESSION['id'], ''));
		$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);
	}

	# Change phone number
	if (!empty($data['tel']))
	{
		$checkCol   = array(array('phone_number', '=', $data['tel'], ''));
		$phoneExist = read('users_table_serge', '', $checkCol, '', $bdd);

		if (!$phoneExist)
		{
			$updateCol = array(array('phone_number', $data['tel']));
			$checkCol  = array(array('id', '=', $_SESSION['id'], ''));
			$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);
		}
	}

	# Change email
	if (!empty($data['email']))
	{
		$checkCol   = array(array('email', '=', $data['email'], ''));
		$emailExist = read('users_table_serge', '', $checkCol, '', $bdd);

		if (!$emailExist)
		{
			$newEmail  = $data['email'];
			$updateCol = array(array('email', $newEmail));
			$checkCol  = array(array('id', '=', $_SESSION['id'], ''));
			$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);
		}
	}

	# Change result backgroundList
	if (!empty($data['backgroundResult']))
	{
		$backgroundResult = $data['backgroundResult'];
		// Update background result
		$updateCol = array(array('background_result', $backgroundResult));
		$checkCol  = array(array('id', '=', $_SESSION['id'], ''));
		$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);
	}

	# Change sending condition
	if (!empty($data['cond']) && ($data['cond'] === 'link_limit' || $data['cond'] === 'freq' || $data['cond'] === 'deadline'))
	{
		$secondDay = $data['secondDay'];
		if (!preg_match("/$secondDay/", $data['days']))
		{
			$data['days'] = $data['days'] . $data['secondDay'];
		}

		$updateCol = array(array('send_condition', $data['cond']),
											array('link_limit', $data['numberLinks']),
											array('frequency', $data['freq']),
											array('selected_days', $data['days']),
											array('selected_hour', $data['hours']));
		$checkCol  = array(array('id', '=', $_SESSION['id'], ''));
		$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);
	}

	# Change sorting for link in email
	if (!empty($data['orderBy']) && ($data['orderBy'] === 'origin' || $data['orderBy'] === 'type' || $data['orderBy'] === 'masterword'))
	{
		// Update background result
		$updateCol = array(array('mail_design', $data['orderBy']));
		$checkCol  = array(array('id', '=', $_SESSION['id'], ''));
		$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);
	}

	# Change privacy settings
	$recordRead = 0;
	if (!empty($data['recordRead']) && $data['recordRead'] === 'active')
	{
		$recordRead = 1;
	}

	if ($formPostSubmit)
	{
		// Change record read
		$updateCol = array(array('record_read', $recordRead));
		$checkCol  = array(array('id', '=', $_SESSION['id'], ''));
		$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);
	}

	// TODO implement in serge AND in the UI
	if (!empty($data['historyLifetime']))
	{
		$updateCol = array(array('history_lifetime', $data['historyLifetime']));
		$checkCol  = array(array('id', '=', $_SESSION['id'], ''));
		$execution = update('users_table_serge', $updateCol, $checkCol, '', $bdd);
	}

}

# Delete history button
if ($emailIsCheck && !empty($data['buttonDeleteHistory']) && !empty($data['deleteHistoryValue']) && !empty($data['deleteHistoryUnit']))
{
	$deleteHistoryValue = $data['deleteHistoryValue'];
	$deleteHistoryUnit  = $data['deleteHistoryUnit'];

	if ($deleteHistoryUnit === 'hour')
	{
		$deleteTimeIntervale = $deleteHistoryValue * 3600;
	}
	elseif ($deleteHistoryUnit === 'day')
	{
		$deleteTimeIntervale = $deleteHistoryValue * 3600 * 24;
	}
	elseif ($deleteHistoryUnit === 'week')
	{
		$deleteTimeIntervale = $deleteHistoryValue * 3600 * 24 * 7;
	}
	elseif ($deleteHistoryUnit === 'month')
	{
		$deleteTimeIntervale = $deleteHistoryValue * 3600 * 24 * 7 * 30;
	}
	elseif ($deleteHistoryUnit === 'year')
	{
		$deleteTimeIntervale = $deleteHistoryValue * 3600 * 24 * 7 * 30 * 12;
	}

	$now        = $_SERVER['REQUEST_TIME'];
	$deleteTime = $now - $deleteTimeIntervale;
	$owner      = '%,' . $_SESSION['id'] . ',%';

	# News results
	$checkCol          = array(array('owners', 'l', $owner, 'AND'),
														array('date', '>=', $deleteTime, ''));
	$readResutlToDel = read('result_news_serge', 'id, owners, send_status, read_status', $checkCol, '', $bdd);

	foreach ($readResutlToDel as $resultToDel)
	{
		$updateCol = array(array('owners', preg_replace("/,$userId,/", ',', $resultToDel['owners'])),
											array('send_status', preg_replace("/,$userId,/", ',', $resultToDel['send_status'])),
											array('read_status', preg_replace("/,$userId,/", ',', $resultToDel['read_status'])));
		$checkCol  = array(array('id', '=', $resultToDel['id'], ''));
		$execution = update('result_news_serge', $updateCol, $checkCol, '', $bdd);
	}

	# Sciences results
	$checkCol          = array(array('owners', 'l', $owner, 'AND'),
														array('date', '>=', $deleteTime, ''));
	$readResutlToDel = read('result_science_serge', 'id, owners', $checkCol, '', $bdd);

	foreach ($readResutlToDel as $resultToDel)
	{
		$updateCol = array(array('owners', preg_replace("/,$userId,/", ',', $resultToDel['owners'])),
											array('send_status', preg_replace("/,$userId,/", ',', $resultToDel['send_status'])),
											array('read_status', preg_replace("/,$userId,/", ',', $resultToDel['read_status'])));
		$checkCol  = array(array('id', '=', $resultToDel['id'], ''));
		$execution = update('result_science_serge', $updateCol, $checkCol, '', $bdd);
	}

	# Patents results
	$checkCol          = array(array('owners', 'l', $owner, 'AND'),
														array('date', '>=', $deleteTime, ''));
	$readResutlToDel = read('result_patents_serge', 'id, owners', $checkCol, '', $bdd);

	foreach ($readResutlToDel as $resultToDel)
	{
		$updateCol = array(array('owners', preg_replace("/,$userId,/", ',', $resultToDel['owners'])),
											array('send_status', preg_replace("/,$userId,/", ',', $resultToDel['send_status'])),
											array('read_status', preg_replace("/,$userId,/", ',', $resultToDel['read_status'])));
		$checkCol  = array(array('id', '=', $resultToDel['id'], ''));
		$execution = update('result_patents_serge', $updateCol, $checkCol, '', $bdd);
	}
}

# Adding new source
if ($emailIsCheck && !empty($data['sourceType']) && !empty($data['newSource']) && $data['sourceType'] === 'inputSource')
{
	$sourceToTest = escapeshellarg($data['newSource']);
	$cmd          = 'timeout 150  /usr/bin/python /var/www/Serge/checkfeed.py ' . $sourceToTest . ' ' . $userId . ' setting >> /var/www/Serge/web/logs/error.log 2>&1 &';

	# Check if the link is valid
	exec($cmd);
}

# Adding new keyword
if ($emailIsCheck && isset($data['sourceKeyword']) && !empty($data['newKeyword']))
{
	include('model/addNewKeyword.php');
	$sourceId = $data['sourceKeyword'];

	$_SESSION['lastSourceUse'] = $sourceId;

	$data['newKeyword'] = preg_replace("/,\s+/", ",", $data['newKeyword']);
	$newKeyword_array   = preg_split('/,/', $data['newKeyword'], -1, PREG_SPLIT_NO_EMPTY);

	foreach ($newKeyword_array as $newKeyword)
	{
		$newKeyword = mb_strtolower($newKeyword);
		# Special keyword :all
		if (preg_match("/^:all$/i", $newKeyword) && $sourceId != '0')
		{
			$newKeyword    = ':all@' . $sourceId;
			$ERROR_MESSAGE = addNewKeyword($sourceId, $newKeyword, $ERROR_MESSAGE, $reqReadOwnerSourcestmp, $bdd);
		}
		elseif (preg_match("/^:all$/i", $newKeyword) && $sourceId == '0')
		{
			$updateBDD = FALSE;
			foreach ($reqReadOwnerSourcestmp as $ownerSourcesList)
			{
				$newKeyword    = ':all@' . $ownerSourcesList['id'];
				$sourceId      = $ownerSourcesList['id'];
				$ERROR_MESSAGE = addNewKeyword($sourceId, $newKeyword, $ERROR_MESSAGE, $reqReadOwnerSourcestmp, $bdd);
			}
		}
		elseif (preg_match("/^:alert.+/i", $newKeyword) && $sourceId != '0')
		{
			$newKeyword    = preg_replace("/:alert/i", '', $newKeyword);
			$newKeyword    = '[!alert!]' . $newKeyword;
			$ERROR_MESSAGE = addNewKeyword($sourceId, $newKeyword, $ERROR_MESSAGE, $reqReadOwnerSourcestmp, $bdd);
		}
		elseif (preg_match("/^:alert.+/i", $newKeyword) && $sourceId === '0')
		{
			$updateBDD  = FALSE;
			$newKeyword = preg_replace("/:alert/i", '', $newKeyword);
			$newKeyword = '[!alert!]' . $newKeyword;
			foreach ($reqReadOwnerSourcestmp as $ownerSourcesList)
			{
				$sourceId      = $ownerSourcesList['id'];
				$ERROR_MESSAGE = addNewKeyword($sourceId, $newKeyword, $ERROR_MESSAGE, $reqReadOwnerSourcestmp, $bdd);
			}
		}
		elseif ($newKeyword != '')
		{
			$ERROR_MESSAGE = addNewKeyword($sourceId, $newKeyword, $ERROR_MESSAGE, $reqReadOwnerSourcestmp, $bdd);
		}
	}
	$_SESSION['ERROR_MESSAGE'] = $ERROR_MESSAGE;
}

# Delete, disable, active keyword
if ($emailIsCheck && !empty($data['delKeyword']))
{
	preg_match_all("/[0-9]*&/", $data['delKeyword'], $matchKeywordAndSource);
	$sourceIdAction  = preg_replace("/[^0-9]/", '', $matchKeywordAndSource[0][0]);
	$keywordIdAction = preg_replace("/[^0-9]/", '', $matchKeywordAndSource[0][1]);
	$action          = 'delKeyword';
}
elseif ($emailIsCheck && !empty($data['disableKeyword']))
{
	preg_match_all("/[0-9]*&/", $data['disableKeyword'], $matchKeywordAndSource);
	$sourceIdAction  = preg_replace("/[^0-9]/", '', $matchKeywordAndSource[0][0]);
	$keywordIdAction = preg_replace("/[^0-9]/", '', $matchKeywordAndSource[0][1]);
	$action          = 'disableKeyword';
}
elseif ($emailIsCheck && !empty($data['activateKeyword']))
{
	preg_match_all("/[0-9]*&/", $data['activateKeyword'], $matchKeywordAndSource);
	$sourceIdAction  = preg_replace("/[^0-9]/", '', $matchKeywordAndSource[0][0]);
	$keywordIdAction = preg_replace("/[^0-9]/", '', $matchKeywordAndSource[0][1]);
	$action          = 'activateKeyword';
}

if ($emailIsCheck && !empty($sourceIdAction) && !empty($keywordIdAction) && !empty($action))
{
	# Check if keyword exist for this ownerSourcesList
	$keywordExist = FALSE;
	foreach ($reqReadOwnerSourcesKeywordtmp as $ownerKeywordList)
	{
		# Source of current keyword for current user
		$applicableOwnerstmp = $ownerKeywordList['applicable_owners_sources'];

		# Search for source in applicable_owners_sources
		$sourceInKeyword = preg_match("/\|" . $_SESSION['id'] . ':[,!0-9,]*,!*' . $sourceIdAction . ",[,!0-9,]*\|/", $applicableOwnerstmp, $applicable_owners_sourceForCurrentUser);

		if ($ownerKeywordList['id'] === $keywordIdAction && $sourceInKeyword)
		{
			$actualOwners            = $applicable_owners_sourceForCurrentUser[0];
			$applicableOwners        = $ownerKeywordList['applicable_owners_sources'];
			$activeForCurrentKeyword = $ownerKeywordList['active'];
			$keywordExist            = TRUE;
		}
	}

	# Delete an existing keyword
	if ($keywordExist && $action === 'delKeyword')
	{
		$newOwners        = preg_replace("/,!*$sourceIdAction,/", ',', $actualOwners);
		$newOwners        = preg_replace("/\|/", '', $newOwners);
		$applicableOwners = preg_replace($actualOwners, $newOwners, $applicableOwners);

		$updateCol = array(array('applicable_owners_sources', $applicableOwners),
											array('active', $activeForCurrentKeyword - 1));
		$checkCol  = array(array('id', '=', $keywordIdAction, ''));
		$execution = update('keyword_news_serge', $updateCol, $checkCol, '', $bdd);
	}
	elseif ($keywordExist && $action === 'disableKeyword')
	{
		$newOwners        = preg_replace("/,$sourceIdAction,/", ",!$sourceIdAction,", $actualOwners);
		$newOwners        = preg_replace("/\|/", '', $newOwners);
		$applicableOwners = preg_replace($actualOwners, $newOwners, $applicableOwners);

		$updateCol = array(array('applicable_owners_sources', $applicableOwners),
											array('active', $activeForCurrentKeyword - 1));
		$checkCol  = array(array('id', '=', $keywordIdAction, ''));
		$execution = update('keyword_news_serge', $updateCol, $checkCol, '', $bdd);
	}
	elseif ($keywordExist && $action === 'activateKeyword')
	{
		$newOwners        = preg_replace("/,!$sourceIdAction,/", ",$sourceIdAction,", $actualOwners);
		$newOwners        = preg_replace("/\|/", '', $newOwners);
		$applicableOwners = preg_replace($actualOwners, $newOwners, $applicableOwners);

		$updateCol = array(array('applicable_owners_sources', $applicableOwners),
												array('active', $activeForCurrentKeyword + 1));
		$checkCol  = array(array('id', '=', $keywordIdAction, ''));
		$execution = update('keyword_news_serge', $updateCol, $checkCol, '', $bdd);
	}
	else
	{
		$ERROR_MESSAGE = 'Keyword doesn\'t exist or invalid action';
	}
}

# Delete, disable, active sources
if ($emailIsCheck && !empty($data['delSource']))
{
	preg_match("/[0-9]*&/", $data['delSource'], $matchSourceId);
	$sourceIdAction  = preg_replace("/[^0-9]/", '', $matchSourceId[0]);
	$action          = 'delSource';
}
elseif ($emailIsCheck && !empty($data['disableSource']))
{
	preg_match("/[0-9]*&/", $data['disableSource'], $matchSourceId);
	$sourceIdAction  = preg_replace("/[^0-9]/", '', $matchSourceId[0]);
	$action          = 'disableSource';
}
elseif ($emailIsCheck && !empty($data['activateSource']))
{
	preg_match("/[0-9]*&/", $data['activateSource'], $matchSourceId);
	$sourceIdAction  = preg_replace("/[^0-9]/", '', $matchSourceId[0]);
	$action          = 'activateSource';
}

if ($emailIsCheck && !empty($sourceIdAction) && !empty($action))
{
	# Check if source exist for this owner
	$sourceExist = FALSE;
	foreach ($reqReadOwnerSourcestmp as $sourceList)
	{
		if ($sourceList['id'] === $sourceIdAction)
		{
			$owners                 = $sourceList['owners'];
			$activeForCurrentSource = $sourceList['active'];
			$sourceExist            = TRUE;
		}
	}

	# Delete an existing sources
	if ($sourceExist && $action === 'delSource')
	{
		$userId    = $_SESSION['id'];
		$updateCol = array(array('owners', preg_replace("/,!*$userId,/", ',', $owners)),
											array('active', $activeForCurrentSource - 1));
		$checkCol  = array(array('id', '=', $sourceIdAction, ''));
		$execution = update('rss_serge', $updateCol, $checkCol, '', $bdd);
	}
	elseif ($sourceExist && $action === 'disableSource')
	{
		$userId    = $_SESSION['id'];
		$updateCol = array(array('owners', preg_replace("/,$userId,/", ",!$userId,", $owners)),
											array('active', $activeForCurrentSource - 1));
		$checkCol  = array(array('id', '=', $sourceIdAction, ''));
		$execution = update('rss_serge', $updateCol, $checkCol, '', $bdd);
	}
	elseif ($sourceExist && $action === 'activateSource')
	{
		$userId    = $_SESSION['id'];
		$updateCol = array(array('owners', preg_replace("/,!$userId,/", ",$userId,", $owners)),
											array('active', $activeForCurrentSource + 1));
		$checkCol  = array(array('id', '=', $sourceIdAction, ''));
		$execution = update('rss_serge', $updateCol, $checkCol, '', $bdd);
	}
	else
	{
		$ERROR_MESSAGE = 'Source doesn\'t exist or invalid action';
	}
}

# Edit science query
if ($emailIsCheck && !empty($data['editQueryScience']))
{
	$checkCol = array(array('id', '=', $data['editQueryScience'], 'AND'),
										array('owners', 'l', '%,' . $_SESSION['id'] . ',%', ''));
	$queryEdit = read('queries_science_serge', 'id, query_serge', $checkCol, '', $bdd);
	$queryEdit = $queryEdit[0] ?? '';

	if (!empty($queryEdit))
	{
		$delEditingScienceQuery = $queryEdit['id'];

		$query = urldecode($queryEdit['query_serge']);
		$queryArray = explode('|', $query);

		$cpt       = 1;
		$typeQuery = '';
		foreach ($queryArray as $queryPart)
		{
			$cptQuery = ceil($cpt/6) - 1;
			if (preg_match("/(^AND$|^NOT$|^OR$)/",$queryPart, $value))
			{
				if (($cpt / 6) != intval($cpt / 6))
				{
					$cpt      = (intval($cpt / 6) + 1) * 6;
					$cptQuery = ceil($cpt/6);
				}
				$value = preg_replace("/ /", '', $value[0]);
				$data['andOrNot' . $cptQuery] = $value;
			}
			elseif (preg_match("/^\($/",$queryPart))
			{
				$data['openParenthesis' . $cptQuery] = 'active';
			}
			elseif (preg_match("/^\)$/",$queryPart))
			{
				$data['closeParenthesis' . $cptQuery] = 'active';
			}
			elseif (!empty($queryPart) && $typeQuery != 'displayed')
			{
				$data['scienceType' . $cptQuery] = $queryPart;
				$typeQuery = 'displayed';
			}
			elseif (!empty($queryPart))
			{
				$data['scienceQuery' . $cptQuery] = preg_replace("/#/", "", $queryPart);
				$typeQuery = '';
			}
			$cpt++;
		}

			$_SESSION['cptScienceQuery'] = ceil(($cptQuery+1)/3) * 3;
	}
}


# Delete editing query
if ($emailIsCheck && !empty($data['delEditingScienceQuery']) && !empty($data['scienceQuerySubmit']))
{
	$checkCol = array(array('id', '=', $data['delEditingScienceQuery'], 'AND'),
										array('owners', 'l', '%,' . $_SESSION['id'] . ',%', ''));
	$queryEditToDel = read('queries_science_serge', 'id, owners, active', $checkCol, '', $bdd);
	$queryEditToDel = $queryEditToDel[0] ?? '';

	if (!empty($queryEditToDel))
	{
		$userId    = $_SESSION['id'];
		$updateCol = array(array('owners', preg_replace("/,!*$userId,/", ',', $queryEditToDel['owners'])),
											array('active', $queryEditToDel['active'] - 1));
		$checkCol  = array(array('id', '=', $queryEditToDel['id'], ''));
		$execution = update('queries_science_serge', $updateCol, $checkCol, '', $bdd);
	}
}

# Add new science query
include('model/addNewScienceQuery.php');
if ($emailIsCheck && !empty($data['scienceQuerySubmit']) && $data['scienceQuerySubmit'] === 'add')
{
	$cpt                         = 0;
	$open                        = 0;
	$close                       = 0;
	$nbscienceType               = 'scienceType0';
	$queryScience_Serge          = '';
	$separator                   = '';
	$_SESSION['cptScienceQuery'] = 3;

	while(!empty($data[$nbscienceType]) && !empty($data['scienceQuery' . $cpt]))
	{
		if (!empty($data['andOrNot' . $cpt])
				&& preg_match("/(^AND$|^OR$|^NOT$)/", $data['andOrNot' . $cpt]))
		{
			$queryScience_Serge = $queryScience_Serge . '|' . $data['andOrNot' . $cpt];
		}
		elseif (!empty($data['andOrNot' . $cpt])
						&& !preg_match("/(^AND$|^OR$|^NOT$)/", $data['andOrNot' . $cpt]))
		{
			$queryScience_Serge = $queryScience_Serge . '|OR';
		}

		if (isset($selected[$data['scienceType' . $cpt]]))
		{
			$openParenthesis  = '';
			$closeParenthesis = '';
			if (!empty($data['openParenthesis' . $cpt]) && $data['openParenthesis' . $cpt] === 'active')
			{
				$openParenthesis = $separator . '(';
				$separator = '|';
				$open ++;
			}

			if (!empty($data['closeParenthesis' . $cpt]) && $data['closeParenthesis' . $cpt] === 'active')
			{
				$closeParenthesis = '|)';
				$close ++;
			}

			$queryScience_Serge = $queryScience_Serge . $openParenthesis . $separator . $data['scienceType' . $cpt] . '|';

			$scienceQuery       = $data['scienceQuery' . $cpt];
			$scienceQuery       = urlencode($scienceQuery);
			$scienceQuery       = preg_replace("/( |:|`|%22|%28|%29)/", '+', $scienceQuery);
			$queryScience_Serge = $queryScience_Serge . '#' . $scienceQuery . $closeParenthesis;
		}

		# Cleaning
		$data['andOrNot' . $cpt]         = '';
		$data['openParenthesis' . $cpt]  = '';
		$data['scienceType' . $cpt]      = '';
		$data['scienceQuery' . $cpt]     = '';
		$data['closeParenthesis' . $cpt] = '';

		$cpt ++;
		$nbscienceType = 'scienceType' . $cpt;
		$separator = '|';
	}

	if ($open != $close)
	{
		$ERROR_SCIENCEQUERY = 'Invalid query : parenthesis does not match';
	}

	if (empty($ERROR_SCIENCEQUERY) && !empty($queryScience_Serge))
	{
		$queryScience_Serge = preg_replace("/\|$/", "", $queryScience_Serge);
		$ERROR_SCIENCEQUERY = addNewScienceQuery($queryScience_Serge, $bdd);
	}
}

#Delete science query
if ($emailIsCheck && !empty($data['delQueryScience']))
{
	// Read owner science query
	$checkCol = array(array('id', '=', $data['delQueryScience'], 'AND'),
										array('owners', 'l', '%,' . $_SESSION['id'] . ',%', 'OR'),
										array('id', '=', $data['delQueryScience'], 'AND'),
										array('owners', 'l', '%,!' . $_SESSION['id'] . ',%', ''));
	$result = read('queries_science_serge', 'owners, active', $checkCol, '', $bdd);
	$result = $result[0] ?? '';

	if (!empty($result))
	{
		$userId    = $_SESSION['id'];
		$updateCol = array(array('owners', preg_replace("/,!*$userId,/", ',', $result['owners'])),
											array('active', $result['active'] - 1));
		$checkCol  = array(array('id', '=', $data['delQueryScience'], ''));
		$execution = update('queries_science_serge', $updateCol, $checkCol, '', $bdd);
	}
}

#Disable science query
if ($emailIsCheck && !empty($data['disableQueryScience']))
{
	$checkCol = array(array('id', '=',$data['disableQueryScience'], 'AND'),
										array('owners', 'l', '%,' . $_SESSION['id'] . ',%', ''));
	$result   = read('queries_science_serge', 'owners, active', $checkCol, '', $bdd);
	$result   = $result[0] ?? '';


	if (!empty($result))
	{
		$queryOwnerNEW = preg_replace("/,$userId,/", ",!$userId,", $result['owners']);
		$userId        = $_SESSION['id'];
		$updateCol = array(array('owners', preg_replace("/,$userId,/", ",!$userId,", $result['owners'])),
											array('active', $result['active'] - 1));
		$checkCol  = array(array('id', '=', $data['disableQueryScience'], ''));
		$execution = update('queries_science_serge', $updateCol, $checkCol, '', $bdd);
	}
}

#Activate science query
if ($emailIsCheck && !empty($data['activateQueryScience']))
{
	$checkCol = array(array('id', '=', $data['activateQueryScience'], 'AND'),
										array('owners', 'l', '%,!' . $_SESSION['id'] . ',%', ''));
	$result = read('queries_science_serge', 'owners, active', $checkCol, '', $bdd);
	$result = $result[0] ?? '';


	if (!empty($result))
	{
		$userId    = $_SESSION['id'];
		$updateCol = array(array('owners', preg_replace("/,!$userId,/", ",$userId,", $result['owners'])),
											array('active', $result['active'] + 1));
		$checkCol  = array(array('id', '=', $data['activateQueryScience'], ''));
		$execution = update('queries_science_serge', $updateCol, $checkCol, '', $bdd);
	}
}

# Edit patent query
if ($emailIsCheck && !empty($data['editQueryPatent']))
{
	$checkCol    = array(array('id', '=', $data['editQueryPatent'], 'AND'),
											array('owners', 'l', '%,' . $_SESSION['id'] . ',%', ''));
	$queryEdit = read('queries_wipo_serge', 'id, query', $checkCol, '', $bdd);
	$queryEdit = $queryEdit[0] ?? '';

	if (!empty($queryEdit))
	{
		$delEditingPatentQuery = $queryEdit['id'];

		$query      = urldecode($queryEdit['query']);
		$query      = preg_replace("/\"/", '', $query);
		$query      = preg_replace("/(\(|\)|[^: ]+:| AND | OR )/", "|$1", $query);
		$query      = preg_replace("/:/", '|', $query);
		$queryArray = explode('|', $query);

		$cpt       = 0;
		$typeQuery = '';
		foreach ($queryArray as $queryPart)
		{
			$cptQuery = ceil($cpt/6) - 1;
			if (preg_match("/(^ AND $|^ OR $)/",$queryPart, $value))
			{
				if (($cpt / 6) != intval($cpt / 6))
				{
					$cpt      = (intval($cpt / 6) + 1) * 6;
					$cptQuery = ceil($cpt/6);
				}
				$value = preg_replace("/ /", '', $value[0]);
				$data['andOrPatent' . $cptQuery] = $value;
			}
			elseif (!empty($queryPart) && $typeQuery != 'displayed')
			{
				$data['patentType' . $cptQuery] = $queryPart;
				$typeQuery = 'displayed';
			}
			elseif (!empty($queryPart))
			{
				$data['patentQuery' . $cptQuery] = $queryPart;
				$typeQuery = '';
			}
			$cpt++;
		}

		$_SESSION['cptPatentQuery'] = ceil(($cptQuery+1)/3) * 3;
	}
}


# Delete editing query
if ($emailIsCheck && !empty($data['delEditingPatentQuery']) && !empty($data['patentQuerySubmit']))
{
	$checkCol = array(array('id', '=', $data['delEditingPatentQuery'], 'AND'),
										array('owners', 'l', '%,' . $_SESSION['id'] . ',%', ''));
	$queryEditToDel = read('queries_wipo_serge', 'id, owners, active', $checkCol, '', $bdd);
	$queryEditToDel = $queryEditToDel[0] ?? '';

	if (!empty($queryEditToDel))
	{
		$userId    = $_SESSION['id'];
		$updateCol = array(array('owners', preg_replace("/,!*$userId,/", ',', $queryEditToDel['owners'])),
											array('active', $queryEditToDel['active'] - 1));
		$checkCol  = array(array('id', '=', $queryEditToDel['id'], ''));
		$execution = update('queries_wipo_serge', $updateCol, $checkCol, '', $bdd);
	}
}

# Add new patents query
if ($emailIsCheck && !empty($data['patentQuerySubmit']) && $data['patentQuerySubmit'] === 'add')
{
	include('model/addNewPatentQuery.php');
	$cpt         = 0;
	$andOrPatent = '';
	$queryPatent = '';
	$_SESSION['cptPatentQuery'] = 3;

	while(!empty($data['patentType' . $cpt]) && !empty($data['patentQuery' . $cpt]))
	{
		if (!preg_match("/^[A-Z_]+$/", $data['patentType' . $cpt]))
		{
			$data['patentType' . $cpt] = 'ALLNAMES';
		}

		$patentQueryInput = urlencode(preg_replace("/(:| $)/", '', $data['patentQuery' . $cpt]));

		$queryPatent = $queryPatent . $andOrPatent . $data['patentType' . $cpt] . '%3A' . $patentQueryInput . '+';

		# Cleaning
		$data['patentType' . $cpt ]  = '';
		$data['patentQuery' . $cpt ] = '';
		$data['andOrPatent' . $cpt ] = '';

		$cpt++;

		if(empty($data['andOrPatent' . $cpt]))
		{
			$andOrPatent = 'AND+';
		}
		else
		{
			$andOrPatent = 'OR+';
		}
	}

	if (!empty($queryPatent))
	{
		$ERROR_PATENTQUERY = addNewPatentQuery($queryPatent, $bdd);
	}
}

#Delete patent query
if ($emailIsCheck && !empty($data['delQueryPatent']))
{
	$checkCol = array(array('id', '=', $data['delQueryPatent'], 'AND'),
										array('owners', 'l', '%,!' . $_SESSION['id'] . ',%', 'OR'),
										array('id', '=', $data['delQueryPatent'], 'AND'),
										array('owners', 'l', '%,' . $_SESSION['id'] . ',%', ''));
	$result = read('queries_wipo_serge', 'owners, active', $checkCol, '', $bdd);
	$result = $result[0] ?? '';

	if (!empty($result))
	{
		$userId    = $_SESSION['id'];
		$updateCol = array(array('owners', preg_replace("/,!*$userId,/", ',', $result['owners'])),
											array('active', $result['active'] - 1));
		$checkCol  = array(array('id', '=', $data['delQueryPatent'], ''));
		$execution = update('queries_wipo_serge', $updateCol, $checkCol, '', $bdd);
	}
}

#Disable patent query
if ($emailIsCheck && !empty($data['disableQueryPatent']))
{
	$checkCol = array(array('id', '=', $data['disableQueryPatent'], 'AND'),
										array('owners', 'l', '%,' . $_SESSION['id'] . ',%', ''));
	$result   = read('queries_wipo_serge', 'owners, active', $checkCol, '', $bdd);
	$result   = $result[0] ?? '';

	if (!empty($result))
	{
		$userId    = $_SESSION['id'];
		$updateCol = array(array('owners', preg_replace("/,$userId,/", ",!$userId,", $result['owners'])),
											array('active', $result['active'] - 1));
		$checkCol  = array(array('id', '=', $data['disableQueryPatent'], ''));
		$execution = update('queries_wipo_serge', $updateCol, $checkCol, '', $bdd);
	}
}

#Activate patent query
if ($emailIsCheck && !empty($data['activateQueryPatent']))
{
	$checkCol = array(array('id', '=', $data['activateQueryPatent'], 'AND'),
										array('owners', 'l', '%,!' . $_SESSION['id'] . ',%', ''));
	$result   = read('queries_wipo_serge', 'owners, active', $checkCol, '', $bdd);
	$result   = $result[0] ?? '';

	if (!empty($result))
	{
		$userId    = $_SESSION['id'];
		$updateCol = array(array('owners', preg_replace("/,!$userId,/", ",$userId,", $result['owners'])),
											array('active', $result['active'] + 1));
		$checkCol  = array(array('id', '=', $data['activateQueryPatent'], ''));
		$execution = update('queries_wipo_serge', $updateCol, $checkCol, '', $bdd);
	}
}

# Extend science query
if (!empty($data['extendScience']))
{
	$_SESSION['cptScienceQuery'] += 3;
	if (!empty($data['delEditingScienceQuery']))
	{
		$delEditingScienceQuery = $data['delEditingScienceQuery'];
	}
}

# Extend patent query
if (!empty($data['extendPatent']))
{
	$_SESSION['cptPatentQuery'] += 3;
	if (!empty($data['delEditingPatentQuery']))
	{
		$delEditingPatentQuery = $data['delEditingPatentQuery'];
	}
}

# Reading after processing
# Read watchPack used
$checkCol          = array(array('users', 'l', '%,' . $_SESSION['id'] . ',%', ''));
$watchPackUsedList = read('watch_pack_serge', 'id, name, description, users', $checkCol, 'ORDER BY name', $bdd);

# Read owner sources
$checkCol  = array(array('owners', 'l', '%,' . $_SESSION['id'] . ',%', 'OR'),
									array('owners', 'l', '%,!' . $_SESSION['id'] . ',%', ''));
$reqReadOwnerSourcestmp = read('rss_serge', 'id, link, name, owners, active', $checkCol, 'ORDER BY name', $bdd);

# Read owner keywords
$checkCol                      = array(array('applicable_owners_sources', 'l', '%|' . $_SESSION['id'] . ':%', ''));
$reqReadOwnerSourcesKeywordtmp = read('keyword_news_serge', 'id, keyword, applicable_owners_sources, active', $checkCol, 'ORDER BY keyword', $bdd);

# Read user settings
$checkCol     = array(array('users', '=', $_SESSION['pseudo'], ''));
$userSettings = read('users_table_serge', 'id, email, phone_number, password, result_by_email, send_condition, frequency, link_limit, selected_days, selected_hour, mail_design, language, record_read, history_lifetime, background_result, record_read, premium_expiration_date, alert_by_sms, sms_credits', $checkCol, '', $bdd);
$userSettings = $userSettings[0];

# Sorting links in email
if ($userSettings['mail_design'] === 'masterword')
{
	$orderByKeyword = 'checked';
}
elseif ($userSettings['mail_design'] === 'origin')
{
	$orderBySource = 'checked';
}
elseif ($userSettings['mail_design'] === 'type')
{
	$orderByType = 'checked';
}

# Privacy
if ($userSettings['record_read'] == 0)
{
	$recordRead = '';
}
elseif ($userSettings['record_read'] == 1)
{
	$recordRead = 'checked';
}

# Sending condition
if ($userSettings['send_condition'] === 'link_limit')
{
	$condNbLink = 'checked';
	$condFreq   = '';
	$condDate   = '';
}
elseif ($userSettings['send_condition'] === 'freq')
{
	$condNbLink = '';
	$condFreq   = 'checked';
	$condDate   = '';
}
elseif ($userSettings['send_condition'] === 'deadline')
{
	$condNbLink = '';
	$condFreq   = '';
	$condDate   = 'checked';
}

preg_match_all("/[1-7]/", $userSettings['selected_days'], $selected_days);
foreach ($selected_days[0] as $value)
{
	$day[$value] = 'selected';
}

$day2 = $day ?? '';

if (isset($day[1]) && isset($day[2]) && isset($day[3]) && isset($day[4]) && isset($day[5]) && isset($day[6]) && isset($day[7]))
{
	$day[1]  = '';
	$day[2]  = '';
	$day[3]  = '';
	$day[4]  = '';
	$day[5]  = '';
	$day[6]  = '';
	$day[7]  = '';
	$day2[1] = '';
	$day2[2] = '';
	$day2[3] = '';
	$day2[4] = '';
	$day2[5] = '';
	$day2[6] = '';
	$day2[7] = '';
	$day[9]  = 'selected';
}
elseif (isset($day[1]) && isset($day[2]) && isset($day[3]) && isset($day[4]) && isset($day[5]))
{
	$day[1]  = '';
	$day[2]  = '';
	$day[3]  = '';
	$day[4]  = '';
	$day[5]  = '';
	$day[6]  = '';
	$day[7]  = '';
	$day2[1] = '';
	$day2[2] = '';
	$day2[3] = '';
	$day2[4] = '';
	$day2[5] = '';
	$day[0]  = 'selected';
}
elseif(isset($day[1]) && isset($day[3]) && isset($day[5]))
{
	$day[1]  = '';
	$day[2]  = '';
	$day[3]  = '';
	$day[4]  = '';
	$day[5]  = '';
	$day[6]  = '';
	$day[7]  = '';
	$day2[1] = '';
	$day2[3] = '';
	$day2[5] = '';
	$day[8]  = 'selected';
}

$firstEntry = FALSE;
$cpt = 1;
while ($cpt <= 7)
{
	if (!empty($day) && $day[$cpt] === 'selected' && $day2[$cpt] === 'selected' && !$firstEntry)
	{
		$day2[$cpt] = '';
		$firstEntry = TRUE;
	}
	elseif (!empty($day) && $day[$cpt] === 'selected' && $day2[$cpt] === 'selected' && $firstEntry)
	{
		$day[$cpt] = '';
		$cpt       = 8;
	}
	$cpt++;
}

if ($userSettings['language'] == 'FR')
{
	$selectLanguageFR = 'selected';
}
else
{
	$selectLanguageEN = 'selected';
}

if ($userSettings['result_by_email'])
{
	$checkResultByMail = 'checked';
}

if ($userSettings['alert_by_sms'])
{
	$checkResultBySMS = 'checked';
}

include('view/nav/nav.php');

include('view/body/setting.php');

include('view/footer/footer.php');

?>
