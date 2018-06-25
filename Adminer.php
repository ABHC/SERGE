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

$regex = array(
  'enableExtern'     => '#(.|\n|^)+Enable extern backup: (True|False)(.|\n|$)+#',
  'enableLocal'      => '#(.|\n|^)+Enable local backup: (True|False)(.|\n|$)+#',
  'databasePassword' => '#(.|\n|^)+Database Password: (.+)(.|\n|$)+#',
  'period'           => '#(.|\n|^)+Period: ([0-9]+)(.|\n|$)+#',
  'lastBackUp'       => '#(.|\n|^)+Last backup: ([0-9]{10,})(.|\n|$)+#',
  'localFolder'      => '#(.|\n|^)+Folder local: (/(.+/)+)(.|\n|$)+#',
  'domain'           => '#(.|\n|^)+Domain name: ([a-z0-9._-]{2,}\.[a-z]{2,4})(.|\n|$)+#',
  'port'             => '#(.|\n|^)+Port: ([0-9]{2,5})(.|\n|$)+#',
  'user'             => '#(.|\n|^)+User: (.{2,})(.|\n|$)+#',
  'password'         => '#(.|\n|^)+Password: (.+)(.|\n|$)+#',
  'externFolder'     => '#(.|\n|^)+Folder extern: (/(.+/)+)(.|\n|$)+#'
);
$error = FALSE;
$configuration = array();

foreach($regex as $fields => $value)
{
  if( preg_match($value, $contents, $matches) )
  {
    $configuration[$fields] = $matches[2];
  }
  else
  {
    echo "ERROR: Value in $fields is not correct\n";
    $error = TRUE;
  }
}

  //If extern backup enabled
  if($configuration['enableExtern'] == 'True' && (time() - $configuration['lastBackUp']) > $configuration['period'] && $error == FALSE)
  {
    //Creating the name of the backup
    $date = date('dMY');
    $backUpName = "Sergedata_$date.sql";

    //Building the backup command
    $backUpCommand = '/usr/bin/mysqldump -u Serge -p' . $configuration['databasePassword'] . ' Serge > ';
    $command = $backUpCommand . $backUpName;

    //Creating the backup file
    exec($command, $returnOutput, $returnValue);

    //Connecting to the distant server
    $connection = ssh2_connect($configuration['domain'], $configuration['port']);
    ssh2_auth_password($connection, $configuration['user'], $configuration['password']);

    //Send the backup file to the distant server
    $destination = $configuration['externFolder'] . $backUpName;
    ssh2_scp_send($connection, $backUpName, $destination, 0644);

    //If local backup enabled move the backup file to local destination
    if($configuration['enableLocal'] == 'True')
    {
      $destination = $configuration['localFolder'] . $backUpName;
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
    if($configuration['enableLocal'] == 'True' && (time() - $configuration['lastBackUp']) > $configuration['period'] && $error == FALSE)
    {
      //Creating the name of the backup
      $date = date('dMY');
      $backUpName = "Sergedata_$date.sql";
      $destination = $configuration['localFolder'] . $backUpName;

      //Building the backup command
      $backUpCommand = '/usr/bin/mysqldump -u Serge -p' . $configuration['databasePassword'] . ' Serge > ';
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
