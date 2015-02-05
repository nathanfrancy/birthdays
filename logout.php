<?php

// Continue the current session
session_start();

// If auth_id variable is set, destroy it
if (isset($_SESSION['auth_id'])) {
	unset($_SESSION['auth_id']);
}

// If auth_token cookie is set, destroy it
if (isset($_COOKIE['auth_token'])) {
	unset($_COOKIE['auth_token']);
}

// As if destroying the session var and cookie isn't enough,
// go ahead and destroy the entire session too
session_destroy();

// Redirect to login page
header("Location: index.php");

?>