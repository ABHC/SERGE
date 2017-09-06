<?php
// Insert new user in database
$insertCol = array(array("users", $pseudo),
									array("email", $data['email']),
									array("password", $password),
									array("salt", $cryptoSalt),
									array("signup_date", time()),
									array("send_condition", 'link_limit'),
									array("mail_design", 'masterword'),
									array("record_read", 1),
									array("background_result", 'Skyscrapers'),
									array("token", $token));
$execution = insert('users_table_serge', $insertCol, '', '', $bdd);

// Read new user information in order to connect it
$checkCol = array(array("users", "=", $pseudo, "AND"),
									array("password", "=", $password, ""));
$result = read('users_table_serge', 'id, users', $checkCol, '', $bdd);
$result = $result[0];
?>
