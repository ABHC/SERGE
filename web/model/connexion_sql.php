<?php

#Récupération des identifiants et mots de passes
$secureAccess = fopen('/var/www/Serge/web/.htpasswd', 'r+');

$identification = fgets($secureAccess);
$password = fgets($secureAccess);

fclose($secureAccess);

#Nettoyage des valeurs récupéré
$identification = preg_replace("/(\r\n|\n|\r)/", "", $identification);
$password = preg_replace("/(\r\n|\n|\r)/", "", $password);

#Connexion à la base sql
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=CairnDevices;charset=utf8mb4', $identification, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch (Exception $e)
{
	die('Erreur : ' . $e->getMessage());
}
?>
