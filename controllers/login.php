<?php
session_start();

require('../data-access/dao.php');

$controllerType = null;
if (isset($_POST['controllerType'])) {
    $controllerType = $_POST['controllerType'];
}

if ($controllerType != null) {
    
    if ($controllerType === 'login') {
		$response = null;
		
		// variable keeps track of whether data is valid
		$valid = true;
		
		// variables that checking for login, and updating $valid if one of them isn't set
		$username = $_POST['username'];
		$valid = isset($_POST['username']) && $_POST['username'] !== "";
		$password = $_POST['password'];
		$valid = isset($_POST['password']) && $_POST['password'] !== "";
		
		if ($valid) {

			/* ======================================
			Get the userid from validate() method
				If the $userid is not 0, must be valid and issue
				auth_id session variable to persist between pages
			Also update a token which will be stored as a cookie
				will not allow access if token doesn't match what is 
				stored in the user table. Thus, must have logged in directly
				to get this random token assigned to you.
			==========================================================
			*/
			$userid = validate($username, $password);
			
			if ($userid != 0) {
				$response['message'] = "valid";
				$response['id'] = $userid;
				//insertLoginAttempt("success", $userid, $username);
				$_SESSION['auth_id'] = $userid;
				//updateToken();
			}
			else {
				insertLoginAttempt("fail", 1, $username);
				$response['message'] = "Invalid username and/or password.";
				$response['id'] = 0;
			}
		}
		else {
			$response['message'] = "Missing username and/or password.";
            $response['id'] = 0;
		}
		
		echo json_encode($response);
	}
    
}