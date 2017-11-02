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
	<link href="css/legal" rel="stylesheet" />
	<?php
	include('favicon.php');
	?>
	<script src="js/piwik/piwik.js" type="text/javascript"></script>
</head>

<body>
	<?php
	include('controller/legal.php');
	?>
</body>

</html>
