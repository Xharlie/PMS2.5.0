appCheckIn.controller('newCheckInController', function($scope, $http, newCheckInFactory,$modal){
    /* Database version */
//    $locationProvider.html5Mode(true).hashPrefix('!');

    $scope.BookRoom = [];
    $scope.roomQuanOBJ = {};
    $scope.roomNegoPriceOBJ={};
    $scope.CONN_RM_ID = "";
    $scope.currentDate = new Date();
    $scope.dt1 =  new Date();
    $scope.viewClick ="";
    var pathArray = window.location.href.split("/");
    var actionType = pathArray.slice(pathArray.indexOf('newCheckIn')+1,pathArray.indexOf('newCheckIn')+2);
    $scope.RoomNumArray = pathArray.slice(pathArray.indexOf('newCheckIn')+2);

    var RESV_ID = "null";
    if (actionType == 'checkIn'){
        $scope.viewClick ="checkIn";
        $scope.button2Show ="submit";
        $scope.rmTpDisabled=false;
    }else if(actionType == 'modify'){
        $scope.viewClick ="modify";
        $scope.button2Show ="save";
        $scope.rmTpDisabled=true;
    }else if(actionType == 'reserveCheck'){
        $scope.viewClick ="modify";
        $scope.button2Show ="submit";
        $scope.rmTpDisabled=false;
        RESV_ID = $scope.RoomNumArray[0];
    }


    $scope.roomInType = [];
    $scope.ID_TP_match = {};
    $scope.ID_SUGG_match = {};
    $scope.roomTypeSelectQuan = {};
    $scope.CusQuan = {};
    $scope.GuestsCheckInfo={BirthInput:"", Gender:"",Province:""};
    $scope.BookCommonInfo = {CHECK_IN_DT:new Date(),CHECK_OT_DT:new Date(Number(new Date())+86400000),sourceCollapsed:[true,true,true,true],
                            CheckInStyle:{},CheckOTStyle:{},memStyle:{},roomSource:'',
                            checkSSN:'',checkMEM_ID:'',checkMEM:'',checkMEM_NM:'',checkMEM_TP:'',checkTreaty:'',
                            chekTreatyId:'',checkTreatyCorp:'',Treaties:[],treatyChoose:'',soldRaw:[]};

    var createNewRoom = function(){
        var newRoom =  {CHECK_IN_DT:new Date(),CHECK_OT_DT:new Date(Number(new Date())+86400000),
                        roomType:"",roomOldType:"",roomSelect:"",finalPrice:"",deposit:"",roomSource:'',
                        Cards_num:0,fixedDeposit:"200",expectedDeposit:"",treatyChoose:'',MasterRoom:'fasle',
                        GuestsInfo:[{MEM_TP:"",Points:"",Phone:"",SSNinput:"",SSNType:"SSN18",
                        NameInput:"",BirthInput:"", Gender:"",MemberId:"",Treaty:"",RemarkInput:"",Province:"",
                        markStyle:"",Pass:false,TIMES:""}]};
        return newRoom;
    }



/* initial rooms info get */

    newCheckInFactory.RoomUnAvail().success(function(data){
            $scope.RoomAllinfo =data;
            for (var i = 0; i <$scope.RoomAllinfo.length; i++ ){
                $scope.roomInType.push({RM_ID : $scope.RoomAllinfo[i]["RM_ID"],
                    RM_TP : $scope.RoomAllinfo[i]["RM_TP"],
                    RM_CONDITION: $scope.RoomAllinfo[i]["RM_CONDITION"],
                    CHECK_OT_DT :   $scope.RoomAllinfo[i]["CHECK_OT_DT"]
                });
                $scope.CusQuan[$scope.RoomAllinfo[i]["RM_TP"]] = $scope.RoomAllinfo[i]["CUS_QUAN"];
                $scope.ID_SUGG_match[$scope.RoomAllinfo[i]["RM_TP"]] = $scope.RoomAllinfo[i]["SUGG_PRICE"];
                $scope.ID_TP_match[$scope.RoomAllinfo[i]["RM_ID"]] = $scope.RoomAllinfo[i]["RM_TP"];
                if(actionType == 'modify' && $scope.RoomNumArray[0] == $scope.RoomAllinfo[i]["RM_ID"]){
                    var OldCHECK_IN_DT =  $scope.RoomAllinfo[i].CHECK_IN_DT;
                    var OldCHECK_OT_DT =  $scope.RoomAllinfo[i].CHECK_OT_DT;
                }
            }
        /* ----------------------------check whether is reserved check in or walk in      ---------------------------------*/
            if (actionType == 'reserveCheck'){
                for(var i = 0; i< parseInt($scope.RoomNumArray[2]); i++){
                    var newRoom = createNewRoom();
                    $scope.BookRoom.push(newRoom);
//                    $scope.BookRoom[i].roomSelect = $scope.RoomNumArray[i];
                    $scope.BookRoom[i].roomType = decodeURI($scope.RoomNumArray[1]);
                    $scope.BookRoom[i].roomOldType = decodeURI($scope.RoomNumArray[1]);
                    $scope.BookRoom[i].finalPrice = $scope.RoomNumArray[3];
//                    $scope.BookRoom[i]['CHECK_IN_DT'] = new Date($scope.RoomNumArray[4].replace("-","/"));
                    $scope.BookRoom[i]['CHECK_OT_DT'] = new Date($scope.RoomNumArray[5].replace("-","/"));
                }
        /* ----------------------------check whether is connected rooms or single room checkin      ---------------------------------*/
                if($scope.RoomNumArray[1]>1){
                    $scope.BookRoom[0]["MasterRoom"] = true;
                    $scope.CONN_RM_ID = $scope.BookRoom[0].roomSelect;
                }else{
                    $scope.MasterRoomDisplay = {"display":"none"};
                }
            }else{
                for (var i =0; i< $scope.RoomNumArray.length; i++){
                    var newRoom = createNewRoom();
                    $scope.BookRoom.push(newRoom);
                    $scope.BookRoom[i].roomSelect = $scope.RoomNumArray[i];
                    $scope.BookRoom[i].roomType = $scope.ID_TP_match[$scope.RoomNumArray[i]];
                    $scope.BookRoom[i].roomOldType = $scope.ID_TP_match[$scope.RoomNumArray[i]];
                }
      /* ----------------------------check whether is connected room Walk In      ---------------------------------*/
                if($scope.RoomNumArray.length < 2){
                    $scope.MasterRoomDisplay = {"display":"none"};
                }else{
                    $scope.BookRoom[0]["MasterRoom"] = true;
                    $scope.CONN_RM_ID = $scope.BookRoom[0].roomSelect;
                }
                if(actionType == 'modify'){
                    $scope.BookRoom[0]['CHECK_IN_DT'] = OldCHECK_IN_DT;
                    $scope.BookRoom[0]['CHECK_OT_DT'] = OldCHECK_OT_DT
                    newCheckInFactory.cusInRoom($scope.BookRoom[0].roomSelect).success(function(data){
                        var roomCus = data;
                        for (var i = 0; i< roomCus.length; i++){
                            var guest = {MEM_TP:roomCus[i]['MEM_TP'],Points:roomCus[i]['POINTS'],Phone:roomCus[i]['PHONE'],
                                SSNinput:roomCus[i]['SSN'],SSNType:"SSN18",
                                NameInput:roomCus[i]['CUS_NAME'],BirthInput:"", Gender:"",MemberId:roomCus[i]['MEM_ID'],
                                Treaty:roomCus[i]['TREATY_ID'],RemarkInput:roomCus[i]['RMRK'],Province:roomCus[i]['PROVNCE'],
                                markStyle:"",Pass:false,TIMES:""};
                            if (i == 0){
                                $scope.BookRoom[0].GuestsInfo[0] = guest;
                            }else{
                                $scope.BookRoom[0].GuestsInfo.push(guest);
                            }
                        }
                    });
                }
            }


        /* ----------------------------@$scope.roomQuanOBJ is object with all room type, room quantity(if all available), key value pair
                                       @$scope.roomTypeSelectQuan is room type, selected rooms for that room type, key value pair --*/

            newCheckInFactory.RoomQuan().success(function(data){
                $scope.roomQuan =data;
                for (var k=0; k< $scope.roomQuan.length; k++){
                    var key = $scope.roomQuan[k]["RM_TP"];
                    var value = $scope.roomQuan[k]["RM_QUAN"];
                    $scope.roomQuanOBJ[key]=value;
                    $scope.roomTypeSelectQuan[key] = ($scope.roomTypeCounter(key)).length;
                    $scope.roomNegoPriceOBJ[key]=$scope.ID_SUGG_match[key];
                }
                $scope.refreshRoomAvailability($scope.BookCommonInfo.CHECK_IN_DT,$scope.BookCommonInfo.CHECK_OT_DT);
            });
    });



/* Service to get temp room plans  */
    newCheckInFactory.tempPlanGet().success(function(data){
        $scope.plans = data;
        $scope.plansFirst = {};
        $scope.plansOBJ={};
        for(var i=0; i<$scope.plans.length; i++){
            $scope.plansOBJ[$scope.plans[i].PLAN_ID] = $scope.plans[i];
            $scope.plans[i]['PLAN_COV_PRCE'] = $scope.Limit($scope.plans[i]['PLAN_COV_PRCE']);
            $scope.plans[i]['PNLTY_PR_MIN'] = $scope.Limit($scope.plans[i]['PNLTY_PR_MIN']);
            $scope.plansFirst[$scope.plans[i].RM_TP] = $scope.plans[i].PLAN_ID;
        }
    });

/* check or uncheck temp room, change datePicker and stuff*/
    $scope.tempChecking = function(singleRoom){
        singleRoom.checkInDtDisabled = singleRoom.tempSelected;
        singleRoom.checkOtDtDisabled = singleRoom.tempSelected;
        if(singleRoom.tempSelected){
            singleRoom.CHECK_OT_DT = new Date();
        }else{
            singleRoom.CHECK_OT_DT = new Date(Number(new Date())+86400000);
        }
    }

/* Array of index of rooms have been selected for certain type */
    $scope.roomTypeCounter = function(roomType){
        var typeAtIndex = [];
        for (var i = 0; i<$scope.BookRoom.length; i++){
            if($scope.BookRoom[i].roomType == roomType){
                typeAtIndex.push(i);
            }
        }
        return typeAtIndex;
    }


/* get the available number for every room type,
    @$scope.roomQuanOBJRecorder is an object with room type, available number, key value pair */

    $scope.refreshRoomAvailability = function(dt1,dt2){
        $scope.BookCommonInfo.soldRaw = [];
        newCheckInFactory.RoomSoldOut(util.dateFormat(dt1), util.dateFormat(dt2)).then(function(data){
            $scope.BookCommonInfo.soldRaw =data;
            var soldArray = [];
            var dateOBJ ={};
            var dt1num = Number(dt1);
            var dt2num = Number(dt2);
            var counter = 0;
            var roomQuanOBJRecorder = JSON.parse(JSON.stringify($scope.roomQuanOBJ));
            for (var i = 0; i< $scope.BookCommonInfo.soldRaw.length; i++){
                var roomleft = $scope.BookCommonInfo.soldRaw[i]['RM_QUAN']
                    -$scope.BookCommonInfo.soldRaw[i]['RESV_QUAN']
                    - $scope.BookCommonInfo.soldRaw[i]['CHECK_QUAN'];
                if(roomleft<roomQuanOBJRecorder[$scope.BookCommonInfo.soldRaw[i]['RM_TP']]){
                    roomQuanOBJRecorder[$scope.BookCommonInfo.soldRaw[i]['RM_TP']] = roomleft;
                }
            }
            $scope.roomQuanOBJRecorder = roomQuanOBJRecorder;
        });
    }


/* if change the date, change the available quan as well */
$scope.dateChange = function(){
    if ($scope.BookCommonInfo.CHECK_IN_DT != undefined &&($scope.BookCommonInfo.CHECK_IN_DT instanceof Date) ){
        var dt1 = $scope.BookCommonInfo.CHECK_IN_DT;
        if($scope.BookCommonInfo.CHECK_OT_DT == undefined || $scope.BookCommonInfo.CHECK_OT_DT==""){
            var dt2 = dt1;
        }else{
            var dt2 = $scope.BookCommonInfo.CHECK_OT_DT;
        }
        $scope.refreshRoomAvailability(dt1,dt2);
    }
}


/* change selected room Num of certain roomType */
    $scope.changeRoomNum = function(roomType){
        if ( isNaN($scope.roomTypeSelectQuan[roomType]) ||
            $scope.roomTypeSelectQuan[roomType]<0 ||
            $scope.roomTypeSelectQuan[roomType]> $scope.roomQuanOBJRecorder[roomType]){
            if (isNaN($scope.roomTypeSelectQuan[roomType]) ||  $scope.roomTypeSelectQuan[roomType]<0){
                $scope.roomTypeSelectQuan[roomType] = 0;
            }
        }else{
            var typeAtIndex = $scope.roomTypeCounter(roomType);
        /*------------------------- if more than already have, add room one by one -------------------------*/
            if($scope.roomTypeSelectQuan[roomType]>typeAtIndex.length){
                for(var i=0; i< ($scope.roomTypeSelectQuan[roomType]-typeAtIndex.length); i++){
                    $scope.Addroom(roomType);
                }
            }else{
        /*------------------------- if less than already have, delete room one by one from bottom to top-------------------------*/
                for(var i = typeAtIndex.length-1; i>= ($scope.roomTypeSelectQuan[roomType]); i--){
                    $scope.DeleteRoomOnly(typeAtIndex[i]);
                }
            }
        }
    }

/*----------------------------------------------Change room number either in bar or individual room----------------------------------*/
/* add room for certain room type increase or adding single room */
    $scope.Addroom =  function(toAddRoomType){
        var newRoom = createNewRoom();
        newRoom['CHECK_IN_DT'] = $scope.BookCommonInfo.CHECK_IN_DT;
        newRoom['CHECK_OT_DT'] = $scope.BookCommonInfo.CHECK_OT_DT;
        newRoom['roomType'] = toAddRoomType;
        newRoom['roomOldType'] = toAddRoomType;
        $scope.BookRoom.push(newRoom);
    }

/* delete room from selection Quan  and  BookRoom array as well */
    $scope.DeleteRoom = function($index){
        $scope.roomTypeSelectQuan[$scope.BookRoom[$index].roomType]--;
        $scope.DeleteRoomOnly($index);
    }


/* delete room from selected room array */
    $scope.DeleteRoomOnly = function($index){
//        if($scope.BookRoom.length!=1){
            $scope.BookRoom.splice($index,1);
            $scope.BookRoom[$scope.BookRoom.length-1].AddStyle={'display': 'inline'};
//        }
    }


/* update roomTypeSelectionQuan by tracting old type and new type for a selected room */
    $scope.roomTypeNumUpdate =function(singleRoom){
        $scope.roomTypeSelectQuan[singleRoom.roomOldType]--;
        $scope.roomTypeSelectQuan[singleRoom.roomType]++;
        singleRoom.roomOldType = singleRoom.roomType;
    }


/*----------------------------------------------Change customers in an individual room----------------------------------*/
/* add new customer in a room */
    $scope.Addcustomer = function(parentIndex){
        var newGuest = {MEM_TP:"",Points:"",Phone:"",SSNinput:"",SSNType:"SSN18",NameInput:"",BirthInput:"", Gender:"",
            MemberId:"",Treaty:"",RemarkInput:"",Province:"",markStyle:"",Pass:false,TIMES:""};
        $scope.BookRoom[parentIndex].GuestsInfo.push(newGuest);
    }

/* delete a customer in a room */
    $scope.Deletecustomer = function(parentIndex,index){
        if($scope.BookRoom[parentIndex].GuestsInfo.length!=1){
            $scope.BookRoom[parentIndex].GuestsInfo.splice(index,1);
            $scope.BookRoom[parentIndex].GuestsInfo[$scope.BookRoom[parentIndex].GuestsInfo.length-1].AddStyle={'display': 'inline'};
        }
    }




/*----------------------------------------------change master room------------------------------------------------------*/
/* choose certain room as Master Room */
    $scope.clickMaster = function(singleRoom){
        if(!singleRoom.MasterRoom){
            for (var i = 0; i< $scope.BookRoom.length; i++){
                $scope.BookRoom[i].MasterRoom = false;
            }
            singleRoom.MasterRoom = true;
            $scope.CONN_RM_ID = singleRoom.roomSelect;
        }else{
            alert("联房主房不可不选");
            singleRoom.MasterRoomFlag = "true";
            $scope.$apply();
        }
    };

/* when change the master room, also eliminate the the masterRoomFlag */
    $scope.changeMaster = function(singleRoom){
        if (singleRoom.MasterRoomFlag == "true"){
            singleRoom.MasterRoomFlag ="";
            singleRoom.MasterRoom = true;
        }
    }




/*-----------------------------------------rm_id in selection filter and initial value-------------------------------------------------*/
/* room type filter for room id */
    $scope.roomTypeFilter = function(RM_TP){
            return function(room){
                 return (RM_TP == "" || room.RM_TP == RM_TP);
            };
    }

/* available date filter for room id */
    $scope.roomDateFilter = function(IN){
        return function(room){
            if(actionType != 'modify'){
                if (IN > new Date()){
                    return (new Date(room.CHECK_OT_DT) <  IN);
                }else {
                    return (room.RM_CONDITION == "空房" || room.RM_CONDITION == "维修" || room.RM_CONDITION=="脏房");
                }
            }else{
                return (room.RM_ID == $scope.RoomNumArray[0]);
            }
        };
    };

/* Initial choose room type for the reserved */
//    $scope.roomTpInit = function(singleRoom){
//        for (var i =0; i<$scope.roomQuan.length; i++){
//            if ($scope.roomQuan[i].RM_TP == singleRoom.roomType){
//                singleRoom.roomType = $scope.roomQuan[i].RM_TP;
//                return;
//            }
//        }
//    }

/* Initial choose room number for the walkin clicked room */
    $scope.roomNmInit = function(singleRoom){
        for (var i =0; i<$scope.roomInType.length; i++){
            if ($scope.roomInType[i].RM_ID == singleRoom.roomSelect){
            singleRoom.roomSelect = $scope.roomInType[i].RM_ID;
            return;
            }
        }
    }


//    $scope.MaptoType = function(singleRoom){
//        singleRoom.roomType = $scope.ID_TP_match[singleRoom.roomSelect];
//        singleRoom.roomOldType = $scope.ID_TP_match[singleRoom.roomSelect];
//    }

//    $scope.cardNum = function(type){
//        return ($scope.CusQuan[type]== undefined)? 0: $scope.CusQuan[type];
//    };

/*----------------------------------------------------- utility method -------------------------------------------------------------*/
    $scope.dateFormat = function(date){
        var yyyy = date.getFullYear().toString();
        var mm = (date.getMonth()+1).toString();
        var dd  = date.getDate().toString();
        return yyyy+"-" + (mm[1]?mm:"0"+mm[0])+"-" + (dd[1]?dd:"0"+dd[0]);
    }

    $scope.birthDateFormat = function(date){
        var mm = (date.getMonth()+1).toString();
        var dd  = date.getDate().toString();
        return (mm[1]?mm:"0"+mm[0])+"-" + (dd[1]?dd:"0"+dd[0]);
    }

    $scope.Limit = function(num){
        return parseFloat(num).toFixed(2);
    }




    $scope.sourceChange = function(){
        $scope.BookCommonInfo.sourceCollapsed = [true,true,true,true];
        $scope.BookCommonInfo.sourceCollapsed[$scope.BookCommonInfo.roomSource] = false;
//        for(var i=0; i<$scope.BookRoom.length;i++){
//            $scope.BookRoom[i].price='';
//            if($scope.BookCommonInfo.roomSource == 2){
//                if ($scope.BookCommonInfo.treatyChoose!=""){
//                    $scope.treatyChange();
//                }
//            }else{
//                $scope.BookCommonInfo.treatyChoose="";
//                $scope.BookRoom[i].finalPrice = $scope.ID_SUGG_match[$scope.BookRoom[i].roomType];
//            }
//        }
    }

/*----------------------------------------------------- check Membership method -------------------------------------------------------------*/

    $scope.checkMem = function(MEM){
        if (MEM.length == 18){
            $scope.checkMEMbySSN(MEM);
        }else{
            $scope.checkMEMbyID(MEM);
        }
    }

    $scope.checkMEMbySSN = function(SSN){
        $scope.BookCommonInfo.checkSSN = '';
        $scope.BookCommonInfo.checkMEM_ID = '';
        $scope.BookCommonInfo.checkMEM_TP = '';
        $scope.BookCommonInfo.checkMEM_NM = '';
        newCheckInFactory.MemberBySSN(SSN).success(function(data){
            if (data.length<1){
                alert("查不到");
                return;
            }
            $scope.BookCommonInfo.checkSSN = data[0].SSN;
            $scope.BookCommonInfo.checkMEM_ID = data[0].MEM_ID;
            $scope.BookCommonInfo.checkMEM_TP = data[0].MEM_TP;
            $scope.BookCommonInfo.checkMEM_NM = data[0].MEM_NM;

        });
    }

    $scope.checkMEMbyID = function(ID){
        $scope.BookCommonInfo.checkSSN = '';
        $scope.BookCommonInfo.checkMEM_ID = '';
        $scope.BookCommonInfo.checkMEM_TP = '';
        $scope.BookCommonInfo.checkMEM_NM = '';
        newCheckInFactory.MemberByID(ID).success(function(data){
            if (data.length<1){
                alert("查不到");
                return;
            }
            $scope.BookCommonInfo.checkSSN = data[0].SSN;
            $scope.BookCommonInfo.checkMEM_ID = data[0].MEM_ID;
            $scope.BookCommonInfo.checkMEM_TP = data[0].MEM_TP;
            $scope.BookCommonInfo.checkMEM_NM = data[0].MEM_NM;
        });
    }

/*----------------------------------------------------- check Treaty method -------------------------------------------------------------*/
    $scope.checkTREATY = function(treaty){
        if(isNaN(treaty)){
            $scope.checkTREATYbyCorp(treaty);
        }else{
            $scope.checkTREATYbyID(treaty);
        }
    }

    $scope.checkTREATYbyID = function(id){
        $scope.BookCommonInfo.treatyChoose='';
        newCheckInFactory.TreatyByID(id).success(function(data){
            if (data.length<1){
                alert("查不到");
                return;
            }
            $scope.BookCommonInfo.Treaties = data;
        });
    }

    $scope.checkTREATYbyCorp = function(corp){
        $scope.BookCommonInfo.treatyChoose='';
        newCheckInFactory.TreatyByCORP(corp).success(function(data){
            if (data.length<1){
                alert("查不到");
                return;
            }
            $scope.BookCommonInfo.Treaties = data;
        });
    }

/* select treaty and change the price of rooms */
    $scope.treatyChange = function(){
//        if ($scope.BookCommonInfo.roomSource == 2){
//            for (var i=0; i< $scope.BookRoom.length; i++){
//                var singleRoom = $scope.BookRoom[i];
//                singleRoom.price = $scope.ID_SUGG_match[singleRoom.roomType]*($scope.BookCommonInfo.treatyChoose.DISCOUNT)/100;
//                singleRoom.finalPrice = singleRoom.price;
//                singleRoom.price = '* '+($scope.BookCommonInfo.treatyChoose.DISCOUNT/100).toString()+'='+singleRoom.price.toString();
//            }
//        };
    }

/* change the source so does the price of rooms */




//    $scope.BookRoom[0].GuestsInfo.push({MEM_TP:"",Points:"",Phone:"",SSNinput:"",SSNType:"SSN18",NameInput:"",BirthInput:"", Gender:"",MemberId:"",Treaty:"",RemarkInput:"",Province:"",markStyle:"",Pass:false, TIMES:""});


/*  mouse over 自动识别 check SSN valid or not */
    $scope.smartIdentify = function(singleGuest){
        singleGuest.SSNinput.replace(/^\s+|\s+$/g,"");
        if(singleGuest.SSNinput == ""){
            return "请您输入客人证件号以查询";
        } else if(singleGuest.SSNType=="SSN18"){
                var sIdCard = JSON.parse(JSON.stringify(singleGuest.SSNinput));
                if (sIdCard.match(/^\d{17}(\d|X)$/gi)==null) {//判断是否全为18或15位数字，最后一位可以是大小写字母X
                    singleGuest.Pass=false;
                    return "身份证号码须为18位数字";      //允许用户输入大小写X代替罗马数字的Ⅹ
                }
                else if (sIdCard.length==18) {
                    if (CheckIdCard.province(sIdCard) && CheckIdCard.birthday18(sIdCard) &&CheckIdCard.validate(sIdCard)) {
                       singleGuest.Pass=true;
                       $scope.GuestsCheckInfo.Gender= CheckIdCard.gender18(sIdCard);
                       return "身份证号码合法";
                    }
                    else{
                        singleGuest.Pass=false;
                        if(!CheckIdCard.province(sIdCard)){
                            return "证件前二位省份代码错误";
                        }else if(!CheckIdCard.birthday18(sIdCard)){
                            return "证件出生日期部分错误";
                        }else if(!CheckIdCard.validate(sIdCard)){
                            return "证件末位验证位错误";
                        }
                    }
                }

        }else{
              return "检测功能更新中";
        }
    }


/*  click 自动识别 get customer name by SSN inputted  */
    $scope.showIdentity = function(singleGuest){
        singleGuest.TIMES='';
        if (singleGuest.Pass == true){
            singleGuest.BirthInput=$scope.GuestsCheckInfo.BirthInput;
            singleGuest.Gender=$scope.GuestsCheckInfo.Gender;
            singleGuest.Province=$scope.GuestsCheckInfo.Province;
            var $SSN =singleGuest.SSNinput;
            newCheckInFactory.HistoCustomer($SSN).success(function(data){
                var history  = data;
                if (data!= undefined && data.length>0){
                    singleGuest.NameInput = history[0].NM;
                    singleGuest.MemberId = history[0].MEM_ID;
                    singleGuest.TIMES = history[0].TIMES;
                    if (singleGuest.MemberId != undefined && singleGuest.MemberId != ""){

                            newCheckInFactory.MemberByID(singleGuest.MemberId).success(function(d){
                                singleGuest.cusInfoCollapsed = false;
                                singleGuest.Points=d[0].POINTS;
                                singleGuest.Province=d[0].PROV;
                                if(singleGuest.Phone.trim() == ""){
                                    singleGuest.Phone=d[0].PHONE;
                                }
                            });
                    }
                }
            });

        }
    }




// check Card
    var CheckIdCard={
        //Wi 加权因子 Xi 余数0~10对应的校验码 Pi省份代码
        Wi:[7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2],
        Xi:[1,0,"X",9,8,7,6,5,4,3,2],
        Pi:[11,12,13,14,15,21,22,23,31,32,33,34,35,36,37,41,42,43,44,45,46,50,51,52,53,54,61,62,63,64,65,71,81,82,91],

        //检验18位身份证号码出生日期是否有效
        //parseFloat过滤前导零，年份必需大于等于1900且小于等于当前年份，用Date()对象判断日期是否有效。
        birthday18:function(sIdCard){
            var year=parseFloat(sIdCard.substr(6,4));
            var month=parseFloat(sIdCard.substr(10,2));
            var day=parseFloat(sIdCard.substr(12,2));
            var checkDay=new Date(year,month-1,day);
            var nowDay=new Date();
            if (1900<=year && year<=nowDay.getFullYear() && month==(checkDay.getMonth()+1) && day==checkDay.getDate()) {
                $scope.GuestsCheckInfo.BirthInput=util.dateFormat(checkDay);
                return true;
            };
            return false;
        },

        //检验15位身份证号码出生日期是否有效
        birthday15:function(sIdCard){
            var year=parseFloat(sIdCard.substr(6,2));
            var month=parseFloat(sIdCard.substr(8,2));
            var day=parseFloat(sIdCard.substr(10,2));
            var checkDay=new Date(year,month-1,day);
            if (month==(checkDay.getMonth()+1) && day==checkDay.getDate()) {
                return true;
            };
            return false;
        },

        gender18:function(sIdCard){
        var genderChar= parseInt(sIdCard.substr(16,1));
            if (genderChar%2 == 1) {
                return "M";
            }
            else {
                return "F";
            }
        },
        //检验校验码是否有效
        validate:function(sIdCard){
            var aIdCard=sIdCard.split("");
            var sum=0;
            for (var i = 0; i < CheckIdCard.Wi.length; i++) {
                sum+=CheckIdCard.Wi[i]*aIdCard[i]; //线性加权求和
            };
            var index=sum%11;//求模，可能为0~10,可求对应的校验码是否于身份证的校验码匹配
            if (CheckIdCard.Xi[index]==aIdCard[17].toUpperCase()) {
                return true;
            };
            return false;
        },

        //检验输入的省份编码是否有效
        province:function(sIdCard){
            var p2=sIdCard.substr(0,2);
            for (var i = 0; i < CheckIdCard.Pi.length; i++) {
                if(CheckIdCard.Pi[i]==p2){
                    return true;
                };
            };
            return false;
        }

//                else if (sIdCard.length==15) {
//                      if (CheckIdCard.province(sIdCard)&&CheckIdCard.brithday15(sIdCard)) {
//                            return "身份证号码合法";
//                      }
//                      else{
//                            return "请输入有效的身份证号码";
//                      };
//                };


    };




/* switch view and assign commen attributes to every room */
    $scope.switchView = function(){
        $scope.viewClick ='modify';
        for (var i=0; i<$scope.BookRoom.length; i++){
            $scope.BookRoom[i].CHECK_IN_DT = $scope.BookCommonInfo.CHECK_IN_DT;
            $scope.BookRoom[i].CHECK_OT_DT = $scope.BookCommonInfo.CHECK_OT_DT;
            $scope.BookRoom[i].roomSource  = $scope.BookCommonInfo.roomSource;
            if($scope.BookCommonInfo.roomSource=='2'){
                $scope.BookRoom[i].treatyChoose =$scope.BookCommonInfo.treatyChoose;
            }
            //$scope.BookRoom[i].finalPrice = $scope.roomNegoPriceOBJ[$scope.BookRoom[i].roomType];
        }
    }


/* --------------------------------Submit check in and induce a modal to confirm ------------------------- */
    $scope.checkInSubmit = function(){
//        if ($scope.styleMarked.border != undefined){
//            return;
//        }
        if ($scope.BookRoom.length<1){
            alert("请至少选中一间房");
            return;
        }
        for (var i=0; i<$scope.BookRoom.length; i++){
            if(actionType == 'checkIn'){
                $scope.BookRoom[i].finalPrice = $scope.roomNegoPriceOBJ[$scope.BookRoom[i].roomType];
            }
            if($scope.BookRoom[i].tempSelected){
                $scope.BookRoom[i].finalPrice = $scope.plansOBJ[$scope.BookRoom[i].PLAN_ID].PLAN_COV_PRCE;
                $scope.BookRoom[i].PLAN_COV_MIN = $scope.plansOBJ[$scope.BookRoom[i].PLAN_ID].PLAN_COV_MIN;
                $scope.BookRoom[i].expectedDeposit = parseFloat($scope.BookRoom[i].fixedDeposit) + parseFloat($scope.BookRoom[i].finalPrice);
            }else{
                $scope.BookRoom[i].expectedDeposit = parseFloat($scope.BookRoom[i].fixedDeposit) + parseFloat($scope.BookRoom[i].finalPrice) *
                Math.round(($scope.BookRoom[i].CHECK_OT_DT.getTime() - $scope.BookRoom[i].CHECK_IN_DT.getTime())/86400000);
            }
        }
        var modalInstance = $modal.open({
            templateUrl: 'checkInModalContent',
            controller: 'checkInModalInstanceCtrl',
            resolve: {
                BookRoom: function () {
                    return $scope.BookRoom;
                },
                CONN_RM_ID: function () {
                    return $scope.CONN_RM_ID;
                },
                RESV_ID: function () {
                    return RESV_ID;
                },
                RoomNumArray: function () {
                    return $scope.RoomNumArray;
                }
            }
        });
    }


    /* --------------------------------in modify mode, save the change to the room ------------------------- */
    $scope.saveModified = function(){
        $scope.ModifyInfo =[];
        for (var i = 0; i<$scope.BookRoom.length; i++){
            var room = $scope.BookRoom[i];
            $scope.ModifyInfo.push({roomSelect:room.roomSelect, CHECK_IN_DT:util.dateFormat(new Date(room.CHECK_IN_DT)),
                                CHECK_OT_DT:util.dateFormat(new Date(room.CHECK_OT_DT)),GuestsInfo: room.GuestsInfo});
        }
        newCheckInFactory.modify(JSON.stringify($scope.ModifyInfo)).success(function(data){
            alert(JSON.stringify("修改成功!"));
            window.close();
        });
    }


//
//    $scope.styleMarked = {};
//
//    $scope.resetMarkedBorder = function(){
//        if ($scope.styleMarked.border != undefined){
//            $scope.styleMarked.border = "default";
//            $scope.styleMarked={};
//        }
//    }
//    $scope.checkInCheck = function(){
//        $scope.err = function(){
//            if (!($scope.BookCommonInfo.CHECK_IN_DT instanceof Date)){
//                $scope.BookCommonInfo.CheckInStyle={border:"2px solid red"};
//                $scope.styleMarked = $scope.BookCommonInfo.CheckInStyle;
//                return "请您正确输入入住时间!"
//            }else if ($scope.BookCommonInfo.CHECK_OT_DT =="" || !($scope.BookCommonInfo.CHECK_OT_DT instanceof Date)){
//                    $scope.BookCommonInfo.CheckOTStyle={border:"2px solid red"};
//                    $scope.styleMarked = $scope.BookCommonInfo.CheckOTStyle;
//                    return "请您正确输入离店时间!"
//            }else if($scope.BookCommonInfo.CHECK_OT_DT < $scope.BookCommonInfo.CHECK_IN_DT){
//                    $scope.BookCommonInfo.CheckOTStyle={border:"2px solid red"};
//                    $scope.styleMarked = $scope.BookCommonInfo.CheckOTStyle;
//                    return "离店时间早于入住时间了。。。"
//            }else{
//                    for (var roomType in $scope.roomTypeSelectQuan) {
//                        if($scope.roomTypeSelectQuan[roomType]> $scope.roomQuanOBJRecorder[roomType]){
//                            return "所选"+roomType+"数目超过其余量";
//                        }
//                    }
//                    var roomDuplicateCheckOBJ = {};
//                    var SSNDuplicateCheckOBJ= {};
//                    for (var i = 0; i<$scope.BookRoom.length; i++){
//                      //alert(JSON.stringify($scope.BookRoom[i].AvailQuanFlag));
//                      if( $scope.BookRoom[i].roomSelect == undefined || $scope.BookRoom[i].roomSelect =='' ){
//                          $scope.BookRoom[i].roomNumStyle={border:"2px solid red"};
//                          $scope.styleMarked = $scope.BookRoom[i].roomNumStyle;
//                          return "请您选择第" + (i+1).toString()+"间房的房间号码!"
//                      }else if(roomDuplicateCheckOBJ[$scope.BookRoom[i].roomSelect] == "1"){
//                          return $scope.BookRoom[i].roomSelect+"号房被重复选择";
//                      }else {
//                    //                      if($scope.BookRoom[i].AvailQuanFlag[$scope.ID_TP_match[$scope.BookRoom[i].roomSelect]] == false){
//                    //                          $scope.BookRoom[i].roomNumStyle={border:"2px solid red"};
//                    //                          $scope.styleMarked = $scope.BookRoom[i].roomNumStyle;
//                    //                          return "第" + (i+1).toString()+"间房的房型在所选的某些天内已被订满!"
//                    //                      }else
//                            if($scope.BookRoom[i].deposit < 300+ parseFloat($scope.BookRoom[i].finalPrice) *
//                               Math.round(($scope.BookCommonInfo.CHECK_OT_DT.getTime() - $scope.BookCommonInfo.CHECK_IN_DT.getTime())/86400000)){
//
//                                      $scope.BookRoom[i].depositStyle={border:"2px solid red"};
//                                      $scope.styleMarked = $scope.BookRoom[i].depositStyle;
//
//                                      return "第" + (i+1).toString()+"间房需要至少"
//                                          +( 300+ parseFloat($scope.BookRoom[i].finalPrice) *
//                                        Math.round(
//                                                    (
//                                                        $scope.BookCommonInfo.CHECK_OT_DT.getTime()
//                                                        - $scope.BookCommonInfo.CHECK_IN_DT.getTime()
//                                                    )
//                                                    /86400000
//                                                   )
//                                           )+ "元";
//                          }else{
//                              for (var j = 0; j<$scope.BookRoom[i].GuestsInfo.length; j++){
//                                  var singleGuest = $scope.BookRoom[i].GuestsInfo[j];
//                                  singleGuest.SSNinput.replace(/^\s+|\s+$/g,"");
//                                  if(singleGuest.SSNinput == ""){
//                                      $scope.BookRoom[i].GuestsInfo[j].markStyle={border:"2px solid red"};
//                                      $scope.styleMarked =  $scope.BookRoom[i].GuestsInfo[j].markStyle;
//                                      return "请您输入第" + (i+1).toString()+"间房第" + (j+1).toString()+"位客人的证件号";
//                                  } else if(SSNDuplicateCheckOBJ[singleGuest.SSNinput] == "1"){
//                                      return "证件号"+singleGuest.SSNinput+"被重复使用";
//                                  }else if(singleGuest.SSNType=="SSN18"){
//                                      var sIdCard = JSON.parse(JSON.stringify(singleGuest.SSNinput));
//                                      if (sIdCard.match(/^\d{17}(\d|X)$/gi)==null || sIdCard.length!=18 || !CheckIdCard.province(sIdCard) || !CheckIdCard.birthday18(sIdCard) || !CheckIdCard.validate(sIdCard)){
//                                          $scope.BookRoom[i].GuestsInfo[j].markStyle={border:"2px solid red"};
//                                          $scope.styleMarked =  $scope.BookRoom[i].GuestsInfo[j].markStyle;
//                                          return "第" + (i+1).toString()+"间房第" + (j+1).toString()+"位客人的证件号不正确";
//                                      }
//                                  }
//                                  SSNDuplicateCheckOBJ[singleGuest.SSNinput] = "1";
//                              }
//                          }
//                      }
//                      roomDuplicateCheckOBJ[$scope.BookRoom[i].roomSelect]="1";
//                    }
//                  return "通过智能信息检测,请点击进行办理";
//                }
//        }();
//    }



    });



