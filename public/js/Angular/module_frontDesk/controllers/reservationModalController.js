app.controller('reservationModalController', function($scope, $http,$modalInstance,focusInSideFactory, paymentFactory,roomFactory,
                                                      $timeout, roomTPs,initialString, newCheckInFactory,newResvFactory){

    /********************************************     validation     ***************************************************/

    $scope.hasError = function(btnPass){
        if(eval("$scope."+btnPass)==null) eval("$scope."+btnPass+"=0");
        eval("$scope."+btnPass+"++");
    }
    $scope.noError = function(btnPass){
        eval("$scope."+btnPass+"--");
    }
    $scope.payError = 0;
    /********************************************     utility     ***************************************************/

    var today = new Date();
    var tomorrow = new Date(today.getTime()+86400000);
    $scope.dateTime = new Date((tomorrow).setHours(18,0,0));
    $scope.dateFormat = function(rawDate){
        return util.dateFormat(rawDate);
    }

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };

    $scope.createNewTP = function(TP,SUGG_PRICE,discount,finalPrice,roomArray,roomAmount,AVAIL_QUAN){
        $scope.BookRoomByTP[TP] =
        {SUGG_PRICE:SUGG_PRICE,discount:discount,finalPrice:finalPrice,rooms:roomArray,roomAmount:roomAmount,AVAIL_QUAN:AVAIL_QUAN};
    }

    $scope.syncSingleRoomTP = function(TP,singleTP){
        for(var i =0; i< singleTP.rooms.length; i++){
            var room = singleTP.rooms[i];
            room.RM_TP = TP;
            room.RM_ID = "";
            room.discount = singleTP.discount;
            room.SUGG_PRICE = singleTP.SUGG_PRICE;
            room.finalPrice = singleTP.finalPrice;
        }
    }

    $scope.createNewRoom = function(){
        return roomFactory.createNewRoom('预付房款');
    }


    var initRoomsAndRoomTypes = function(){
        for (var i = 0; i <$scope.RoomAllinfo.length; i++ ){
            var RM_TP = $scope.RoomAllinfo[i]["RM_TP"];
//            if ($scope.RoomAllinfo[i].RM_CONDITION != "空房") continue;
            if($scope.roomsAndRoomTypes[RM_TP] == undefined){
                $scope.roomsAndRoomTypes[RM_TP]=[$scope.RoomAllinfo[i]];
                $scope.roomTpsDisableList[RM_TP] = false;
            }else{
                $scope.roomsAndRoomTypes[RM_TP].push($scope.RoomAllinfo[i]);
            }
            $scope.roomsDisableList[$scope.RoomAllinfo[i].RM_ID] = false;  // all room enabled
        }
    }


    var updateMemberGuest = function(singleGuest){
        newCheckInFactory.searchMember(singleGuest.MemberId,["MEM_ID"]).success(function(d){
            if (d.length==0) return;
            singleGuest.Points=d[0].POINTS;
//            singleGuest.Province=d[0].PROV;
            singleGuest.MEM_TP=d[0].MEM_TP;
            if(singleGuest.Phone.trim() == ""){
                singleGuest.Phone=d[0].PHONE;
            }
        });
    }

    var updateDiscount4TP = function(discount){
        if(!$scope.watcher.discount ) return;
        for (var TP in $scope.BookRoomByTP) {
            $scope.BookRoomByTP[TP].discount = discount;
            updateFinalPrice4TP($scope.BookRoomByTP[TP]);
        }
    }



    var  updateFinalPrice = function(singleRoom){
        if(!$scope.watcher.finalPrice ) return;
        var discount = (singleRoom.discount=="")? 100: singleRoom.discount;
        if ($scope.BookCommonInfo.rentType == "全日租"){
            singleRoom.finalPrice = util.Limit(singleRoom.SUGG_PRICE * discount /100);
        } else {
            singleRoom.finalPrice = util.Limit($scope.plansOBJ[$scope.BookCommonInfo.rentType].PLAN_COV_PRCE * discount /100);
        }
        $scope.updatePayment();
    };

    var  updateFinalPrice4TP = function(singleTP){
        if(!$scope.watcher.finalPrice ) return;
        var discount = (singleTP.discount=="")? 100: singleTP.discount;
        if ($scope.BookCommonInfo.rentType == "全日租"){
            singleTP.finalPrice = util.Limit(singleTP.SUGG_PRICE * discount /100);
        } else {
            singleTP.finalPrice = util.Limit($scope.plansOBJ[$scope.BookCommonInfo.rentType].PLAN_COV_PRCE * discount /100);
        }
        for (var i=0; i < singleTP.rooms.length; i++ ){
            singleTP.rooms[i].finalPrice = util.Limit(singleTP.finalPrice);
        }
        $scope.updatePayment();
    };

    $scope.updateFinalPrice4TP = function(singleTP){
        updateFinalPrice4TP(singleTP);
    }

    var depositMethod = function(basic){
        // for check in
//        var sum = Math.floor((basic+200)/100)*100;
        // for new reserve
        var sum = basic;
        return sum;
    }

    var basicPrice = function(singleRoom){
        var sum = 0
        if($scope.BookCommonInfo.rentType=="全日租"){
            sum = parseFloat(singleRoom.finalPrice) *
                Math.round(((new Date($scope.BookCommonInfo.CHECK_OT_DT)).getTime() -
                    (new Date($scope.BookCommonInfo.CHECK_IN_DT)).getTime())/86400000);
        }else{
            sum =  parseFloat(singleRoom.finalPrice);
        }
        return sum;
    }

    $scope.updatePayment = function(){
        if(!$scope.watcher.paymentRequest ) return;
        var sum4master = 0;
        var sum4distribute = 0;   // sum of basic room price
        for(var i = 0; i < $scope.BookRoom.length; i++){
            var payment = $scope.BookRoom[i].payment;
            payment.base = basicPrice($scope.BookRoom[i]);
            payment.paymentRequest = depositMethod(payment.base);
            sum4master = sum4master + payment.paymentRequest;
            sum4distribute = sum4distribute + payment.base;
        }
            sum4master=util.Limit(sum4master);
            $scope.BookCommonInfo.payment.paymentRequest = sum4master;
            $scope.BookCommonInfo.payment.payByMethods[0].payAmount = sum4master;
            $scope.BookCommonInfo.payment.payByMethods[0].payMethod = "现金";
            $scope.BookCommonInfo.payment.payInDue = 0;
            $scope.BookCommonInfo.payment.base = util.Limit(sum4distribute);
    }


    var clearZeroRM_QUAN = function(BookRoomByTP,property,forbiddenArray){
        for(var TP in BookRoomByTP){
//            show(BookRoomByTP[TP][property])
            if(forbiddenArray.indexOf(BookRoomByTP[TP][property]) > -1 ){
                BookRoomByTP[TP] = null;
                delete BookRoomByTP[TP];
            }
        }
    }

    /**********************************/
    /************** ********************************** Initial functions ******************************************* *************/


    var newReservation = function(CHECK_IN_DT,CHECK_OT_DT){
        $scope.BookCommonInfo = {CHECK_IN_DT: CHECK_IN_DT,CHECK_OT_DT:CHECK_OT_DT ,arriveTime:$scope.dateTime,check:true,initFlag:null,Members:[],Treaties:[],
                                roomSource:'', rentType:"全日租",Member:{},Treaty:{}, payment:paymentFactory.createNewPayment('预付房款'),
                                comment:"",discount4ALL:"",paymentFlag:false};
        $scope.BookRoomMaster = [$scope.BookCommonInfo];
        roomFactory.createBookRoom($scope.BookRoom,1,'预付房款');
        newResvFactory.getRMInfoWithAvail(CHECK_IN_DT,CHECK_OT_DT).success(function(data){
            $scope.RoomAllinfo = data;
            initRoomsAndRoomTypes();
            for (var key in $scope.roomsAndRoomTypes){
                $scope.BookRoom[0].RM_TP = key;
                $scope.BookRoom[0].RM_ID = "";
                $scope.BookRoom[0].SUGG_PRICE = $scope.roomsAndRoomTypes[key][0].SUGG_PRICE;
                $scope.BookRoom[0].finalPrice = $scope.roomsAndRoomTypes[key][0].SUGG_PRICE;
                $scope.BookRoom[0].AVAIL_QUAN = $scope.roomsAndRoomTypes[key][0].AVAIL_QUAN;
                break;
            }
            roomFactory.createBookRoomByTP($scope.BookRoom[0],$scope.BookRoomByTP);
            $scope.BookCommonInfo.roomSource='普通预定';
        });
    };


    var initialTreatyCheck = function(checkInput,call_back){
        newCheckInFactory.searchTreaties(checkInput,["TREATY_ID"]).success(function(data){
            $scope.watcher.finalPrice=false;
            $scope.watcher.paymentRequest=false;
            $scope.BookCommonInfo.initFlag = true;
            $scope.BookCommonInfo.Treaties = data;
            call_back();
            $scope.BookCommonInfo.initFlag = null; // close initFlag
        });
    }

    var initialMemCheck = function(checkInput,call_back){
        newCheckInFactory.searchMember(checkInput,["MEM_ID"]).success(function(data){
            $scope.watcher.finalPrice=false;
            $scope.watcher.paymentRequest=false;
            $scope.BookCommonInfo.initFlag = true;
            $scope.BookCommonInfo.Members = data;
            call_back();
            $scope.BookCommonInfo.initFlag = null; // close initFlag
        });
    }

    var initEditCall_back = function(){
        for(var i =0; i< roomTPs.length; i++){
            for (var j=0; j<roomTPs[i].RM_QUAN; j++ ){
                var newRoom =  {RM_TP:roomTPs[i]["RM_TP"], RM_ID:"",finalPrice:roomTPs[i]["RESV_DAY_PAY"],
                    SUGG_PRICE:$scope.roomsAndRoomTypes[roomTPs[i]["RM_TP"]][0].SUGG_PRICE,
                    discount:"",deposit:"",MasterRoom:'fasle',
                    GuestsInfo:[roomFactory.createNewGuest()],payment:paymentFactory.createNewPayment('预付房款'), check:true};
                $scope.BookRoom.push(newRoom);
                roomFactory.createBookRoomByTP(newRoom,$scope.BookRoomByTP);
                $scope.roomTpsDisableList[roomTPs[i]["RM_TP"]] = true;
            }
        }
        $timeout(function(){
            if($scope.BookCommonInfo.paymentFlag){
                $scope.BookCommonInfo.payment.paymentRequest=roomTPs[0]["PRE_PAID_RMN"];
                $scope.watcher.finalPrice=true;
                $scope.watcher.paymentRequest=true;
            }else{
                $scope.watcher.finalPrice=true;
                $scope.watcher.paymentRequest=true;
                $scope.updatePayment();
            }
        }, 0);
    }

    var  editReservation = function(roomTPs){
        $scope.BookCommonInfo ={CHECK_IN_DT:    new Date(roomTPs[0]["CHECK_IN_DT"]),
            CHECK_OT_DT:    new Date(roomTPs[0]["CHECK_OT_DT"]),
            arriveTime:     (roomTPs[0]["RESV_LATEST_TIME"] == null)?
                new Date(util.time2DateTime(new Date(roomTPs[0]["CHECK_IN_DT"]),"14:00:00")):
                new Date(util.time2DateTime(new Date(roomTPs[0]["CHECK_IN_DT"]),roomTPs[0]["RESV_LATEST_TIME"])),
            roomSource:     roomTPs[0]["RESV_WAY"],
            rentType:       "全日租",
            Member:         {},
            Treaty:         {},
            payment:        paymentFactory.createNewPayment('预付房款'),
            comment:        roomTPs[0]["RMRK"],
            discount4ALL:   "",
            paymentFlag:    (roomTPs[0].STATUS=="预付"),
            initFlag:       null,
            check:          true,
            Members:        [],
            Treaties:       []
        };
        $scope.BookRoomMaster = [$scope.BookCommonInfo];
        $scope.reserver["Name"]= roomTPs[0]["RESVER_NAME"];
        $scope.reserver["Phone"]= roomTPs[0]["RESVER_PHONE"];
        newResvFactory.getRMInfoWithAvail(roomTPs["CHECK_IN_DT"],roomTPs["CHECK_OT_DT"]).success(function(data){
            $scope.RoomAllinfo = data;
            initRoomsAndRoomTypes();
            if (roomTPs[0]["RESV_WAY"]=="协议" && roomTPs[0]["TREATY_ID"] != null){
                $scope.check["checkInput"] = roomTPs[0]["TREATY_ID"];
                initialTreatyCheck($scope.check["checkInput"],initEditCall_back);
            }else if(roomTPs[0]["RESV_WAY"]=="会员" && roomTPs[0]["MEMBER_ID"] != null){
                $scope.check["checkInput"] = roomTPs[0]["MEMBER_ID"];
                initialMemCheck($scope.check["checkInput"],initEditCall_back);
            }else{
                initEditCall_back();
            }
        });
    };

    var testFail = function(){
        return false;
    }

    /**********************************/
    /********************************************     common initial setting     *****************************************/
    $scope.initialString=initialString;
    $scope.viewClick = "Info";
    $scope.caption = {searchCaption:"",resultCaption:""};
    $scope.styles = {CheckInStyle:{},CheckOTStyle:{},memStyle:{}};
    $scope.disable = {searchDisable:false};
    $scope.Connected=false;
    $scope.roomsAndRoomTypes = {};
    $scope.roomsDisableList = {};
    $scope.roomTpsDisableList = {};
    $scope.BookRoom = [];
    $scope.BookRoomByTP = {};   //  only for multi walk in or multi checkin
    $scope.reserver = roomFactory.createNewGuest();
    $scope.check = {"checkInput": ""};
    $scope.watcher={"treaty":true,"member":true,"finalPrice":true,"discount":true,"paymentRequest":true};
    $scope.payMethodOptions=paymentFactory.checkInPayMethodOptions();
    $scope.roomTPs= roomTPs;
    focusInSideFactory.tabInit('wholeModal');
    $timeout(function(){
        focusInSideFactory.manual('wholeModal');
    },0)
    /**********************************/
    /************** ********************************** Initialized by conditions ********************************** *************/
    if(initialString == "newReservation"){
        newReservation(today,tomorrow);
    }else if(initialString == "editReservation"){
        editReservation(roomTPs);
    }
    /**********************************/
    /************** ********************************** Page Logical Confinement ********************************** *************/

    $scope.$watch(function(){
            return $scope.BookCommonInfo.CHECK_IN_DT;
        },
        function(newValue, oldValue) {
            if(newValue == oldValue || !$scope.watcher.treaty ) return;
            if($scope.BookCommonInfo.CHECK_IN_DT != undefined && $scope.BookCommonInfo.CHECK_OT_DT != undefined &&
                $scope.BookCommonInfo.CHECK_IN_DT <= $scope.BookCommonInfo.CHECK_OT_DT){
                $scope.updatePayment();
            }
        },
        true
    );

    $scope.$watch(function(){
            return $scope.BookCommonInfo.CHECK_OT_DT;
        },
        function(newValue, oldValue) {
            if(newValue == oldValue || !$scope.watcher.treaty ) return;
            if( $scope.BookCommonInfo.CHECK_IN_DT != undefined && $scope.BookCommonInfo.CHECK_OT_DT != undefined
                && $scope.BookCommonInfo.CHECK_IN_DT <= $scope.BookCommonInfo.CHECK_OT_DT){
                $scope.updatePayment();
            }
        },
        true
    );


    // roomSource change: 1. search caption, 2. check bar disable, 3.discount
    $scope.$watch(function(){
            return $scope.BookCommonInfo.roomSource;
        },
        function(newValue, oldValue) {
            if(newValue == oldValue) return;
            $scope.check.checkInput = "";
            switch(newValue){
                case '普通预定':
                    $scope.caption.searchCaption = "N/A";
                    $scope.disable.searchDisable = true;
//                    updateDiscount("");
                    $scope.BookCommonInfo.discount4ALL=""
                    updateDiscount4TP("");
                    break;
                case '会员':
                    $scope.caption.searchCaption = "会员号/身份证/姓名/电话";
                    $scope.disable.searchDisable = false;
                    if($scope.BookCommonInfo.Member.DISCOUNT_RATE != undefined){
//                        updateDiscount($scope.BookCommonInfo.Member.DISCOUNT_RATE);
                        $scope.BookCommonInfo.discount4ALL=$scope.BookCommonInfo.Member.DISCOUNT_RATE;
                        updateDiscount4TP($scope.BookCommonInfo.Member.DISCOUNT_RATE);
                    }else{
//                        updateDiscount("");
                        updateDiscount4TP("");
                    }
                    break;
                case '协议':
                    $scope.caption.searchCaption = "协议号/协议单位";
                    $scope.disable.searchDisable = false;
                    if($scope.BookCommonInfo.Treaty.DISCOUNT != undefined){
//                        updateDiscount($scope.BookCommonInfo.Treaty.DISCOUNT);
                        $scope.BookCommonInfo.discount4ALL=$scope.BookCommonInfo.Treaty.DISCOUNT;
                        updateDiscount4TP($scope.BookCommonInfo.Treaty.DISCOUNT);
                    }else{
//                        updateDiscount("");
                        updateDiscount4TP("");
                    }
                    break;
                case '活动码':
                    $scope.caption.searchCaption = "N/A(暂时)";
                    $scope.disable.searchDisable = true;
//                    updateDiscount("");
                    $scope.BookCommonInfo.discount4ALL=""
                    updateDiscount4TP("");
                    break;
            }
        },
        true
    );

    // Member change: change discount
    $scope.$watch(function(){
            return $scope.BookCommonInfo.Member;
        },
        function(newValue, oldValue) {
            if(newValue == oldValue || !$scope.watcher.member ) return;
            if($scope.BookCommonInfo.Member.DISCOUNT_RATE != undefined){
//                updateDiscount($scope.BookCommonInfo.Member.DISCOUNT_RATE);
                $scope.BookCommonInfo.discount4ALL=$scope.BookCommonInfo.Member.DISCOUNT_RATE;
                updateDiscount4TP($scope.BookCommonInfo.Member.DISCOUNT_RATE);
            }else{
//                updateDiscount("");
                updateDiscount4TP("");
                $scope.BookCommonInfo.discount4ALL="";
            }
        },
        true
    );

    // Treaty change: change discount
    $scope.$watch(function(){
            return $scope.BookCommonInfo.Treaty;
        },
        function(newValue, oldValue) {
            if(newValue == oldValue || !$scope.watcher.treaty ) return;
            if($scope.BookCommonInfo.Treaty.DISCOUNT != undefined){
//                updateDiscount($scope.BookCommonInfo.Treaty.DISCOUNT);
                $scope.BookCommonInfo.discount4ALL=$scope.BookCommonInfo.Treaty.DISCOUNT;
                updateDiscount4TP($scope.BookCommonInfo.Treaty.DISCOUNT);
            }else{
//                updateDiscount("");
                updateDiscount4TP("");
                $scope.BookCommonInfo.discount4ALL="";
            }
        },
        true
    );

    $scope.addRmTp = function(){
        for(var RmTp in $scope.roomsAndRoomTypes){
            if(!(RmTp in $scope.BookRoomByTP)){
                // make a new room
                var newRoom = roomFactory.createNewRoom('预付房款');
                newRoom.RM_TP = RmTp;
                newRoom.RM_ID = "";
                newRoom.discount = $scope.BookCommonInfo.discount4ALL;
                newRoom.SUGG_PRICE = $scope.roomsAndRoomTypes[RmTp][0].SUGG_PRICE;
                newRoom.AVAIL_QUAN = $scope.roomsAndRoomTypes[RmTp][0].AVAIL_QUAN;
                $scope.BookRoom.push(newRoom);
                updateFinalPrice(newRoom);
                //create corresponding roomType
                roomFactory.createBookRoomByTP(newRoom,$scope.BookRoomByTP);
                $scope.roomTpsDisableList[RmTp] = true;
                break;
            }
        }
    }



    /**********************************/
    /************** ********************************** pay  ********************************** *************/

    $scope.addNewPayByMethod = function(singleRoom){
        singleRoom.payment.payByMethods.push(createNewPayByMethod());
    }

    /************************************************/
    /************** ********************************** page change ********************************** *************/
    $scope.confirm = function(){
        if ($scope.BookCommonInfo.paymentFlag){
            $scope.viewClick = "Pay";
            $scope.payError = 0;
            $scope.BookCommonInfo.payment.payByMethods[0].payAmount = $scope.BookCommonInfo.payment.paymentRequest;
        }else{
            $scope.submit();
        }
    }

    $scope.editConfirm = function(){
        // per_paid amount != paymentRequest or change mind of whether to pre paid,
        var PRE_PAID_AMOUNT = (util.isNum(roomTPs[0]["PRE_PAID_RMN"])) ? roomTPs[0]["PRE_PAID_RMN"] : 0;
        var SHOULD_PAY_AMOUNT = (util.isNum($scope.BookCommonInfo.payment.paymentRequest) )
                                ? $scope.BookCommonInfo.payment.paymentRequest : 0;
        var NOW_PAY_AMOUNT = ($scope.BookCommonInfo.paymentFlag)? SHOULD_PAY_AMOUNT : 0;
        if  (NOW_PAY_AMOUNT != PRE_PAID_AMOUNT){
//            show([ PRE_PAID_AMOUNT,SHOULD_PAY_AMOUNT, NOW_PAY_AMOUNT ])
            $scope.BookCommonInfo.payment.backUpPrepaid = $scope.BookCommonInfo.payment.paymentRequest;
            $scope.BookCommonInfo.payment.paymentRequest = util.Limit(NOW_PAY_AMOUNT - PRE_PAID_AMOUNT);
            $scope.BookCommonInfo.payment.payByMethods[0].payAmount = $scope.BookCommonInfo.payment.paymentRequest;
            $scope.BookCommonInfo.payment.payByMethods[0].payMethod = "现金";
            $scope.BookCommonInfo.payment.payInDue = 0;
            $scope.payError = 0;
            $scope.viewClick = "Pay";
        }else{
//            show([ PRE_PAID_AMOUNT,SHOULD_PAY_AMOUNT, NOW_PAY_AMOUNT ])
            $scope.editSubmit(0);
        }
    }

    $scope.backward = function(page){
        if(initialString=='editReservation') $scope.BookCommonInfo.payment.paymentRequest = $scope.BookCommonInfo.payment.backUpPrepaid;
        $scope.viewClick = page;
    }

    /**********************************/
    /************** ********************************** submit  ********************************** *************/

    $scope.submit = function(){
        if (testFail()) return;
        $scope.submitLoading = true;
        clearZeroRM_QUAN($scope.BookRoomByTP,"roomAmount",["0",0,null]);
        var newResv = {
            "roomSource": $scope.BookCommonInfo.roomSource,
            "RESV_TMESTMP": util.tstmpFormat(new Date()),
            "CHECK_IN_DT":$scope.dateFormat($scope.BookCommonInfo.CHECK_IN_DT),
            'RESV_LATEST_TIME':util.timeFormat($scope.BookCommonInfo.arriveTime),
            "CHECK_OT_DT":$scope.dateFormat($scope.BookCommonInfo.CHECK_OT_DT),
            "BookRoomByTP":$scope.BookRoomByTP,
            "email":'',
            "RMRK":$scope.BookCommonInfo.comment,
            "Name":$scope.reserver.Name,
            "Phone":$scope.reserver.Phone          //,"email":$scope.singleGuest.email
        };

        if ($scope.BookCommonInfo.roomSource == "协议" && $scope.BookCommonInfo.Treaty != ""){
            newResv['roomSourceID'] = $scope.BookCommonInfo.Treaty.TREATY_ID;
        }else if($scope.BookCommonInfo.roomSource == "会员" && $scope.BookCommonInfo.Member != ""){
            newResv['roomSourceID'] = $scope.BookCommonInfo.Member.MEM_ID;
        }

        newResv['STATUS'] = ($scope.BookCommonInfo.paymentFlag)?'预付':'预订';
        newResv['PRE_PAID_RMN'] = (util.isNum($scope.BookCommonInfo.payment.paymentRequest) && $scope.BookCommonInfo.paymentFlag)
                                        ? util.Limit($scope.BookCommonInfo.payment.paymentRequest):0;

        var payment = ($scope.BookCommonInfo.paymentFlag) ? $scope.BookCommonInfo.payment : null;

//        show(newResv);

        newResvFactory.resvSubmit(newResv,payment).success(function(data){
            $scope.submitLoading = false;
            if(JSON.stringify(data)!= null){
                show("成功预定");
                $modalInstance.close("checked");
                util.closeCallback();
            }else{
                show("数据库出错")
            }
        });
    }

    $scope.editSubmit = function(PAY_DIFF){
        if (testFail()) return;
        $scope.submitLoading = true;
        clearZeroRM_QUAN($scope.BookRoomByTP,"roomAmount",["0",0,null]);
        var PRE_PAID_RMN =  util.Limit((util.isNum(roomTPs[0]["PRE_PAID_RMN"]))? roomTPs[0]["PRE_PAID_RMN"] : 0) + util.Limit(PAY_DIFF);
        var reResv = {
            "roomSource": $scope.BookCommonInfo.roomSource,
            "RESV_TMESTMP": util.tstmpFormat(new Date()),
            "CHECK_IN_DT":$scope.dateFormat($scope.BookCommonInfo.CHECK_IN_DT),
            'RESV_LATEST_TIME':util.timeFormat($scope.BookCommonInfo.arriveTime),
            "CHECK_OT_DT":$scope.dateFormat($scope.BookCommonInfo.CHECK_OT_DT),
            "BookRoomByTP":$scope.BookRoomByTP,
            "email":'',
            "RMRK":$scope.BookCommonInfo.comment,
            "Name":$scope.reserver.Name,
            "Phone":$scope.reserver.Phone,          //,"email":$scope.singleGuest.email
            "PRE_PAID_RMN":PRE_PAID_RMN
        };
//        show(reResv["RESV_TMESTMP"])
        if ($scope.BookCommonInfo.roomSource == "协议" && $scope.BookCommonInfo.Treaty != ""){
            reResv['roomSourceID'] = $scope.BookCommonInfo.Treaty.TREATY_ID;
        }else if($scope.BookCommonInfo.roomSource == "会员" && $scope.BookCommonInfo.Member != ""){
            reResv['roomSourceID'] = $scope.BookCommonInfo.Member.MEM_ID;
        }

        reResv['STATUS'] = ($scope.BookCommonInfo.paymentFlag)?'预付':'预订';
        var payment = (PAY_DIFF != 0)? $scope.BookCommonInfo.payment : null;
//
        newResvFactory.resvEditSubmit(reResv,payment,roomTPs).success(function(data){
            $scope.submitLoading = false;
            if(JSON.stringify(data)!= null){
                show("修改成功!");
                $modalInstance.close("checked");
                util.closeCallback();
            }else{
                show("数据库出错")
            }
        });
    };
    /*********************************************/
})
/************************                       singleTPsub controller                      ***********************/
    .controller('resvSingleTPCtrl', function ($scope,roomFactory) {

        $scope.$watch('singleTP.roomAmount',
            function(newValue, oldValue) {
                if(newValue==oldValue || !util.isNum(newValue) || newValue<=0 ) return;
                var diff = newValue-$scope.singleTP.rooms.length;
                if(diff < 0){
                    for(var i=newValue; i < $scope.singleTP.rooms.length; i++){
                        delete $scope.singleTP.rooms[i]
                    }
                    $scope.singleTP.rooms.splice(newValue,-diff);
                }else{
                    for(var i =0; i< diff; i++ ){
                        var newRoom = roomFactory.createNewRoom('预付房款');
                        $scope.$parent.BookRoom.push(newRoom);
                        $scope.singleTP.rooms.push(newRoom);
                    }
                    $scope.$parent.syncSingleRoomTP($scope.singleTP.rooms[0]["RM_TP"],$scope.singleTP);
                    $scope.$parent.updatePayment();
                }
            },
            true
        );

        $scope.$watch('TP',
            function(newValue, oldValue) {
                if(newValue==oldValue) return;
                var roomsAndRoomTypes = $scope.$parent.roomsAndRoomTypes;
                $scope.$parent.createNewTP(newValue
                            ,roomsAndRoomTypes[newValue][0].SUGG_PRICE
                            ,$scope.$parent.BookRoomByTP[oldValue].discount
                            ,roomsAndRoomTypes[newValue][0].SUGG_PRICE
                            ,$scope.$parent.BookRoomByTP[oldValue].rooms
                            ,$scope.$parent.BookRoomByTP[oldValue].roomAmount
                            ,roomsAndRoomTypes[newValue][0].AVAIL_QUAN);
                $scope.$parent.updateFinalPrice4TP($scope.$parent.BookRoomByTP[newValue]);
                $scope.$parent.syncSingleRoomTP(newValue,$scope.$parent.BookRoomByTP[newValue]);
                $scope.$parent.BookRoomByTP[oldValue]["rooms"]="";
                delete  $scope.$parent.BookRoomByTP[oldValue];
                $scope.$parent.roomTpsDisableList[oldValue] = false;
                $scope.$parent.roomTpsDisableList[newValue] = true;
            },
            true
        );

        $scope.$watch('singleTP.finalPrice',
            function(newValue, oldValue) {
                if(newValue==oldValue || !$scope.$parent.watcher.finalPrice) return;
                for (var i = 0 ; i< $scope.singleTP.rooms.length; i++){
                    $scope.singleTP.rooms[i].finalPrice = newValue;
                }
                $scope.$parent.updatePayment();
            },
            true
        );

        $scope.$watch('singleTP.discount',
            function(newValue, oldValue) {
                if(newValue==oldValue) return;
                for (var i = 0 ; i< $scope.singleTP.rooms.length; i++){
                    $scope.singleTP.rooms[i].discount = newValue;
                }
                $scope.$parent.updateFinalPrice4TP($scope.singleTP);
            },
            true
        );
    });