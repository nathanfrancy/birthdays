<?php

require('../data-access/dao.php');


$id = $_GET['id'];

$birthdays = getBirthdaysForUser($id);

echo json_encode($birthdays);


?>