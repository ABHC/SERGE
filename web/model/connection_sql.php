<?php
#Reading of id and password
$secureAccess = fopen('/var/www/Serge/web/.htpasswd', 'r');

$identification = fgets($secureAccess);
$password = fgets($secureAccess);

fclose($secureAccess);

#Cleaning values
$identification = preg_replace("/(\r\n|\n|\r)/", "", $identification);
$password = preg_replace("/(\r\n|\n|\r)/", "", $password);

#Connection to SQL database
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=Serge;charset=utf8mb4', $identification, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch (Exception $e)
{
	error_log('Error : ' . $e->getMessage());
	die('Something happend : Sorry for the inconvenience');
}
?>
