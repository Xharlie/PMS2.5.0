/**
 * Created by Xharlie on 12/18/14.
 */

app.controller('smallMenuController',function ($scope, $http,cusModalFactory,$modal) {
    $scope.excAction = function(actionString){
        if(actionString == '入住办理'){
            var modalInstance = $modal.open({
                windowTemplateUrl: 'directiveViews/modalWindowTemplate',
                templateUrl: 'directiveViews/singleCheckInModal',
                controller: 'checkInModalController',
                resolve: {
                    roomST: function () {
                        return [$scope.owner];
                    },
                    initialString: function () {
                        return "singleWalkIn";
                    }
                }
            });
//            var location = "newCheckIn/checkIn/"+$scope.roomST.RM_ID;
//            openTab(location,"","",closeCallback);
        }else if(actionString == '房间维修'){
            cusModalFactory.Change2Mending($scope.owner.RM_ID).success(function(data){
                $scope.owner.RM_CONDITION = "维修";
                $scope.owner.menuIconAction = util.mendIconAction;
                $scope.owner.roomBlockClass[0] = "room-disabled";
            });
        }else if(actionString == '维修完毕'){
            cusModalFactory.Change2Mended($scope.owner.RM_ID).success(function(data){
                $scope.owner.RM_CONDITION = "脏房";
                $scope.owner.menuIconAction = util.dirtIconAction;
                $scope.owner.roomBlockClass[0] = "room-dirty";
            });
        }else if(actionString == '清洁完毕'){
            cusModalFactory.Change2Cleaned($scope.owner.RM_ID).success(function(data){
                $scope.owner.RM_CONDITION = "空房";
                $scope.owner.menuIconAction = util.avaIconAction;
                $scope.owner.roomBlockClass[0] = "room-empty";

            });
        }else if(actionString == '预定入住'){
            var sameID = $scope.$parent.$parent.sameID[$scope.owner.RESV_ID];
            var roomST = [];
            for (var i =0; i < sameID.length; i++){
                for (var j=0; j<sameID[i].RM_QUAN; j++){
                    var room = {RM_TP:sameID[i].RM_TP, RM_ID:"", finalPrice:sameID[i].RESV_DAY_PAY};
                    roomST.push(room);
                }
            }
            var modalInstance = $modal.open({
                windowTemplateUrl: 'directiveViews/modalWindowTemplate',
                templateUrl: 'directiveViews/multiCheckInModal',
                controller: 'MultiCheckInModalController',
                resolve: {
                    roomST: function () {
                        return roomST;
                    },
                    initialString: function () {
                        return "multiReserve";
                    },
                    RESV: function (){
                        return {RESV_ID:$scope.owner.RESV_ID, CHECK_IN_DT:$scope.owner.CHECK_IN_DT,
                            CHECK_OT_DT:$scope.owner.CHECK_OT_DT, roomSource:'预订'};
                    }
                }
            });
        }else if(actionString == '预定修改'){
            var sameID = $scope.$parent.$parent.sameID[$scope.owner.RESV_ID];
            var roomTPs = [];
            for (var i = 0; i < sameID.length; i++){
                var TP = {RESV_DAY_PAY:   util.Limit(sameID[i].RESV_DAY_PAY),
                            RESV_ID:        sameID[i].RESV_ID,
                            RESVER_PHONE:   sameID[i].RESVER_PHONE,
                            RESVER_NAME:    sameID[i].RESVER_NAME,
                            RESV_WAY:       sameID[i].RESV_WAY,
                            RESV_TMESTMP:   sameID[i].RESV_TMESTMP,
                            CHECK_IN_DT:    sameID[i].CHECK_IN_DT,
                            CHECK_OT_DT:    sameID[i].CHECK_OT_DT,
                            RESV_LATEST_TIME:sameID[i].RESV_LATEST_TIME,
                            RM_TP:          sameID[i].RM_TP,
                            RM_QUAN:        sameID[i].RM_QUAN,
                            TREATY_ID:      sameID[i].TREATY_ID,
                            MEMBER_ID:      sameID[i].MEMBER_ID,
                            RMRK:           sameID[i].RMRK,
                            STATUS:         sameID[i].STATUS,
                            PRE_PAID:       util.Limit(sameID[i].PRE_PAID)
                };
                roomTPs.push(TP);
            }
            var modalInstance = $modal.open({
                windowTemplateUrl: 'directiveViews/modalWindowTemplate',
                templateUrl: 'directiveViews/reserveModal',
                controller: 'reservationModalController',
                resolve: {
                    roomTPs: function () {
                        return roomTPs;
                    },
                    initialString: function () {
                        return "editReservation";
                    }
                }
            });
        }
    }

    $scope.close = function(owner){
        var ind =owner.blockClass.indexOf($scope.blockClass);
        if (ind >=0) owner.blockClass.splice(ind,1);
        if($scope.$parent.$parent.extraCleaner!= undefined) $scope.$parent.$parent.extraCleaner(owner);  // clean associate affected element
    }
});