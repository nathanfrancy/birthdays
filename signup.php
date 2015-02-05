<?php
/*=========================================================
 * Start the session and redirect if the user is logged in
 */
session_start();
if (isset($_SESSION['auth_id'])) { header("Location: app.php"); }

/*=========================================================
 * If there is feedback, save in variable for later
 */
$feedback = "";
$feedbackValid = false;
if (isset($_GET['feedback'])) { $feedback = $_GET['feedback']; $feedbackValid = true; }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sign In</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/vendor/css/bootswatch/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/vendor/css/signin.css" rel="stylesheet">
    
    <script src="assets/vendor/js/jquery.min.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <div class="container">
        <form class="form-signin" action="controllers/signup.php" method="post" role="form">
            <h1 class="text-center">Sign up</h1><br>
            <div class="alert alert-danger" id="loginAlert" role="alert" style="display: none;"></div>
            <input type="text" class="form-control" id="name" placeholder="Name" required autofocus>
            <input type="text" class="form-control" id="email" placeholder="Email" required>
			<input type="text" class="form-control" id="username" placeholder="Username" required>
			<input type="password" class="form-control" id="password" placeholder="Password" required>
			<button class="btn btn-lg btn-primary btn-block" id="signUpButton">Sign up</button>
			<br /><br />
            <a class="btn btn-lg btn-default btn-block" href="index.php"><span class="glyphicon glyphicon-arrow-left"></span> Back to Sign in</a>
		</form>
    </div>
</body>
    
<script>
    $("#signUpButton").click(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "controllers/signup.php",
            data: {
                controllerType: "login",
                name: $("#name").val().trim(),
                email: $("#email").val().trim(),
                username: $("#username").val().trim(),
                password: $("#password").val()
            },
            dataType: "json",
            success: function (data) {
                if (data.id !== 0) {
                    $(".form-signin").fadeOut();
                    setTimeout(
                        function () {
                            window.location = "app.php";
                        }, 500);
                } else {
                    $("#loginAlert").html(data.message);
                    $("#loginAlert").fadeIn();
                    setTimeout(
                        function () {
                            $("#loginAlert").fadeOut();
                        }, 5000);
                }
            }
        });
    });
</script>

</html>