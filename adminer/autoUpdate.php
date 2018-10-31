<?php

/*
This script will execute a shell file at a certain frequency given in a configuration file
The shell file is a script to update Serge to the last stable version
*/

//Reading configuration file
$filename      = '/var/www/Serge/configuration/core_configuration.txt';
$handle        = fopen($filename, 'r+');
$contents      = fread($handle, filesize($filename));

$flag          = TRUE;
$configuration = array();
$regex         = array('period'     => '#(?:.|\n|^)+Update period: ([0-9]+|False)(?:.|\n|$)+#',
                       'lastUpdate' => '#(?:.|\n|^)+Last update: ([0-9]{10,})(?:.|\n|$)+#'
                      );
$errorMessage  = "ERROR: Bad value in field Update period or Last update\n";


//Checking configuration file
foreach($regex as $key => $value)
{
  if( preg_match($value, $contents, $match) )
  {
    $configuration[$key] = $match[1];
  }
  else
  {
    $flag = FALSE;
  }
}

//If no error, auto update enable and over the period duration then
//execute the auto update command and write back the last backup timestamp
if($flag)
{
  if( !( $configuration['period'] == 'False' ) )
  {
    if( ( time() - $configuration['lastUpdate'] ) > $configuration['period'] )
    {
      $command = './autoUpdate.sh';
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
      $time            = time();
      $regexLastBackup = '#(Last update:) ([0-9]{10,})#';
      $contents        = preg_replace($regexLastBackup, "$1 $time", $contents);
      rewind($handle);
      fwrite($handle, $contents);
      fclose($handle);
    }
  }
}
else
{
  error_log("$errorMessage");
}

?>
