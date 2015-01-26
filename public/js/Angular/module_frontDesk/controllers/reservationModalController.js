app.controller('reservationModalController', function($scope, $http,$modalInstance, $timeout, roomST,initialString, newCheckInFactory){

    /********************************************     utility     ***************************************************/
    var today = util.toLocal(new Date());
    var tomorrow = new Date(today.getTime()+86400000);
    $scope.OT_DT = util.dateFormat(tomorrow)   // for fixing angular 1.30 bug
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
            $scope.BookRoomByTP[singleRoom.RM_TP] = {SUGG_PRICE:singleRoom.SUGG_PRICE,discount:singleRoom.discount,finalPrice:singleRoom.finalPrice,rooms:[singleRoom],roomAmount:1};
        }
    }

    var createNewRoom = function(){
        var newRoom =  {RM_TP:"", RM_ID:"",finalPrice:"",SUGG_PRICE:"",discount:"",deposit:"",MasterRoom:'fasle',
            GuestsInfo:[createNewGuest()],payment:createNewPayment(), check:true};
        return newRoom;
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
        for (var TP in $scope.BookRoomByTP) {
            $scope.BookRoomByTP[TP].discount = discount;
            updateFinalPrice4TP($scope.BookRoomByTP[TP]);
        }
    }

//
//    var  updateFinalPrice = function(singleRoom){
//        var discount = (singleRoom.discount=="")? 100: singleRoom.discount;
//        if ($scope.BookCommonInfo.rentType == "全日租"){
//            singleRoom.finalPrice = util.Limit(singleRoom.SUGG_PRICE * discount /100);
//        } else {
//            singleRoom.finalPrice = util.Limit($scope.plansOBJ[$scope.BookCommonInfo.rentType].PLAN_COV_PRCE * discount /100);
//        }
//    };

    var  updateFinalPrice4TP = function(singleTP){
        var discount = (singleTP.discount=="")? 100: singleTP.discount;
        if ($scope.BookCommonInfo.rentType == "全日租"){
            singleTP.finalPrice = util.Limit(singleTP.SUGG_PRICE * discount /100);
        } else {
            singleTP.finalPrice = util.Limit($scope.plansOBJ[$scope.BookCommonInfo.rentType].PLAN_COV_PRCE * discount /100);
        }
        for (var i=0; i < singleTP.rooms.length; i++ ){
            singleTP.rooms[i].finalPrice = singleTP.finalPrice;
        }
    };
    /**********************************/
    /************** ********************************** Initial functions ******************************************* *************/


    var newReservation = function(CHECK_IN_DT,CHECK_OT_DT){
        newCheckInFactory.getRMInfoWithAvail(CHECK_IN_DT,CHECK_OT_DT).success(function(data){
            $scope.RoomAllinfo = data;
//            $scope.BookCommonInfo.roomSource="普通散客";
            initRoomsAndRoomTypes();
            for (var key in $scope.roomsAndRoomTypes){
                $scope.BookRoom[0].RM_TP = key;
                $scope.BookRoom[0].RM_ID = "";
                $scope.BookRoom[0].SUGG_PRICE = $scope.roomsAndRoomTypes[key][0].SUGG_PRICE;
                $scope.BookRoom[0].finalPrice = $scope.roomsAndRoomTypes[key][0].SUGG_PRICE;
                break;

            }
            createBookRoomByTP($scope.BookRoom[0]);
            $scope.BookCommonInfo.roomSource='普通散客';
//            for (var i=0; i< len; i++){
//                $scope.BookRoom[i].RM_TP = roomST[i].RM_TP;
//                $scope.BookRoom[i].RM_ID = roomST[i].RM_ID;
//                $scope.BookRoom[i].SUGG_PRICE = $scope.roomsAndRoomTypes[roomST[i].RM_TP][0].SUGG_PRICE;
//                updateFinalPrice($scope.BookRoom[i]);
//                createBookRoomByTP($scope.BookRoom[i]);
        });
    };


    /**********************************/
    /********************************************     common initial setting     *****************************************/
    $scope.initialString=initialString;
    $scope.BookCommonInfo = {CHECK_IN_DT: util.dateFormat(today),CHECK_OT_DT: util.dateFormat(tomorrow),arriveTime:$scope.dateTime,
        roomSource:'', rentType:"全日租",Member:{},Treaty:{},payment:createNewPayment()};
    $scope.caption = {searchCaption:"",resultCaption:""};
    $scope.styles = {CheckInStyle:{},CheckOTStyle:{},memStyle:{}};
    $scope.disable = {searchDisable:false};
    $scope.Members =[];
    $scope.Treaties =[];
    $scope.Connected=false;
    $scope.roomsAndRoomTypes = [];
    $scope.roomsDisableList = {};
    $scope.BookRoom = [];
    $scope.BookRoomByTP = {};   //  only for multi walk in or multi checkin
    createBookRoom(1);
    $scope.reserver = createNewReserver();
    $scope.check= {checkInput: ""};