appCheckIn.controller('Datepicker', function($scope){

    $scope.today = function() {
        $scope.minDate = new Date();
    };

    $scope.today();

    // Disable weekend selection
    $scope.disabled = function(date, mode) {
        return false; //( mode === 'day' && ( date.getDay() === 0 || date.getDay() === 6 ) );
    };

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

    $scope.format = 'yyyy-MM-dd';

});



appCheckIn.controller('checkInModalInstanceCtrl',function ($scope, $modalInstance, newCheckInFactory, $http,
                                                           CONN_RM_ID,BookRoom,RoomNumArray,RESV_ID) {

    $scope.BookRoom = BookRoom;
    $scope.CONN_RM_ID =CONN_RM_ID;

/*watch whether fixedDeposit has been changed, change the required expected Deposit accordingly*/
    $scope.$watch(function(){
            return $scope.BookRoom;
        },
        function(newValue, oldValue) {
            for (var i =0; i< $scope.BookRoom.length; i++){
                if(newValue[i].fixedDeposit != oldValue[i].fixedDeposit){
                  $scope.BookRoom[i].expectedDeposit= parseFloat(newValue[i].expectedDeposit) - parseFloat(
                       oldValue[i].fixedDeposit) + parseFloat(newValue[i].fixedDeposit);
               }
            }
        },
        true
    );



    $scope.birthDateFormat = function(date){
        var mm = (date.getMonth()+1).toString();
        var dd  = date.getDate().toString();
        return (mm[1]?mm:"0"+mm[0])+"-" + (dd[1]?dd:"0"+dd[0]);
    }

    $scope.Limit = function(num){
        return parseFloat(num).toFixed(2);
    }

    $scope.confirm = function(){
        $scope.SubmitInfo =[];
        for (var i = 0; i<$scope.BookRoom.length; i++){
            var room = $scope.BookRoom[i];
            if (room.finalPrice ==""){
                room.finalPrice = $scope.ID_SUGG_match[room.roomType];
            }
            $scope.SubmitInfo.push({roomSelect:room.roomSelect, roomType:room.roomType, CHECK_IN_DT:util.dateFormat($scope.BookRoom[i].CHECK_IN_DT)
                ,CHECK_OT_DT:util.dateFormat($scope.BookRoom[i].CHECK_OT_DT),finalPrice: room.finalPrice, roomSource:$scope.BookRoom[i].roomSource,
                deposit:room.deposit, fixedDeposit:room.fixedDeposit,payMethod: room.payMethod, GuestsInfo: room.GuestsInfo, CARDS_NUM: room.Cards_num});
            if ($scope.BookRoom[i].roomSource ==2 && $scope.BookRoom[i].treatyChoose != ""){
                $scope.SubmitInfo[i]["TREATY_ID"]= $scope.BookRoom[i].treatyChoose.TREATY_ID;
            }else if($scope.BookRoom[i].roomSource ==2 && $scope.BookRoom[i].treatyChoose == ""){
                $scope.SubmitInfo[i]['roomSource']= 0;
            }else if($scope.BookRoom[i].roomSource ==1 && $scope.BookRoom[i].checkMEM_TP != ""){
                $scope.SubmitInfo[i]["MEM_ID"]= $scope.BookRoom[i].checkMEM_ID;
            }else if($scope.BookRoom[i].roomSource ==1 && $scope.BookRoom[i].checkMEM_TP == ""){
                $scope.SubmitInfo[i]['roomSource']= 0;
            }

            if ($scope.CONN_RM_ID != ""){
                $scope.SubmitInfo[i]["Conn_RM_ID"]=$scope.CONN_RM_ID;
                if( i!=0 && $scope.SubmitInfo[i]["roomSelect"] == $scope.CONN_RM_ID){
                    var temp = JSON.parse(JSON.stringify($scope.SubmitInfo[0]));
                    $scope.SubmitInfo[0]= JSON.parse(JSON.stringify($scope.SubmitInfo[i]));
                    $scope.SubmitInfo[i] = temp;
                }
            }else{
                $scope.SubmitInfo[i]["Conn_RM_ID"]="";
            }

            if($scope.BookRoom[i].tempSelected){
                $scope.SubmitInfo[i]["TMP_PLAN_ID"]=$scope.BookRoom[i].PLAN_ID;
                $scope.SubmitInfo[i]["PLAN_COV_MIN"]=$scope.BookRoom[i].PLAN_COV_MIN;
            }else{
                $scope.SubmitInfo[i]["TMP_PLAN_ID"]='';
            }
        }

        var resetAvail=[];
        if (RESV_ID!="null"){
            resetAvail =[decodeURI(RoomNumArray[1]),RoomNumArray[2],new Date(RoomNumArray[4].replace("-","/"))
                ,new Date(RoomNumArray[5].replace("-","/"))];
        }
        alert(JSON.stringify($scope.SubmitInfo));
        newCheckInFactory.submit(JSON.stringify([$scope.SubmitInfo,RESV_ID,resetAvail])).success(function(data){
            alert(JSON.stringify("办理成功!"));
            window.close();
        });
    }
});