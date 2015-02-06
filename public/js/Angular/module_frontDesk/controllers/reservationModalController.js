app.controller('reservationModalController', function($scope, $http,$modalInstance,$timeout,
                                                      $timeout, roomTPs,initialString, newCheckInFactory,newResvFactory){

    /********************************************     utility     ***************************************************/
    var today = util.toLocal(new Date());
    var tomorrow = new Date(today.getTime()+86400000);
//    $scope.OT_DT = util.dateFormat(tomorrow)   // for fixing angular 1.30 bug
    $scope.dateTime = new Date((tomorrow).setHours(12,0,0));
    $scope.dateFormat = function(rawDate){
        return util.dateFormat(rawDate);
    }

    var createBookRoom = function(len){
        for (var i=0; i<len; i++){
            $scope.BookRoom.push(createNewRoom());
        }
    }

    var createBookRoomByTP = function(singleRoom){
        if (singleRoom.RM_TP in $scope.BookRoomByTP){
            $scope.BookRoomByTP[singleRoom.RM_TP].rooms.push(singleRoom);
            $scope.BookRoomByTP[singleRoom.RM_TP].roomAmount++;
        }else{
            $scope.BookRoomByTP[singleRoom.RM_TP] = {SUGG_PRICE:singleRoom.SUGG_PRICE,
                discount:singleRoom.discount,finalPrice:singleRoom.finalPrice,rooms:[singleRoom],roomAmount:1};
        }
    }

    $scope.createNewTP = function(TP,SUGG_PRICE,discount,finalPrice,roomArray,roomAmount){
        $scope.BookRoomByTP[TP] =
        {SUGG_PRICE:SUGG_PRICE,discount:discount,finalPrice:finalPrice,rooms:roomArray,roomAmount:roomAmount};
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

    var createNewRoom = function(){
        var newRoom =  {RM_TP:"", RM_ID:"",finalPrice:"",SUGG_PRICE:"",discount:"",deposit:"",MasterRoom:'fasle',
            GuestsInfo:[createNewGuest()],payment:createNewPayment(), check:true};
        return newRoom;
    }

    $scope.createNewRoom = function(){
        return createNewRoom();
    }

    var createNewGuest = function(){
        var newGuest =  {Name:"",MemberId:"",Phone:"",SSN:"",SSNType:"二代身份证",MEM_TP:"",Points:"",RemarkInput:"",TIMES:""}
        return newGuest;
    }

    var createNewReserver = function(){
        var newReserver =  {Name:"",MemberId:"",Phone:"",MEM_TP:"",Points:"",RemarkInput:"",TIMES:""}
        return newReserver;
    }

    var createNewPayByMethod = function(){
        var payByMethod =  {payAmount:"",payMethod:""};
        return payByMethod;
    }

    var createNewPayment = function(){
        var Payment =  {paymentRequest:"", paymentType:"住房押金", base:"",payInDue:"",payByMethods:[createNewPayByMethod()]};
        return Payment;
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

    var updateMembers = function(data){
        $scope.Members = data;
        if (data.length<1){
            alert("查不到");
            $scope.BookCommonInfo.Member = "";
            return;
        }
        $scope.BookCommonInfo.Member = $scope.Members[0];
        for(var i = 0 ; i < $scope.Members.length; i++){
            $scope.Members[i]["summary"] = "<table>"+
                "<tr>" +  "<td>" + "证件:" + "</td>" + "<td>" + $scope.Members[i].SSN + "</td>" + "</tr>"+
                "<tr>" + "<td>" + "级别:" + "</td>" + "<td>" + $scope.Members[i].MEM_TP + "</td>" + "</tr>"+
                "<tr>" + "<td>" + "折扣:" + "</td>" + "<td>" + $scope.Members[i].DISCOUNT_RATE + "</td>" + "</tr>"+
                "<tr>" + "<td>" + "电话:" + "</td>" + "<td>" + $scope.Members[i].PHONE + "</td>" + "</tr>"+
                "<tr>" + "<td>" + "积分:" + "</td>" + "<td>" + $scope.Members[i].POINTS + "</td>" + "</tr>"+
                "</table>";
        }
    }

    var updateTreaties= function(data){
        $scope.Treaties = data;
        if (data.length<1){
            alert("查不到");
            $scope.BookCommonInfo.Treaty = "";
            return;
        }
        $scope.BookCommonInfo.Treaty = $scope.Treaties[0];
        for(var i = 0 ; i < $scope.Treaties.length; i++){
            $scope.Treaties[i]["summary"] = "<table>"+
                "<tr>" +  "<td>" + "类型:" + "</td>" + "<td>" + $scope.Treaties[i].TREATY_TP + "</td>" + "</tr>"+
                "<tr>" + "<td>" + "公司电话:" + "</td>" + "<td>" + $scope.Treaties[i].CORP_PHONE + "</td>" + "</tr>"+
                "<tr>" + "<td>" + "联系人:" + "</td>" + "<td>" + $scope.Treaties[i].CONTACT_NM + "</td>" + "</tr>"+
                "<tr>" + "<td>" + "备注:" + "</td>" + "<td>" + $scope.Treaties[i].RMARK + "</td>" + "</tr>"+
                "<tr>" + "<td>" + "优惠:" + "</td>" + "<td>" + $scope.Treaties[i].DISCOUNT + "</td>" + "</tr>"+
                "</table>";
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

    $scope.updatePayInDue = function(singleRoom){
        var totalDue= parseFloat(singleRoom.payment.paymentRequest);
        for (var i=0; i<singleRoom.payment.payByMethods.length; i++){
            totalDue = totalDue - singleRoom.payment.payByMethods[i].payAmount;
        }
        singleRoom.payment.payInDue = util.Limit(parseFloat(totalDue));
        singleRoom.payment.payInDue = (isNaN(singleRoom.payment.payInDue))? 0.00: singleRoom.payment.payInDue;
    };

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
//            payment.payByMethods[0].payAmount = payment.paymentRequest;
//            payment.payInDue = 0;
//            payment.payByMethods[0].payMethod = "现金";
            sum4master = sum4master + payment.paymentRequest;
            sum4distribute = sum4distribute + payment.base;
        }
            sum4master=util.Limit(sum4master);
            $scope.BookCommonInfo.payment.paymentRequest = sum4master;
            $scope.BookCommonInfo.payment.payByMethods[0].payAmount = sum4master;
            $scope.BookCommonInfo.payment.payByMethods[0].payMethod = "现金";
            $scope.BookCommonInfo.payment.payInDue = 0;
            $scope.BookCommonInfo.payment.base = sum4distribute;
    }

    var clearZeroRM_QUAN = function(BookRoomByTP,property,forbiddenArray){
        for(var TP in BookRoomByTP){
            show(BookRoomByTP[TP][property])
            if(forbiddenArray.indexOf(BookRoomByTP[TP][property]) > -1 ){
                BookRoomByTP[TP] = null;
                delete BookRoomByTP[TP];
            }
        }
    }

    /**********************************/
    /************** ********************************** Initial functions ******************************************* *************/


    var newReservation = function(CHECK_IN_DT,CHECK_OT_DT){
        $scope.BookCommonInfo = {CHECK_IN_DT: CHECK_IN_DT,CHECK_OT_DT:CHECK_OT_DT ,arriveTime:$scope.dateTime,
                                roomSource:'', rentType:"全日租",Member:{},Treaty:{},payment:createNewPayment(),
                                comment:"",discount4ALL:"",paymentFlag:false};
        createBookRoom(1);
        $scope.Members =[];
        $scope.Treaties =[];

        newCheckInFactory.getRMInfoWithAvail(CHECK_IN_DT,CHECK_OT_DT).success(function(data){
            $scope.RoomAllinfo = data;
            initRoomsAndRoomTypes();
            for (var key in $scope.roomsAndRoomTypes){
                $scope.BookRoom[0].RM_TP = key;
                $scope.BookRoom[0].RM_ID = "";
                $scope.BookRoom[0].SUGG_PRICE = $scope.roomsAndRoomTypes[key][0].SUGG_PRICE;
                $scope.BookRoom[0].finalPrice = $scope.roomsAndRoomTypes[key][0].SUGG_PRICE;
                break;
            }
            createBookRoomByTP($scope.BookRoom[0]);
            $scope.BookCommonInfo.roomSource='普通预定';
        });
    };


    var initialTreatyCheck = function(checkInput,call_back){
        newCheckInFactory.searchTreaties(checkInput,["TREATY_ID"]).success(function(data){
            $scope.Treaties = data;
            $scope.watcher.finalPrice=false;
            $scope.watcher.paymentRequest=false;
            if (data.length<1){
                alert("查不到");
                $scope.Treaties = [];
                $scope.BookCommonInfo.Treaty = "";
                return;
            }
            for(var i = 0 ; i < $scope.Treaties.length; i++){
                $scope.Treaties[i]["summary"] = "<table>"+
                    "<tr>" +  "<td>" + "类型:" + "</td>" + "<td>" + $scope.Treaties[i].TREATY_TP + "</td>" + "</tr>"+
                    "<tr>" + "<td>" + "公司电话:" + "</td>" + "<td>" + $scope.Treaties[i].CORP_PHONE + "</td>" + "</tr>"+
                    "<tr>" + "<td>" + "联系人:" + "</td>" + "<td>" + $scope.Treaties[i].CONTACT_NM + "</td>" + "</tr>"+
                    "<tr>" + "<td>" + "备注:" + "</td>" + "<td>" + $scope.Treaties[i].RMARK + "</td>" + "</tr>"+
                    "<tr>" + "<td>" + "优惠:" + "</td>" + "<td>" + $scope.Treaties[i].DISCOUNT + "</td>" + "</tr>"+
                    "</table>";
                if($scope.Treaties[i]["TREATY_ID"] == roomTPs[0]["TREATY_ID"]) $scope.BookCommonInfo.Treaty = $scope.Treaties[i];
            }
            call_back();
        });
    }

    var initialMemCheck = function(checkInput,call_back){
        newCheckInFactory.searchMember(checkInput,["MEM_ID"]).success(function(data){
            $scope.Members = data;
            $scope.watcher.finalPrice=false;
            $scope.watcher.paymentRequest=false;
            if (data.length<1){
                alert("查不到");
                $scope.BookCommonInfo.Member = "";
                return;
            }
            for(var i = 0 ; i < $scope.Members.length; i++){
                $scope.Members[i]["summary"] = "<table>"+
                    "<tr>" +  "<td>" + "证件:" + "</td>" + "<td>" + $scope.Members[i].SSN + "</td>" + "</tr>"+
                    "<tr>" + "<td>" + "级别:" + "</td>" + "<td>" + $scope.Members[i].MEM_TP + "</td>" + "</tr>"+
                    "<tr>" + "<td>" + "折扣:" + "</td>" + "<td>" + $scope.Members[i].DISCOUNT_RATE + "</td>" + "</tr>"+
                    "<tr>" + "<td>" + "电话:" + "</td>" + "<td>" + $scope.Members[i].PHONE + "</td>" + "</tr>"+
                    "<tr>" + "<td>" + "积分:" + "</td>" + "<td>" + $scope.Members[i].POINTS + "</td>" + "</tr>"+
                    "</table>";
                if($scope.Members[i]["MEMBER_ID"] == roomTPs[0]["MEMBER_ID"]) $scope.BookCommonInfo.Member = $scope.Members[i];
            }
            call_back();
        });
    }

    var initEditCall_back = function(){
        for(var i =0; i< roomTPs.length; i++){
            for (var j=0; j<roomTPs[i].RM_QUAN; j++ ){
                var newRoom =  {RM_TP:roomTPs[i]["RM_TP"], RM_ID:"",finalPrice:roomTPs[i]["RESV_DAY_PAY"],
                    SUGG_PRICE:$scope.roomsAndRoomTypes[roomTPs[i]["RM_TP"]][0].SUGG_PRICE,
                    discount:"",deposit:"",MasterRoom:'fasle',
                    GuestsInfo:[createNewGuest()],payment:createNewPayment(), check:true};
                $scope.BookRoom.push(newRoom);
                createBookRoomByTP(newRoom);
                $scope.roomTpsDisableList[roomTPs[i]["RM_TP"]] = true;
            }
        }
        $timeout(function(){
            if($scope.BookCommonInfo.paymentFlag){
                $scope.BookCommonInfo.payment.paymentRequest=roomTPs[0]["PRE_PAID"];
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
        $scope.BookCommonInfo ={CHECK_IN_DT:    roomTPs[0]["CHECK_IN_DT"],
            CHECK_OT_DT:    roomTPs[0]["CHECK_OT_DT"],
            arriveTime:     util.time2DateTime(new Date(roomTPs[0]["CHECK_IN_DT"]),roomTPs[0]["RESV_LATEST_TIME"]),
            roomSource:     roomTPs[0]["RESV_WAY"],
            rentType:       "全日租",
            Member:         {},
            Treaty:         {},
            payment:        createNewPayment(),
            comment:        roomTPs[0]["RMRK"],
            discount4ALL:   "",
            paymentFlag:    (roomTPs[0].STATUS=="预付")
        };
        $scope.reserver["Name"]= roomTPs[0]["RESVER_NAME"];
        $scope.reserver["Phone"]= roomTPs[0]["RESVER_PHONE"];
        newCheckInFactory.getRMInfoWithAvail(roomTPs["CHECK_IN_DT"],roomTPs["CHECK_OT_DT"]).success(function(data){
            $scope.RoomAllinfo = data;
            initRoomsAndRoomTypes();
            if (roomTPs[0]["RESV_WAY"]=="协议" && roomTPs[0]["TREATY_ID"] != null){
                $scope.Members =[];
                $scope.check["checkInput"] = roomTPs[0]["TREATY_ID"];
                initialTreatyCheck($scope.check["checkInput"],initEditCall_back);
            }else if(roomTPs[0]["RESV_WAY"]=="会员" && roomTPs[0]["MEMBER_ID"] != null){
                $scope.Treaties =[];
                $scope.check["checkInput"] = roomTPs[0]["MEMBER_ID"];
                initialMemCheck($scope.check["checkInput"],initEditCall_back);
            }else{
                $scope.Members =[];
                $scope.Treaties =[];
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
    $scope.reserver = createNewReserver();
    $scope.check = {"checkInput": ""};
    $scope.watcher={"treaty":true,"member":true,"finalPrice":true,"discount":true,"paymentRequest":true};
    /**********************************/
    /************** ********************************** Initialized by conditions ********************************** *************/
    if(initialString == "newReservation"){
        newReservation(util.dateFormat(today),util.dateFormat(tomorrow));
    }else if(initialString == "editReservation"){
        editReservation(roomTPs);
    }

    /**********************************/
    /************** ********************************** Page Logical Confinement ********************************** *************/

        // work around fixing Anuglar 1.30 bug....
//    $scope.$watch("BookCommonInfo.CHECK_OT_DT",
//        function(newValue,oldValue){
//            if (newValue == null || newValue == undefined) {
////                $scope.BookCommonInfo.CHECK_OT_DT = $scope.OT_DT;
//            }else{
//                $scope.OT_DT = util.dateFormat(newValue);
//            }
//        }
//        ,true
//    )

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

    //rentType change, price change
//    $scope.$watch(function(){
//            return $scope.BookCommonInfo.rentType;
//        },
//        function(newValue, oldValue) {
//            if(newValue != "全日租"){
//                //    $scope.$apply(function(){
//                $scope.BookCommonInfo.CHECK_OT_DT = util.dateFormat(today);
//                $scope.OT_DT = util.dateFormat(today);
//                $scope.BookCommonInfo.leaveTime = new Date($scope.plansOBJ[newValue].PLAN_COV_MIN*60*1000 + today.getTime());
//            }else {
//                $scope.BookCommonInfo.CHECK_OT_DT = util.dateFormat(tomorrow);
//                $scope.OT_DT = util.dateFormat(tomorrow);
//                $scope.BookCommonInfo.leaveTime = $scope.dateTime;
//            }
//            for (var i =0 ; i<$scope.BookRoom.length; i++){
//                updateFinalPrice($scope.BookRoom[i]);
//            }
//        },
//        true
//    );



    // RM_TP change induce price change



    // discount rate and final price
//    $scope.discountChange = function(singleRoom) {
//        updateFinalPrice(singleRoom);
//    }


    $scope.addRmTp = function(){
        for(var RmTp in $scope.roomsAndRoomTypes){
            if(!(RmTp in $scope.BookRoomByTP)){
                // make a new room
                var newRoom = createNewRoom();
                newRoom.RM_TP = RmTp;
                newRoom.RM_ID = "";
                newRoom.discount = $scope.BookCommonInfo.discount4ALL;
                newRoom.SUGG_PRICE = $scope.roomsAndRoomTypes[RmTp][0].SUGG_PRICE;
                $scope.BookRoom.push(newRoom);
                updateFinalPrice(newRoom);
                //create corresponding roomType
                createBookRoomByTP(newRoom);
                $scope.roomTpsDisableList[RmTp] = true;
                break;
            }
        }
    }

    $scope.$watch('BookCommonInfo.payment.paymentRequest',
        function(newValue, oldValue) {
            $scope.updatePayInDue($scope.BookCommonInfo);
        },
        true
    );

    /**********************************/
    /************** ********************************** pay  ********************************** *************/

    $scope.addNewPayByMethod = function(singleRoom){
        singleRoom.payment.payByMethods.push(createNewPayByMethod());
    }

    $scope.distributeMasterPay = function(){
        $scope.updatePayInDue($scope.BookCommonInfo.Master);
        var masterPay = $scope.BookCommonInfo.Master.payment;
        var extraPay = masterPay.paymentRequest - masterPay.base;
        for(var i = 0; i<$scope.BookRoom.length; i++){
            $scope.BookRoom[i].payment.paymentRequest =
                util.Limit($scope.BookRoom[i].payment.base + extraPay * $scope.BookRoom[i].payment.base / masterPay.base);
        }
    }
    /************************************************/
    /************** ********************************** page change ********************************** *************/
    $scope.confirm = function(){
        if ($scope.BookCommonInfo.paymentFlag){
            $scope.viewClick = "Pay";
        }else{
            $scope.submit();
        }
    }

    $scope.editConfirm = function(){
        // per_paid amount != paymentRequest or change mind of whether to pre paid,
        var PRE_PAID_AMOUNT = (util.isNum(roomTPs[0]["PRE_PAID"])) ? roomTPs[0]["PRE_PAID"] : 0;
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
    /************** ********************************** room source check ********************************** *************/
    $scope.checkSource = function(source,checkInput){
        checkInput = checkInput.trim();
        if (checkInput == ""){
            return;
        }
        if (source == '会员'){
            $scope.memCheck(checkInput);
        }else if(source == '协议'){
            $scope.treatyCheck(checkInput);
        }
    }
    /******** ************ MemberCheck ******* *************/

    $scope.memCheck = function(checkInput){
        if(util.isName(checkInput)){
            newCheckInFactory.searchMember(checkInput,["MEM_NM"]).success(function(data){
                updateMembers(data);
            });
        }else if(util.isSSN(checkInput)){
            newCheckInFactory.searchMember(checkInput,["SSN"]).success(function(data){
                updateMembers(data);
            });
        }else{
            newCheckInFactory.searchMember(checkInput,["MEM_ID","PHONE"]).success(function(data){
                updateMembers(data);
            });
        }
    }

    /********** ********** TreatyCheck ******* *************/
    $scope.treatyCheck = function(checkInput){
        newCheckInFactory.searchTreaties(checkInput,["TREATY_ID","CORP_NM"]).success(function(data){
            updateTreaties(data);
        });
    }

    /************************************************/
    /************** ********************************** submit  ********************************** *************/

    $scope.submit = function(){
        if (testFail()) return;
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
        newResv['PRE_PAID']=util.isNum($scope.BookCommonInfo.payment.paymentRequest)
                                        ? util.Limit($scope.BookCommonInfo.payment.paymentRequest):0;

        var payment = ($scope.BookCommonInfo.paymentFlag)?$scope.BookCommonInfo.payment : null;

        show(newResv);

        newResvFactory.resvSubmit(newResv,payment).success(function(data){
            if(JSON.stringify(data)!= null){
                alert(data);
            }else{
                alert("数据库出错")
            }
        });
    }

    $scope.editSubmit = function(PAY_DIFF){
        if (testFail()) return;
        clearZeroRM_QUAN($scope.BookRoomByTP,"roomAmount",["0",0,null]);
        var PRE_PAID =  util.Limit((util.isNum(roomTPs[0]["PRE_PAID"]))? roomTPs[0]["PRE_PAID"] : 0) + util.Limit(PAY_DIFF);
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
            "PRE_PAID":PRE_PAID
        };
        alert(reResv["RESV_TMESTMP"])
        if ($scope.BookCommonInfo.roomSource == "协议" && $scope.BookCommonInfo.Treaty != ""){
            reResv['roomSourceID'] = $scope.BookCommonInfo.Treaty.TREATY_ID;
        }else if($scope.BookCommonInfo.roomSource == "会员" && $scope.BookCommonInfo.Member != ""){
            reResv['roomSourceID'] = $scope.BookCommonInfo.Member.MEM_ID;
        }

        reResv['STATUS'] = ($scope.BookCommonInfo.paymentFlag)?'预付':'预订';
        var payment = (PAY_DIFF != 0)? $scope.BookCommonInfo.payment : null;
//
        newResvFactory.resvEditSubmit(reResv,payment,roomTPs).success(function(data){
            if(JSON.stringify(data)!= null){
                alert("修改成功!");
                util.closeCallback();
            }else{
                alert("数据库出错")
            }
        });
    };
    /*********************************************/
})
/************************                       singleTPsub controller                      ***********************/
    .controller('resvSingleTPCtrl', function ($scope) {

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
                        var newRoom = $scope.$parent.createNewRoom();
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
                            ,$scope.$parent.BookRoomByTP[oldValue].roomAmount);
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
    })
/************************                       singlePay sub controller                      ***********************/
    .controller('resvSinglePayCtrl', function ($scope) {
        $scope.$watch('singlePay.payAmount',
            function(newValue, oldValue) {
                if(newValue == oldValue) return;
                $scope.$parent.updatePayInDue($scope.$parent.BookCommonInfo);
            },
            true
        );
    });