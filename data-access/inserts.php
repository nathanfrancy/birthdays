<?php

function insertBirthday($name, $birthdate, $phonenumber, $user_id) {
	$link = connect_db();
	$sql = "INSERT INTO  `birthdates` (`name`, `birthdate`, `phonenumber`, `user_id`) VALUES ()";
	$stmt = $link->stmt_init();
	$stmt->prepare($sql);
	$stmt->bind_param('sssi', 
                      $link->real_escape_string($name), 
                      $link->real_escape_string($birthdate), 
                      $link->real_escape_string($phonenumber), 
                      $user_id);
	$stmt->execute();
	$id = $link->insert_id;
	mysqli_stmt_close($stmt);
	$link->close();
	
	return "true";
}


?>