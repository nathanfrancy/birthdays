app.controller('HomeCtrl', function ($scope, $log, $location, birthdayFactory, alertService) {

    birthdayFactory.getBirthdaysForUser(1)
        .success(function (data) {
        	$scope.birthdates = data;

        	$scope.todayEmpty = true;
        	$scope.upcomingEmpty = true;
        	$scope.otherEmpty = true;
            if ($scope.birthdates.upcoming.length > 0) {
            	$scope.upcomingEmpty = false;
            }
            if ($scope.birthdates.today.length > 0) {
            	$scope.todayEmpty = false;
            }
            if ($scope.birthdates.other.length > 0) {
            	$scope.otherEmpty = false;
            }
        })
        .error(function (error) {
            alertService.alert("Couldn't load birthdays.", "danger", 3);
        });
});

app.controller('AddCtrl', function ($scope, $log, $location, birthdayFactory, alertService) {

    $scope.insertBirthday = function(birthday) {
    	birthdayFactory.insertBirthday(birthday, birthday.user_id)
	        .success(function (data) {
	        	alertService.alert("Successfully inserted birthday.", "success", 3);
	        	$location.path("#/home");
	        })
	        .error(function (error) {
	            alertService.alert("Couldn't insert birthday.", "danger", 3);
	        });
    }
});