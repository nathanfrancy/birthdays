<?php

require('data-access/dao.php');

if (isset($_GET['id'])) {
    // Get the necessary resources
    $user = getSingleUser($_GET['id']);
    $birthdays = getBirthdaysForUser($_GET['id']);

    // Allocate these different types of birthdays into arrays
    $today = $birthdays['today'];
    $upcoming = $birthdays['upcoming'];
    ?>

    <!doctype html>
    <html>
        <head>

        </head>
        <body>

            <h1><?php echo $user['name'] . "'s Birthdays";?></h1>
            
            <h3>Today's Birthdays</h3>
            <?php
                for ($i = 0; $i < count($today); $i++) {
                    echo $today[$i]['name'];
                }
            ?>
            
            <h3>Upcoming's Birthdays</h3>
            <?php
                for ($i = 0; $i < count($upcoming); $i++) {
                    echo $upcoming[$i]['name']."<br>";
                }
            ?>
            
            <h3>Other Birthdays</h3>
            <?php
                for ($i = 0; $i < count($other); $i++) {
                    echo $other[$i]['name']."<br>";
                }
            ?>

        </body>
    </html>
<?php
}
?>