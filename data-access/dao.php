<?php
// Start a session if one doesn't already exist
if ( (session_status() == PHP_SESSION_NONE) || (session_id() == '') ) {
    session_start();
}

date_default_timezone_set('America/Chicago');
set_magic_quotes_runtime(0);

require("connection.php");

function validate($input_username, $input_password) {
    // Connect and initialize sql template
    $link = connect_db();
    $sql = "SELECT id FROM user WHERE BINARY username = ? AND BINARY password = ?";
    
    // Create prepared statement and bind passed in variables username and password
    $stmt = $link->stmt_init();
    $stmt->prepare($sql);
    $stmt->bind_param('ss', $link->real_escape_string($input_username), sha1($input_password));
    $userid = $stmt->execute();
    $userid = 0;
    $stmt->bind_result($userid);
    $stmt->fetch();
    
    mysqli_stmt_close($stmt);
    return $userid;
}

function checkPassword($try) {
    $password_encrypted = null;
    
    // Connect and initialize sql and prepared statement template
    $link = connect_db();
    $sql = "SELECT password FROM user WHERE id = ? LIMIT 1";
    $stmt = $link->stmt_init();
    $stmt->prepare($sql);
    $stmt->bind_param('i', $_SESSION['auth_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    // bind the result to $theBook for json encoding
    while ($row = $result->fetch_array(MYSQLI_BOTH)) {
        $password_encrypted = $row['password'];
    }
    mysqli_stmt_close($stmt);

    if (sha1($try) === $password_encrypted) {
        return TRUE;
    }
    return FALSE;
}

function getSingleBirthday($id) {
    $birthday = null;
    
    // Connect and initialize sql and prepared statement template
    $link = connect_db();
    $sql = "SELECT * FROM `birthdates` WHERE id = ? LIMIT 1";
    $stmt = $link->stmt_init();
    $stmt->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_array(MYSQLI_BOTH)) {
        $birthday['id'] = $row['id'];
        $birthday['name'] = $row['name'];
        $birthday['birthdate'] = $row['birthdate'];
        $birthday['phonenumber'] = $row['phonenumber'];
        $birthday['user_id'] = $row['user_id'];
    }
    mysqli_stmt_close($stmt);
    return $birthday;
}

function insertBirthday($name, $birthdate, $phonenumber) {
	$link = connect_db();
	$sql = "INSERT INTO  `birthdates` (`name`, `birthdate`, `phonenumber`, `user_id`) VALUES (?,?,?,?)";
	$stmt = $link->stmt_init();
	$stmt->prepare($sql);
	$stmt->bind_param('sssi', 
                      $link->real_escape_string($name), 
                      $link->real_escape_string($birthdate), 
                      $link->real_escape_string($phonenumber), 
                      $_SESSION['auth_id']);
	$stmt->execute();
	$id = $link->insert_id;
	mysqli_stmt_close($stmt);
	$link->close();
	
	return $id;
}

function editBirthday($id, $name, $birthdate, $phonenumber) {
    $link = connect_db();
    $sql = "UPDATE `birthdates` SET `name`=?, `birthdate`=?, `phonenumber`=? WHERE `id` = ?";
    $stmt = $link->stmt_init();
    $stmt->prepare($sql);
    $stmt->bind_param('sssi', 
                      $link->real_escape_string($name), 
                      $link->real_escape_string($birthdate), 
                      $link->real_escape_string($phonenumber), 
                      $id);
    $stmt->execute();
    mysqli_stmt_close($stmt);
    $link->close();
    
    return $id;
}

function deleteBirthday($id) {
    $link = connect_db();
    $sql = "DELETE FROM `birthdates` WHERE `id` = ?";
    $stmt = $link->stmt_init();
    $stmt->prepare($sql);
    $stmt->bind_param('i',
                      $id);
    $stmt->execute();
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

function createNewUser($name, $email, $username, $password) {
    //$first_token = generate_random_string(50);
    $link = connect_db();
    $sql = "INSERT INTO  `user` (`name`, `email`, `username`, `password`) VALUES (?, ?, ?, ?)";
    $stmt = $link->stmt_init();
    $stmt->prepare($sql);
    $stmt->bind_param('ssss', 
                      $link->real_escape_string($name), 
                      $link->real_escape_string($email), 
                      $link->real_escape_string($username), 
                      $link->real_escape_string(sha1($password)));
    $stmt->execute();
    $id = $link->insert_id;
    mysqli_stmt_close($stmt);
    $link->close();

    if ($id !== 0) {
        $_SESSION['auth_id'] = $id;
        //set_cookie('auth_token', $first_token);
    }
    
    return $id;
}


function getBirthdaysForUser($user_id) {
    $response = null;
    $array_today = array();
    $array_upcoming = array();
    $array_other = array();

    // Get the current id and all the birthdays
    $birthdays = array();

    $link = connect_db();
    //$sql = "SELECT * FROM `birthdates` WHERE `user_id` = ? ORDER BY MONTH(birthdate), DAYOFMONTH(birthdate)";
    $sql = "SELECT * 
,CASE WHEN BirthdayThisYear>=NOW() THEN BirthdayThisYear ELSE BirthdayThisYear + INTERVAL 1 YEAR END AS NextBirthday
FROM (
    SELECT * 
    ,birthdate - INTERVAL YEAR(birthdate) YEAR + INTERVAL YEAR(NOW()) YEAR AS BirthdayThisYear
    FROM birthdates WHERE user_id = ?
) AS bdv
ORDER BY NextBirthday";

    $stmt = $link->stmt_init();
    $stmt->prepare($sql);
    $stmt->bind_param('i', $user_id);
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