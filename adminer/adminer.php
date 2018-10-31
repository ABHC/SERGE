<?php
/*
Adminer is a script aimed to automate tasks
Adminer will make back up of your database, delete user who ask to be removed,
delete history of user, delete logs and look for new update for Serge.
All these functions are split into different files in order to have a better understanding.
*/

//Adding connection and functions
include('/var/www/Serge/web/model/connection_sql.php');
include('/var/www/Serge/web/model/read.php');
include('/var/www/Serge/web/model/update.php');


include('databaseBackUp.php');

include('deleteUser.php');

//deleteHistory.php needs a variable from deleteUser.php
//So deleteHistory.php must be after deleteUser.php
include('deleteHistory.php');

include('deleteLogs.php');

include('autoUpdate.php');

?>
