<?php

function connect_db() {
    $host = "localhost";
    $username = "root";
    $password = "root";
    $db = "birthdays";

	$link = new mysqli($host, $username, $password, $db) or trigger_error($link->error);
	return $link;
}

?>