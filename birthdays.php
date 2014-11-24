<?php

require('data-access/general.php');

$id = null;

$array_today = array();
$array_thismonth = array();
$array_other = array();

if (isset($_GET['id'])) {
    
    // Get the current id and all the birthdays
    $id = $_GET['id'];
    $birthdays = getBirthdaysForUser($id);
    
    // Get today to get today's birthdays
    $today_month = date("m");
    $today_day = date("j");
    
    // Initialize empty arrays for processing birthdays
    
    $temp_birthday = null;
    
    for ($i = 0; $i < count($birthdays); $i++) {
        // Capture all the details of this birthday
        $this_birthmonth = stripLeadingZero(date('m', $birthdays[$i]['birthdate']));
        $this_birthday = stripLeadingZero(date('j', $birthdays[$i]['birthdate']));
        $this_id = $birthdays[$i]['id'];
        $this_name = $birthdays[$i]['name'];
        $this_phonenumber = $birthdays[$i]['phonenumber'];
        
        $temp_birthday['id'] = $this_id;
        $temp_birthday['name'] = $this_name;
        $temp_birthday['birthday'] = $birthdays[$i]['birthdate'];
        $temp_birthday['phonenumber'] = $this_phonenumber;
            
        // Check if the months match
        if ($today_month == $this_birthmonth) {
            
            // Check if the months and days match (if so, today is their birthday!)
            if ($today_day == $this_birthday) {
                array_push($array_today, $temp_birthday);
            }
            else {
                // The months still match
                array_push($array_thismonth, $temp_birthday);
            }
        }
        else { // Nothing matches
            array_push($array_other, $temp_birthday);
        }
        
        $temp_birthday = null;
    }
    
}
else {
    echo "No id specified.";
}

echo "<h1>Today</h1>";
for ($i = 0; $i < count($array_today); $i++) {
    echo $array_today[$i]['name'];
}

echo "<h1>This Month</h1>";
for ($i = 0; $i < count($array_thismonth); $i++) {
    echo $array_thismonth[$i]['name'];
}

echo "<h1>Other</h1>";
for ($i = 0; $i < count($array_other); $i++) {
    echo $array_other[$i]['name'];
}


?>