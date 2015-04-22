app.controller('Datepicker', function($scope){

    $scope.minDate = new Date();
    $scope.minDate2 = new Date($scope.minDate.getTime()+86400000);

    $scope.twoDaysBefore = new Date($scope.minDate.getTime()-200*86400000);
    // Disable weekend selection
    $scope.disabled = function(date, mode) {
        return false; //( mode === 'day' && ( date.getDay() === 0 || date.getDay() === 6 ) );
    };

    $scope.open1 = function($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.opened1 = true;
    };

    $scope.open2 = function($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.opened2 = true;
    };

    $scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };

    $scope.format = "yyyy-MM-dd";

});



app.controller('TimePickerDemoCtrl', function ($scope) {
    $scope.chineseM =["上午","下午"]
    $scope.ismeridian = true;

});