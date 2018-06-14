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

//Extracting local backup enable
$regex_enable_local = "#(.|\n|^)+Enable local back-up: (True|False)(.|\n|$)+#";
$enable_local = preg_replace($regex_enable_local, "$2", $contents);

  //If backup enabled, extracting backup configuration
  if ($enable_local == "True")
  {
    $regex_period = "#(.|\n|^)+Period: ([0-9]{4,})(.|\n|$)+#";
    $period = preg_replace($regex_period, "$2", $contents);
    $regex_folder_local = "#(.|\n|^)+Folder local: (/(.+/)+)(.|\n|$)+#";
    $folder_local = preg_replace($regex_folder_local, "$2", $contents);
    echo $period, "\n", $folder_local, "\n";

      /*
      //Making a local Backup
      if( $period > (now - last timestamp) )
      {
        Backup to $folder_local
      }
      */
  }
  else
  {
    echo "no back up\n";
  }



?>
