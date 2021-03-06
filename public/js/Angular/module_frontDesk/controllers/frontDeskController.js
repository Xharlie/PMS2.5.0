
app.controller('sideBarController', function($scope, $http,sessionFactory,$modal){
    var pathArray = window.location.href.split("/");
    $scope.tab = pathArray[pathArray.length-1].substring(0, 4);
    if ($scope.tab.length<4){
        $scope.tab = pathArray[pathArray.length-2].substring(0, 4);
    }
    $scope.tabClassObj={'room':'','rese':'','merc':'','cust':'','acco':'','prob':'',
        'oneK':'','sett':''};
    $scope.tabClassObj[$scope.tab]='tabChosen';
    $scope.emphasize = function(tabName){
        for(key in $scope.tabClassObj){
            $scope.tabClassObj[key]='';
        }
        $scope.tabClassObj[tabName]='tabChosen';
    }

    /*********   get userInfo   **********/
    sessionFactory.getUserInfo().success(function(data){
        $scope.userInfo = data;
        if($scope.userInfo.SHFT_ID == null || $scope.userInfo.SHFT_ID ==""){
            var modalInstance = $modal.open({
                windowTemplateUrl: 'directiveViews/modalWindowTemplate',
                templateUrl: 'directiveViews/shiftSelectModal',
                controller: 'shiftSelectModalController',
                resolve: {
                    HTL_ID: function () {
                        return $scope.userInfo.HTL_ID;
                    }
                }
            }).result.then(function (data) {
                $scope.userInfo = data;
            });
        }
    });
});

app.controller('reservationController', function($scope, $http, resrvFactory,$modal,roomStatusFactory){

    $scope.selectTo = function(value,caption,ngModel){
        ngModel.value = value;
        ngModel.caption = caption;
    }

    $scope.ready=false;
    var now = new Date();
    $scope.menuNoshow = false;
    $scope.iconAndAction = {"resvIconAction":util.resvIconAction};
    $scope.iconAndActionDisabled = {"resvIconAction":util.iconAndActionDisabled};
    $scope.sameID={};
    $scope.blockClass = "reserveClick";
    $scope.timeOutClass = "timeOutResv";
    $scope.clicked = false;
    $scope.roomType = {};
    $scope.sorter ={};

    roomStatusFactory.getAllRoomTypes().success(function(data){
        $scope.allType = data;
    });

    resrvFactory.resvShow().success(function(data){
        $scope.resvInfo =data;
        $scope.ready=true;
        var nowDate = util.dateFormat(now);
        var nowTime = util.timeFormat(now);
        for (var i =0 ; i< $scope.resvInfo.length; i++){
            $scope.resvInfo[i].blockClass=[];
            if(($scope.resvInfo[i].CHECK_IN_DT+$scope.resvInfo[i].RESV_LATEST_TIME) <= (nowDate + nowTime) ){
                $scope.resvInfo[i].timeOutClass = $scope.timeOutClass;
                $scope.resvInfo[i].iconAndAction = $scope.iconAndActionDisabled;
            }else{
                $scope.resvInfo[i].iconAndAction = $scope.iconAndAction;
            }
            if($scope.sameID[$scope.resvInfo[i].RESV_ID] == undefined){
                $scope.sameID[$scope.resvInfo[i].RESV_ID]=[$scope.resvInfo[i]];
            }else{
                $scope.sameID[$scope.resvInfo[i].RESV_ID].push($scope.resvInfo[i]);
            }
        }
    });
// get all room types for filters


    $scope.addNew = function(){
        var modalInstance = $modal.open({
            windowTemplateUrl: 'directiveViews/modalWindowTemplate',
            templateUrl: 'directiveViews/reserveModal',
            controller: 'reservationModalController',
            resolve: {
                roomTPs: function () {
                    return null;
                },
                initialString: function () {
                    return "newReservation";
                }
            }
        });
//        util.openTab(location,"","",util.closeCallback);
    };
    $scope.fastAction = function(reserve){
        /* out of date reservation, disabled  */
        if(reserve.iconAndAction.resvIconAction[0].action!="预定入住") return;
        var sameID = $scope.sameID[reserve.RESV_ID];
        var roomST = [];
        for (var i =0; i < sameID.length; i++){
            for (var j=0; j<sameID[i].RM_QUAN; j++){
                var room = sameID[i];
                roomST.push(room);
            }
        }
        var modalInstance = $modal.open({
            windowTemplateUrl: 'directiveViews/modalWindowTemplate',
            templateUrl: 'directiveViews/multiCheckInModal',
            controller: 'MultiCheckInModalController',
            resolve: {
                roomST: function () {
                    return roomST;
                },
                initialString: function () {
                    return "multiReserve";
                },
                RESV: function (){
                    return {RESV_ID:reserve.RESV_ID, CHECK_IN_DT:reserve.CHECK_IN_DT,
                        CHECK_OT_DT:reserve.CHECK_OT_DT, roomSource:'预订'};
                }
            }
        });
    }

//    $scope.check = function(resv){
//        var location = "newCheckIn/reserveCheck/"+resv.RESV_ID +"/"+encodeURI(resv.RM_TP) + "/" +  resv.RM_QUAN + "/" + resv.RESV_DAY_PAY + "/"+ resv.CHECK_IN_DT+
//            "/" + resv.CHECK_OT_DT;
//        util.openTab(location,"","",util.closeCallback);
//    }

    $scope.open = function (reserve) {
        $scope.sameIDLightUp(reserve);
        $scope.clicked = true;
    };

    $scope.extraCleaner = function(reserve){
        $scope.clicked = false;
        $scope.sameIDLightBack(reserve);
    }

    $scope.sameIDLightUp = function(reserve){
        if($scope.clicked) return;  // if some reservation been selected, don't do the hover light up
        for (var i=0; i< $scope.sameID[reserve.RESV_ID].length; i++){
            if ($scope.sameID[reserve.RESV_ID][i].blockClass.indexOf($scope.blockClass)<0){
                $scope.sameID[reserve.RESV_ID][i].blockClass.push($scope.blockClass);
            }
        }
    }

    $scope.sameIDLightBack = function(reserve){
        if($scope.clicked) return;  // if some reservation been selected, don't do the hover light back
        for (var i=0; i< $scope.sameID[reserve.RESV_ID].length; i++){
            var ind =$scope.sameID[reserve.RESV_ID][i].blockClass.indexOf($scope.blockClass);
            if (ind >=0) $scope.sameID[reserve.RESV_ID][i].blockClass.splice(ind,1);
        }
    }
});


