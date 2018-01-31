<?php
set_time_limit(25);
session_start();
include('model/connection_sql.php');
include('languages.php');
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($language); ?>">
<head>
	<meta charset="utf-8" />
	<title>Serge : your news monitoring</title>
	<link href="css/watchPack" rel="stylesheet" />
	<script src="https://code.jquery.com/jquery.min.js"></script>
	<script src="js/scrollPos.js?<?php echo $_SERVER['REQUEST_TIME'];?>" type="text/javascript"></script>
	<?php
	include('favicon.php');
	?>
	<script src="js/piwik/piwik.js"></script>
</head>

<body>
	<?php
	include('controller/watchPack.php');
	?>
</body>

</html>
