<?php
session_start();
include('model/connection_sql.php');
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Serge : redirect to your link</title>
	<link href="css/result" rel="stylesheet" />
	<?php
	include('favicon.php');
	?>
</head>

<body>
	<?php
	include('controller/redirect.php');
	?>
</body>

</html>
