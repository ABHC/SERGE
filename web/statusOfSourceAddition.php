<?php
set_time_limit(25);
session_start();
include('model/connection_sql.php');
include('model/readStatusOfSourceAddition.php');
include('languages.php');
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($language); ?>">
	<head>
		<meta charset="utf-8" />
		<link href="css/sourceStatus" rel="stylesheet"/>
	</head>
		<?php
		include('controller/statusOfSourceAddition.php');
		?>
</html>
