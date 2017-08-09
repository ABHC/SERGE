<div class="background">
	<div class="subBackground"></div>
</div>

<div class="body">
	<div>Stay tuned, we are coming soon</div>
	<div class="name">Cairn Devices</div>
	<div id="timer"></div>
	<?php
	$deleveryTime = 1504260000 ;
	$timeLeft = $deleveryTime - time();
	$day = floor($timeLeft / (24*3600));
	$hour = floor(($timeLeft - ($day*24*3600)) / (3600));
	$minute = floor(($timeLeft - ($day*24*3600) - ($hour*3600)) / 60);
	$second = ($timeLeft - ($day*24*3600) - ($hour*3600) - ($minute*60));
	?>

	<script>counter(<?php echo $day; ?>,<?php echo $hour; ?>,<?php echo $minute; ?>,<?php echo $second; ?>,"timer");</script>

	<form method="post" action="workinprogress">
		<input type="email" name="email" id="email" placeholder="Enter your email adress" value=""/> <input title="Stay tuned" class="submit" type="submit" name="settings" value=">" />
	</form>
</div>