app.controller('roomStatusController', function($scope, $http, roomStatusFactory, $modal,cusModalFactory,resrvFactory,roomStatusInterFactory){
    /* Database version */
    var today = new Date();
    $scope.BookCommonInfo = {
        roomStatusInfo: null,
        master2BranchStyle: null,
        master2BranchID: null,
        roomSummary: null,
        roomFloor: null,
        infoCenterQueue: null
    }
    $scope.connectClick = "toStart";
    $scope.ready=false;
    $scope.blockClass="roomBlockSelected";
    $scope.updateInfo = {
        updateAllRoom: function(){
            roomStatusFactory.roomShow().success(function(data){
                roomStatusInterFactory.updateAllRoom($scope.BookCommonInfo,data);
            });
        }
    };


    /******************* allRoomComparer to compare every single room ******************/


    /****************************     init      ****************************/
    (function roomStatusInit(){
        roomStatusFactory.roomShow().success(function(data){
            $scope.BookCommonInfo = roomStatusInterFactory.roomStatusPackaging(data);
            $scope.ready = true;
        });
    })();

    resrvFactory.showComResv(util.dateFormat(today)).success(function(data){
        $scope.resvComInfo = data;
    })

    $scope.connLightUp = function(roomST){
        if(roomST['connLightUp'] != null && roomST.connRM_TRAN_IDs.length>1){
            for (var key in roomST['connLightUp']){
                roomST['connLightUp'][key].push("connRoomBlock");
            }
        }
    }

    $scope.connLightback = function(roomST){
        if(roomST['connLightUp'] != null && roomST.connRM_TRAN_IDs.length>1){
            for (var key in roomST['connLightUp']){
                roomST['connLightUp'][key].
                    splice(roomST['connLightUp'][key].indexOf('connRoomBlock'),1);
            }
        }
    }
    $scope.connectRooms = [];
    $scope.connectFlag = false;

    $scope.connectStart= function () {
        $scope.connectFlag = true;
        $scope.connectClick = "toEnd";
    };

    $scope.connectEnd = function(result){


        if(result == "confirm" && $scope.connectRooms.length > 1){

//            var modalInstance = $modal.open({
//                templateUrl: 'connectRMModalContent',
//                controller: 'connectRMModalInstanceCtrl',
//                resolve: {
//                    connectRooms: function () {
//                        return $scope.connectRooms;
//                    }
//                }
//            });
            var modalInstance = $modal.open({
                windowTemplateUrl: 'directiveViews/modalWindowTemplate',
                templateUrl: 'directiveViews/multiCheckInModal',
                controller: 'MultiCheckInModalController',
                resolve: {
                    roomST: function () {
                        return $scope.connectRooms;
                    },
                    initialString: function () {
                        return "multiWalkIn";
                    },
                    RESV: function(){
                        return "";
                    }
                }
            }).result.then(function(data) {
                    $scope.updateInfo.updateAllRoom();
                });

        }
        for (var i =0; i< $scope.connectRooms.length; i++){
            $scope.connectRooms[i].blockClass.splice($scope.connectRooms[i].blockClass.indexOf($scope.blockClass),1);
        }
        $scope.connectClick = "toStart";
        $scope.connectRooms = [];
        $scope.connectFlag = false;

    }

    $scope.overall ='';

    $scope.ngFloorFilter = function(roomST){
        var test = new RegExp("^"+$scope.roomFloor+".*");
        var a = test.test(roomST.RM_ID); //$scope.roomFloor;
        return a;
    };

    $scope.customerizeFilter = function(roomST){
        var test = new RegExp("^.*"+$scope.overall+".*$", "i");
        return (( roomST.RM_ID.match(test) != null) || (roomST.RM_TP.match(test)!= null) || (roomST.RM_CONDITION.match(test)!= null)
        || (roomST['customers'] != null && roomST['customers'][0].CUS_NAME.match(test) != null) );
    };

    $scope.rmTpToggle = function(rmTp){
        if($scope.RM_TPfilter != rmTp) {
            $scope.RM_TPfilter = rmTp;
        }else{
            $scope.RM_TPfilter = '';
        }
    }

    /* Room Click Modal is here !*/


    $scope.open = function (roomST) {
        if($scope.connectFlag == true) {
            if( roomST.RM_CONDITION == "空房"){
                if (roomST.blockClass.indexOf('connRoomBlockAdd')<0){
                    roomST.blockClass.push("connRoomBlockAdd");
                    $scope.connectRooms.push(roomST);
                }else{
                    var index = $scope.connectRooms.indexOf(roomST);
                    $scope.connectRooms.splice(index,1);
                    roomST.blockClass.splice(roomST.blockClass.indexOf('connRoomBlockAdd'),1);
                }
            }
        }else if($scope.connectFlag == false){
            if (roomST.blockClass.indexOf('roomBlockSelected')<0){
                roomST.blockClass.push("roomBlockSelected");
            }
        }
    };
    $scope.fastAction = function(roomST){
        if($scope.connectFlag == false && roomST.RM_CONDITION == "空房"){
            var modalInstance = $modal.open({
                windowTemplateUrl: 'directiveViews/modalWindowTemplate',
                templateUrl: 'directiveViews/singleCheckInModal',
                controller: 'checkInModalController',
                resolve: {
                    roomST: function () {
                        return [roomST];
                    },
                    initialString: function () {
                        return "singleWalkIn";
                    }
                }
            }).result.then(function(data) {
                    $scope.updateInfo.updateAllRoom();
                });

        }else if($scope.connectFlag == false && roomST.RM_CONDITION == "有人"){
            var modalInstance = $modal.open({
                windowTemplateUrl: 'directiveViews/modalWindowTemplate',
                templateUrl: 'directiveViews/singleCheckInModal',
                controller: 'checkInModalController',
                resolve: {
                    roomST: function () {
                        return [roomST];          // leave flexibility to have multiple parameters or rooms
                    },
                    initialString: function () {
                        return "editRoom";
                    }
                }
            }).result.then(function(data) {
                    $scope.updateInfo.updateAllRoom();
                });
        }else if($scope.connectFlag == false && roomST.RM_CONDITION == "脏房"){
            cusModalFactory.Change2Cleaned(roomST.RM_ID).success(function(data){
                roomST.RM_CONDITION = "空房";
                roomST.menuIconAction = util.avaIconAction;
                roomST.blockClass[0] = "room-empty";
            });
        }else if($scope.connectFlag == false && roomST.RM_CONDITION == "维修"){
            cusModalFactory.Change2Mended(roomST.RM_ID).success(function(data){
                roomST.RM_CONDITION = "脏房";
                roomST.menuIconAction = util.dirtIconAction;
                roomST.blockClass[0] = "room-dirty";
            });
        }
    };

});


