
app.controller('reservationController', function($scope, $http, resrvFactory){
   /* Database version */

    resrvFactory.resvShow().success(function(data){
        $scope.resvInfo =data;
    });

    $scope.addNew = function(){
        var location = "newResv";
        window.open(location);
    };

    $scope.check = function(resv){
        var location = "newCheckIn/"+ resv.RM_TP + "/" +  resv.RM_QUAN + "/" + resv.RESV_DAY_PAY + "/"+ resv.CHECK_IN_DT+
            "/" + resv.CHECK_OT_DT;
        window.open(location);
    }

    /* static verion
    $scope.resvInfo = resrvFactory.resvShowStatic();

     'Reservations.RESV_DAY_PAY as RESV_DAY_PAY',
     'Reservations.RESVER_PHONE as RESVER_PHONE', 'Reservations.RESVER_NAME as RESVER_NAME',
     'Reservations.RESV_WAY as RESV_WAY','Reservations.RESV_TMESTMP as RESV_TMESTMP',
     'Reservations.CHECK_IN_DT as CHECK_IN_DT','Reservations.CHECK_OT_DT as CHECK_OT_DT',
     'ReservationRoom.RM_TP as RM_TP','ReservationRoom.RM_QUAN as RM_QUAN','Reservations.TREATY_ID as TREATY_ID',
     'Reservations.MEMBER_ID as MEMBER_ID','Reservations.RMRK as RMRK'
    */
});


app.controller('roomStatusController', function($scope, $http, roomStatusFactory, $modal){
/* Database version */

    $scope.connectClick = "toStart";
    roomStatusFactory.roomShow().success(function(data){
        $scope.roomStatusInfo =data;
    });
    $scope.connectRooms = [];
    $scope.connectFlag = false;

    $scope.connectStart= function () {
        $scope.connectFlag = true;
        $scope.connectClick = "toEnd";
    };

    $scope.connectEnd = function(result){
        if(result == "confirm" && $scope.connectRooms.length > 1){

            var modalInstance = $modal.open({
                templateUrl: 'connectRMModalContent',
                controller: 'connectRMModalInstanceCtrl',
                resolve: {
                    connectRooms: function () {
                        return $scope.connectRooms;
                    }
                }
            });
            return;
        }
        for (var i =0; i< $scope.connectRooms.length; i++){
            delete $scope.connectRooms[i].boxStyle['border'];
        }

        $scope.connectClick = "toStart";
        $scope.connectRooms = [];
        $scope.connectFlag = false;
        //$scope.$apply();
    }

  //  window.addEventListener('keydown',ConnectCtrlkeydownListner , false);



    /* static verion
    $scope.roomStatusInfo = roomStatusFactory.roomShowStatic();*/

   // for floor filter $scope.roomFloor = '';
    $scope.overall ='';

    $scope.ngSetRoomBoxColor = function(roomST){
        roomST.boxStyle = {};
        var color = "";
            switch(roomST.RM_CONDITION){
                case 'Empty':
                    return "room-empty";
                case 'Occupied':
                    return "room-full";
                case 'Preparing':
                    return "room-dirty";
                case 'Mending':
                    return"room-disabled";
            }
    };
    /* shape for fun
    $scope.shape = function(RM_TP){
        switch(RM_TP){
            case 'Single':
                  return 'tv';
            case 'Double':
                  return 'burst-12';
            case 'Kingbed':
                  return 'burst-8';
            default: return '';
        }
    }   */

    $scope.ngFloorFilter = function(roomST){
        var test = new RegExp("^"+$scope.roomFloor+".*");
        var a = test.test(roomST.RM_ID); //$scope.roomFloor;
        return a;
    };

    $scope.customerizeFilter = function(roomST){
        var test = new RegExp("^.*"+$scope.overall+".*$", "i");
        var RM_ID = roomST.RM_ID.toString();
        var RM_TP = roomST.RM_TP.toString()
        var RM_CONDITION =roomST.RM_CONDITION.toString();
        return (RM_ID.match(test) != null) || (RM_TP.match(test)!= null) || (RM_CONDITION.match(test)!= null);  //  | roomST.RM_TP.match(test) | roomST.RM_CONDITION.match(test);
    };

/* Room Click Modal is here !*/
    $scope.open = function (roomST) {
        if($scope.connectFlag == true && roomST.RM_CONDITION == "Empty"){
            if (roomST.boxStyle['border']== undefined){
                roomST.boxStyle['border']='2px solid #FF9933';
                $scope.connectRooms.push(roomST);
            }else{
                delete roomST.boxStyle['border'];
                var index = $scope.connectRooms.indexOf(roomST);
                $scope.connectRooms.splice(index,1);
            }
            return;
        }
        var modalInstance = $modal.open({
            templateUrl: 'roomModalContent',
            controller: 'roomModalInstanceCtrl',
            resolve: {
                roomST: function () {
                    return roomST;
                }
            }
        });

    /*  modalInstance.result.then(function (selectedAction) {
            $scope.selected = selectedAction;
        }); */
    };


});



