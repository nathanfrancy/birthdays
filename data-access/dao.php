<?php
// Start a session if one doesn't already exist
if ( (session_status() == PHP_SESSION_NONE) || (session_id() == '') ) {
    session_start();
}

date_default_timezone_set('America/Chicago');
set_magic_quotes_runtime(0);


function connect_db() {
	
	$connection_array = parse_ini_file("connection.ini");
    $host = $connection_array['host'];
    $username = $connection_array['username'];
    $password = $connection_array['password'];
    $db = $connection_array['db'];

	$link = new mysqli($host, $username, $password, $db) or trigger_error($link->error);
	return $link;
}

function insertBirthday($name, $birthdate, $phonenumber, $user_id) {
	$link = connect_db();
	$sql = "INSERT INTO  `birthdates` (`name`, `birthdate`, `phonenumber`, `user_id`) VALUES (?,?,?,?)";
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
	
	return $id;
}


function getAllUsers() {
    $users = array();

    $link = connect_db();
    $sql = "SELECT * FROM `user` ORDER BY `username`";
    $stmt = $link->stmt_init();
    $stmt->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $user = null;

    while ($row = $result->fetch_array(MYSQLI_BOTH)) {
        $user['id'] = $row['id'];
        $user['name'] = $row['name'];
        $user['username'] = $row['username'];
        $user['email'] = $row['email'];
        array_push($users, $user);
        $user = null;
    }

    mysqli_stmt_close($stmt);
    
    return $users;
}

function getSingleUser($id) {
	$user = null;
	
	// Connect and initialize sql and prepared statement template
	$link = connect_db();
	$sql = "SELECT * FROM `user` WHERE id = ? LIMIT 1";
	$stmt = $link->stmt_init();
	$stmt->prepare($sql);
	$stmt->bind_param('i', $id);
	$stmt->execute();
	$result = $stmt->get_result();
    
	while ($row = $result->fetch_array(MYSQLI_BOTH)) {
		$user['id'] = $row['id'];
        $user['name'] = $row['name'];
        $user['username'] = $row['username'];
        $user['email'] = $row['email'];
    }
	mysqli_stmt_close($stmt);
	
	return $user;
}


function getBirthdaysForUser($userid) {
    $response = null;
    $array_today = array();
    $array_upcoming = array();
    $array_other = array();

    // Get the current id and all the birthdays
    $birthdays = array();

    $link = connect_db();
    $sql = "SELECT * FROM `birthdates` WHERE `user_id` = ?";
    $stmt = $link->stmt_init();
    $stmt->prepare($sql);
    $stmt->bind_param('i', $userid);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_array(MYSQLI_BOTH)) {
        $birthday['id'] = $row['id'];
        $birthday['name'] = $row['name'];
        $birthday['birthdate'] = $row['birthdate'];
        $birthday['phonenumber'] = $row['phonenumber'];
        array_push($birthdays, $birthday);
        $birthday = null;
    }
    mysqli_stmt_close($stmt);

    // Get today to get today's birthdays
    $today_month = date("m");
    $today_day = date("j");

    // Initialize empty arrays for processing birthdays

    $temp_birthday = null;
    for ($i = 0; $i < count($birthdays); $i++) {

        // Capture all the details of this birthday
        $this_birthmonth = date('m', strtotime($birthdays[$i]['birthdate']));
        $this_birthday = date('j', strtotime($birthdays[$i]['birthdate']));

        $this_id = $birthdays[$i]['id'];
        $this_name = $birthdays[$i]['name'];
        $this_phonenumber = $birthdays[$i]['phonenumber'];

        $temp_birthday['id'] = $this_id;
        $temp_birthday['name'] = $this_name;
        $temp_birthday['birthday'] = $birthdays[$i]['birthdate'];
        $temp_birthday['phonenumber'] = $this_phonenumber;

        // Check if today is the birthday
        if ( ($today_day == $this_birthday) && ($today_month == $this_birthmonth) ) {
            array_push($array_today, $temp_birthday);
        }
        // Check if this month
        else if ($today_month == $this_birthmonth) {
            // Make sure the birthday hasn't already passed
            if ($this_birthday > $today_day) {
                array_push($array_upcoming, $temp_birthday);
            }
        }
        else if ( $today_month+1 == $this_birthmonth) {
            array_push($array_upcoming, $temp_birthday);
        }
        else {
            array_push($array_other, $temp_birthday);
        }

        $temp_birthday = null;
    }

    // Add today's birthdays to response
    $response['today'] = $array_today;

    // Add upcoming birthdays to response
    $response['upcoming'] = $array_upcoming;

    // Add other birthdays to response
    $response['other'] = $array_other;
    
    return $response;
}

?>