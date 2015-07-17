var app = angular.module('Operationer',['ngRoute','ngAnimate','ui.bootstrap']); //,''ui','ui.bootstrap.position'
app.config(['$routeProvider',function ($routeProvider){
    $routeProvider
        .when('/roomStatus',
        {
            controller:'roomStatusController',
            templateUrl: '../app/views/roomStatus.php'
        })
        .when('/reservation',
        {
            controller:'reservationController',
            templateUrl: '../app/views/reservation.blade.php'
        })
        .when('/merchandise/:RM_ID',
        {
            controller:'merchandiseController',
            templateUrl: '../app/views/merchandise.blade.php'
        })
        .when('/merchandiseHisto/:RM_ID',
        {
            controller:'merchandiseHistoController',
            templateUrl: '../app/views/merchandiseHisto.blade.php'
        })
        .when('/customer',
        {
            controller:'customerController',
            templateUrl: '../app/views/customer.blade.php'
        })
        .when('/accounting/',
        {
            controller:'accountingController',
            templateUrl: '../app/views/accounting.blade.php'
        })
        .when('/oneKeyShift',{
            controller:'oneKeyShiftController',
            templateUrl: '../app/views/oneKeyShift.blade.php'
        })
        .when('/settingRoomTp',{
            controller:'settingRoomTpController',
            templateUrl: '../app/views/settingRoomTp.blade.php'
        })
        .when('/settingRooms',{
            controller:'settingRoomsController',
            templateUrl: '../app/views/settingRooms.blade.php'
        })
        .when('/settingTempRoom',{
            controller:'settingTempRoomController',
            templateUrl: '../app/views/settingTempRoom.blade.php'
        })
        .otherwise({redirectTo: '/roomStatus'})
    }
]).config(['$tooltipProvider', function($tooltipProvider){
        $tooltipProvider.setTriggers({'openEvent': 'closeEvent'});   // dynamically open or close popover
    }]);


app.filter('paginate', function() {
    return function(input, start, number) {
        if (!input || !input.length) { return; }
        start = parseInt(start, 10);
        return input.slice(start,start+number);
    };
});

app.filter('unique', function() {
    return function(input, key) {
        var unique = {};
        var uniqueList = [];
        for(var i = 0; i < input.length; i++){
            if(typeof unique[input[i][key]] == "undefined"){
                unique[input[i][key]] = "";
                uniqueList.push(input[i]);
            }
        }
        return uniqueList;
    };
});


app.filter('orderObjectByLex', function() {
    return function(items, field, reverse) {
        var filtered = [];
        angular.forEach(items, function(item) {
            filtered.push(item);
        });
        filtered.sort(function (a, b) {
            return (a[field] > b[field] ? 1 : -1);
        });
        if(reverse) filtered.reverse();
        return filtered;
    };
});

app.filter('orderObjectByNum', function() {
    return function(items, field, reverse) {
        var filtered = [];
        angular.forEach(items, function(item) {
            filtered.push(item);
        });
        filtered.sort(function (a, b) {
            return (parseFloat(a[field]) > parseFloat(b[field]) ? 1 : -1);
        });
        if(reverse) filtered.reverse();
        return filtered;
    };
});