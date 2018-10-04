<?php
/******Adminer******/

/******Database backup********/

//Reading config file
$filename = 'configuration/core_configuration.txt';
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
//TRUE -> incorrect field or value in the configuration file
//FALSE -> correct field with a correct value in the configuration file
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
$fatalErrorMessage = 'FATAL ERROR: could not find field or value is not correct in these fields ';
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
  else if($configuration['enableExtern'] == 'False' && $configuration['enableLocal'] == 'False')
  {
    error_log("$warningMessage");
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
    error_log("$warningMessage");
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
    error_log("$warningMessage");
  }
}

//If fatal error -> display all unrecognized fields
if($localFatalError == TRUE || $externFatalError == TRUE)
{
  foreach ($incorrect as $fields => $value)
  {
    if($value == TRUE)
    {
      $fatalErrorMessage = $fatalErrorMessage . "-> $fields ";
    }
  }
  error_log("$fatalErrorMessage");
}

/***Backup building***/

  //If extern backup enabled
  if( $configuration['enableExtern'] == 'True' &&
      ( time() - $configuration['lastBackUp'] ) > $configuration['period'] &&
      $externFatalError == FALSE
    )
  {
    //Creating the name of the backup
    $date = date('dMY');
    $backUpName = "Sergedata_$date.sql";

    //Building the backup command
    $backUpCommand = '/usr/bin/mysqldump -u Serge -p' . $configuration['databasePassword'] . ' Serge 2>&1 > ';
    $command = $backUpCommand . $backUpName;

    //Creating the backup file
    exec($command, $returnOutput, $returnValue);

    //Error management of the exec function
    if($returnValue)
    {
      foreach($returnOutput as $output)
      {
        error_log("$output");
      }
    }

    //Connecting to the distant server
    $connection = ssh2_connect($configuration['domain'], $configuration['port']);
    if(!$connection)
    {
      error_log("ERROR: cannot connect to distant server, please check domain name and port");
    }
    else if( !ssh2_auth_password($connection, $configuration['user'], $configuration['sshPassword']) )
    {
      error_log("ERROR: Bad authentification, please check user and password");
    }

    //Send the backup file to the distant server
    $destination = $configuration['externFolder'] . $backUpName;
    if( !ssh2_scp_send($connection, $backUpName, $destination, 0644) )
    {
      error_log("ERROR: cannot send file");
    }

    //If local backup enabled move the backup file to local destination
    if($configuration['enableLocal'] == 'True' && $localFatalError == FALSE )
    {
      $destination = $configuration['localFolder'] . $backUpName;
      if( !rename($backUpName, $destination) )
      {
        error_log("error when moving file");
      }
    }
    //If local backup not enabled remove backup file
    else
    {
      if( !unlink($backUpName) )
      {
        error_log("error when removing file");
      }
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
    if( $configuration['enableLocal'] == 'True' &&
        ( time() - $configuration['lastBackUp'] ) > $configuration['period'] &&
        $localFatalError == FALSE
      )
    {
      //Creating the name of the backup
      $date = date('dMY');
      $backUpName = "Sergedata_$date.sql";
      $destination = $configuration['localFolder'] . $backUpName;

      //Building the backup command
      $backUpCommand = '/usr/bin/mysqldump -u Serge -p' . $configuration['databasePassword'] . ' Serge 2>&1 > ';
      $command = $backUpCommand . $destination;

      //Creating the backup file
      exec($command, $returnOutput, $returnValue);

      //Error management of the exec function
      if($returnValue)
      {
        foreach($returnOutput as $output)
        {
          error_log("$output");
        }
      }

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
