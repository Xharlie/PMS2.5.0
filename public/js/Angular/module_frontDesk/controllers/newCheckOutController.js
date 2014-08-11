/**
 * Created by Xharlie on 8/6/14.
 */
appCheckOut.controller('newCheckOutController', function($scope, $http, newCheckOutFactory){
    $scope.BookRoom = [];
    $scope.MASTER_RM_ID = "";
    $scope.currentDate = new Date();
    var pathArray = window.location.href.split("/");
    $scope.RoomNumArray = pathArray.slice(pathArray.indexOf('newCheckOut')+1);
    if ($scope.RoomNumArray[$scope.RoomNumArray.length-1][0] == 'M'){
        $scope.MASTER_RM_ID = $scope.RoomNumArray[$scope.RoomNumArray.length-1].slice(1);
        $scope.RoomNumArray = $scope.RoomNumArray.slice(0,$scope.RoomNumArray.length-1);
    }

    newCheckOutFactory.getAllInfo($scope.RoomNumArray).success(function(data){
        $scope.test = JSON.stringify(data);
        $scope.BookRoom = data;
    });
    for (var i = 0; i < $scope.RoomNumArray.length; i++){

    }
});