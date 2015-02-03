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
    
    .otherwise({redirectTo: '/home'})
});