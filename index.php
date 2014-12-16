<?php

require_once('data-access/dao.php');

$connection = connect_db();

if ($connection != null) {
    $users = getAllUsers();
    ?>

    <h1>Birthdays</h1>
    
    <?php
    for ($i = 0; $i < count($users); $i++) {
        echo '<a href="birthdays.php?id='. $users[$i]["id"]. '">'. $users[$i]['name'] . '</a><br>';
    }

}
else {
    echo "Database issue. No birthdays for you today sucka.";
}