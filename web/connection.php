<?php
session_start();
include_once('model/connection_sql.php');
$language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
$language = $language[0] . $language[1];
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($language); ?>">
<head>
	<meta charset="utf-8" />
	<title>Serge : your news monitoring</title>
	<link href="css/connection" rel="stylesheet" />
	<?php
	include_once('favicon.php');
	?>
</head>

<body>
	<?php
	include_once('controller/connection.php');
	?>
</body>

</html>
