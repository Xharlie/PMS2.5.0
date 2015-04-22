/**
 * Created by Xharlie on 12/22/14.
 */
app.controller('MultiCheckInModalController', function($scope, $http, newCheckInFactory,
                                                       $modalInstance, $timeout, roomST,initialString,RESV){

    /********************************************     utility     ***************************************************/
    var today = new Date();
    var tomorrow = new Date(today.getTime()+86400000);
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
            if ($scope.RoomAllinfo[i].RM_CONDITION != "空房") continue;
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
//
//    var updateDiscount = function(discount){
//        for (var i=0; i< $scope.BookRoom.length; i++){
//            $scope.BookRoom[i].discount = discount;
//            updateFinalPrice($scope.BookRoom[i]);
//        }
//    }

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

//    var  updateForRmtpChange = function(singleRoom){
//        singleRoom.SUGG_PRICE=$scope.roomsAndRoomTypes[singleRoom.RM_TP][0].SUGG_PRICE;
//        $scope.BookCommonInfo.rentType="全日租";
//        updateFinalPrice(singleRoom);
//    };

    $scope.updateDisabledRmId = function(oldValue,newValue){
        $scope.roomsDisableList[oldValue]=false;  //enable old one
        $scope.roomsDisableList[newValue]=true;  // disable new one
    }

    $scope.openPopover = function(id){
        $timeout(function() {
            $('#'+id).trigger('openEvent');
        }, 0);
    }

//    $scope.closePopover = function(id){
//        $timeout(function() {
//            $('#'+id).trigger('closeEvent');
//        }, 0);
//    }


    $scope.dateChange = function(){
        return;
    }

    $scope.updatePayInDue = function(singleRoom){
        var totalDue= parseFloat(singleRoom.payment.paymentRequest);
        for (var i=0; i<singleRoom.payment.payByMethods.length; i++){
            totalDue = totalDue - singleRoom.payment.payByMethods[i].payAmount;
        }
        singleRoom.payment.payInDue = util.Limit(parseFloat(totalDue));
        singleRoom.payment.payInDue = (isNaN(singleRoom.payment.payInDue))? 0.00: singleRoom.payment.payInDue;
    };


    // need improved
    var depositMethod = function(basic){
        var sum = Math.floor((basic+200)/100)*100;
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

    var testFail = function(){
        return false;
    }

    /**********************************/
    /************** ********************************** Initial functions ******************************************* *************/
//    var singleWalkInInit = function(RM_ID){
//        newCheckInFactory.getSingleRoomInfo(RM_ID).success(function(data){
//            $scope.RoomAllinfo = data;
//            $scope.BookCommonInfo.roomSource="普通散客";
//            $scope.BookRoom[0].RM_TP = $scope.RoomAllinfo[0].RM_TP;
//            $scope.BookRoom[0].RM_ID = $scope.RoomAllinfo[0].RM_ID;
//            $scope.BookRoom[0].SUGG_PRICE = $scope.RoomAllinfo[0].SUGG_PRICE;
//            $scope.BookRoom[0].finalPrice = $scope.RoomAllinfo[0].SUGG_PRICE;
//            $scope.roomsAndRoomTypes[$scope.RoomAllinfo[0].RM_TP]=[$scope.RoomAllinfo[0]];
//        });
//    };

    var multiReserve = function(roomST){
        $scope.BookCommonInfo.CHECK_OT_DT = (RESV.CHECK_OT_DT == "")? $scope.BookCommonInfo.CHECK_OT_DT: RESV.CHECK_OT_DT;
        newCheckInFactory.getRoomInfo().success(function(data){
            $scope.RoomAllinfo = data;
            $scope.BookCommonInfo.roomSource=RESV.roomSource;
            initRoomsAndRoomTypes();
            for (var i=0; i< roomST.length; i++){
                $scope.BookRoom[i].RM_TP = roomST[i].RM_TP;
                $scope.BookRoom[i].RM_ID = roomST[i].RM_ID;
                $scope.BookRoom[i].SUGG_PRICE = roomST[i].finalPrice;
//                updateFinalPrice($scope.BookRoom[i]);
                createBookRoomByTP($scope.BookRoom[i]);
            }
        });
    };

    var multiWalkIn = function(roomST){
        newCheckInFactory.getRoomInfo().success(function(data){
            $scope.RoomAllinfo = data;
            $scope.BookCommonInfo.roomSource="普通散客";
            initRoomsAndRoomTypes();
            for (var i=0; i< roomST.length; i++){
                $scope.BookRoom[i].RM_TP = roomST[i].RM_TP;
                $scope.BookRoom[i].RM_ID = roomST[i].RM_ID;
                $scope.BookRoom[i].SUGG_PRICE = $scope.roomsAndRoomTypes[roomST[i].RM_TP][0].SUGG_PRICE;
//                updateFinalPrice($scope.BookRoom[i]);
                createBookRoomByTP($scope.BookRoom[i]);
            }
        });
    };

    // get temp Plan for all kinds of room type
//    var getTempPlan = function(){
//        newCheckInFactory.tempPlanGet().success(function(data){
//            $scope.plans = data;
//            $scope.plansOBJ={};
//            for(var i=0; i<$scope.plans.length; i++){
//                $scope.plans[i]['PLAN_COV_PRCE'] = util.Limit($scope.plans[i]['PLAN_COV_PRCE']);
//                $scope.plans[i]['PNLTY_PR_MIN'] = util.Limit($scope.plans[i]['PNLTY_PR_MIN']);
//                $scope.plansOBJ[$scope.plans[i].PLAN_ID] = $scope.plans[i];
//            }
//        });
//    }
    /**********************************/
    /********************************************     common initial setting     *****************************************/
    $scope.viewClick = "Info";
    $scope.initialString=initialString;
    $scope.BookCommonInfo = {CHECK_IN_DT: today,CHECK_OT_DT: tomorrow,leaveTime:$scope.dateTime,
        roomSource:'', rentType:"全日租",Member:{},Treaty:{},Master:{CONN_RM_ID:"",payment:createNewPayment()}};
    $scope.caption = {searchCaption:"",resultCaption:""};
    $scope.styles = {CheckInStyle:{},CheckOTStyle:{},memStyle:{}};
    $scope.disable = {searchDisable:false};
    $scope.Members =[];
    $scope.Treaties =[];
    $scope.Connected=true;
    $scope.roomsAndRoomTypes = [];
    $scope.roomsDisableList = {};
    $scope.BookRoom = [];
    $scope.BookRoomByTP = {};   //  only for multi walk in or multi checkin
    createBookRoom(roomST.length);
    $scope.check= {checkInput: ""};
//    show(roomST)



    /**********************************/
    /************** ********************************** Initialized by conditions ********************************** *************/
    if(initialString == "multiReserve"){
        multiReserve(roomST);
    }else if (initialString == "multiWalkIn"){
        multiWalkIn(roomST);
    }

    /**********************************/
    /************** ********************************** Page Logical Confinement ********************************** *************/


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
                case '预订':
                    $scope.caption.searchCaption = "N/A(暂时)";
                    $scope.disable.searchDisable = true;
//                    updateDiscount("");
                    updateDiscount4TP("");
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


    /************************************************/
    /************** ********************************** guest  ********************************** *************/
        // add customer
    $scope.addCustomer = function(guestInfo){
        guestInfo.push(createNewGuest());
    }
    // remove customer
    $scope.deleteCustomer = function(GuestsInfo,index){
        if(GuestsInfo.length != 1){
            GuestsInfo.splice(index,1);
        }
    }
    // check the identity, guest history through ssn
    $scope.showIdentity = function(singleGuest,index){
        var $SSN = singleGuest.SSN.trim();
        if($SSN == "") {
            singleGuest.SSN = "";
            return;
        }
        newCheckInFactory.HistoCustomer($SSN).success(function(data){
            if (data!= undefined && data.length>0){
                singleGuest.Name = data[0].NM;
                singleGuest.MemberId = data[0].MEM_ID;
                singleGuest.TIMES = data[0].TIMES;
                if (singleGuest.MemberId != undefined && singleGuest.MemberId != ""){
                    // check whether member and update information
                    updateMemberGuest(singleGuest);
                }
            }else{
                singleGuest.notFindWarning ="历史记录中未查到该证件号";
                $scope.openPopover('guest'+index.toString()+'SSN');
            }
        });
    }


    /************************************************/
    /************** ********************************** connect toggled  ********************************** *************/
    $scope.toggleMaster = function(){
        $scope.Connected = (!$scope.Connected);

    }
    /************************************************/
    /************** ********************************** page change  ********************************** *************/
    $scope.confirm = function(){
        $scope.viewClick = "Pay";
        var sum4master = 0;
        var sum4distribute = 0;   // sum of basic room price
        for(var i = 0; i < $scope.BookRoom.length; i++){
            var payment = $scope.BookRoom[i].payment;
            payment.base = basicPrice($scope.BookRoom[i]);
            payment.paymentRequest = depositMethod(payment.base);
            payment.payByMethods[0].payAmount = payment.paymentRequest;
            payment.payInDue = 0;
            payment.payByMethods[0].payMethod = "现金";
            sum4master = sum4master + payment.paymentRequest;
            sum4distribute = sum4distribute + payment.base;
        }
        if ($scope.Connected){
            $scope.BookCommonInfo.Master.payment.paymentRequest = sum4master;
            $scope.BookCommonInfo.Master.payment.payByMethods[0].payAmount = sum4master;
            $scope.BookCommonInfo.Master.payment.payByMethods[0].payMethod = "现金";
            $scope.BookCommonInfo.Master.payment.payInDue = 0;
            $scope.BookCommonInfo.Master.payment.base = sum4distribute;
        }
    }

    $scope.backward = function(page){
        $scope.viewClick = page;
    }


    $scope.editRoomAndGuest = function(){
        $scope.viewClick = "roomInfo";
        if ($scope.Connected && $scope.BookCommonInfo.Master.CONN_RM_ID != ""){
            for(var i = 0; i<$scope.BookRoom.length; i++){
                if ($scope.BookRoom[i].RM_ID == $scope.BookCommonInfo.Master.CONN_RM_ID){
                    if (!$scope.BookRoom[i].check) {
                        $scope.BookCommonInfo.Master.CONN_RM_ID = "";
                        return;
                    }
                }
            }
        }
    }

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };
    /************************************************/
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
    /************** ********************************** submit  ********************************** *************/

    $scope.submit = function(){
        if (testFail()) return;
        $scope.submitLoading = true;
        $scope.SubmitInfo=[];
        var CHECK_IN_DT =  util.dateFormat($scope.BookCommonInfo.CHECK_IN_DT);
        var CHECK_OT_DT =  util.dateFormat($scope.BookCommonInfo.CHECK_OT_DT);
        var j = 0;
        for (var i = 0; i<$scope.BookRoom.length; i++){
            var room = $scope.BookRoom[i];
            if (!room.check) continue;
            $scope.SubmitInfo.push({roomSelect:room.RM_ID, roomType:room.RM_TP, CHECK_IN_DT: CHECK_IN_DT
                ,CHECK_OT_DT: CHECK_OT_DT, leaveTime:util.toLocal($scope.BookCommonInfo.leaveTime), finalPrice: room.finalPrice,
                roomSource:$scope.BookCommonInfo.roomSource, GuestsInfo: room.GuestsInfo,pay:room.payment});

            if ($scope.BookCommonInfo.roomSource == "协议" && $scope.BookCommonInfo.Treaty != ""){
                $scope.SubmitInfo[j]["TREATY_ID"]= $scope.BookCommonInfo.Treaty.TREATY_ID;
            }else if($scope.BookCommonInfo.roomSource == "会员" && $scope.BookCommonInfo.Member != ""){
                $scope.SubmitInfo[j]["MEM_ID"]= $scope.BookCommonInfo.Member.MEM_ID;
            }

            if($scope.BookCommonInfo.rentType!="全日租"){
                $scope.SubmitInfo[j]["TMP_PLAN_ID"]=$scope.BookCommonInfo.rentType;
            }else{
                $scope.SubmitInfo[j]["TMP_PLAN_ID"]='';
            }

            if ($scope.BookCommonInfo.Master.CONN_RM_ID != ""){
                $scope.SubmitInfo[j]["CONN_RM_ID"]=$scope.BookCommonInfo.Master.CONN_RM_ID;
                if( $scope.SubmitInfo[j]["roomSelect"] == $scope.BookCommonInfo.Master.CONN_RM_ID){
                    // master room pay all deposit
                    $scope.SubmitInfo[j]["pay"] = util.deepCopy($scope.BookCommonInfo.Master.payment);
                }else{
                    // non-master room pay no deposit
                    $scope.SubmitInfo[j]["pay"]=createNewPayment();
                }

            }else{
                $scope.SubmitInfo[j]["CONN_RM_ID"]="";
            }

            // always move master room to the front
            if( j!=0 && $scope.SubmitInfo[j]["roomSelect"] == $scope.BookCommonInfo.Master.CONN_RM_ID){
                var temp = JSON.parse(JSON.stringify($scope.SubmitInfo[0]));
                $scope.SubmitInfo[0]= JSON.parse(JSON.stringify($scope.SubmitInfo[j]));
                $scope.SubmitInfo[j] = temp;
            }
            j++;
        }

        var unfilled = {};
        if (RESV == "" || RESV == undefined || RESV == null) {
            RESV = null;
            unfilled = null;
        }else{
            for (var rmTp in $scope.BookRoomByTP){
                var diff =  $scope.BookRoomByTP[rmTp].rooms.length - $scope.BookRoomByTP[rmTp].roomAmount;
                unfilled[rmTp]={checked:$scope.BookRoomByTP[rmTp].roomAmount, unchecked: diff};
            }
            if(Object.keys(unfilled).length == 0) unfilled = null;
        }

//    var resetAvail=[];
//    if (initialString!="singleWalkIn"){
//        resetAvail =[decodeURI(RoomNumArray[1]),RoomNumArray[2],new Date(RoomNumArray[4].replace("-","/"))
//            ,new Date(RoomNumArray[5].replace("-","/"))];
//    }
//        show(unfilled);
        newCheckInFactory.submit(JSON.stringify({SubmitInfo:$scope.SubmitInfo,RESV:RESV,unfilled:unfilled})).success(function(data){
            show("办理成功!");
            $scope.submitLoading = false;
            $modalInstance.close("checked");
            util.closeCallback();
        });
    }


    /*********************************************/

})
/************************                       singleRoom sub controller                      ***********************/
    .controller('multiSingleRoomCtrl', function ($scope) {
        $scope.$watch('singleRoom.RM_ID',
            function(newValue, oldValue) {
                $scope.$parent.updateDisabledRmId(oldValue,newValue);
            },
            true
        );
    })