app.controller('customerController', function($scope, $http, customerFactory){


    $scope.viewClick = "recentCustomer" ;
    $scope.customerInfo ='';
    $scope.memberInfo = '';
    $scope.clearMEMIDfilter = function(){
        if($scope.memberID == '') delete $scope.memberID;
    };

    $scope.clearMEMphonefilter = function(){
        if($scope.memPhone == '') delete $scope.memPhone;
    };

    /* Database version */

    customerFactory.customerShow().success(function(data){
        $scope.customerInfo =data;
    });



    $scope.viewClickCustomer = function(){
        $scope.viewClick = "recentCustomer";
        if ($scope.customerInfo == '') {
            customerFactory.customerShow().success(function(data){
                $scope.customerInfo =data;
            });
        }

    };

    $scope.viewClickMember = function(){
        $scope.viewClick = "membership";
        if ($scope.memberInfo == '') {
            customerFactory.memberShow().success(function(data){
                $scope.memberInfo =data;
            });
        }
    };


    /* static version
    $scope.customerInfo = customerFactory.customerShowStatic();

    $scope.viewClickCustomer = function(){
        $scope.viewClick = "recentCustomer";
        if ($scope.customerInfo == '') {
                $scope.customerInfo = customerFactory.customerShowStatic();
        }

    };

    $scope.viewClickMember = function(){
        $scope.viewClick = "membership";
        if ($scope.memberInfo == '') {
                $scope.memberInfo = customerFactory.memberShowStatic();
        }
    };
     */

});

app.controller('accountingController', function($scope, $http, accountingFactory){
    $scope.Conaddup = 0;
    $scope.Payaddup = 0;
    $scope.collections = [];
    accountingFactory.accountingGetAll().success(function(data){
   //     alert(JSON.stringify(data));
        $scope.acctInfo = data;
    });

    $scope.TypeFilter = function(acct){
        if ($scope.Type == ""){
            return true;
        }else if($scope.Type == "CON"){
            return acct['CON'];
        }else if($scope.Type == "PAY"){
            return acct['PAY'];
        }
    };

    $scope.$watch('collections', function(newValue, oldValue) {
        $scope.Conaddup = 0;
        $scope.Payaddup = 0;
        if (newValue == undefined){
            return;
        }
        for(var i =0; i< newValue.length; i++){
            $scope.Conaddup = $scope.Conaddup + ((newValue[i]['CONSUME_PAY_AMNT'] == '') ? 0 : parseFloat(newValue[i]['CONSUME_PAY_AMNT']));
            $scope.Payaddup = $scope.Payaddup + ((newValue[i]['SUBMIT_PAY_AMNT'] == '' ) ? 0: parseFloat(newValue[i]['SUBMIT_PAY_AMNT']));
        }
    });


    var reverse = function(CLASS){
        switch (CLASS){
            case '存入押金':
                return 'RoomDepositAcct';
                break;
            case '现金支出':
                return 'RoomDepositAcct';
                break;
            case '损坏罚金':
                return 'PenaltyAcct';
                break;
            case '夜核房费':
                return 'RoomAcct';
                break;
            case '商品':
                return 'StoreTransaction';
                break;
            default :
                return "error";
        }
    };

    $scope.modify = function(acct){
        var classTP = reverse(acct['CLASS']);
        if(classTP!="error"){
            var location = "newModifyWindow/"+classTP+"/"+acct['ACCT_ID'];
            var v = window.open(location,"","menubar=no,scrollbars=no,resizable=no,width=500,height=300,top=200,left=300;");
        }else{
            alert("error");
        }

    }
});



