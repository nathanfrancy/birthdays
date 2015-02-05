var app = angular.module('app', ['ngRoute', 'ui.bootstrap']);

app.config(function($routeProvider) {
$routeProvider
    .when('/',
        {
          redirectTo: '/home'
        })
    .when('/home',
        {
          controller: 'HomeCtrl',
          templateUrl: 'partials/home.html'
        })
    .when('/add',
        {
          controller: 'AddCtrl',
          templateUrl: 'partials/add.html'
        })
    .when('/edit/:id',
      {
          controller: 'EditCtrl',
          templateUrl: 'partials/edit.html'
      })
    
    .otherwise({redirectTo: '/home'})
});

app.controller('NavBarController', function ($scope, $log, $location) {
    $scope.navbarCollapsed = true;
    
    $scope.isActive = function (viewLocation) { 
        return $location.path().indexOf(viewLocation) == 0;
    };
    
    $scope.status = {
        isopen: false
    };

    $scope.toggled = function(open) {
        $log.log('Dropdown is now: ', open);
    };

    $scope.toggleDropdown = function($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.status.isopen = !$scope.status.isopen;
    };
});