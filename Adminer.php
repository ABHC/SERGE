<?php
/******Adminer******/

/******Database Back-up********/

//Reading config file
$filename = "permission/core_configuration.txt";
$handle = fopen($filename, "r+");
$contents = fread($handle, filesize($filename));

//Extracting extern and local backup enable
$regex_enable_extern = "#(.|\n|^)+Enable extern back-up: (True|False)(.|\n|$)+#";
$regex_enable_local = "#(.|\n|^)+Enable local back-up: (True|False)(.|\n|$)+#";
$enable_extern = preg_replace($regex_enable_extern, "$2", $contents);
$enable_local = preg_replace($regex_enable_local, "$2", $contents);

//Extracting period and last back-up
$regex_period = "#(.|\n|^)+Period: ([0-9]+)(.|\n|$)+#";
$regex_last_backup = "#(.|\n|^)+Last back-up: ([0-9]{10,})(.|\n|$)+#";
$period = preg_replace($regex_period, "$2", $contents);
$last_backup = preg_replace($regex_last_backup, "$2", $contents);

  //If extern backup enabled
  if($enable_extern == "True" && (time() - $last_backup) > $period)
  {
    //extracting backup configuration
    $regex_domain = "#(.|\n|^)+Domain name: ([a-z0-9._-]{2,}\.[a-z]{2,4})(.|\n|$)+#";
    $regex_user = "#(.|\n|^)+User: (.{2,})(.|\n|$)+#";
    $regex_password = "#(.|\n|^)+Password: (.{8,})(.|\n|$)+#";
    $regex_folder = "#(.|\n|^)+Folder extern: (/(.+/)+)(.|\n|$)+#";

    $domain = preg_replace($regex_domain, "$2", $contents);
    $user = preg_replace($regex_user, "$2", $contents);
    $password = preg_replace($regex_password, "$2", $contents);
    $folder = preg_replace($regex_folder, "$2", $contents);

    echo $period, " into ", $user, "@", $domain, ":", $folder, " with ", $password, "\n";

    /*Making an extern Backup*/
    //creating_backup();
    //scp to $user, "@", $domain, ":", $folder, " with ", $password;

    //If local backup enabled
    if($enable_local == "True")
    {
      $regex_folder = "#(.|\n|^)+Folder local: (/(.+/)+)(.|\n|$)+#";
      $folder = preg_replace($regex_folder, "$2", $contents);

      echo $period, " into ", $folder, "\n";

      //move backup file to $folder;

    }
    //If local backup not enabled
    else
    {
      echo "No local backup\n";
      //remove backup file;
    }

    $time = time();
    $regex_last_backup = "#(Last back-up:) ([0-9]+)#";
    $contents = preg_replace($regex_last_backup, "$1 $time", $contents);
    rewind($handle);
    fwrite($handle, $contents);
    fclose($handle);
  }
  //If extern backup not enabled
  else
  {
    echo "no extern back up\n";

    //If local backup enabled
    if($enable_local == "True" && (time() - $last_backup) > $period)
    {
      $regex_folder = "#(.|\n|^)+Folder local: (/(.+/)+)(.|\n|$)+#";
      $folder = preg_replace($regex_folder, "$2", $contents);

      echo $period, " into ", $folder, "\n";

      /*Making a local Backup*/
      //Backup to $folder;

      $time = time();
      $regex_last_backup = "#(Last back-up:) ([0-9]+)#";
      $contents = preg_replace($regex_last_backup, "$1 $time", $contents);
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
