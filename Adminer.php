<?php
/******Adminer******/

/******Database Back-up********/

//Reading config file
$filename = '/home/ruffenach/Serge/SERGE/permission/core_configuration.txt';
$handle = fopen($filename, 'r+');
$contents = fread($handle, filesize($filename));

//Extracting extern and local backup enable
$regexEnableExtern = '#(.|\n|^)+Enable extern back-up: (True|False)(.|\n|$)+#';
$regexEnableLocal = '#(.|\n|^)+Enable local back-up: (True|False)(.|\n|$)+#';
$enableExtern = preg_replace($regexEnableExtern, '$2', $contents);
$enableLocal = preg_replace($regexEnableLocal, '$2', $contents);

//Extracting period and last back-up
$regexPeriod = '#(.|\n|^)+Period: ([0-9]+)(.|\n|$)+#';
$regexLastBackup = '#(.|\n|^)+Last back-up: ([0-9]{10,})(.|\n|$)+#';
$period = preg_replace($regexPeriod, '$2', $contents);
$lastBackup = preg_replace($regexLastBackup, '$2', $contents);

  //If extern backup enabled
  if($enableExtern == 'True' && (time() - $lastBackup) > $period)
  {
    //extracting backup configuration
    $regexDomain = '#(.|\n|^)+Domain name: ([a-z0-9._-]{2,}\.[a-z]{2,4})(.|\n|$)+#';
    $regexUser = '#(.|\n|^)+User: (.{2,})(.|\n|$)+#';
    $regexPassword = '#(.|\n|^)+Password: (.{8,})(.|\n|$)+#';
    $regexDatabasePassword = '#(.|\n|^)+Database Password: (.{8,})(.|\n|$)+#';
    $regexExternFolder = '#(.|\n|^)+Folder extern: (/(.+/)+)(.|\n|$)+#';

    $domain = preg_replace($regexDomain, '$2', $contents);
    $user = preg_replace($regexUser, '$2', $contents);
    $password = preg_replace($regexPassword, '$2', $contents);
    $databasePassword = preg_replace($regexDatabasePassword, '$2', $contents);
    $externFolder = preg_replace($regexExternFolder, '$2', $contents);

    echo "$period into $user@$domain:$externFolder with $password\n";

    /*Making an extern Backup*/
    //creating_backup();
    $date = date('dMY');
    $localFolder = '';
    $backUpCommand = "/usr/bin/mysqldump -u Serge -p$databasePassword Serge > ";
    $destination = $localFolder . "Sergedata_$date.sql";
    $command = $backUpCommand . $destination;
    //echo "$command\n";
    exec($command, $returnOutput, $returnValue);
    //echo "var dump return output\n";
    //var_dump($returnOutput);
    //echo "return value : $returnValue\n";

    //scp to $user, "@", $domain, ":", $folder, " with ", $password;
    //ne pas oublier scp -p pour les metadonné -P pour le port

    //If local backup enabled
    if($enableLocal == 'True')
    {
      $regexFolder = '#(.|\n|^)+Folder local: (/(.+/)+)(.|\n|$)+#';
      $folder = preg_replace($regexFolder, '$2', $contents);

      echo "$period into $folder\n";

      //move backup file to $folder;

    }
    //If local backup not enabled
    else
    {
      echo "No local backup\n";
      //remove backup file;
      unlink($destination);
    }

    $time = time();
    $regexLastBackup = '#(Last back-up:) ([0-9]+)#';
    $contents = preg_replace($regexLastBackup, "$1 $time", $contents);
    rewind($handle);
    fwrite($handle, $contents);
    fclose($handle);
  }
  //If extern backup not enabled
  else
  {
    echo "no extern back up\n";

    //If local backup enabled
    if($enableLocal == 'True' && (time() - $lastBackup) > $period)
    {
      $regexLocalFolder = '#(.|\n|^)+Folder local: (/(.+/)+)(.|\n|$)+#';
      $regexDatabasePassword = '#(.|\n|^)+Database Password: (.{8,})(.|\n|$)+#';

      $localFolder = preg_replace($regexLocalFolder, '$2', $contents);
      $databasePassword = preg_replace($regexDatabasePassword, '$2', $contents);

      /*Making a local Backup*/
      //Backup to $folder;
      $date = date('dMY');
      $backUpCommand = "/usr/bin/mysqldump -u Serge -p$databasePassword Serge > ";
      $destination = $localFolder . "Sergedata_$date.sql";
      $command = $backUpCommand . $destination;
      exec($command, $returnOutput, $returnValue);
      //echo "var dump return output\n";
      //var_dump($returnOutput);
      //echo "return value : $returnValue\n";

      $time = time();
      $regexLastBackup = '#(Last back-up:) ([0-9]+)#';
      $contents = preg_replace($regexLastBackup, "$1 $time", $contents);
      rewind($handle);
      fwrite($handle, $contents);
      fclose($handle);
    }
    //If local backup not enabled
    else
    {
      echo "no local back up\n";
    }
  }
?>