app.controller('oneKeyShiftController', function($scope, $http, accountingFactory){

    accountingFactory.summerize().success(function(data){
        //     alert(JSON.stringify(data));
        $scope.cashSum = data['cash'];
        $scope.roomCardSum = data['cards'];
        $scope.productSellSum=data['store'];
    });

});

app.controller('Datepicker', function($scope){

    $scope.dateFormat = function(date){
        var yyyy = date.getFullYear().toString();
        var mm = (date.getMonth()+1).toString();
        var dd  = date.getDate().toString();
        return yyyy+"/" + (mm[1]?mm:"0"+mm[0])+"/" + (dd[1]?dd:"0"+dd[0]);
    }

    $scope.toDay = new Date();

    // Disable weekend selection
    $scope.disabled = function(date, mode) {
        return false; //( mode === 'day' && ( date.getDay() === 0 || date.getDay() === 6 ) );
    };

    $scope.dateChange = function(){};

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

    $scope.format = 'yyyy/MM/dd';

});


app.controller('merchandiseController', function($scope, $http, merchandiseFactory){
    /* Database version */


    $scope.prodInfo ='';
    $scope.onCounter = [];
    $scope.prodSumPrice = 0;
    $scope.prodRoom = "";
    $scope.room = "";
    $scope.notValidAmount = 0;
    $scope.prodRoomTranId = "";
    $scope.prodRoomId = "";
    $scope.submitFlag=false;


    var pathArray = window.location.href.split("/");
    var room_ID = pathArray[pathArray.length-1];
    if(room_ID != ":"){
        $scope.prodRoom = room_ID;
    }

    merchandiseFactory.productShow().success(function(data){
        $scope.prodInfo = data;
    });

    $scope.initRoomsInfo = function(prodRoom){
        merchandiseFactory.merchanRoomShow().success(function(data){
            $scope.rooms = data;
            $scope.checkRoom(prodRoom);
        });
    }
    $scope.checkRoom = function(prodRoom){
        $scope.prodRoomTranId = "";
        if(prodRoom == ""){
            $scope.room ="";
            $scope.prodRoomTranId = "";
        }else{
            $scope.room = "请输入正确房间号";
            for (var i =0; i< $scope.rooms.length; i++){
                if (prodRoom == $scope.rooms[i].RM_ID){
                    if($scope.rooms[i].RM_TRAN_ID != undefined){
                        $scope.prodRoomTranId = $scope.rooms[i].RM_TRAN_ID;
                        $scope.room = "找到房间，并现有客人";
                        $scope.prodRoomId = $scope.rooms[i].RM_ID;
                    }
                    break;
                }
            }
        }
    };


    $scope.viewClickBuy = function(){
        $scope.viewClick = "Buy";
        if ($scope.prodInfo =='') {
            merchandiseFactory.productShow().success(function(data){
                $scope.prodInfo = data;
            });
        }
    };

    $scope.nullify = function(filter){
        if (filter == ""){
            filter = null;
        }
    }

    $scope.addProd = function(prod){
        if(prod.arrow == "<<"){
            prod.buyArrow = {"color":"black"};
            var index = $scope.onCounter.indexOf(prod);
            $scope.removeBuy(index);
        }else{
            $scope.onCounter.push(prod);
            prod.AMOUNT=1;
            prod.MONEY=prod.PROD_PRICE;
            prod.buyArrow = {"color":"#1AFF00"};
            prod.arrow = "<<";
            $scope.changeSumPrice();
        }
    }

    $scope.calculateMoney = function(buy){
        if ((buy.AMOUNT % 1 == 0) && buy.AMOUNT>=1 && buy.AMOUNT <= buy.PROD_AVA_QUAN){
            if (JSON.stringify(buy.amountInput) == JSON.stringify({"color": "red" })){
                $scope.notValidAmount--;
                buy.amountInput ={"color": "black" };
            }
            buy.MONEY= (buy.AMOUNT * buy.PROD_PRICE).toFixed(2);
            $scope.changeSumPrice();
        }else{
            if (buy.amountInput == undefined || JSON.stringify(buy.amountInput) ==JSON.stringify({"color": "black" })){
                $scope.notValidAmount++;
                buy.amountInput ={"color": "red" };
            }
        }
    }

    $scope.removeBuy = function($index){
        $scope.onCounter[$index].buyArrow = "";
        $scope.onCounter[$index].arrow = ">>";
        $scope.onCounter.splice($index,1);
        $scope.changeSumPrice();
    }

    $scope.changeSumPrice = function(){
        $scope.prodSumPrice = 0;
        for (var i= 0; i<$scope.onCounter.length; i++){
            $scope.prodSumPrice += Number($scope.onCounter[i].MONEY);
        }
        $scope.prodSumPrice = $scope.prodSumPrice.toFixed(2);
    }

    $scope.purchaseCheck = function(){
        if ($scope.onCounter.length == 0){
            $scope.err = "请选购商品";
        }else if($scope.notValidAmount!= 0){
            $scope.err = "商品数量输入错误";
        }else if($scope.room == "请输入正确房间号"){
            $scope.err = "房间号不存在";
        }else{
            $scope.err = "";
        }

    }

    $scope.buySubmit = function(){
        if ($scope.err == ""){
            $scope.buySubmitInfo = {
                "STR_PAY_AMNT": $scope.prodSumPrice,
                "RM_TRAN_ID": $scope.prodRoomTranId,
                "RM_ID": $scope.prodRoomId,
                "products": $scope.onCounter
            }

            merchandiseFactory.buySubmit(JSON.stringify($scope.buySubmitInfo)).success(function(data){
                $scope.submitFlag=true;
                $scope.onCounter = [];
                $scope.prodSumPrice = 0;
                $scope.prodRoom = "";
                $scope.notValidAmount = 0;
                $scope.prodRoomTranId = 0;
                $scope.prodRoomId = 0;
                document.location.reload(true);
              //  window.open('http://localhost/~Xharlie/Hotel_Dev0/public/#/merchandise/:');
            });
        }

    }

    $scope.viewClickManage = function(){
        $scope.viewClick = "Manage";
        if($scope.histoInfo == undefined || $scope.submitFlag == true){
            merchandiseFactory.histoPurchaseShow().success(function(data){
                $scope.histoInfo = data;
                $scope.submitFlag = false;
            });
        }
    };

    $scope.expandHisto = function(info){
        if(info.expand == '+'){
            merchandiseFactory.histoProductShow(info.STR_TRAN_ID).success(function(data){
                info.histoInfoCollapsed = !(info.histoInfoCollapsed);
                info.histoProduct = data;
                info.expand='-';
            });
        }else{
            info.histoInfoCollapsed = !(info.histoInfoCollapsed);
            info.expand='+';
        }
    }
    /*  $info = Input::all();

     */

});


