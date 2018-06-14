<?php
/******Adminer******/

/*Sauvegarde de la bdd*/
//Back est-elle autorisé ? Period ? Localement ? À distance ?
//Back up local -> où mettre la backup ?
//Back up externe -> récuperer : domaine, login, mdp et dossier.

//Reading config file
$filename = "BackUpConfig";
$handle = fopen($filename, "r");
$contents = fread($handle, filesize($filename));
fclose($handle);



?>
