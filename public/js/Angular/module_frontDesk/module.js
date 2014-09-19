var app = angular.module('Operationer',['ngRoute','ngAnimate','ui.bootstrap','ui']);
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
//        .when('/accounting',
//        {
//            controller:'accountingController',
//            templateUrl: '../app/views/accounting.blade.php'
//        })
        .when('/merchandise/:RM_ID',
        {
            controller:'merchandiseController',
            templateUrl: '../app/views/merchandise.blade.php'
        })
        .when('/customer',
        {
            controller:'customerController',
            templateUrl: '../app/views/customer.blade.php'
        })
        .when('/accounting',
        {
            controller:'accountingController',
            templateUrl: '../app/views/accounting.blade.php'
        })
        .when('/oneKeyShift',{
            controller:'oneKeyShiftController',
            templateUrl: '../app/views/oneKeyShift.blade.php'
        })
        .otherwise({redirectTo: '/roomStatus'})
    }
]);


//angular.bootstrap(document, ['Operationer']);