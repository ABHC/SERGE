<?php

/*
This script will delete all logs files that are existing more than a specific amount of time

This script work with a certain custom log of apache
for error log :
ErrorLog "|/usr/bin/rotatelogs -l ${APACHE_LOG_DIR}/error_%Y-%m-%d.log 86400"
for access log :
CustomLog "|/usr/bin/rotatelogs -l ${APACHE_LOG_DIR}/access_%Y-%m-%d.log 86400" combined
This custom log will create a file log for every day

both works with the program rotatelogs
-l => work with local time instead of GMT
${APACHE_LOG_DIR} => here is /var/log/apache2
error or access
%Y => Year(4 digits)
%m => month(2 digits)
%d => day(2 digits)
86400 => every day
*/

//Reading config file
$filename      = '/var/www/Serge/configuration/core_configuration.txt';
$handle        = fopen($filename, 'r+');
$contents      = fread($handle, filesize($filename));

$flag          = TRUE;
$configuration = array();
$regex         = array('duration' => '#(?:.|\n|^)+Log deletion: ([0-9]+|False)(?:.|\n|$)+#',
                       'folder'   => '#(?:.|\n|^)+Log folder: (\/(?:.+\/)+)(?:.|\n|$)+#'
                      );

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

$logFolder         = $configuration['folder'];
$duration          = $configuration['duration'];
$contentLogFolder  = scandir($logFolder);
$regex             = '#(?:error|access)_([0-9]{4})-([0-9]{2})-([0-9]{2})\.log#';
$errorMessage      = "ERROR: Bad value in field Log Deletion or Log Folder\n";

//Look for logs to delete then delete them
if($flag)
{
  foreach($contentLogFolder as $file)
  {
    if( !( $file == '.' || $file == '..' ) )
    {
      if( preg_match($regex, $file, $match) )
      {
        //$match[1] = year
        //$match[2] = month
        //$match[3] = day
        //mktime(hour, minute, second, month, day, year)
        $timestamp = mktime( 0, 0, 0, $match[2], $match[3], $match[1] )."\n";

        if( (time() - intval($timestamp) ) > $duration )
        {
          if( !unlink($logFolder.$file) )
          {
            error_log("error when removing log");
          }
        }

      }
    }
  }
}
else
{
  error_log("$errorMessage");
}

?>
