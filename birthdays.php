<?php

require('data-access/dao.php');

if (isset($_GET['id'])) {
    // Get the necessary resources
    $user = getSingleUser($_GET['id']);
    $birthdays = getBirthdaysForUser($_GET['id']);

    // Allocate these different types of birthdays into arrays
    $today = $birthdays['today'];
    $this_month = $birthdays['this_month'];
    $other = $birthdays['other'];
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
            
            <h3>This Month's Birthdays</h3>
            <?php
                for ($i = 0; $i < count($this_month); $i++) {
                    echo $this_month[$i]['name'];
                }
            ?>
            
            <h3>Other Birthdays</h3>
            <?php
                for ($i = 0; $i < count($other); $i++) {
                    echo $other[$i]['name'];
                }
            ?>

        </body>
    </html>
<?php
}
?>