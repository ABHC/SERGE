<?php
/******Adminer******/

/******Database Back-up********/

//Reading config file
$filename = "BackUpConfig";
$handle = fopen($filename, "r");
$contents = fread($handle, filesize($filename));
fclose($handle);

//Extracting extern and local backup enable
$regex_enable_extern = "#(.|\n|^)+Enable extern back-up: (True|False)(.|\n|$)+#";
$regex_enable_local = "#(.|\n|^)+Enable local back-up: (True|False)(.|\n|$)+#";
$enable_extern = preg_replace($regex_enable_extern, "$2", $contents);
$enable_local = preg_replace($regex_enable_local, "$2", $contents);

//Extracting period
$regex_period = "#(.|\n|^)+Period: ([0-9]{4,})(.|\n|$)+#";
$period = preg_replace($regex_period, "$2", $contents);

  //If backup enabled, extracting backup configuration
  if($enable_extern == "True" && $period > 42 )
  {
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

    if($enable_local == "True")
    {
      $regex_folder = "#(.|\n|^)+Folder local: (/(.+/)+)(.|\n|$)+#";
      $folder = preg_replace($regex_folder, "$2", $contents);

      echo $period, " into ", $folder, "\n";

      //move backup file to $folder;
    }
    else
    {
      echo "No local backup\n";
      //remove backup file;
    }
  }
  else
  {
    echo "no extern back up\n";

    if($enable_local == "True" && $period > 42)
    {
      $regex_folder = "#(.|\n|^)+Folder local: (/(.+/)+)(.|\n|$)+#";
      $folder = preg_replace($regex_folder, "$2", $contents);
      echo $period, " into ", $folder, "\n";

      /*Making a local Backup*/
      //Backup to $folder;
    }
    else
    {
      echo "no local back up\n";
    }
  }
?>
