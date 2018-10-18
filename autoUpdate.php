<?php

$filename      = 'configuration/core_configuration';
$handle        = fopen($filename, 'r+');
$contents      = fread($handle, filesize($filename));
$flag          = TRUE;
$configuration = array();
$regex         = array('period'     => '#(?:.|\n|^)+Update period: ([0-9]+|False)(?:.|\n|$)+#',
                       'lastUpdate' => '#(?:.|\n|^)+Last update: ([0-9]{10,})(?:.|\n|$)+#'
                      );

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

if($flag)
{
  if( !( $configuration['period'] == 'False' ) )
  {
    if( ( time() - $configuration['lastUpdate'] ) > $configuration['period'] )
    {
      echo "Auto Update\n";
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
      $time = time();
      $regexLastBackup = '#(Last update:) ([0-9]{10,})#';
      $contents = preg_replace($regexLastBackup, "$1 $time", $contents);
      rewind($handle);
      fwrite($handle, $contents);
      fclose($handle);
    }
  }
  else
  {
    echo "value is False\n";
  }
}
else
{
  echo "Wrong data\n";
}

?>
