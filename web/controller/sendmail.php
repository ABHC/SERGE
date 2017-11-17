<?php
# Read mail address
$mailAddr  = fopen('/var/www/Serge/web/.mailaddr', 'r');
$emailAddr = fgets($mailAddr);
fclose($mailAddr);

#Cleaning value
$emailAddr = preg_replace("/(\r\n|\n|\r)/", "", $emailAddr);

$headers = "From: $emailAddr" . "\r\n" .
"Reply-To: $emailAddr" . "\r\n" .
'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $body, $headers);
?>
