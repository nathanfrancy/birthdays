<?php
// Start a session if one doesn't already exist
if ( (session_status() == PHP_SESSION_NONE) || (session_id() == '') ) {
    session_start();
}

date_default_timezone_set('America/Chicago');
set_magic_quotes_runtime(0);

require('inserts.php');
require('selects.php');


function connect_db() {
	
	$connection_array = parse_ini_file("connection.ini");
    $host = $connection_array['host'];
    $username = $connection_array['username'];
    $password = $connection_array['password'];
    $db = $connection_array['db'];

	$link = new mysqli($host, $username, $password, $db) or trigger_error($link->error);
	return $link;
}


function stripLeadingZero($str) {
    if ($str[0] === "0") {
        return $str[1];
    }
    return $str;
}

?>