<?php
$result = $bdd->query('SELECT add_source_status FROM users_table_serge WHERE id = ' . $_SESSION['id']);
$status = $result->fetch();
$status = $status['add_source_status'];
$result->closeCursor();
?>
