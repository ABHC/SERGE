<?php
$req = $bdd->prepare("UPDATE users_table_serge SET add_source_status = 'Search for your link begin :' WHERE id = " . $_SESSION['id']);
$req->execute();
$req->closeCursor();
?>
