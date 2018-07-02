<?php
/******Adminer******/

/******Database backup********/

/********************************************/
/*Gérer aussi les commandes des exec() si   */
/*elles retournent des erreurs ou pas *******/

//Reading config file
$filename = '/home/ruffenach/Serge/SERGE/permission/core_configuration.txt';
$handle = fopen($filename, 'r+');
$contents = fread($handle, filesize($filename));

//Initialize arrays and variables
//Array with all regex
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
  'sshPassword'      => '#(.|\n|^)+SSH Password: (.+)(.|\n|$)+#',
  'externFolder'     => '#(.|\n|^)+Folder extern: (/(.+/)+)(.|\n|$)+#'
);
//FALSE -> incorrect field or value in the configuration file
//TRUE -> correct field with a correct value in the configuration file
$incorrect = array(
  'enableExtern'     => FALSE,
  'enableLocal'      => FALSE,
  'databasePassword' => FALSE,
  'period'           => FALSE,
  'lastBackUp'       => FALSE,
  'localFolder'      => FALSE,
  'domain'           => FALSE,
  'port'             => FALSE,
  'user'             => FALSE,
  'sshPassword'      => FALSE,
  'externFolder'     => FALSE
);
//Values of the configuration file
$configuration = array(
  'enableExtern'     => '',
  'enableLocal'      => '',
  'databasePassword' => '',
  'period'           => 0,
  'lastBackUp'       => 0,
  'localFolder'      => '',
  'domain'           => '',
  'port'             => '',
  'user'             => '',
  'sshPassword'      => '',
  'externFolder'     => ''
);
//Flags
$localFatalError = FALSE;
$externFatalError = FALSE;
//Messages
$fatalErrorMessage = 'FATAL ERROR: could not find field or value is not correct in these fields :';
$warningMessage = 'WARNING: Informations in configuration file are not correct but you did not enable backup, so It is good.';

//Checking configuration file
foreach($regex as $fields => $value)
{
  if( preg_match($value, $contents, $matches) )
  {
    $configuration[$fields] = $matches[2];
  }
  else
  {
    $incorrect[$fields] = TRUE;
  }
}

/***Error management***/
//If an enable fields can't be read -> fatal error
if($incorrect['enableLocal'] == TRUE)
{
  $localFatalError = TRUE;
}
if($incorrect['enableExtern'] == TRUE)
{
  $externFatalError = TRUE;
}

//If fields databasePassword, period and lastBackUp can't be read
if( $incorrect['databasePassword'] == TRUE || $incorrect['period'] == TRUE || $incorrect['lastBackUp'] == TRUE )
{
  //If one or more enable = true then fatal error
  if( $configuration['enableLocal'] == 'True' || $configuration['enableExtern'] == 'True' )
  {
    $externFatalError = TRUE;
    $localFatalError = TRUE;
  }
  //If enable = false, display warning
  else
  {
    echo "$warningMessage\n";
  }
}

//If one these fields can't be read
if( $incorrect['domain'] == TRUE ||
    $incorrect['port'] == TRUE ||
    $incorrect['user'] == TRUE ||
    $incorrect['sshPassword'] == TRUE ||
    $incorrect['externFolder'] == TRUE
  )
{
  //If enable = true -> fatal error
  if($configuration['enableExtern'] == 'True')
  {
    $externFatalError = TRUE;
  }
  //enable = false -> warning
  else if($configuration['enableExtern'] == 'False')
  {
    echo "$warningMessage\n";
  }
}

//If field localFolder can't be read
if($incorrect['localFolder'] == TRUE)
{
    //If enable = true -> fatal error
    if($configuration['enableLocal'] == 'True')
    {
      $localFatalError = TRUE;
    }
    //enable = false -> warning
    else if($configuration['enableLocal'] == 'False')
    {
      echo "$warningMessage\n";
    }
}

//If fatal error -> display all unrecognized fields
if($localFatalError == TRUE || $externFatalError == TRUE)
{
  echo "$fatalErrorMessage\n";
  foreach ($incorrect as $fields => $value)
  {
    if($value == TRUE)
    {
      echo "$fields\n";
    }
  }
}

  //If extern backup enabled
  if($configuration['enableExtern'] == 'True' &&
     ( time() - $configuration['lastBackUp'] ) > $configuration['period'] &&
     $externFatalError == FALSE
    )
  {
    //Creating the name of the backup
    $date = date('dMY');
    $backUpName = "Sergedata_$date.sql";

    //Building the backup command
    $backUpCommand = '/usr/bin/mysqldump -u Serge -p' . $configuration['databasePassword'] . ' Serge > ';
    $command = $backUpCommand . $backUpName;

    //Creating the backup file
    //exec($command, $returnOutput, $returnValue);
    echo "$command\n";

    //Connecting to the distant server
    //$connection = ssh2_connect($configuration['domain'], $configuration['port']);
    //ssh2_auth_password($connection, $configuration['user'], $configuration['sshPassword']);

    //Send the backup file to the distant server
    $destination = $configuration['externFolder'] . $backUpName;
    //ssh2_scp_send($connection, $backUpName, $destination, 0644);
    echo "sending to ".$configuration['domain']." ".$configuration['port']." ".$configuration['user']." ".$configuration['sshPassword']." ".$configuration['externFolder']."\n";

    //If local backup enabled move the backup file to local destination
    if($configuration['enableLocal'] == 'True' && $localFatalError == FALSE )
    {
      $destination = $configuration['localFolder'] . $backUpName;
      //rename($backUpName, $destination);
      echo "move to $destination\n";
    }
    //If local backup not enabled remove backup file
    else
    {
      //unlink($backUpName);
      echo "remove\n";
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
    if($configuration['enableLocal'] == 'True' &&
       ( time() - $configuration['lastBackUp'] ) > $configuration['period'] &&
       $localFatalError == FALSE
      )
    {
      //Creating the name of the backup
      $date = date('dMY');
      $backUpName = "Sergedata_$date.sql";
      $destination = $configuration['localFolder'] . $backUpName;

      //Building the backup command
      $backUpCommand = '/usr/bin/mysqldump -u Serge -p' . $configuration['databasePassword'] . ' Serge > ';
      $command = $backUpCommand . $destination;

      //Creating the backup file
      //exec($command, $returnOutput, $returnValue);
      echo "$command\n";

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
