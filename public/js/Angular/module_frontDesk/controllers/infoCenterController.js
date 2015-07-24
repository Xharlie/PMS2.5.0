/**
 * Created by charlie on 7/24/15.
 */
app.controller('infoCenterController', function($scope, $http, focusInSideFactory,newCheckInFactory, paymentFactory,roomFactory,
                                                  $modalInstance,$timeout, roomST,initialString) {

    /********************************************     validation     ***************************************************/
    $scope.hasError = function (btnPass) {
        if (eval("$scope." + btnPass) == null) eval("$scope." + btnPass + "=0");
        eval("$scope." + btnPass + "++");
    }
    $scope.noError = function (btnPass) {
        eval("$scope." + btnPass + "--");
    }
    $scope.payError = 0;
    /********************************** simulate select **************************************/
    //$scope.selectValue = roomFactory.selectValue;

    /********************************************     utility     ***************************************************/

    var today = new Date();
    var tomorrow = new Date(today.getTime() + 86400000);
    $scope.dateTime = new Date((tomorrow).setHours(12, 0, 0));
    $scope.dateFormat = function (rawDate) {
        return util.dateFormat(rawDate);
    }

    var initRoomsAndRoomTypes = function (exceptedRM_ID) {
        for (var i = 0; i < $scope.RoomAllinfo.length; i++) {
            var RM_TP = $scope.RoomAllinfo[i]["RM_TP"];
            if ($scope.RoomAllinfo[i].RM_CONDITION != "空房" && $scope.RoomAllinfo[i].RM_ID != exceptedRM_ID) continue;
            if ($scope.roomsAndRoomTypes[RM_TP] == undefined) {
                $scope.roomsAndRoomTypes[RM_TP] = [$scope.RoomAllinfo[i]];
            } else {
                $scope.roomsAndRoomTypes[RM_TP].push($scope.RoomAllinfo[i]);
            }
            $scope.roomsDisableList[$scope.RoomAllinfo[i].RM_ID] = false;  // all room enabled
        }
    }
})