app.controller('customerController', function($scope, $http, customerFactory,$modal){

    $scope.VarMaxSize =util.VarMaxSize;
    $scope.VarItemPerPage=2;
    $scope.totalItems =0;
    $scope.currentPage=1;
    $scope.ready=false;
    $scope.sorter = {};
    $scope.selectTo = function(value,caption,ngModel){
        ngModel.value = value;
        ngModel.caption = caption;
    }
    customerFactory.customerShow().success(function(data){
        $scope.customerInfo =data;
        $scope.ready=true;
    });

});
app.controller('memberController', function($scope, $http, customerFactory,$modal){

    $scope.VarMaxSize =util.VarMaxSize;
    $scope.VarItemPerPage=2;
    $scope.totalItems =0;
    $scope.currentPage=1;
    $scope.ready=false;

    $scope.menuType = "small-menu";
    $scope.menuNoshow = false;
    $scope.iconAndAction = {"memberIconAction":util.memberIconAction };
    $scope.blockClass = "reserveClick"
    $scope.clicked = false;
    $scope.memSorter = {};

    $scope.selectTo = function(value,caption,ngModel){
        ngModel.value = value;
        ngModel.caption = caption;
    }

    $scope.clearMEMIDfilter = function(){
        if($scope.memberID == '') delete $scope.memberID;
    };

    $scope.clearMEMphonefilter = function(){
        if($scope.memPhone == '') delete $scope.memPhone;
    };
    customerFactory.memberShow().success(function(data){
        $scope.memberInfo =data;
        $scope.ready=true;
        for (var i =0 ; i< $scope.memberInfo.length; i++){
            $scope.memberInfo[i].blockClass=[];
        }
    });

    $scope.addNewCustomer = function(){
        var modalInstance = $modal.open({
            windowTemplateUrl: 'directiveViews/modalWindowTemplate',
            templateUrl: 'directiveViews/addMemberModal',
            controller: 'addMemberModalController',
            resolve: {
                initialString: function () {
                    return "addMember";
                },
                member: function () {
                    return null;
                }
            }
        });
    }

    $scope.fastAction = function(member){
        var modalInstance = $modal.open({
            windowTemplateUrl: 'directiveViews/modalWindowTemplate',
            templateUrl: 'directiveViews/addMemberModal',
            controller: 'addMemberModalController',
            resolve: {
                initialString: function () {
                    return "editProfile";
                },
                member: function () {
                    return member;
                }
            }
        });
    }
    $scope.open = function (member) {
        $scope.LightUp(member);
        $scope.clicked = true;
    };

    $scope.extraCleaner = function(member){
        $scope.clicked = false;
        $scope.LightBack(member);
    }

    $scope.LightUp = function(member){
        if($scope.clicked) return;  // if some member been selected, don't do the hover light up
        if (member.blockClass.indexOf($scope.blockClass)<0){
            member.blockClass.push($scope.blockClass);
        }
    }

    $scope.LightBack = function(member){
        if($scope.clicked) return;  // if some reservation been selected, don't do the hover light back
        var ind =member.blockClass.indexOf($scope.blockClass);
        if (ind >=0) member.blockClass.splice(ind,1);
    }

});
app.controller('accountingController', function($scope, $http, accountingFactory,$modal){

    /******************************************     utilities     **********************************************/

    $scope.selectTo = function(value,caption,ngModel){
        ngModel.value = value;
        ngModel.caption = caption;
    }

    function queryAcct(startTime, endTime) {
        accountingFactory.accountingGetAll(startTime, endTime).success(function(data){
            $scope.acctInfo = data;
            $scope.ready=true;
            for (var i =0 ; i< $scope.acctInfo.length; i++){
                $scope.acctInfo[i].blockClass=[];
                var date = new Date($scope.acctInfo[i].TSTMP);
                //$scope.acctInfo[i].adjustedTSTMP = util.tstmpFormat(new Date(Number(date) - date.getTimezoneOffset()*1000*60));
                $scope.acctInfo[i].adjustedTSTMP = util.tstmpFormat(date);
                $scope.acctInfo[i]['CONSUME_PAY_AMNT'] = parseFloat($scope.acctInfo[i]['CONSUME_PAY_AMNT']);
                $scope.acctInfo[i]['SUBMIT_PAY_AMNT'] = parseFloat($scope.acctInfo[i]['SUBMIT_PAY_AMNT']);
            }
        });
    }

    $scope.LightUp = function(member){
        if($scope.clicked) return;
        if (member.blockClass.indexOf($scope.blockClass)<0){
            member.blockClass.push($scope.blockClass);
        }
    }

    $scope.LightBack = function(member){
        if($scope.clicked) return;
        var ind =member.blockClass.indexOf($scope.blockClass);
        if (ind >=0) member.blockClass.splice(ind,1);
    }

    $scope.open = function(acct){
        //$scope.clicked = true;
        var modalInstance = $modal.open({
            windowTemplateUrl: 'directiveViews/modalWindowTemplate',
            templateUrl: 'directiveViews/modifyAcctModal',
            controller: 'modifyAcctModalController',
            resolve: {
                initialString: function () {
                    return "modifyAcct";
                },
                acct: function () {
                    return acct;
                }
            }
        });
    }

    $scope.refreshResult = function(){
        queryAcct(util.dateFormat($scope.QueryDates.startDate),util.dateFormat($scope.QueryDates.endDate));
    }

    /******************************************     init     **********************************************/

    $scope.Conaddup = 0;
    $scope.Payaddup = 0;
    $scope.collections = ['TSTMP'];
    $scope.ready=false;
    $scope.Type={};
    $scope.class={};
    $scope.payMethod={};
    $scope.sorter={};
    var today =  new Date();
    $scope.QueryDates={today:today,startDate:today,endDate:today};
    queryAcct(util.dateFormat(today),util.dateFormat(today));

    /********************* common variables *****************************************/

    $scope.blockClass = "reserveClick";
    $scope.clicked = false;

    $scope.TypeFilter = function(acct){
        if ($scope.Type.value == ""){
            return true;
        }else if($scope.Type.value == "CON"){
            return acct['CON'];
        }else if($scope.Type.value == "PAY"){
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
            $scope.Conaddup = $scope.Conaddup + ((newValue[i]['CONSUME_PAY_AMNT'] == '') ? 0 : newValue[i]['CONSUME_PAY_AMNT']);
            $scope.Payaddup = $scope.Payaddup + ((newValue[i]['SUBMIT_PAY_AMNT'] == '' ) ? 0: newValue[i]['SUBMIT_PAY_AMNT']);
        }
    });

    $scope.toFixed = function(numberShow){
        if( numberShow == '' || numberShow == undefined || numberShow == null || isNaN(numberShow)){
            return '';
        }else{
            return parseFloat(numberShow).toFixed(2);
        }
    }

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
            openTab(location,"","menubar=no,scrollbars=no,resizable=no,width=500,height=300,top=200,left=300;",util.closeCallback);
        }else{
            alert("error");
        }
    }
});



