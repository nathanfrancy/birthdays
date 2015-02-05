angular.module('app').factory('birthdayFactory', ['$http', function($http) {
    var dataFactory = {};

    dataFactory.getBirthdaysForUser = function () {
        return $http.get('/api/get.php?task=getBirthdaysForUser');
    };
    dataFactory.getSingleBirthday = function (id) {
        return $http.get('/api/get.php?task=getSingleBirthday&id='+id);
    };
    dataFactory.insertBirthday = function (birthday) {
        return $http.get('/api/get.php?task=insertBirthday&name='+birthday.name+'&birthdate='+birthday.birthdate+'&phonenumber='+birthday.phonenumber);
    };
    dataFactory.editBirthday = function (birthday) {
        return $http.get('/api/get.php?task=editBirthday&id='+birthday.id+'&name='+birthday.name+'&birthdate='+birthday.birthdate+'&phonenumber='+birthday.phonenumber);
    };
    dataFactory.deleteBirthday = function (id) {
        return $http.get('/api/get.php?task=deleteBirthday&id='+id);
    };

    return dataFactory;
}]);

/* Notification service, include in in dependencies - call alertService.alert(msg, type, seconds) to push to view */
/* Temporarily using jquery to show and hide messages */
app.service('alertService', function($timeout) {
    var serv = {};
    serv.alert = function(msg, type, seconds) {
        jQuery("#alert-container .alert").removeClass("alert-success alert-danger alert-warning alert-info");
        jQuery("#alert-container .alert").addClass("alert-" + type);
        jQuery("#alert-container .alert span").html(msg);
        jQuery("#alert-container").fadeIn();
        $timeout(function() {
            jQuery("#alert-container").fadeOut();
        }, (seconds*1000) );
    }
    return serv;
});