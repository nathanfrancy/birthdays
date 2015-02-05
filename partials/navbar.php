<nav class="navbar navbar-default navbar-fixed-top" role="navigation" ng-controller="NavBarController">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" ng-click="navbarCollapsed = !navbarCollapsed">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#/home">Birthday Memo</a>
        </div>
        <div class="collapse navbar-collapse" collapse="navbarCollapsed">
            <ul class="nav navbar-nav">
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#/home"><?php echo $user['name']; ?></a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>