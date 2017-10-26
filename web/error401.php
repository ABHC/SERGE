<?php
set_time_limit(25);
session_start();
include('model/connection_sql.php');
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Serge : your news monitoring</title>
	<link href="css/error401.php" rel="stylesheet" />
	<?php
	include('favicon.php');
	?>
	<script src="js/piwik/piwik.js" type="text/javascript"></script>
</head>

<body>
	<?php
	include('controller/error404.php');
	?>
</body>

</html>
