<?php
session_start();
include_once('model/connection_sql.php');
$language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
$language = $language[0] . $language[1];
?>

<!DOCTYPE html>
<html lang="<?php echo $language; ?>">
<head>
	<meta charset="utf-8" />
	<title>Serge : your news monitoring</title>
	<link href="css/setting" rel="stylesheet" />
	<script type="text/javascript">
	var scrollPosition = <?php if (isset($_SESSION['scrollPos'])) {echo $_SESSION['scrollPos'];} else {echo '0';} ?>;
	<?php include_once('js/scrollPos.js'); ?>
	</script>
	<script src="https://code.jquery.com/jquery.min.js" type="text/javascript"></script>
	<script src="js/backgroundPreviewAsTitle.js" type="text/javascript"></script>
</head>

<body>
	<?php
	include_once('controller/setting.php');
	?>
</body>

</html>
