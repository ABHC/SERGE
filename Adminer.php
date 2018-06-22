<?php
/******Adminer******/

/******Database backup********/

/********************************************/
/*Scanner le fichier de configuration pour  */
/*savoir si toutes les informations sont ok */
/*et présentes ******************************/
/*Gérer aussi les commandes des exec() si   */
/*elles retournent des erreurs ou pas *******/

//Reading config file
$filename = '/home/ruffenach/Serge/SERGE/permission/core_configuration.txt';
$handle = fopen($filename, 'r+');
$contents = fread($handle, filesize($filename));

//Extracting extern and local backup enable
$regexEnableExtern = '#(.|\n|^)+Enable extern backup: (True|False)(.|\n|$)+#';
$regexEnableLocal = '#(.|\n|^)+Enable local backup: (True|False)(.|\n|$)+#';
$enableExtern = preg_replace($regexEnableExtern, '$2', $contents);
$enableLocal = preg_replace($regexEnableLocal, '$2', $contents);

//Extracting period and last backup
$regexPeriod = '#(.|\n|^)+Period: ([0-9]+)(.|\n|$)+#';
$regexLastBackup = '#(.|\n|^)+Last backup: ([0-9]{10,})(.|\n|$)+#';
$period = preg_replace($regexPeriod, '$2', $contents);
$lastBackup = preg_replace($regexLastBackup, '$2', $contents);

  //If extern backup enabled
  if($enableExtern == 'True' && (time() - $lastBackup) > $period)
  {
    //Extracting Information about distant server
    $regexDomain = '#(.|\n|^)+Domain name: ([a-z0-9._-]{2,}\.[a-z]{2,4})(.|\n|$)+#';
    $regexUser = '#(.|\n|^)+User: (.{2,})(.|\n|$)+#';
    $regexPassword = '#(.|\n|^)+Password: (.+)(.|\n|$)+#';
    $regexDatabasePassword = '#(.|\n|^)+Database Password: (.+)(.|\n|$)+#';
    $regexExternFolder = '#(.|\n|^)+Folder extern: (/(.+/)+)(.|\n|$)+#';
    $regexPort = '#(.|\n|^)+Port: ([0-9]{2,4})(.|\n|$)+#';

    $domain = preg_replace($regexDomain, '$2', $contents);
    $user = preg_replace($regexUser, '$2', $contents);
    $password = preg_replace($regexPassword, '$2', $contents);
    $databasePassword = preg_replace($regexDatabasePassword, '$2', $contents);
    $externFolder = preg_replace($regexExternFolder, '$2', $contents);
    $port = preg_replace($regexPort, '$2', $contents);

    //Creating the name of the backup
    $date = date('dMY');
    $backUpName = "Sergedata_$date.sql";

    //Building the backup command
    $backUpCommand = "/usr/bin/mysqldump -u Serge -p$databasePassword Serge > ";
    $command = $backUpCommand . $backUpName;

    //Creating the backup file
    exec($command, $returnOutput, $returnValue);

    //Connecting to the distant server
    $connection = ssh2_connect($domain, $port);
    ssh2_auth_password($connection, $user, $password);

    //Send the backup file to the distant server
    $destination = $externFolder . $backUpName;
    ssh2_scp_send($connection, $backUpName, $destination, 0644);

    //If local backup enabled move the backup file to local destination
    if($enableLocal == 'True')
    {
      //Extracting the local destination
      $regexLocalFolder = '#(.|\n|^)+Folder local: (/(.+/)+)(.|\n|$)+#';
      $localFolder = preg_replace($regexLocalFolder, '$2', $contents);

      $destination = $localFolder . $backUpName;
      rename($backUpName, $destination);
    }
    //If local backup not enabled remove backup file
    else
    {
      unlink($backUpName);
    }

    //Updating the last backup fields of the core configuration file
    $time = time();
    $regexLastBackup = '#(Last backup:) ([0-9]+)#';
    $contents = preg_replace($regexLastBackup, "$1 $time", $contents);
    rewind($handle);
    fwrite($handle, $contents);
    fclose($handle);
  }
  //If extern backup not enabled
  else
  {
    //If local backup enabled
    if($enableLocal == 'True' && (time() - $lastBackup) > $period)
    {
      //Extracting destination folder and database password
      $regexLocalFolder = '#(.|\n|^)+Folder local: (/(.+/)+)(.|\n|$)+#';
      $regexDatabasePassword = '#(.|\n|^)+Database Password: (.+)(.|\n|$)+#';
      $localFolder = preg_replace($regexLocalFolder, '$2', $contents);
      $databasePassword = preg_replace($regexDatabasePassword, '$2', $contents);

      //Creating the name of the backup
      $date = date('dMY');
      $backUpName = "Sergedata_$date.sql";
      $destination = $localFolder . $backUpName;

      //Building the backup command
      $backUpCommand = "/usr/bin/mysqldump -u Serge -p$databasePassword Serge > ";
      $command = $backUpCommand . $destination;

      //Creating the backup file
      exec($command, $returnOutput, $returnValue);

      //Updating the last backup fields of the core configuration file
      $time = time();
      $regexLastBackup = '#(Last backup:) ([0-9]+)#';
      $contents = preg_replace($regexLastBackup, "$1 $time", $contents);
      rewind($handle);
      fwrite($handle, $contents);
      fclose($handle);
    }
  }
?>
