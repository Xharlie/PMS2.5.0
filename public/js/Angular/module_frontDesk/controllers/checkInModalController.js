/**
 * Created by Xharlie on 12/22/14.
 */
app.controller('checkInModalController', function($scope, $http, newCheckInFactory,$modalInstance,$timeout, roomST,initialString){

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


    var createNewRoom = function(){
        var newRoom =  {RM_TP:"", RM_ID:"",finalPrice:"",SUGG_PRICE:"",discount:"",deposit:"",MasterRoom:'fasle',
            GuestsInfo:[createNewGuest()],payment:createNewPayment()};
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
        var Payment =  {paymentRequest:"", paymentType:"住房押金", payInDue:"",payByMethods:[createNewPayByMethod()]};
        return Payment;
    }


    var initRoomsAndRoomTypes = function(exceptedRM_ID){
        for (var i = 0; i <$scope.RoomAllinfo.length; i++ ){
            var RM_TP = $scope.RoomAllinfo[i]["RM_TP"];
            if ($scope.RoomAllinfo[i].RM_CONDITION != "空房" && $scope.RoomAllinfo[i].RM_ID!=exceptedRM_ID) continue;
            if($scope.roomsAndRoomTypes[RM_TP] == undefined){
                $scope.roomsAndRoomTypes[RM_TP]=[$scope.RoomAllinfo[i]];
            }else{
                $scope.roomsAndRoomTypes[RM_TP].push($scope.RoomAllinfo[i]);
            }
            $scope.roomsDisableList[$scope.RoomAllinfo[i].RM_ID] = false;  // all room enabled
        }
    }

    var updateMembers = function(data,initFlag){
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
            if(initFlag!=null && roomST[0]["MEM_ID"] == $scope.Members[i]["MEM_ID"])    $scope.BookCommonInfo.Member = $scope.Members[i];
        }
    }

    var updateTreaties= function(data,initFlag){
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
            if(initFlag!=null && roomST[0]["TREATY_ID"] == $scope.Treaties[i]["TREATY_ID"]) $scope.BookCommonInfo.Treaty = $scope.Treaties[i];
        }
    }

    var updateDiscount = function(discount){
        for (var i=0; i< $scope.BookRoom.length; i++){
            $scope.BookRoom[i].discount = discount;
            updateFinalPrice($scope.BookRoom[i]);
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

    var  updateFinalPrice = function(singleRoom){
        var discount = (singleRoom.discount=="")? 100: singleRoom.discount;
        if ($scope.BookCommonInfo.rentType == "全日租"){
            singleRoom.finalPrice = util.Limit(singleRoom.SUGG_PRICE * discount /100);
        } else {
            singleRoom.finalPrice = util.Limit($scope.plansOBJ[$scope.BookCommonInfo.rentType].PLAN_COV_PRCE * discount /100);
        }
    };

    var  updateForRmtpChange = function(singleRoom){
        singleRoom.SUGG_PRICE=$scope.roomsAndRoomTypes[singleRoom.RM_TP][0].SUGG_PRICE;
        $scope.BookCommonInfo.rentType="全日租";
        updateFinalPrice(singleRoom);
    };

    $scope.updateDisabledRmId = function(oldValue,newValue){
        $scope.roomsDisableList[oldValue]=false;  //enable old one
        $scope.roomsDisableList[newValue]=true;  // disable new one
//        alert(oldValue+"out"+"  "+newValue+"in");
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
    var depositMethod = function(singleRoom){
        var sum = 0
        if($scope.BookCommonInfo.rentType=="全日租"){
            sum = parseFloat(singleRoom.finalPrice) *
                Math.round(((new Date($scope.BookCommonInfo.CHECK_OT_DT)).getTime() - (new Date($scope.BookCommonInfo.CHECK_IN_DT)).getTime())/86400000);
        }else{
            sum =  parseFloat(singleRoom.finalPrice);
        }
        sum = Math.floor((sum+200)/100)*100;
        return sum;
    }

    var testFail = function(){
        return false;
    }

    /**********************************/
    /************** ********************************** Initial functions ******************************************* *************/
    var singleWalkInInit = function(RM_ID){
        newCheckInFactory.getSingleRoomInfo(RM_ID).success(function(data){
            $scope.RoomAllinfo = data;
            $scope.BookCommonInfo.roomSource="普通散客";
            $scope.BookRoom[0].RM_TP = $scope.RoomAllinfo[0].RM_TP;
            $scope.BookRoom[0].RM_ID = $scope.RoomAllinfo[0].RM_ID;
            $scope.BookRoom[0].SUGG_PRICE = $scope.RoomAllinfo[0].SUGG_PRICE;
            $scope.BookRoom[0].finalPrice = $scope.RoomAllinfo[0].SUGG_PRICE;
            $scope.roomsAndRoomTypes[$scope.RoomAllinfo[0].RM_TP]=[$scope.RoomAllinfo[0]];
        });
    };

    var initialMemCheck = function(checkInput,call_back){
            newCheckInFactory.searchMember(checkInput,["MEM_ID"]).success(function(data){
                updateMembers(data,true);
                call_back();
            });
    }

    var initialTreatyCheck = function(checkInput,call_back){
            newCheckInFactory.searchTreaties(checkInput,["TREATY_ID"]).success(function(data){
                updateTreaties(data,true);
                call_back();
            });

    }

    var initEditCall_back = function(){
        $timeout(function(){
            getTempPlan( editInitRentTypeCall_back );
        }, 0);
    }

    var editRoomInit = function(rmTp){
        newCheckInFactory.getRoomInfo().success(function(data){
            $scope.RoomAllinfo = data;
            initRoomsAndRoomTypes(roomST[0]["RM_ID"]);
            $scope.BookRoom[0].RM_TP = roomST[0]["RM_TP"];
            $scope.BookRoom[0].RM_ID = roomST[0]["RM_ID"];
            if (roomST[0]["LEAVE_TM"] != null &&roomST[0]["LEAVE_TM"] != "" )$scope.BookRoom[0].leaveTime = roomST[0]["LEAVE_TM"];
            $scope.BookCommonInfo.CHECK_OT_DT = roomST[0]["CHECK_OT_DT"];
            $scope.BookRoom[0].SUGG_PRICE = $scope.roomsAndRoomTypes[roomST[0]["RM_TP"]][0]["SUGG_PRICE"];
            for(var i = 0; i < roomST[0]["customer"].length; i++){
                if($scope.BookRoom[0].GuestsInfo.length <= i) $scope.BookRoom[0].GuestsInfo.push(createNewGuest());
                $scope.BookRoom[0].GuestsInfo[i].Name = roomST[0]["customer"][i].CUS_NAME;
                $scope.BookRoom[0].GuestsInfo[i].MemberId = roomST[0]["customer"][i].MEM_ID;
                $scope.BookRoom[0].GuestsInfo[i].Phone = roomST[0]["customer"][i].PHONE;
                $scope.BookRoom[0].GuestsInfo[i].SSN = roomST[0]["customer"][i].SSN;
                $scope.BookRoom[0].GuestsInfo[i].MEM_TP = roomST[0]["customer"][i].MEM_TP;
                $scope.BookRoom[0].GuestsInfo[i].Points = roomST[0]["customer"][i].POINTS;
                $scope.BookRoom[0].GuestsInfo[i].RemarkInput = roomST[0]["customer"][i].RMRK;
            }
            $scope.watcher.member=false;
            $scope.watcher.treaty=false;
            $scope.watcher.rentType=false;
            $scope.BookCommonInfo.roomSource=(roomST[0]["CHECK_TP"] == null || roomST[0]["CHECK_TP"] == "")?"普通散客":roomST[0]["CHECK_TP"];
            if (roomST[0]["CHECK_TP"]=="协议" && roomST[0]["TREATY_ID"] != null){
                $timeout(function(){
                    $scope.check["checkInput"] = roomST[0]["TREATY_ID"];
                    initialTreatyCheck($scope.check["checkInput"],initEditCall_back);
                }, 0);
            }else if(roomST[0]["CHECK_TP"]=="会员" && roomST[0]["MEM_ID"] != null){
                $timeout(function(){
                    $scope.check["checkInput"] = roomST[0]["MEM_ID"].toString();
                    initialMemCheck($scope.check["checkInput"],initEditCall_back);
                }, 0);
            }else{
                initEditCall_back();
            }
        });
    };

    var editInitRentTypeCall_back = function(){
        $scope.BookCommonInfo.rentType=(roomST[0]["TMP_PLAN_ID"] == null)? "全日租":roomST[0]["TMP_PLAN_ID"];
        $timeout(function(){
            $scope.BookRoom[0].finalPrice = roomST[0]["RM_AVE_PRCE"];
            $scope.watcher.member=true;
            $scope.watcher.treaty=true;
            $scope.watcher.rentType=true;
        },0);
    }

//   var multiWalkIn = function(roomST){
//       newCheckInFactory.getRoomInfo().success(function(data){
//           $scope.RoomAllinfo = data;
//           $scope.BookCommonInfo.roomSource="普通散客";
//           initRoomsAndRoomTypes();
//           for (var i=0; i< roomST.length; i++){
//               $scope.BookRoom[i].RM_TP = roomST[i].RM_TP;
//               $scope.BookRoom[i].RM_ID = roomST[i].RM_ID;
//               $scope.BookRoom[i].SUGG_PRICE = $scope.roomsAndRoomTypes[roomST[i].RM_TP][0].SUGG_PRICE;
//           }
//       });
//   };

    // get temp Plan for all kinds of room type
    var getTempPlan = function(call_back){
        newCheckInFactory.tempPlanGet().success(function(data){
            $scope.plans = data;
            $scope.plansOBJ={};
            for(var i=0; i<$scope.plans.length; i++){
                $scope.plans[i]['PLAN_COV_PRCE'] = util.Limit($scope.plans[i]['PLAN_COV_PRCE']);
                $scope.plans[i]['PNLTY_PR_MIN'] = util.Limit($scope.plans[i]['PNLTY_PR_MIN']);
                $scope.plansOBJ[$scope.plans[i].PLAN_ID] = $scope.plans[i];
            }
            if(call_back!=null) call_back();
        });
    }
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
    $scope.roomsAndRoomTypes = [];
    $scope.roomsDisableList = {};
    $scope.BookRoom = [];
    $scope.BookRoomByTP = {};   //  only for multi walk in or multi checkin
    createBookRoom(roomST.length);
    $scope.check= {checkInput: ""};
    $scope.watcher={"roomSource":true,"treaty":true,"member":true,"finalPrice":true,"discount":true,"paymentRequest":true,"rentType":true};

//    show(roomST)



    /**********************************/
    /************** ********************************** Initialize by conditions ********************************** *************/
    if(initialString == "singleWalkIn"){
        getTempPlan(null);
        singleWalkInInit(roomST[0].RM_ID);
//        reserveCheckInInit("单人间");
    }else if(initialString == "editRoom"){
        editRoomInit(roomST);
    }

    /**********************************/
    /************** ********************************** Page Logical Confinement ********************************** *************/

    // roomSource change: 1. search caption, 2. check bar disable, 3.discount
    $scope.$watch(function(){
            return $scope.BookCommonInfo.roomSource;
        },
        function(newValue, oldValue) {
            if(newValue == oldValue || $scope.watcher.roomSource===false) return;
            $scope.check.checkInput = "";
            switch(newValue){
                case '普通散客':
                    $scope.caption.searchCaption = "N/A";
                    $scope.disable.searchDisable = true;
                    updateDiscount("");
                    break;
                case '会员':
                    $scope.caption.searchCaption = "会员号/身份证/姓名/电话";
                    $scope.disable.searchDisable = false;
                    if($scope.BookCommonInfo.Member.DISCOUNT_RATE != undefined){
                        updateDiscount($scope.BookCommonInfo.Member.DISCOUNT_RATE);
                    }else{
                        updateDiscount("");
                    }
                    break;
                case '协议':
                    $scope.caption.searchCaption = "协议号/协议单位";
                    $scope.disable.searchDisable = false;
                    if($scope.BookCommonInfo.Treaty.DISCOUNT != undefined){
                        updateDiscount($scope.BookCommonInfo.Treaty.DISCOUNT);
                    }else{
                        updateDiscount("");
                    }
                    break;
                case '活动码':
                    $scope.caption.searchCaption = "N/A(暂时)";
                    $scope.disable.searchDisable = true;
                    updateDiscount("");
                    break;
                case '预定':
                    $scope.caption.searchCaption = "N/A(暂时)";
                    $scope.disable.searchDisable = true;
                    updateDiscount("");
            }
        },
        true
    );

    // Member change: change discount
    $scope.$watch(function(){
            return $scope.BookCommonInfo.Member;
        },
        function(newValue, oldValue) {
            if(newValue == oldValue || $scope.watcher.member===false) return;
            if($scope.BookCommonInfo.Member.DISCOUNT_RATE != undefined){
                updateDiscount($scope.BookCommonInfo.Member.DISCOUNT_RATE);
            }else{
                updateDiscount("");
            }
        },
        true
    );

    // Treaty change: change discount
    $scope.$watch(function(){
            return $scope.BookCommonInfo.Treaty;
        },
        function(newValue, oldValue) {
            if(newValue == oldValue || $scope.watcher.treaty===false) return;
            if($scope.BookCommonInfo.Treaty.DISCOUNT != undefined){
                updateDiscount($scope.BookCommonInfo.Treaty.DISCOUNT);
            }else{
                updateDiscount("");
            }
        },
        true
    );

    //rentType change, price change
    $scope.$watch(function(){
            return $scope.BookCommonInfo.rentType;
        },
        function(newValue, oldValue) {
            if(newValue == oldValue || $scope.watcher.rentType===false) return;
//            show($scope.BookRoom[0].finalPrice);
            if(newValue != "全日租"){
            //    $scope.$apply(function(){
                $scope.BookCommonInfo.CHECK_OT_DT = util.dateFormat(today);
                $scope.BookCommonInfo.leaveTime = new Date($scope.plansOBJ[newValue].PLAN_COV_MIN*60*1000 + today.getTime());
            }else {
                $scope.BookCommonInfo.CHECK_OT_DT = util.dateFormat(tomorrow);
                $scope.BookCommonInfo.leaveTime = $scope.dateTime;
            }
            for (var i =0 ; i<$scope.BookRoom.length; i++){
                updateFinalPrice($scope.BookRoom[i]);
            }
        },
        true
    );



    // RM_TP change induce price change
    $scope.roomTypeChange = function(singleRoom){
        updateForRmtpChange(singleRoom);
    }


    // discount rate and final price
    $scope.discountChange = function(singleRoom) {
        updateFinalPrice(singleRoom);
    }


    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };

    /**********************************/
    /************** ********************************** room source check ********************************** *************/
    $scope.checkSource = function(source,checkInput){
        checkInput = checkInput.toString().trim();
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
                updateMembers(data,null);
            });
        }else if(util.isSSN(checkInput)){
            newCheckInFactory.searchMember(checkInput,["SSN"]).success(function(data){
                updateMembers(data,null);
            });
        }else{
            newCheckInFactory.searchMember(checkInput,["MEM_ID","PHONE"]).success(function(data){
                updateMembers(data,null);
            });
        }
    }

        /********** ********** TreatyCheck ******* *************/
    $scope.treatyCheck = function(checkInput){
        newCheckInFactory.searchTreaties(checkInput,["TREATY_ID","CORP_NM"]).success(function(data){
            updateTreaties(data,null);
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
    /************** ********************************** page change  ********************************** *************/
    $scope.confirm = function(){
        if(initialString == "singleWalkIn"){
            $scope.viewClick = "Pay";
            for(var i = 0; i < $scope.BookRoom.length; i++){
                var payment = $scope.BookRoom[i].payment;
                payment.paymentRequest = depositMethod($scope.BookRoom[i]);
                payment.payByMethods[0].payAmount = payment.paymentRequest;
                payment.payInDue = 0;
                payment.payByMethods[0].payMethod = "现金";
            }
        }else if(initialString == "editRoom"){
            var sum=0;
            for(var i = 0; i < $scope.BookRoom.length; i++){
                var payment = $scope.BookRoom[i].payment;
                payment["backUppaid"] = depositMethod($scope.BookRoom[i]);
                payment.paymentRequest = payment.backUppaid - roomST[0].DPST_RMN;
                payment.payByMethods[0].payAmount = payment.paymentRequest;
                payment.payInDue = 0;
                payment.payByMethods[0].payMethod = "现金";
                sum = sum + payment.paymentRequest;
//                show(payment)
            }
            if(sum != 0){
                $scope.viewClick = "Pay";
            }else{
                $scope.editSubmit(false);
            }
        }
    }

    $scope.backward = function(){
        if(initialString!='singleWalkIn'){
            for(var i = 0; i < $scope.BookRoom.length; i++){
                var payment = $scope.BookRoom[i].payment;
                payment.paymentRequest = payment["backUppaid"];
            }
        }
        $scope.viewClick = "Info";
    }

    /************************************************/
    /************** ********************************** pay  ********************************** *************/

    $scope.addNewPayByMethod = function(singleRoom){
//        if(initialString!='editReservation') $scope.BookCommonInfo.payment.paymentRequest = $scope.BookCommonInfo.payment.backUpPrepaid;
        singleRoom.payment.payByMethods.push(createNewPayByMethod());
    }

    /************************************************/
    /************** ********************************** submit  ********************************** *************/

    $scope.submit = function(){
        if (testFail()) return;
        $scope.SubmitInfo=[];

        for (var i = 0; i<$scope.BookRoom.length; i++){
            var room = $scope.BookRoom[i];
            var CHECK_IN_DT =  $scope.BookCommonInfo.CHECK_IN_DT;
            var CHECK_OT_DT =  $scope.BookCommonInfo.CHECK_OT_DT;
            $scope.SubmitInfo.push({roomSelect:room.RM_ID, roomType:room.RM_TP, CHECK_IN_DT: CHECK_IN_DT
                ,CHECK_OT_DT: CHECK_OT_DT, leaveTime:util.toLocal($scope.BookCommonInfo.leaveTime), finalPrice: room.finalPrice,
                roomSource:$scope.BookCommonInfo.roomSource, GuestsInfo: room.GuestsInfo,pay:room.payment});

            if ($scope.BookCommonInfo.roomSource == "协议" && $scope.BookCommonInfo.Treaty != ""){
                $scope.SubmitInfo[i]["TREATY_ID"]= $scope.BookCommonInfo.Treaty.TREATY_ID;
            }else if($scope.BookCommonInfo.roomSource == "会员" && $scope.BookCommonInfo.Member != ""){
                $scope.SubmitInfo[i]["MEM_ID"]= $scope.BookCommonInfo.Member.MEM_ID;
            }

            if($scope.BookCommonInfo.rentType!="全日租"){
                $scope.SubmitInfo[i]["TMP_PLAN_ID"]=$scope.BookCommonInfo.rentType;
            }else{
                $scope.SubmitInfo[i]["TMP_PLAN_ID"]='';
            }

            if ($scope.BookCommonInfo.Master.CONN_RM_ID != ""){
                $scope.SubmitInfo[i]["CONN_RM_ID"]=$scope.BookCommonInfo.Master.CONN_RM_ID;
                if( $scope.SubmitInfo[i]["roomSelect"] == $scope.BookCommonInfo.Master.CONN_RM_ID){
                    // master room pay all deposit
                    $scope.SubmitInfo[i]["pay"]["payByMethods"] = $scope.BookCommonInfo.Master.payment["payByMethods"];
                }else{
                    // non-master room pay no deposit
                    $scope.SubmitInfo[i]["pay"]["payByMethods"] = [];
                }

            }else{
                $scope.SubmitInfo[i]["CONN_RM_ID"]="";
            }

            // always move master room to the front
            if( i!=0 && $scope.SubmitInfo[i]["roomSelect"] == $scope.BookCommonInfo.Master.CONN_RM_ID){
                var temp = JSON.parse(JSON.stringify($scope.SubmitInfo[0]));
                $scope.SubmitInfo[0]= JSON.parse(JSON.stringify($scope.SubmitInfo[i]));
                $scope.SubmitInfo[i] = temp;
            }
        }

//    var resetAvail=[];
//    if (initialString!="singleWalkIn"){
//        resetAvail =[decodeURI(RoomNumArray[1]),RoomNumArray[2],new Date(RoomNumArray[4].replace("-","/"))
//            ,new Date(RoomNumArray[5].replace("-","/"))];
//    }
        alert(JSON.stringify($scope.SubmitInfo));
        newCheckInFactory.submit(JSON.stringify({SubmitInfo:$scope.SubmitInfo,RESV_ID:null,unfilled:null})).success(function(data){
            show("办理成功!");
            $modalInstance.close("checked");
            util.closeCallback();
        });
    }


    // for edit
    $scope.editSubmit = function(moneyInvolved){
        for (var i = 0; i<$scope.BookRoom.length; i++){
            var room = $scope.BookRoom[i];
            var CHECK_IN_DT =  util.dateFormat(today);
            var CHECK_OT_DT =  $scope.BookCommonInfo.CHECK_OT_DT;
            $scope.SubmitInfo=[];
            $scope.SubmitInfo.push({roomSelect:room.RM_ID, roomType:room.RM_TP, CHECK_IN_DT: CHECK_IN_DT
                ,CHECK_OT_DT: CHECK_OT_DT, leaveTime:util.toLocal($scope.BookCommonInfo.leaveTime), finalPrice: room.finalPrice,
                roomSource:$scope.BookCommonInfo.roomSource, GuestsInfo: room.GuestsInfo,pay:room.payment});

            if ($scope.BookCommonInfo.roomSource == "协议" && $scope.BookCommonInfo.Treaty != ""){
                $scope.SubmitInfo[i]["TREATY_ID"]= $scope.BookCommonInfo.Treaty.TREATY_ID;
            }else if($scope.BookCommonInfo.roomSource == "会员" && $scope.BookCommonInfo.Member != ""){
                $scope.SubmitInfo[i]["MEM_ID"]= $scope.BookCommonInfo.Member.MEM_ID;
            }

            if($scope.BookCommonInfo.rentType!="全日租"){
                $scope.SubmitInfo[i]["TMP_PLAN_ID"]=$scope.BookCommonInfo.rentType;
            }else{
                $scope.SubmitInfo[i]["TMP_PLAN_ID"]='';
            }

            if ($scope.BookCommonInfo.Master.CONN_RM_ID != ""){
                $scope.SubmitInfo[i]["CONN_RM_ID"]=$scope.BookCommonInfo.Master.CONN_RM_ID;
                if( $scope.SubmitInfo[i]["roomSelect"] == $scope.BookCommonInfo.Master.CONN_RM_ID){
                    // master room pay all deposit
                    $scope.SubmitInfo[i]["pay"]["payByMethods"] = $scope.BookCommonInfo.Master.payment["payByMethods"];
                }else{
                    // non-master room pay no deposit
                    $scope.SubmitInfo[i]["pay"]["payByMethods"] = [];
                }

            }else{
                $scope.SubmitInfo[i]["CONN_RM_ID"]="";
            }

            // always move master room to the front
            if( i!=0 && $scope.SubmitInfo[i]["roomSelect"] == $scope.BookCommonInfo.Master.CONN_RM_ID){
                var temp = JSON.parse(JSON.stringify($scope.SubmitInfo[0]));
                $scope.SubmitInfo[0]= JSON.parse(JSON.stringify($scope.SubmitInfo[i]));
                $scope.SubmitInfo[i] = temp;
            }

            alert(JSON.stringify($scope.SubmitInfo));
            newCheckInFactory.modify(JSON.stringify({SubmitInfo:$scope.SubmitInfo,
                    RM_TRAN_ID:roomST[0].RM_TRAN_ID,initialString:initialString,moneyInvolved:moneyInvolved})).success(function(data){
//                show(data);
                $modalInstance.close("checked");
                util.closeCallback();
            });
        }
    };
    /*********************************************/

})
/************************                       singleRoom sub controller                      ***********************/
.controller('scheckSingleRoomCtrl', function ($scope) {
    $scope.$watch('singleRoom.RM_ID',
        function(newValue, oldValue) {
            $scope.$parent.updateDisabledRmId(oldValue,newValue);
        },
        true
    );
})
/************************                       singleRoomPay sub controller                      ***********************/
.controller('scheckSingleRoomPayCtrl', function ($scope) {
    $scope.$watch('singleRoom.payment.paymentRequest',
        function(newValue, oldValue) {
            $scope.$parent.$parent.updatePayInDue($scope.singleRoom);
        },
        true
    );
})
/************************                       singlePay sub controller                      ***********************/
.controller('scheckSinglePayCtrl', function ($scope) {
    $scope.$watch('singlePay.payAmount',
        function(newValue, oldValue) {
            $scope.$parent.$parent.updatePayInDue($scope.$parent.singleRoom);
        },
        true
    );
});

