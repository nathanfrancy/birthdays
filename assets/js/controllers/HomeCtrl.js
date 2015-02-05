app.controller('HomeCtrl', function ($scope, $log, $location, birthdayFactory, alertService) {

    birthdayFactory.getBirthdaysForUser()
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

    $scope.deleteBirthday = function(birthday) {
        birthdayFactory.deleteBirthday(birthday.id)
            .success(function (data) {
                alertService.alert("Successfully deleted birthday.", "success", 3);
                $location.path("#/home");
            })
            .error(function (error) {
                alertService.alert("Couldn't delete birthday.", "danger", 3);
            });
    }
});

app.controller('AddCtrl', function ($scope, $log, $location, birthdayFactory, alertService) {

    $scope.insertBirthday = function(birthday) {
        birthdayFactory.insertBirthday(birthday)
            .success(function (data) {
                alertService.alert("Successfully inserted birthday.", "success", 3);
                $location.path("#/home");
            })
            .error(function (error) {
                alertService.alert("Couldn't insert birthday.", "danger", 3);
            });
    }
});

app.controller('EditCtrl', function ($scope, $log, $location, $routeParams, birthdayFactory, alertService) {
    birthdayFactory.getSingleBirthday($routeParams.id)
        .success(function (data) {
            $scope.birthday = data;
        })
        .error(function (error) {
            alertService.alert("Couldn't load birthday.", "danger", 3);
        });

    $scope.submitEdit = function(birthday) {
        birthdayFactory.editBirthday(birthday)
            .success(function (data) {
                alertService.alert("Successfully edited birthday.", "success", 3);
                $location.path("#/home");
            })
            .error(function (error) {
                alertService.alert("Couldn't save birthday.", "danger", 3);
            });
    }

    $scope.deleteBirthday = function(birthday) {
        birthdayFactory.deleteBirthday(birthday.id)
            .success(function (data) {
                alertService.alert("Successfully deleted birthday.", "success", 3);
                $location.path("#/home");
            })
            .error(function (error) {
                alertService.alert("Couldn't delete birthday.", "danger", 3);
            });
    }
});