app.controller('oneKeyShiftController', function($scope, $http, accountingFactory){

    /****************************************************  utilities *******************************************************/

    $scope.twoDigit = function(digit){
        return parseFloat(digit).toFixed(2);
    };

    var testFail = function(){
        return false;
    }
    /****************************************************  initFunction *******************************************************/
    var shiftInfoInit = function(){
        accountingFactory.summerize().success(function(data){
            //     alert(JSON.stringify(data));
            $scope.cashSum = data['cash'];
            $scope.roomCardSum = data['cards'];
            $scope.productSellSum=data['store'];
            $scope.depositReceiptSum=data['receipt'];
            $scope.memberCardSum=data['member'];
            $scope.ready=true;
        });
    }

    /****************************************************  init *******************************************************/
    $scope.ready=false;
    $scope.ShiftInfo = {SHFT_CSH_BOX:null,SHFT_END_PSS2_CSH:null,SHFT_PSS_EMP_ID:null,pssEmpPw:null};
    shiftInfoInit();
    /****************************************************  submit *******************************************************/

    $scope.changeShiftSubmit = function(){
        if(testFail()) return;
        $scope.submitLoading = true;
        accountingFactory.changeShiftSubmit($scope.ShiftInfo).success(function(data){
            $scope.submitLoading = false;
        });
    }
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

app.controller('merchandiseHistoController', function($scope, $http, merchandiseFactory,$modal){

    /****************************************************  utilities *******************************************************/
    $scope.ready=false;
    $scope.menuNoshow = false;
    $scope.iconAndAction = {"merchIconAction":util.merchIconAction};
    $scope.blockClass = "reserveClick"
    $scope.clicked=false;
    $scope.showNotice = true;
    $scope.contentFormat="aaa";

    $scope.open = function(prod){
        $scope.lightUp(prod);
        $scope.clicked = true;
    }

    $scope.lightUp = function(prod){
        if($scope.clicked) return;
        if (prod.blockClass.indexOf($scope.blockClass)<0){
            prod.blockClass.push($scope.blockClass);
        }
    }

    $scope.lightBack = function(prod){
        if($scope.clicked) return;
        var ind = prod.blockClass.indexOf($scope.blockClass);
        if (ind >=0) prod.blockClass.splice(ind,1);
    }

    $scope.extraCleaner=function(){
        $scope.clicked=false;
    }

    /****************************************************  init *******************************************************/

    var allHistoInit = function(){
        merchandiseFactory.histoPurchaseShow().success(function(data){
            $scope.histoInfo = data;
            $scope.ready=true;
            for(var i = 0; i < $scope.histoInfo.length; i++){
                $scope.histoInfo[i]["blockClass"] = [];
            }
        });
    }


    allHistoInit();



    $scope.twoDigit = function(digit){
        return parseFloat(digit).toFixed(2);
    };

});



app.controller('merchandiseController', function($scope, $http, merchandiseFactory,$modal){

    /****************************************************  utilities *******************************************************/
// for dropdown simulate select
    $scope.selectTo = function(value,caption,ngModel){
        ngModel.value = value;
        ngModel.caption = caption;
    }

    $scope.nullify = function(filter){
        if (filter == ""){
            filter = null;
        }
    }

    $scope.open = function(prod){
        $scope.lightUp(prod);
        $scope.clicked = true;
    }

    $scope.lightUp = function(prod){
        if($scope.clicked) return;
        if (prod.blockClass.indexOf($scope.blockClass)<0){
            prod.blockClass.push($scope.blockClass);
        }
    }

    $scope.lightBack = function(prod){
        if($scope.clicked) return;
        var ind = prod.blockClass.indexOf($scope.blockClass);
        if (ind >=0) prod.blockClass.splice(ind,1);
    }

    $scope.extraCleaner=function(){
        $scope.clicked=false;
    }

    $scope.changeSumPrice = function(context){
        if(context!=null){
            $scope.prodSumPrice = "N/A";
        }else{
            $scope.prodSumPrice = 0;
            for (var i= 0; i<$scope.onCounter.length; i++){
                $scope.prodSumPrice += Number($scope.onCounter[i].PROD_PRICE * $scope.onCounter[i].AMOUNT);
            }
            $scope.prodSumPrice = util.Limit($scope.prodSumPrice);
        }
    }

    var testFail = function(){ return false};

    // change notice on shopping cart area
    var checkBoughtLength = function(){
        if($scope.onCounter.length > 0){
            $scope.buyButtonClass = "btn-primary";
            $scope.showNotice = false;
        }else{
            $scope.buyButtonClass = "btn-disabled";
            $scope.showNotice = true;
        }
    }
    /****************************************************  init *******************************************************/
    $scope.ready=false;
    $scope.menuNoshow = false;
    $scope.iconAndAction = {"merchIconAction":util.merchIconAction};
    $scope.blockClass = "reserveClick"
    $scope.clicked=false;
    $scope.buyButtonClass = "btn-disabled";
    $scope.showNotice = true;

    $scope.prodInfo ='';
    $scope.onCounter = [];
    $scope.BookCommonInfo = {prodSumPrice:0};
    $scope.prodSorter = {};
    $scope.prodTypeFilter = {};

    merchandiseFactory.productShow().success(function(data){
        $scope.ready=true;
        $scope.prodInfo = data;
        $scope.prodType={};
        for(var i = 0; i < $scope.prodInfo.length; i++){
            $scope.prodInfo[i]["blockClass"] = [];
            $scope.prodType[$scope.prodInfo[i].PROD_TP] = $scope.prodInfo[i].PROD_TP;
        }
    });

    var buyer_RM_TRAN_ID = null;
    if(window.location.toString().slice(-1) !=':') {
        var paramsArray = window.location.toString().split(":");
        buyer_RM_TRAN_ID = paramsArray[paramsArray.length - 1];
    }

    /****************************************************  confinement *******************************************************/


    $scope.removeBuy = function($index){
        $scope.onCounter.splice($index,1);
//        $scope.changeSumPrice(null);
        checkBoughtLength();
    }



    $scope.addBuy = function(prod){
        if($scope.onCounter.indexOf(prod)>=0) return;
        prod.AMOUNT=0;
        $scope.onCounter.push(prod);
        checkBoughtLength();
    }

    $scope.buySubmit = function(){
        $scope.changeSumPrice(null);
        if(testFail()) return;
        var modalInstance = $modal.open({
            windowTemplateUrl: 'directiveViews/modalWindowTemplate',
            templateUrl: 'directiveViews/purchaseModal',
            controller: 'purchaseModalController',
            resolve: {
                paymentRequest: function () {
                    return $scope.prodSumPrice;
                },
                buyer_RM_TRAN_ID: function () {
                    return buyer_RM_TRAN_ID;
                },
                owner: function () {
                    return $scope.onCounter;
                }
            }
        });
    }

    $scope.twoDigit = function(digit){
        return util.Limit(digit);
    };

    $scope.confirmDialog = function (content,type,action) {

        var modalInstance = $modal.open({
            templateUrl: 'dialog',
            windowTemplateUrl:'window',
            controller: 'dialogController',
            resolve: {
                content: function () {
                    return content;
                },
                type: function () {
                    return type;
                }
            }
        });

        var result= modalInstance.result.then(function (feedback) {
            if(feedback){
                action();
            }
        });

    };

})
/************************                       single Master Pay sub controller                      ***********************/
    .controller('eachBuyCtrl', function ($scope) {
        $scope.$watch('buy.AMOUNT',
            function(newValue, oldValue) {
                if($scope.buy.PROD_AVA_QUAN >= $scope.buy.AMOUNT){
                    $scope.buy.amountClass = "blackNum";
                }else{
                    $scope.buy.amountClass = "redNum";
                }
            },
            true
        );
    });


app.controller('dialogController', function ($scope, $modalInstance, content,type) {

    $scope.content= content;


    switch(type){
        case 'confirm':
            $scope.dialogType ="";
            break;
        case 'alert':
            $scope.dialogType = {display:'none'};
            break;
    }


    $scope.ok = function () {
        $modalInstance.close(true);
    };

    $scope.cancel = function () {
        $modalInstance.close(false);
    };
});

app.controller('settingRoomTpController', function($scope, $http, settingRoomFactory){
    $scope.editRecorder ="";                //储存该房型object;
    $scope.editInitRecorder="";             //储存该房型修改前的参数；
    $scope.ready=false;

    $scope.newTp = {RM_TP:'',SUGG_PRICE:'0.0',CUS_QUAN:1,RM_PROD_RMRK:'',RM_QUAN:'0'};

    var reset = function(){
        $scope.editRecorder.focusedCss="";
        $scope.editRecorder ="";
        $scope.editInitRecorder="";
    };

    settingRoomFactory.roomTpGet().success(function(data){
        $scope.rmTps = data;
        $scope.ready=true;
    });

    $scope.focus = function(rmTp){
        if($scope.editRecorder == ""){
            rmTp.focusedCss = "focusedRow";
            $scope.editRecorder = rmTp;
            $scope.editInitRecorder= JSON.parse(JSON.stringify(rmTp));
        }else if($scope.editRecorder != rmTp){
            alert("您将修改下一种房型设置,确认取消对当前房型的修改");
            for (key in $scope.editInitRecorder){
                $scope.editRecorder[key]=$scope.editInitRecorder[key];
            }
            rmTp.focusedCss = "focusedRow";
            $scope.editRecorder.focusedCss="";
            $scope.editRecorder = rmTp;
            $scope.editInitRecorder= JSON.parse(JSON.stringify(rmTp));
        }
    };

    $scope.confirmEdit = function(rmTp){
        if($scope.editRecorder != rmTp) {
            return;
        }
        if(confirm("确认修改?")){
            settingRoomFactory.roomTpEdit(JSON.stringify([$scope.editInitRecorder.RM_TP,rmTp])).success(function(data){
                reset();
            });
        };
    };

    $scope.deleteEdit = function(rmTp,index){
        var db_RM_TP = rmTp.RM_TP;
        if (db_RM_TP == ""){
            db_RM_TP = " ";
        }
        if($scope.editInitRecorder!="" && $scope.editRecorder == rmTp){
            db_RM_TP = $scope.editInitRecorder.RM_TP;
        }
        if(confirm("确认删除该房型?")){
            settingRoomFactory.roomTpDelete(db_RM_TP).success(function(data){
                $scope.rmTps.splice(index, 1);
            });
        };

    };

    $scope.addNewTp = function(){
        if ($scope.newTp.RM_TP ==""){
            alert("请提供房型名称");
            return;
        }
        for(var i=0; i<$scope.rmTps.length ;i++){
            if($scope.rmTps[i].RM_TP == $scope.newTp.RM_TP){
                alert("新加房型名称与已有房型名称重复");
                return;
            };
        }
        settingRoomFactory.roomTpAdd($scope.newTp).success(function(data){
            $scope.rmTps.push($scope.newTp);
            $scope.newTp = {RM_TP:'',SUGG_PRICE:'0.0',CUS_QUAN:1,RM_PROD_RMRK:'',RM_QUAN:'0'};
        });
    }


});



app.controller('settingRoomsController', function($scope, $http, settingRoomFactory ){

    $scope.VarMaxSize =util.VarMaxSize;
    $scope.VarItemPerPage=VarItemPerPage;
    $scope.editRecorder ="";                //储存该房间object;
    $scope.editInitRecorder=""
    $scope.editRecorderFloor ="";                //储存该房间object;
    $scope.editInitRecorderFloor="";
    $scope.selectedFloor ='';
    $scope.floors = [];
    $scope.totalItems =0;
    $scope.currentPage=1;
    $scope.ready = false;

    var reset = function(){
        $scope.editRecorder.focusedCss="";
        $scope.editRecorder ="";
        $scope.editInitRecorder="";
    };

    var resetFloor = function(){
        $scope.editRecorderFloor.focusedCss="";
        $scope.editRecorderFloor ="";
        $scope.editInitRecorderFloor="";
    };

    $scope.newRoom = {RM_ID:'',RM_TRAN_ID:null,RM_CONDITION:'空房',RM_TP:'',
        RM_CHNG_TSTMP:null,FLOOR:'',FLOOR_ID:'',PHONE:'',RMRK:''};
    $scope.newFloor = {FLOOR:'',FLOOR_ID:''};

    settingRoomFactory.roomTpGet().success(function(data){
        $scope.rmTps = data;
    });

    settingRoomFactory.roomsGet().success(function(data){
        $scope.allRooms = data;
        $scope.rooms={};
        for (var i=0; i< $scope.allRooms.length; i++){
            if ($scope.allRooms[i].FLOOR_ID in $scope.rooms){
                $scope.rooms[$scope.allRooms[i].FLOOR_ID].push($scope.allRooms[i]);
            }else{
                $scope.floors.push({FLOOR:$scope.allRooms[i]['FLOOR'],FLOOR_ID:$scope.allRooms[i]['FLOOR_ID']});
                $scope.rooms[$scope.allRooms[i].FLOOR_ID] = [$scope.allRooms[i]];
            }
        }
        if(!('-1' in $scope.rooms)){
            $scope.floors.push({FLOOR:"待定",FLOOR_ID:-1});
            $scope.rooms["-1"]=[];
        }
        $scope.selectedFloor = $scope.floors[0];
        $scope.totalItems = $scope.rooms[$scope.selectedFloor.FLOOR_ID].length;
        $scope.ready = true;
    });

    $scope.$watch(function(){
            return $scope.selectedFloor;
        },
        function(newValue, oldValue) {
            for (var i = 0; i < $scope.floors.length; i++){
                if ($scope.floors[i].FLOOR_ID == oldValue.FLOOR_ID){
                    $scope.floors[i].selectedStyle = "";
                }
            }
            newValue.selectedStyle = {border:'2px solid #6535DE'};
        },
        true
    );

    $scope.select = function(floor){
        $scope.selectedFloor = floor;
        $scope.totalItems = $scope.rooms[floor.FLOOR_ID].length;

    };





    (function drawTriangle(context, x, y, triangleWidth, triangleHeight, fillStyle){

        var canvas = document.getElementById("canvas");
        var context = canvas.getContext("2d");
        var triangleWidth = 130;
        var triangleHeight = 50;
        var x = canvas.width / 2;
        var y = 10;
        var grd = context.createLinearGradient(canvas.width  / 5, y, canvas.width / 5, y + triangleHeight);
        grd.addColorStop(0, "#8ED6FF");
        grd.addColorStop(1, "#004CB3");

        context.beginPath();
        context.moveTo(x, y);
        context.lineTo(x + triangleWidth / 2, y + triangleHeight);
        context.lineTo(x - triangleWidth / 2, y + triangleHeight);
        context.closePath();
        context.fillStyle = grd;
        context.fill();
    })();



    $scope.ConfirmEditFloor = function(floor){
        if(floor.FLOOR_ID=="-1"){
            alert("不可修改待定,该层用于存放待决定房间");
            return;
        }
        if($scope.editRecorderFloor != floor) {
            alert("未修改该楼层");
            return;
        }
        if(confirm("确认修改?")){
            settingRoomFactory.floorsEdit(JSON.stringify(floor)).success(function(data){
                for( var i =0; i< $scope.rooms[floor.FLOOR_ID].length; i++){
                    $scope.rooms[floor.FLOOR_ID][i].FLOOR = floor.FLOOR;
                }
                resetFloor();
            });
        };
    };

    $scope.focusFloor = function(floor){
        if($scope.editRecorderFloor == ""){
            floor['focusedCss'] = "focusedRow";
            $scope.editRecorderFloor = floor;
            $scope.editInitRecorderFloor= JSON.parse(JSON.stringify(floor));
        }else if($scope.editRecorderFloor != floor){
            alert("您将修改下一种房型设置,确认取消对当前房型的修改");
            for (key in $scope.editInitRecorderFloor){
                $scope.editRecorderFloor[key]=$scope.editInitRecorderFloor[key];
            }
            floor.focusedCss = "focusedRow";
            $scope.editRecorderFloor.focusedCss="";
            $scope.editRecorderFloor = floor;
            $scope.editInitRecorderFloor= JSON.parse(JSON.stringify(floor));
        }
    };

    $scope.deleteEditFloor = function(floor){
        if(floor.FLOOR_ID=="-1"){
            alert("不可修改待定,该层用于存放待决定房间");
            return;
        }
        if(confirm("确认删除该楼层? 该楼层房间楼层熟悉将变为待定")){
            settingRoomFactory.floorsDelete(floor.FLOOR_ID).success(function(data){
                $scope.floors.splice($scope.floors.indexOf(floor), 1);
                for( var i =0; i< $scope.rooms[floor.FLOOR_ID].length; i++){
                    $scope.rooms[floor.FLOOR_ID][i].FLOOR = '待定';
                    $scope.rooms[floor.FLOOR_ID][i].FLOOR_ID = -1;
                    $scope.rooms["-1"].push($scope.rooms[floor.FLOOR_ID][i]);
                }
                delete $scope.rooms[floor.FLOOR_ID];
                resetFloor();
            });
        };
    };

    $scope.addNewFl = function(){
        if ($scope.newFloor.FLOOR ==""){
            alert("请提供楼层名称");
            return;
        }
        for (var floor in $scope.floors){
            if(floor.FLOOR == $scope.newFloor.FLOOR){
                alert("新加楼层名与已有楼层名重复");
                return;
            };
        };

        settingRoomFactory.floorsAdd($scope.newFloor).success(function(data){
            $scope.newFloor.FLOOR_ID = data;
            $scope.floors.push({FLOOR:$scope.newFloor.FLOOR,FLOOR_ID:$scope.newFloor.FLOOR_ID});
            $scope.rooms[$scope.newFloor.FLOOR_ID]=[];
            $scope.newFloor = {FLOOR:'',FLOOR_ID:''};
            alert("新楼层已确认,需添加该楼层房间");
        });
    }



    $scope.focusRoom = function(room){
        if($scope.editRecorder == ""){
            room['focusedCss'] = "focusedRow";
            $scope.editRecorder = room;
            $scope.editInitRecorder= JSON.parse(JSON.stringify(room));
        }else if($scope.editRecorder != room){
            alert("您将修改下一种房型设置,确认取消对当前房型的修改");
            for (key in $scope.editInitRecorder){
                $scope.editRecorder[key]=$scope.editInitRecorder[key];
            }
            room.focusedCss = "focusedRow";
            $scope.editRecorder.focusedCss="";
            $scope.editRecorder = room;
            $scope.editInitRecorder= JSON.parse(JSON.stringify(room));
        }
    };




//    var focusInit = function(initialRecorder,recorder,changeable){
//        changeable['focusedCss'] = "focusedRow";
//        recorder = changeable;
//        initialRecorder= JSON.parse(JSON.stringify(changeable));
//    }
//
//    $scope.focusFloor = function(floor){
//        if (Object.getOwnPropertyNames($scope.editRecorderFloor).length == 0){
//            focusInit($scope.editInitRecorderFloor,$scope.editRecorderFloor,floor);
//        }else if( $scope.editRecorderFloor != floor){
//            focusChange($scope.editInitRecorderFloor,$scope.editRecorderFloor,
//                floor,"您将修改下一种房型设置,确认取消对当前房型的修改?");
//        }
//    };
//    var focusChange = function(initialRecorder,recorder,changeable,confirmSentence){
//             if (confirm(confirmSentence)){
//                for (key in initialRecorder){
//                    recorder[key]=initialRecorder[key];
//                }
//                 changeable.focusedCss = "focusedRow";
//                 recorder.focusedCss="";
//                 recorder = changeable;
//                 initialRecorder= JSON.parse(JSON.stringify(changeable));
//            }
//    };


    $scope.confirmEditRoom = function(room,$index){
        if($scope.editRecorder != room) {
            alert("未修改该房间");
            return;
        }
        if(room.FLOOR != $scope.editInitRecorder.FLOOR){
            for (var i = 0; i<$scope.floors.length; i++ ){
                if (room.FLOOR == $scope.floors[i].FLOOR){
                    room.FLOOR_ID = $scope.floors[i].FLOOR_ID;
                }
            }
        }
        if(confirm("确认修改?")){
            settingRoomFactory.roomsEdit(JSON.stringify([$scope.editInitRecorder.RM_ID,room])).success(function(data){
                $scope.rooms[$scope.editInitRecorder.FLOOR_ID].splice($index,1);
                $scope.rooms[room.FLOOR_ID].push(room);
                reset();
            });
        };
    };

    $scope.deleteEditRoom = function(room,index){
        var db_RM_ID = room.RM_ID;
        if (db_RM_ID == ""){
            db_RM_ID = " ";
        }
        if($scope.editInitRecorder!="" && $scope.editRecorder == room){
            db_RM_ID = $scope.editInitRecorder.RM_TP;
        }
        if(confirm("确认删除该房间?")){
            settingRoomFactory.roomsDelete(db_RM_ID).success(function(data){
                $scope.rooms[$scope.selectedFloor.FLOOR_ID].splice(index, 1);
                $scope.editRecorder == "";
            });
        };
    };

    $scope.addNewRm = function(){
        if ($scope.newRoom.RM_ID ==""){
            alert("请提供房间号");
            return;
        }
        for (key in $scope.rooms){
            for(var i=0; i<$scope.rooms[key].length ;i++){
                if($scope.rooms[key][i].RM_ID == $scope.newRoom.RM_ID){
                    alert("新加房间号与已有房号重复");
                    return;
                };
            }
        };

        $scope.newRoom.FLOOR_ID = $scope.selectedFloor.FLOOR_ID;
        $scope.newRoom.FLOOR = $scope.selectedFloor.FLOOR;
        settingRoomFactory.roomsAdd($scope.newRoom).success(function(data){
            $scope.rooms[$scope.selectedFloor.FLOOR_ID].push($scope.newRoom);
            $scope.newRoom = {RM_ID:'',RM_TRAN_ID:null,RM_CONDITION:'空房',RM_TP:'',
                RM_CHNG_TSTMP:null,FLOOR:'',FLOOR_ID:'',PHONE:'',RMRK:''};
            $scope.editRecorder == "";
        });
    }

});



app.controller('settingTempRoomController', function($scope, $http, settingTempRoomFactory,settingRoomFactory){
    $scope.editRecorder ="";                //储存该房型object;
    $scope.editInitRecorder="";             //储存该房型修改前的参数；
    $scope.plans=[];

    $scope.newPlan = {RM_TP:'',PLAN_COV_MIN:'0',PLAN_COV_PRCE:'0.00',PNLTY_PR_MIN:'0.00'};

    var reset = function(){
        $scope.editRecorder.focusedCss="";
        $scope.editRecorder ="";
        $scope.editInitRecorder="";
    };

    settingTempRoomFactory.tempPlanGet().success(function(data){
        $scope.plans = data;
        for(var i=0; i<$scope.plans.length; i++){
            $scope.plans[i]['PLAN_COV_PRCE'] = $scope.Limit($scope.plans[i]['PLAN_COV_PRCE']);
            $scope.plans[i]['PNLTY_PR_MIN'] = $scope.Limit($scope.plans[i]['PNLTY_PR_MIN']);

        }
    });

    settingRoomFactory.roomTpGet().success(function(data){
        $scope.rmTps = data;
    });

    $scope.focus = function(plan){
        if($scope.editRecorder == ""){
            plan.focusedCss = "focusedRow";
            $scope.editRecorder = plan;
            $scope.editInitRecorder= JSON.parse(JSON.stringify(plan));
        }else if($scope.editRecorder != plan){
            alert("您将修改下一种套餐,确认取消对当前套餐的修改")
            for (key in $scope.editInitRecorder){
                $scope.editRecorder[key]=$scope.editInitRecorder[key];
            }
            plan.focusedCss = "focusedRow";
            $scope.editRecorder.focusedCss="";
            $scope.editRecorder = plan;
            $scope.editInitRecorder= JSON.parse(JSON.stringify(plan));
        }
    };

    $scope.confirmEdit = function(plan){
        if($scope.editRecorder != plan) {
            return;
        }
        if(confirm("确认修改?")){
            settingTempRoomFactory.planEdit(JSON.stringify([$scope.editInitRecorder.PLAN_ID,plan])).success(function(data){
                reset();
            });
        };
    };

    $scope.deleteEdit = function(plan,index){
        if(confirm("确认删除该套餐?")){
            settingTempRoomFactory.planDelete(plan.PLAN_ID).success(function(data){
                $scope.plans.splice(index, 1);
            });
        };

    };

    $scope.addNewPlan = function(){
        if ($scope.newPlan.RM_TP ==""){
            alert("请提供房型名称");
            return;
        }
        for(var i=0; i<$scope.plans.length ;i++){
            if($scope.plans[i].RM_TP == $scope.newPlan.RM_TP && $scope.plans[i].PLAN_COV_MIN == $scope.newPlan.PLAN_COV_MIN
                && $scope.plans[i].PLAN_COV_PRCE == $scope.newPlan.PLAN_COV_PRCE){
                alert("新加套餐与已有套餐重复");
                return;
            };
        }
        settingTempRoomFactory.planAdd($scope.newPlan).success(function(data){
            $scope.newPlan.PLAN_ID = data;
            $scope.plans.push($scope.newPlan);
            $scope.newPlan = {RM_TP:'',PLAN_COV_MIN:'0',PLAN_COV_PRCE:'0.00',PNLTY_PR_MIN:'0.00'};
        });
    }

    $scope.Limit = function(num){
        return parseFloat(num).toFixed(2);
    }
});
/*-----------------------------------utility class:----------------------------------*/