//    show(roomST)
    /**********************************/
    /************** ********************************** Initialized by conditions ********************************** *************/
    if(initialString == "newReservation"){
        newReservation($scope.BookCommonInfo.CHECK_IN_DT,$scope.BookCommonInfo.CHECK_OT_DT);
    }

    /**********************************/
    /************** ********************************** Page Logical Confinement ********************************** *************/

        // work around fixing Anuglar 1.30 bug....
    $scope.$watch("BookCommonInfo.CHECK_OT_DT",
        function(newValue,oldValue){
            if (newValue == null || newValue == undefined) {
//                $scope.BookCommonInfo.CHECK_OT_DT = $scope.OT_DT;
            }else{
                $scope.OT_DT = util.dateFormat(newValue);
            }
        }
        ,true
    )

    // roomSource change: 1. search caption, 2. check bar disable, 3.discount
    $scope.$watch(function(){
            return $scope.BookCommonInfo.roomSource;
        },
        function(newValue, oldValue) {
            $scope.check.checkInput = "";
            switch(newValue){
                case '普通散客':
                    $scope.caption.searchCaption = "N/A";
                    $scope.disable.searchDisable = true;
//                    updateDiscount("");
                    updateDiscount4TP("");
                    break;
                case '会员':
                    $scope.caption.searchCaption = "会员号/身份证/姓名/电话";
                    $scope.disable.searchDisable = false;
                    if($scope.BookCommonInfo.Member.DISCOUNT_RATE != undefined){
//                        updateDiscount($scope.BookCommonInfo.Member.DISCOUNT_RATE);
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
            if($scope.BookCommonInfo.Member.DISCOUNT_RATE != undefined){
//                updateDiscount($scope.BookCommonInfo.Member.DISCOUNT_RATE);
                updateDiscount4TP($scope.BookCommonInfo.Member.DISCOUNT_RATE);
            }else{
//                updateDiscount("");
                updateDiscount4TP("");
            }
        },
        true
    );

    // Treaty change: change discount
    $scope.$watch(function(){
            return $scope.BookCommonInfo.Treaty;
        },
        function(newValue, oldValue) {
            if($scope.BookCommonInfo.Treaty.DISCOUNT != undefined){
//                updateDiscount($scope.BookCommonInfo.Treaty.DISCOUNT);
                updateDiscount4TP($scope.BookCommonInfo.Treaty.DISCOUNT);
            }else{
//                updateDiscount("");
                updateDiscount4TP("");
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
    $scope.roomTypeChange = function(singleRoom){
        updateForRmtpChange(singleRoom);
    }


    // discount rate and final price
//    $scope.discountChange = function(singleRoom) {
//        updateFinalPrice(singleRoom);
//    }

    $scope.discountChange4TP = function(singleTP) {
        updateFinalPrice4TP(singleTP);
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
});