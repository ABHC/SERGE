<?php
        // Check email and password
        $req = $bdd->prepare('SELECT id FROM users_table_serge WHERE email = :pseudo AND password = :pass');
        $req->execute(array(
            'pseudo' => $pseudo,
            'pass' => $password));
        $result = $req->fetch();
	$req->closeCursor();
?>
