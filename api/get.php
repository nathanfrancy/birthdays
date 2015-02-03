<?php

require('../data-access/dao.php');

$data = json_decode(file_get_contents("php://input"));

$response = null;

$task = $_GET['task'];
$id = $_GET['id'];

if (isset($_GET['task'])) {
	$task = $_GET['task'];
	if ($task !== null && $task !== "") {

		/* Begin if statements determining what to do */
		if ($task == "getBirthdaysForUser") {
			$response = getBirthdaysForUser($id);
		}
		else if ($task == "insertBirthday") {
			if ($_GET['name'] !== "" && $_GET['birthdate'] !== "" && $_GET['phonenumber'] !== "" && $_GET['user_id'] !== "")
			$response = insertBirthday($_GET['name'], $_GET['birthdate'], $_GET['phonenumber'], $_GET['user_id']);
		}
		else {
			$response['message'] = "Invalid task.";
		}
	}
}
else {
	$response['message'] = "Task is not set.";
}

echo json_encode($response);
?>