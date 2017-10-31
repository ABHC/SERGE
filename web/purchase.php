<?php
set_time_limit(25);
session_start();
include('model/connection_sql.php');
$language = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
$language = $language[0] . $language[1];
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($language); ?>">
<head>
	<meta charset="utf-8" />
	<title>Serge : your news monitoring</title>
	<link href="css/purchase" rel="stylesheet" />
	<script src="js/updatePrice.js" type="text/javascript"></script>
	<?php
	include('favicon.php');
	?>
	<script src="js/piwik/piwik.js" type="text/javascript"></script>
</head>

<body>
	<?php
	include('controller/purchase.php');
	?>
</body>

</html>
