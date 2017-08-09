<?php
session_start();
include_once('model/connection_sql.php');
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Serge : your news monitoring</title>
	<link href="css/workinprogress" rel="stylesheet" />
	<script src="js/counter.js" type="text/javascript"></script>
</head>

<body>
	<?php
	include_once('controller/workinprogress.php');
	?>
</body>

</html>
