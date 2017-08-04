<?php
session_start();
include_once('model/connection_sql.php');
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Serge : your news monitoring</title>
	<link href="css/index?<?php echo time(); ?>" rel="stylesheet" />
</head>

<body>
	<?php
	include_once('controller/index.php');
	?>
</body>

</html>
