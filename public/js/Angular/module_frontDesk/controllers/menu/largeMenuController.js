/**
 * Created by Xharlie on 12/19/14.
 */

app.controller('largeMenuController',function ($scope, $http, $modal, cusModalFactory) {
    $scope.RMviewClick = "infoAction";
    $scope.nametogether = '';
    $scope.roomNumString="暂无";

    //cusModalFactory.OccupiedShow($scope.owner.RM_TRAN_ID).success(function(data){
    //      $scope.RoomCustomers = data;
    //});
    $scope.nametogether="";
    angular.forEach($scope.owner.customers, function(value,index){
        $scope.nametogether +=(' '+ value.CUS_NAME);
    });
    $scope.roomInfo = [
        ["房间类型",$scope.owner.RM_TP],
        ["客人",$scope.nametogether],
        ["入住时间",$scope.owner.CHECK_IN_DT],
        ["预离时间",$scope.owner.CHECK_OT_DT],
        ["日均价",$scope.owner.RM_AVE_PRCE],
        ["押金余额",$scope.owner.CONN_DPST_RMN]
    ];


    if ($scope.owner.RSRV_PAID_DYS !=0 ){
        $scope.roomInfo.push(["预付天数",$scope.owner.RSRV_PAID_DYS]);
    }



    $scope.excAction = function(actionString){
        if(actionString == '信息修改'){
            var modalInstance = $modal.open({
                windowTemplateUrl: 'directiveViews/modalWindowTemplate',
                templateUrl: 'directiveViews/singleCheckInModal',
                controller: 'checkInModalController',
                resolve: {
                    roomST: function () {
                        return [$scope.owner];          // leave flexibility to have multiple parameters or rooms
                    },
                    initialString: function () {
                        return "editRoom";
                    }
                }
            }).result.then(function(data) {
                    $scope.updateAllRoom();
            });
        }else if(actionString == '退房办理'){
            var modalInstance = $modal.open({
                windowTemplateUrl: 'directiveViews/modalWindowTemplate',
                templateUrl: 'directiveViews/checkOutModal',
                controller: 'checkOutModalController',
                resolve: {
                    connRM_TRAN_IDs: function () {
                        return $scope.owner.connRM_TRAN_IDs;          // leave flexibility to have multiple parameters or rooms
                    },
                    initialString: function () {
                        return "checkOut";
                    },
                    RM_TRAN_IDFortheRoom: function() {
                        return $scope.owner.RM_TRAN_ID;
                    },
                    ori_Mastr_RM_TRAN_ID: function() {
                        return $scope.owner.CONN_RM_TRAN_ID;
                    }
                }
            }).result.then(function(data) {
                    $scope.updateAllRoom();
            });
        }else if(actionString == '入押金'){
            var modalInstance = $modal.open({
                windowTemplateUrl: 'directiveViews/modalWindowTemplate',
                templateUrl: 'directiveViews/depositModal',
                controller: 'depositModalController',
                resolve: {
                    roomST: function () {
                        return [$scope.owner];          // leave flexibility to have multiple parameters or rooms
                    },
                    initialString: function () {
                        return "singleDepositIn";
                    }
                }
            }).result.then(function(data) {
                    $scope.updateAllRoom();
            });
        }else if(actionString == '账目查看'){
            var modalInstance = $modal.open({
                windowTemplateUrl: 'directiveViews/modalWindowTemplate',
                templateUrl: 'directiveViews/checkOutModal',
                controller: 'checkOutModalController',
                resolve: {
                    connRM_TRAN_IDs: function () {
                        return $scope.owner.connRM_TRAN_IDs;          // leave flexibility to have multiple parameters or rooms
                    },
                    initialString: function () {
                        return "checkLedger";
                    },
                    RM_TRAN_IDFortheRoom: function() {
                        return $scope.owner.RM_TRAN_ID;
                    },
                    ori_Mastr_RM_TRAN_ID: function() {
                        return $scope.owner.CONN_RM_TRAN_ID;
                    }
                }
            }).result.then(function(data) {
                    $scope.updateAllRoom();
            });
        }else if(actionString == '商品购买'){
            angular.element('#largeMenu').remove();
            window.location.href = '#/merchandise/:' + $scope.owner.RM_TRAN_ID;
        }
    }

    $scope.editWakeUp = function(WKC_TSTMP){
        var initialString ='editWakeUp';
        if(WKC_TSTMP == null){
            initialString = 'initWakeUp';
        }
        var modalInstance = $modal.open({
            windowTemplateUrl: 'directiveViews/modalWindowTemplate',
            templateUrl: 'directiveViews/wakeUpCallModal',
            controller: 'wakeUpCallModalController',
            resolve: {
                initialString: function () {
                    return initialString;
                },
                RM_TRAN_IDFortheRoom: function() {
                    return $scope.owner.RM_TRAN_ID;
                },
                WKC_TSTMP: function() {
                    return WKC_TSTMP;
                }
            }
        }).result.then(function(data) {
                $scope.updateAllRoom();
        });
    }

    $scope.close = function(owner){
        owner.blockClass.splice(owner.blockClass.indexOf($scope.blockClass),1);
    }
});