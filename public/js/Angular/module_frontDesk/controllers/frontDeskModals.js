app.controller('roomModalInstanceCtrl',function ($scope, $modalInstance, cusModalFactory, $http, roomST) {

    $scope.roomST =roomST;
    $scope.RMviewClick = "infoAction";
    $scope.nametogether = '';
    $scope.roomNumString="暂无";
    if ($scope.roomST.RM_CONDITION =="Occupied"){

        cusModalFactory.OccupiedShow($scope.roomST.RM_TRAN_ID).success(function(data){
            $scope.RoomCustomers = data;

            angular.forEach($scope.RoomCustomers, function(value,index){
                $scope.nametogether +=(' '+ value.CUS_NAME);
            });

            $scope.roomInfo = [
                ["房间类型",$scope.roomST.RM_TP],
                ["客人",$scope.nametogether],
                ["入住时间",$scope.roomST.CHECK_IN_DT],
                ["预离时间",$scope.roomST.CHECK_OT_DT],
                ["日均价",$scope.roomST.RM_AVE_PRCE],
                ["押金余额",$scope.roomST.DPST_RMN]
            ];

            if (roomST.RSRV_PAID_DYS !=0 ){
                $scope.roomInfo.push(["预付天数",roomST.RSRV_PAID_DYS]);
            }

        });
        $scope.roomAction = [['基本信息','basicInfo'],["账单查看",'viewAccounting'],["叫醒服务",'wakeUp'],["退房办理",'checkOut'],
            ["商品购买",'shopping']];
    }else if ($scope.roomST.RM_CONDITION =="Empty"){
        cusModalFactory.EmptyShow($scope.roomST.RM_TP).success(function(data){
            $scope.RoomTP = data;
            $scope.roomInfo = [
                ["房间类型",$scope.roomST.RM_TP],
                ["建议价",$scope.RoomTP[0].SUGG_PRICE+'元']
            ];
        });
        $scope.roomAction = [["入住办理",'checkIn'],["房间维修",'mending']];
    }else if ($scope.roomST.RM_CONDITION =="Mending"){
        cusModalFactory.EmptyShow($scope.roomST.RM_TP).success(function(data){
            $scope.RoomTP = data;
            $scope.roomInfo = [
                ["房间类型",$scope.roomST.RM_TP],
                ["建议价",$scope.RoomTP[0].SUGG_PRICE+'元']
            ];
        });
        $scope.roomAction = [["入住办理",'checkIn'],["维修完毕",'mended']];
    }else if ($scope.roomST.RM_CONDITION =="Preparing"){
        cusModalFactory.EmptyShow($scope.roomST.RM_TP).success(function(data){
            $scope.RoomTP = data;
            $scope.roomInfo = [
                ["房间类型",$scope.roomST.RM_TP],
                ["建议价",$scope.RoomTP[0].SUGG_PRICE+'元']
            ];
        });
        $scope.roomAction = [["入住办理",'checkIn'],["清洁完毕",'cleaned']];
    }

    $scope.excAction = function(actionString){
        if(actionString == 'checkIn'){
            var location = "newCheckIn/"+$scope.roomST.RM_ID;
            window.open(location);
        }else if(actionString == 'shopping'){
            var location = "#/merchandise/"+$scope.roomST.RM_ID;
            window.open(location);
        }else if(actionString == 'mending'){
            cusModalFactory.Change2Mending($scope.roomST.RM_ID).success(function(data){
                window.location.reload();
            });
        }else if(actionString == 'mended'){
            cusModalFactory.Change2Mended($scope.roomST.RM_ID).success(function(data){
                window.location.reload();
            });
        }else if(actionString == 'cleaned'){
            cusModalFactory.Change2Cleaned($scope.roomST.RM_ID).success(function(data){
                window.location.reload();
            });
        }else if(actionString == 'viewAccounting'){
            cusModalFactory.getAccounting($scope.roomST.RM_TRAN_ID).success(function(data){
                $scope.AcctDepo = data[0];
                $scope.AcctPay = data[1];
                $scope.AcctStore = data[2];
                $scope.RMviewClick = "infoAccounting";
            });
        }else if(actionString == 'basicInfo'){
            $scope.RMviewClick = "infoAction";
        }else if(actionString == 'checkOut'){
            cusModalFactory.getConnect($scope.roomST.RM_TRAN_ID).success(function(data){
                if(data == "null"){
                    var location = "newCheckOut/"+$scope.roomST.RM_TRAN_ID;
                    window.open(location);
                }else{
                    $scope.CurrentMaster = data[0];

                    $scope.ConnRooms = data;
                    $scope.RMviewClick = "connectCheckOut";
                }

            //var location = "newCheckIn/"+$scope.roomST.RM_ID;
            //window.open(location);
            });
        }

    }

    var getRoomNames = function(){
        $scope.roomNumString ="";
        for (var i = 0; i< $scope.outRoomPool.length; i++){
            $scope.roomNumString = $scope.roomNumString +" "+$scope.outRoomPool[i]["RM_ID"];
        }
        if ($scope.roomNumString == ""){
            $scope.roomNumString ="暂无";
        }
    }

    $scope.outRoomPool = [];
    $scope.checkItOut = function(room){
        if (room.checkRoom["border"] == undefined){
            $scope.outRoomPool.push(room);
            getRoomNames();
            room.checkRoom["border"] = "2px solid #FF9933";
            if($scope.ConnRooms.indexOf(room) == 0){
                for (var i = 1; i < $scope.ConnRooms.length; i++){
                    if($scope.ConnRooms[i].checkRoom["border"] == undefined){
                        var temp = JSON.parse(JSON.stringify(room));
                        $scope.ConnRooms[0]= JSON.parse(JSON.stringify($scope.ConnRooms[i]));
                        $scope.ConnRooms[i] = temp;
                        return;
                    }
                }
         //       var temp = JSON.parse(JSON.stringify(room));
         //       $scope.ConnRooms[0]= JSON.parse(JSON.stringify($scope.ConnRooms[i]));
         //       $scope.ConnRooms[i] = temp;
         //       return;
         //       $scope.ConnRooms[0] = $scope.CurrentMaster
            }
        }else{
            delete room.checkRoom["border"];
            $scope.outRoomPool.splice($scope.outRoomPool.indexOf(room),1);
            getRoomNames();
            var j = $scope.ConnRooms.indexOf(room);
            if( j != 0){
                if($scope.ConnRooms[0].checkRoom["border"] != undefined){
                    var temp = JSON.parse(JSON.stringify($scope.ConnRooms[0]));
                    $scope.ConnRooms[0]= JSON.parse(JSON.stringify(room));
                    $scope.ConnRooms[j] = temp;
                }
            }
        }
    }


    $scope.PayMethod = function(method){
        switch (method){
            case "cash":
                return "现金";
                break;
            case "debit":
                return "储蓄卡";
                break;
            case "credit":
                return "信用卡";
                break;
            default:
                return "未知";
        }
    }

    $scope.cancelConnCheckOut = function(){
        $modalInstance.dismiss('cancel');
    }

    $scope.confirmConnCheckOut = function(){
        if ($scope.outRoomPool.length == 0){
            return;
        }
        var roomsString = "";
        for (var i = 0; i < $scope.outRoomPool.length; i++){
            roomsString += "/" + $scope.outRoomPool[i].RM_TRAN_ID;
        }
        if($scope.ConnRooms[0]["RM_TRAN_ID"] != $scope.CurrentMaster["CONN_RM_TRAN_ID"]){
            roomsString += "/M" + $scope.ConnRooms[0].RM_TRAN_ID;
        }
        var location = "newCheckOut"+roomsString;
        window.open(location);
    };

});


app.controller('connectRMModalInstanceCtrl',function ($scope, $modalInstance, cusModalFactory, $http, connectRooms) {
    $scope.rooms = connectRooms;
    $scope.confirmConn = function(){
        var roomsString = "";
        for (var i = 0; i < connectRooms.length; i++){
            roomsString += ("/"+connectRooms[i].RM_ID);
        }
        var location = "newCheckIn"+roomsString;
        window.open(location);
    };

    $scope.cancelConn = function(){
        $modalInstance.dismiss('cancel');
    }
});
