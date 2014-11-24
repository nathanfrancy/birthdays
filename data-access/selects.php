<?php

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
        $user['phonenumber'] = $row['phonenumber'];
        array_push($users, $user);
        $user = null;
    }

    mysqli_stmt_close($stmt);
    
    return $users;
}


function getBirthdaysForUser($userid) {
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
    
    return $birthdays;
}

?>