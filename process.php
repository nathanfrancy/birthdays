<?php

date_default_timezone_set('America/Chicago');
$today_date = date("M-j-Y h:i:s A");

$task = $_GET['task'];

if ($task !== null && $task !== "") {
    require('data-access/dao.php');

    // Get all users in the system
    $users = getAllUsers();

    // Loop through each user
    for ($u = 0; $u < count($users); $u++) {
        $user_id = $users[$u];

        // Get the necessary resources
        $user = getSingleUser($user_id);
        $birthdays = getBirthdaysForUser($user_id);

        // Allocate these different types of birthdays into arrays
        $today = $birthdays['today'];
        $upcoming = $birthdays['upcoming'];

        if ($task === "test") {
            echo "<pre>";
            echo print_r($birthdays);
            echo "</pre>";
        }

        // Email body for birthdays
        $email_body = "<!doctype html><html><body>";
        
        // Add today's birthdays into the list
        $email_body .= "<h3>Today's Birthdays</h3>";

        // Add today's birthdays into the list
        if (count($today) > 0) {
            $email_body .= "<ul>";
            for ($i = 0; $i < count($today); $i++) {
                $a = date('m-d', strtotime($today[$i]['birthday']));
                $email_body .= "<li>" . $today[$i]['name'] . " (". $a .") <a href='tel:". $today[$i]['phonenumber'] ."'>Contact</a>)</li>";
            }
            $email_body .= "</ul>";
        }
        else { $email_body .= "<i>No birthdays today.</i>"; }

        // Add this months birthdays into the list
        $email_body .= "<h3>Upcoming Birthdays</h3>";
        if (count($upcoming) > 0) {
            $email_body .= "<ul>";
            for ($i = 0; $i < count($upcoming); $i++) {
                $a = date('m-d', strtotime($upcoming[$i]['birthday']));
                $email_body .= "<li>" . $upcoming[$i]['name'] . " (". $a .")  <a href='tel:". $upcoming[$i]['phonenumber'] ."'>Contact</a></li>";
            }
            $email_body .= "</ul>";
        }
        else { $email_body .= "<i>No other birthdays this month.</i>\n\n\n<hr>"; }
        
        $email_body .= "\r\n\r\n<i>Processed: " . $today_date . "</i>";
        $email_body .= "</body></html>";

        if ($task === "test") {
            echo $email_body;
        }

        $subject = "Today's Birthdays";

        // Headers to include in the email
        $headers = "From: Birthday Reminder <nathan@nathanfrancy.com>\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $headers .= "X-Mailer: PHP/".phpversion();

        if ($task === "run") {
            $mailed = mail($user['email'], $subject, $email_body, $headers);

            if ($mailed) { echo "Mailed successfully.\n\n"; }
            else { echo "An error occurred in the email.\n\n"; }
        }
    }
}
else {
    echo "No task specified.";
}


?>