<div class="background">
	<div class="subBackground"></div>
</div>

<div class="body">
	<div>Stay tuned, we are coming soon</div>
	<div class="name">Cairn Devices</div>
	<div id="timer"></div>

	<script>counter(<?php echo $day; ?>,<?php echo $hour; ?>,<?php echo $minute; ?>,<?php echo $second; ?>,"timer");</script>

	<form method="post" action="workinprogress">
		<input type="hidden" name="nonce" value="<?php echo $nonce; ?>"/>
		<input type="email" name="email" id="email" placeholder="Enter your email adress" value=""/> <input title="Stay tuned" class="submit" type="submit" name="newsletter" value="submit" />
	</form>
</div>