/************************                       singleTPsub controller                      ***********************/
    .controller('multiSingleTPCtrl', function ($scope) {
        $scope.$watch('singleTP.finalPrice',
            function(newValue, oldValue) {
                for (var i = 0 ; i< $scope.singleTP.rooms.length; i++){
                    $scope.singleTP.rooms[i].finalPrice = newValue;
                }
            },
            true
        );

        $scope.$watch('singleTP.roomAmount',
            function(newValue, oldValue) {
                for (var i = 0 ; i< $scope.singleTP.rooms.length; i++){
                    if(i < newValue) $scope.singleTP.rooms[i].check = true;
                    else $scope.singleTP.rooms[i].check = false;
                }
            },
            true
        );
    })

    /************************                       singleRoomPay sub controller                      ***********************/
    .controller('multiSingleRoomPayCtrl', function ($scope) {
        $scope.$watch('singleRoom.payment.paymentRequest',
            function(newValue, oldValue) {
                $scope.$parent.updatePayInDue($scope.singleRoom);
            },
            true
        );
    })

     /************************                       singlePay sub controller                      ***********************/
    .controller('multiSinglePayCtrl', function ($scope) {
        $scope.$watch('singlePay.payAmount',
            function(newValue, oldValue) {
                $scope.$parent.$parent.updatePayInDue($scope.$parent.singleRoom);
            },
            true
        );
    })/************************                       single Master Pay sub controller                      ***********************/
    .controller('multiSingleMasterPayCtrl', function ($scope) {
        $scope.$watch('singlePay.payAmount',
            function(newValue, oldValue) {
                $scope.$parent.updatePayInDue($scope.$parent.BookCommonInfo.Master);
            },
            true
        );
    });

