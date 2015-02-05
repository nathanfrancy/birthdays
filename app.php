<?php 
session_start();
if (!isset($_SESSION['auth_id'])) { header("Location: index.php"); } ?>

<!DOCTYPE html>
<html lang="en" ng-app="app">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/vendor/css/bootswatch/bootstrap.css">

    <!-- Custom Fonts -->
    <link href="assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="assets/vendor/js/jquery.min.js"></script>
    
    <!-- Angular -->
    <script src="assets/vendor/js/angular.min.js"></script>
    <script src="assets/vendor/js/angular-route.min.js"></script>
    <script src="assets/vendor/js/angular-ui-bootstrap.min.js"></script>
    
    <!-- Angular custom app -->
    <script src="assets/js/app.js"></script>
    <script src="assets/js/services.js"></script>
    <script src="assets/js/controllers/HomeCtrl.js"></script>

    <!-- Angular custom services -->
    <script src="assets/js/services.js"></script>
    
</head>

<body>
    <?php include('partials/navbar.php'); ?>

	<div ng-view></div>

    <!-- Alert service -->
    <div id="alert-container" style="display: none;">
        <div class="row">
            <div class="col-sm-offset-4 col-sm-4">
                <div class="alert alert-success"><span></span></div>
            </div>
        </div>
    </div>
</body>

</html>