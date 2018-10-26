<?php

// this work with a certain custom log of apache
// for error log :
// ErrorLog "|/usr/bin/rotatelogs -l ${APACHE_LOG_DIR}/error_%Y-%m-%d.log 86400"
// for access log :
// CustomLog "|/usr/bin/rotatelogs -l ${APACHE_LOG_DIR}/access_%Y-%m-%d.log 86400" combined

// both works with the program rotatelogs
// -l => work with local time instead of GMT
// ${APACHE_LOG_DIR} => here is /var/log/apache2
// error or access
// %Y => Year(4 digits)
// %m => month(2 digits)
// %d => day(2 digits)
// 86400 => every day

//Reading config file
$filename      = 'configuration/core_configuration.txt';
$handle        = fopen($filename, 'r+');
$contents      = fread($handle, filesize($filename));
$flag          = TRUE;
$configuration = array();
$regex         = array('duration' => '#(?:.|\n|^)+Log deletion: ([0-9]+|False)(?:.|\n|$)+#',
                       'folder'   => '#(?:.|\n|^)+Log folder: (\/(?:.+\/)+)(?:.|\n|$)+#'
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

$logFolder         = $configuration['folder'];
$duration          = $configuration['duration'];
$contentLogFolder  = scandir($logFolder);
$regex             = '#(?:error|access)_([0-9]{4})-([0-9]{2})-([0-9]{2})\.log#';

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
          unlink($logFolder.$file);
        }

      }
    }
  }
}
else
{
  echo "ERROR: Bad value in field Log Deletion or Log Folder\n";
}

?>
