app.controller('roomModalInstanceCtrl',function ($scope, $modalInstance, cusModalFactory, $http, roomST) {
    $scope.ngSetRoomClass = function(roomST){

        switch(roomST.RM_CONDITION){
            case '空房':
                return "room-empty";
            case '有人':
                return "room-full";
            case '脏房':
                return "room-dirty";
            case '维修':
                return"room-disabled";
        }
    };
    $scope.roomST =roomST;
    $scope.RMviewClick = "infoAction";
    $scope.nametogether = '';
    $scope.roomNumString="暂无";
    if ($scope.roomST.RM_CONDITION =="有人"){

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
    }else if ($scope.roomST.RM_CONDITION =="空房"){
        cusModalFactory.EmptyShow($scope.roomST.RM_TP).success(function(data){
            $scope.RoomTP = data;
            $scope.roomInfo = [
                ["房间类型",$scope.roomST.RM_TP],
                ["建议价",$scope.RoomTP[0].SUGG_PRICE+'元']
            ];
        });
        $scope.roomAction = [["入住办理",'checkIn'],["房间维修",'mending']];
    }else if ($scope.roomST.RM_CONDITION =="维修"){
        cusModalFactory.EmptyShow($scope.roomST.RM_TP).success(function(data){
            $scope.RoomTP = data;
            $scope.roomInfo = [
                ["房间类型",$scope.roomST.RM_TP],
                ["建议价",$scope.RoomTP[0].SUGG_PRICE+'元']
            ];
        });
        $scope.roomAction = [["入住办理",'checkIn'],["维修完毕",'mended']];
    }else if ($scope.roomST.RM_CONDITION =="脏房"){
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
            var location = "newCheckIn/checkIn/"+$scope.roomST.RM_ID;
//            window.open(location);
            util.openTab(location,"","",util.closeCallback);
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
            var location = "newCheckIn/modify/"+$scope.roomST.RM_ID;
//            window.open(location);
            util.openTab(location,"","",util.closeCallback);
        }else if(actionString == 'checkOut'){
            cusModalFactory.getConnect($scope.roomST.RM_TRAN_ID).success(function(data){
                if(data != "null"){
                    $scope.CurrentMaster = data[0];
                    $scope.ConnRooms = data;
                    $scope.RMviewClick = "connectCheckOut";
                }
            });
            if ($scope.roomST.CONN_RM_TRAN_ID == null){
                var location = "newCheckOut/"+$scope.roomST.RM_TRAN_ID;
//                window.open(location);
                util.openTab(location,"","",util.closeCallback);
            }
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
//                var temp = JSON.parse(JSON.stringify(room));
//                $scope.ConnRooms[0]= JSON.parse(JSON.stringify($scope.ConnRooms[i]));
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
//        window.open(location);
        util.openTab(location,"","",util.closeCallback);
    };

});


app.controller('connectRMModalInstanceCtrl',function ($scope, $modalInstance, cusModalFactory, $http, connectRooms) {
    $scope.rooms = connectRooms;
    $scope.confirmConn = function(){
        var roomsString = "";
        for (var i = 0; i < connectRooms.length; i++){
            roomsString += ("/"+connectRooms[i].RM_ID);
        }
        var location = "newCheckIn/checkIn"+roomsString;
//        window.open(location);
        openTab(location,"","",util.closeCallback);
    };

    $scope.cancelConn = function(){
        $modalInstance.dismiss('cancel');
    }
});


//
//$scope.BookRoom[i]['realMoneyOut'] = 0;
//$scope.BookRoom[i]["DEPO_SUM"] = 0;
//$scope.BookRoom[i]["Acct_SUM"] = 0;
//$scope.BookRoom[i]["Store_SUM"] = 0;
//$scope.BookRoom[i]["newRMProduct"] = {"newRProductNM":"",
//    "newRProductQUAN":"",
//    "newRProductPAY":"",
//    "newRProductPAYmethod":"",
//    "PROD_ID":""};
//$scope.BookRoom[i]["RoomConsume"] = [];
//$scope.BookRoom[i]["newConsumeSum"] = 0;
//$scope.BookRoom[i]["newFee"] = {"RMRK":"","PAY_METHOD":"","PAY_AMNT":"", "PAYER":"","PAYER_PHONE":""};
//$scope.BookRoom[i]["penalty"] = [];
//$scope.BookRoom[i]["newFeeSum"] = 0;
//$scope.BookRoom[i]["DAYS_STAY"] = Math.round( (new Date($scope.BookRoom[i]["CHECK_OT_DT"]).getTime()
//    -new Date($scope.BookRoom[i]["CHECK_IN_DT"]).getTime())/86400000);
//
//
//for (var j = 0; j < $scope.BookRoom[i]['AcctDepo'].length; j++){
//    $scope.BookRoom[i]["DEPO_SUM"] += parseFloat($scope.BookRoom[i]['AcctDepo'][j]["DEPO_AMNT"]);
//
//}
//for (var j = 0; j < $scope.BookRoom[i]['AcctPay'].length; j++){
//    $scope.BookRoom[i]["Acct_SUM"] += parseFloat($scope.BookRoom[i]['AcctPay'][j]["RM_PAY_AMNT"]);
//}
//for (var j = 0; j < $scope.BookRoom[i]['AcctStore'].length; j++){
//    $scope.BookRoom[i]["Store_SUM"] += (parseFloat($scope.BookRoom[i]['AcctStore'][j]["PROD_PRICE"])
//        *parseFloat($scope.BookRoom[i]['AcctStore'][j]["PROD_QUAN"]));
//}
//
//$scope.BookRoom[i]["Sumation"]= parseFloat($scope.BookRoom[i]["DEPO_SUM"] -
//    $scope.BookRoom[i]["Acct_SUM"] -
//    $scope.BookRoom[i]["Store_SUM"] -
//    $scope.BookRoom[i]["newConsumeSum"] -
//    $scope.BookRoom[i]["newFeeSum"]).toFixed(2) ;