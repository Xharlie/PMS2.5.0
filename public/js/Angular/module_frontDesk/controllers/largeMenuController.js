/**
 * Created by Xharlie on 12/19/14.
 */

app.controller('largeMenuController',function ($scope, $http, cusModalFactory) {
    $scope.RMviewClick = "infoAction";
    $scope.nametogether = '';
    $scope.roomNumString="暂无";

    cusModalFactory.OccupiedShow($scope.owner.RM_TRAN_ID).success(function(data){
        $scope.RoomCustomers = data;

        angular.forEach($scope.RoomCustomers, function(value,index){
            $scope.nametogether +=(' '+ value.CUS_NAME);
        });

        $scope.roomInfo = [
            ["房间类型",$scope.owner.RM_TP],
            ["客人",$scope.nametogether],
            ["入住时间",$scope.owner.CHECK_IN_DT],
            ["预离时间",$scope.owner.CHECK_OT_DT],
            ["日均价",$scope.owner.RM_AVE_PRCE],
            ["押金余额",$scope.owner.DPST_RMN]
        ];

        if ($scope.owner.RSRV_PAID_DYS !=0 ){
            $scope.roomInfo.push(["预付天数",$scope.owner.RSRV_PAID_DYS]);
        }

    });



    $scope.excAction = function(actionString){
        if(actionString == '入住办理'){
            var location = "newCheckIn/checkIn/"+$scope.owner.RM_ID;
            util.openTab(location,"","",util.closeCallback);
        }else if(actionString == '房间维修'){
            cusModalFactory.Change2Mending($scope.owner.RM_ID).success(function(data){
                $scope.owner.RM_CONDITION = "维修";
                $scope.owner.menuIconAction = mendIconAction;
            });
        }else if(actionString == '维修完毕'){
            cusModalFactory.Change2Mended($scope.owner.RM_ID).success(function(data){
                $scope.owner.RM_CONDITION = "脏房";
                $scope.owner.menuIconAction = dirtIconAction;
            });
        }else if(actionString == '清洁完毕'){
            cusModalFactory.Change2Cleaned($scope.owner.RM_ID).success(function(data){
                $scope.owner.RM_CONDITION = "空房";
                $scope.owner.menuIconAction = avaIconAction;
            });
        }
    }

    $scope.close = function(owner){
        owner.blockClass.splice(owner.blockClass.indexOf($scope.blockClass),1);
    }
});