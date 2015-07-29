/**
 * Created by Xharlie on 12/22/14.
 */
app.controller('checkInModalController', function($scope, $http, focusInSideFactory,newCheckInFactory, paymentFactory,roomFactory,
                                                  sessionFactory, $modalInstance,$timeout, roomST,initialString){

    /********************************************     validation     ***************************************************/
    $scope.hasError = function(btnPass){
        if(eval("$scope."+btnPass)==null) eval("$scope."+btnPass+"=0");
        eval("$scope."+btnPass+"++");
    }
    $scope.noError = function(btnPass){
        eval("$scope."+btnPass+"--");
    }
    $scope.payError = 0;
    /********************************** simulate select **************************************/
    //$scope.selectValue = roomFactory.selectValue;

    /********************************************     utility     ***************************************************/

    var today = new Date();
    var tomorrow = new Date(today.getTime()+86400000);
    $scope.dateTime = new Date((tomorrow).setHours(12,0,0));
    $scope.dateFormat = function(rawDate){
        return util.dateFormat(rawDate);
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
        var discount = (singleRoom.discount==="")? 100: singleRoom.discount;
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


    // need improved
    var depositMethod = function(singleRoom){
        var sum = 0
        if($scope.BookCommonInfo.rentType=="全日租"){
            sum = parseFloat(singleRoom.finalPrice) *
                Math.round((    (new Date($scope.BookCommonInfo.CHECK_OT_DT)).getTime()
                                - (today.getTime())   )/86400000);
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

    function focusInit(id){
        focusInSideFactory.tabInit(id);
        $timeout(function(){
            focusInSideFactory.manual(id);
        },0)
    }
    var singleWalkInInit = function(RM_ID){
        newCheckInFactory.getSingleRoomInfo(RM_ID).success(function(data){
            $scope.ready = true;
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
                $scope.BookCommonInfo.initFlag = true;
                $scope.BookCommonInfo.Members = data;
                call_back();
                $scope.BookCommonInfo.initFlag = null; // close initFlag
            });
    }

    var initialTreatyCheck = function(checkInput,call_back){
            newCheckInFactory.searchTreaties(checkInput,["TREATY_ID"]).success(function(data){
                $scope.BookCommonInfo.initFlag = true;
                $scope.BookCommonInfo.Treaties = data;
                call_back();
                $scope.BookCommonInfo.initFlag = null; // close initFlag
            });

    }

    var initEditCall_back = function(){
        $timeout(function(){
            getTempPlan( editInitRentTypeCall_back );
        }, 0);
    }

    var editRoomInit = function(rmTp){
        newCheckInFactory.getRoomInfo().success(function(data){
            $scope.ready = true;
            $scope.RoomAllinfo = data;
            initRoomsAndRoomTypes(roomST[0]["RM_ID"]);
            $scope.BookRoom[0].RM_TP = roomST[0]["RM_TP"];
            $scope.BookRoom[0].RM_ID = roomST[0]["RM_ID"];
            $scope.BookCommonInfo.CHECK_OT_DT = new Date(roomST[0]["CHECK_OT_DT"]);
            $scope.BookCommonInfo.CHECK_IN_DT = new Date(roomST[0]["CHECK_IN_DT"]);
            if (roomST[0]["IN_TM"] != null &&roomST[0]["IN_TM"] != "" )
                $scope.BookCommonInfo.inTime = new Date(util.time2DateTime($scope.BookCommonInfo.CHECK_IN_DT,roomST[0]["IN_TM"]));
            if (roomST[0]["LEAVE_TM"] != null &&roomST[0]["LEAVE_TM"] != "" )
                $scope.BookCommonInfo.leaveTime = new Date(util.time2DateTime($scope.BookCommonInfo.CHECK_OT_DT ,roomST[0]["LEAVE_TM"]));
            $scope.BookRoom[0].SUGG_PRICE = $scope.roomsAndRoomTypes[roomST[0]["RM_TP"]][0]["SUGG_PRICE"];
            for(var i = 0; i < roomST[0]["customers"].length; i++){
                if($scope.BookRoom[0].GuestsInfo.length <= i) $scope.BookRoom[0].GuestsInfo.push(roomFactory.createNewGuest());
                $scope.BookRoom[0].GuestsInfo[i].Name = roomST[0]["customers"][i].CUS_NAME;
                $scope.BookRoom[0].GuestsInfo[i].MemberId = roomST[0]["customers"][i].MEM_ID;
                $scope.BookRoom[0].GuestsInfo[i].Phone = roomST[0]["customers"][i].PHONE;
                $scope.BookRoom[0].GuestsInfo[i].SSN = roomST[0]["customers"][i].SSN;
                $scope.BookRoom[0].GuestsInfo[i].MEM_TP = roomST[0]["customers"][i].MEM_TP;
                $scope.BookRoom[0].GuestsInfo[i].Points = roomST[0]["customers"][i].POINTS;
                $scope.BookRoom[0].GuestsInfo[i].DOB = roomST[0]["customers"][i].DOB;
                $scope.BookRoom[0].GuestsInfo[i].Address = roomST[0]["customers"][i].ADDRSS;
                $scope.BookRoom[0].GuestsInfo[i].RemarkInput = roomST[0]["customers"][i].RMRK;
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
    if(initialString == "singleWalkIn"){
        $scope.initialString=initialString;
        //focusInit('wholeModal');
    }else{
        $scope.initialString='edit';
        //focusInit(initialString);
    }
    $scope.BookCommonInfo = {CHECK_IN_DT: today,CHECK_OT_DT: tomorrow,leaveTime:$scope.dateTime,inTime:today,initFlag:null,Members :[],
    Treaties : [], roomSource:'', rentType:"全日租",Member:{},Treaty:{},Master:{CONN_RM_ID:"",payment:paymentFactory.createNewPayment('住房押金')}};
    $scope.caption = {searchCaption:"",resultCaption:""};
    $scope.styles = {CheckInStyle:{},CheckOTStyle:{},memStyle:{}};
    $scope.disable = {searchDisable:false};
    $scope.roomsAndRoomTypes = [];
    $scope.roomsDisableList = {};
    $scope.BookRoom = [];
    $scope.BookRoomByTP = {};   //  only for multi walk in or multi checkin
    roomFactory.createBookRoom($scope.BookRoom,roomST.length,'住房押金');
    $scope.check= {checkInput: ""};
    $scope.watcher={"roomSource":true,"treaty":true,"member":true,"finalPrice":true,"discount":true,"paymentRequest":true,"rentType":true};
    $scope.submitLoading = false;
    $scope.payMethodOptions=paymentFactory.checkInPayMethodOptions();
    $scope.ready=false;
    $scope.roomST =roomST;
    /**********************************/
    /************** ********************************** Initialize by conditions ********************************** *************/
    if($scope.initialString == "singleWalkIn"){
        getTempPlan(null);
        singleWalkInInit(roomST[0].RM_ID);
    }else if($scope.initialString == "edit"){
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
                    break;
                case '免费房':
                    $scope.caption.searchCaption = "N/A(暂时)";
                    $scope.disable.searchDisable = true;
                    updateDiscount(0);
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
            if(newValue != "全日租"){
            //    $scope.$apply(function(){
                $scope.BookCommonInfo.CHECK_OT_DT = today;
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
    /************** ********************************** guest  ********************************** *************/
    // add customer
    $scope.addCustomer = function(guestInfo){
        guestInfo.push(roomFactory.createNewGuest());
    }
    // remove customer
    $scope.deleteCustomer = function(GuestsInfo,index){
        if(GuestsInfo.length != 1){
            GuestsInfo.splice(index,1);
        }
    }
    // check the identity, guest history through ssn
    $scope.showIdentity = function(singleGuest,index){
        /*****
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
        ******/
    }
    /************** ********************************** hardware functions ******************************************* *************/


    $scope.readFromIDCard = function(singleGuest){
        var cusSSNInfo = printer.readIDCard();
        singleGuest.Name = cusSSNInfo.CUS_NAME;
        singleGuest.SSN = cusSSNInfo.SSN;
        singleGuest.DOB = cusSSNInfo.guestDOB;
        singleGuest.Address = cusSSNInfo.guestAddress;
    }

    function preparePrintInfo(room,pms,data){
        room.CHECK_IN_DT = util.dateFormat($scope.BookCommonInfo.CHECK_IN_DT);
        room.inTime = util.timeFormat($scope.BookCommonInfo.inTime);
        room.CHECK_OT_DT = util.dateFormat($scope.BookCommonInfo.CHECK_OT_DT);
        room.leaveTime = util.timeFormat($scope.BookCommonInfo.leaveTime);
        room.CONN_RM_TRAN_ID = data.CONN_RM_TRAN_ID;
        room.RM_TRAN_ID = data.RM_TRAN_ID;
        //printer.printMoveInCheck(pms,room,room.GuestsInfo[0]);
        //setTimeout (function(){
        printer.printDepositCheck(pms,room,room.GuestsInfo[0]);
        //}, 5000);
    }

    /************************************************/
    /************** ********************************** page change  ********************************** *************/
    $scope.confirm = function(){
        if($scope.initialString == "singleWalkIn"){
            for(var i = 0; i < $scope.BookRoom.length; i++){
                var payment = $scope.BookRoom[i].payment;
                payment.paymentRequest = util.Limit(depositMethod($scope.BookRoom[i]));
                payment.payByMethods[0].payAmount = payment.paymentRequest;
                payment.payInDue = 0;
                payment.payByMethods[0].payMethod = "现金";
            }
            $scope.viewClick = "Pay";
        }else if($scope.initialString == "edit"){
                $scope.editSubmit(false);
        }
    }

    $scope.backward = function(){
        if($scope.initialString!='singleWalkIn'){
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
//        if($scope.initialString!='editReservation') $scope.BookCommonInfo.payment.paymentRequest = $scope.BookCommonInfo.payment.backUpPrepaid;
        singleRoom.payment.payByMethods.push(paymentFactory.createNewPayByMethod());
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
                ,CHECK_OT_DT: CHECK_OT_DT, inTime:util.timeFormat($scope.BookCommonInfo.inTime),
                leaveTime: util.timeFormat($scope.BookCommonInfo.leaveTime),finalPrice: room.finalPrice,
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
                    $scope.SubmitInfo[j]["pay"]["payByMethods"] = $scope.BookCommonInfo.Master.payment["payByMethods"];
                }else{
                    // non-master room pay no deposit
                    $scope.SubmitInfo[j]["pay"]["payByMethods"] = [];
                }

            }else{
                $scope.SubmitInfo[j]["CONN_RM_ID"]="";
            }
            // always move master room to the front
            if( j!=0 && $scope.SubmitInfo[i]["roomSelect"] == $scope.BookCommonInfo.Master.CONN_RM_ID){
                var temp = JSON.parse(JSON.stringify($scope.SubmitInfo[0]));
                $scope.SubmitInfo[0]= JSON.parse(JSON.stringify($scope.SubmitInfo[j]));
                $scope.SubmitInfo[j] = temp;
            }
            j++;
        }

        var pms ={HTL_NM:null,EMP_NM:null};
        sessionFactory.getUserInfo().success(function(data){
            pms.HTL_NM = data.HTL_NM;
            pms.EMP_NM = data.EMP_NM;

            newCheckInFactory.submit(JSON.stringify({SubmitInfo:$scope.SubmitInfo,RESV_ID:null,unfilled:null})).success(function(data){
                $scope.submitLoading = false;
                show("办理成功!");
                var room = $scope.BookRoom[0];
                preparePrintInfo($scope.BookRoom[0], pms, data[0]);
                $modalInstance.close("checked");
            });
        });
    }



    // for edit
    $scope.editSubmit = function(moneyInvolved){
        if (testFail()) return;
        $scope.submitLoading = true;
        for (var i = 0; i<$scope.BookRoom.length; i++){
            var room = $scope.BookRoom[i];
            var CHECK_IN_DT =  util.dateFormat($scope.BookCommonInfo.CHECK_IN_DT);
            var CHECK_OT_DT =  util.dateFormat($scope.BookCommonInfo.CHECK_OT_DT);
            $scope.SubmitInfo=[];
            $scope.SubmitInfo.push({roomSelect:room.RM_ID, roomType:room.RM_TP, CHECK_IN_DT: CHECK_IN_DT
                ,CHECK_OT_DT: CHECK_OT_DT, leaveTime:util.timeFormat($scope.BookCommonInfo.leaveTime), finalPrice: room.finalPrice,
                roomSource:$scope.BookCommonInfo.roomSource, GuestsInfo: room.GuestsInfo});
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

            // always move master room to the front
            if( i!=0 && $scope.SubmitInfo[i]["roomSelect"] == $scope.BookCommonInfo.Master.CONN_RM_ID){
                var temp = JSON.parse(JSON.stringify($scope.SubmitInfo[0]));
                $scope.SubmitInfo[0]= JSON.parse(JSON.stringify($scope.SubmitInfo[i]));
                $scope.SubmitInfo[i] = temp;
            }

            newCheckInFactory.modify(JSON.stringify({SubmitInfo:$scope.SubmitInfo,today:util.dateFormat(today),
                    RM_TRAN_ID:roomST[0].RM_TRAN_ID,initialString:$scope.initialString})).success(function(data){
                $scope.submitLoading = false;
                show("办理成功!");
                $modalInstance.close("checked");
                //util.closeCallback();
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
});
