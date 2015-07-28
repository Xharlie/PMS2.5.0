/**
 * Created by Xharlie on 2/6/15.
 */

app.controller('checkOutModalController', function($scope, $http, focusInSideFactory,newCheckOutFactory, $modalInstance,merchandiseFactory,
                               sessionFactory,paymentFactory,$timeout, RM_TRAN_IDFortheRoom,connRM_TRAN_IDs,ori_Mastr_RM_TRAN_ID,initialString){
    /********************************************     validation     ***************************************************/

    $scope.hasError = function(btnPass){
        if(eval("$scope."+btnPass)==null) eval("$scope."+btnPass+"=0");
        eval("$scope."+btnPass+"++");
    }
    $scope.noError = function(btnPass){
        eval("$scope."+btnPass+"--");
    }
    $scope.payError=0;
    /********************************************     utility     ***************************************************/
    var today = new Date();
    var tomorrow = new Date(today.getTime()+86400000);
    var defaultTimeString="13:00:00";

    var createNewRMProduct = function(){
        var newProduct={PROD_ID:"",PROD_NM:"", PROD_PRICE:"", PROD_QUAN:"",PROD_PAY:""};
        return newProduct;
    }

    var createNewFee = function(){
        var newFee = {"RMRK":"","PAY_AMNT":"", "PAYER":"","PAYER_PHONE":""};
        return newFee;
    }

    var initNewRoomAcct = function(room){
        room["DEPO_SUM"] = 0;
        room["Acct_SUM"] = 0;
        room["Store_SUM"] = 0;
        room["DAYS_STAY"] = Math.round( (new Date(room["CHECK_OT_DT"]).getTime()
                            -new Date(room["CHECK_IN_DT"]).getTime())/86400000);
    }

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };

    $scope.updateSumation=function(){
        var sumation=0;
        for( var key in $scope.acct){
            for(var i = 0; i < $scope.acct[key].length; i++){
                if(!$scope.acct[key][i]['transfer'] && $scope.acct[key][i]['show']){
                    sumation = sumation + util.Limit($scope.acct[key][i].payAmount)*((key=="AcctDepo")?-1:1);
                }
            }
        }
        for(var i = 0; i < $scope.addedItems.length; i++){
            if(!$scope.addedItems[i]['transfer']){
                sumation = sumation + util.Limit($scope.addedItems[i].PAY_AMNT) // * (($scope.addedItems[i].itemCategory=="newDepo")?-1:1);
            }
        }
        $scope.BookCommonInfo.Master.payment.paymentRequest = sumation;
    }



    $scope.twoDigit = function(limitee){
        return util.Limit(limitee);
    }

    $scope.abs = function(limitee){
        return Math.abs(limitee);
    }


    var createNewAddedItem = function(category){
        var newAddedItem = {RM_TRAN_ID:RM_TRAN_IDFortheRoom, itemCategory:category, paymentRequest:"",RMRK:""
                            ,prodInfo:createNewRMProduct(), penalty:createItemPenalty()};
        return newAddedItem;
    }

    var createItemPenalty = function(){
        var newPenalty  = { "PENALTY_ITEM": "",
                            "PAY_AMNT": "",
                            "PAYER": "",
                            "PAYER_PHONE": ""};
        return newPenalty;
    }


    function checkQuanInvalid(quan){
        return   (filterAlert.isNotEmpty(quan)!=null
            || filterAlert.isNumber(quan)!=null
            || filterAlert.isLargerEqualThan1(quan)!=null);
    }

    $scope.updateItemPrice = function(item){
        if(item.itemCategory=='merchant'){
            if(filterAlert.isNotEmpty(item.prodInfo.PROD_PRICE)!=null
                || checkQuanInvalid(item.prodInfo.PROD_QUAN)){
                return null;
            }else{
                return util.Limit(item.prodInfo.PROD_QUAN*item.prodInfo.PROD_PRICE);
            }
        }
    }



    var addNewItemsInList = function(item,newIds){
        if(item.itemCategory == "merchant"){
            var prod = item.prodInfo;
            if(prod.PROD_QUAN !=0){
                $scope.addedItems.push({
                    "itemCategory": "merchant",
                    "ID": ++$scope.newItemID,
                    "transfer": false,
                    "TSTMP": util.tstmpFormat(today),
                    "RM_ID": $scope.TRAN2RMmapping[item.RM_TRAN_ID],
                    "showUp":prod.PROD_NM+"X"+prod.PROD_QUAN,
                    "RM_TRAN_ID":item.RM_TRAN_ID,
                    "PAY_AMNT": util.Limit(prod.PROD_PAY),
                    "PROD_ID":prod.PROD_ID,
                    "PROD_QUAN": prod.PROD_QUAN,
                    "RMRK": item.RMRK
                })
                newIds.push("newItem"+$scope.newItemID.toString());
            }
        }
        else if(item.itemCategory == "penalty"){
            var pen = item.penalty;
            if(pen.PAY_AMNT!='' && pen.PENALTY_ITEM!='' && pen.PAYER_PHONE!=''){
                $scope.addedItems.push({
                    "itemCategory": "penalty",
                    "ID": ++$scope.newItemID,
                    "transfer": false,
                    "TSTMP": util.tstmpFormat(today),
                    "RM_ID": $scope.TRAN2RMmapping[item.RM_TRAN_ID],
                    "showUp":"赔偿:"+pen.PENALTY_ITEM ,
                    "RM_TRAN_ID":item.RM_TRAN_ID,
                    "PAY_AMNT": util.Limit(pen.PAY_AMNT),
                    "PENALTY_ITEM": pen.PENALTY_ITEM ,
                    "PAYER": pen.PAYER,
                    "PAYER_PHONE": pen.PAYER_PHONE,
                    "RMRK": item.RMRK
                })
                newIds.push("newItem"+$scope.newItemID.toString());
            }
        }else if(item.itemCategory == "newAcct"){
            if(filterAlert.isNotEmpty(item.RMRK) == null){
                $scope.addedItems.push({
                    "itemCategory": "newAcct",
                    "ID": ++$scope.newItemID,
                    "transfer": false,
                    "TSTMP": util.tstmpFormat(today),
                    "RM_ID": $scope.TRAN2RMmapping[item.RM_TRAN_ID],
                    "showUp":"补录房费:",
                    "RM_TRAN_ID":item.RM_TRAN_ID,
                    "PAY_AMNT": util.Limit(item.paymentRequest),
                    "RMRK": item.RMRK
                })
                newIds.push("newItem"+$scope.newItemID.toString());
            }
        }
    }
        ;

    var testFail = function(){
        return false;
    }

    // to be implemented
    var exceedPayMethod = function(diff){
        return util.Limit(diff/10);
    }

    $scope.checkTransferable= function(){
        if(initialString == 'checkOut') {
            for (var i = 0; i < $scope.BookRoom.length; i++) {
                if (!$scope.BookRoom[i]["selected"]) {
                    $scope.BookCommonInfo.transferable = true;
                    return;
                }
            }
        }
        $scope.BookCommonInfo.transferable = false;
    };

    $scope.loadSelectedProd = function(prod){
        $scope.newItem.prodInfo.PROD_ID = prod.PROD_ID;
        $scope.newItem.prodInfo.PROD_PRICE = prod.PROD_PRICE;
        $scope.newItem.prodInfo.PROD_QUAN = (checkQuanInvalid($scope.newItem.prodInfo.PROD_QUAN))?1:$scope.newItem.prodInfo.PROD_QUAN;
        $scope.newItem.prodInfo.PROD_PAY = $scope.updateItemPrice($scope.newItem);

    }
    $scope.cleanSelectedProd = function(){
        $scope.newItem.prodInfo.PROD_ID = null;
        $scope.newItem.prodInfo.PROD_NM = null;
        $scope.newItem.prodInfo.PROD_PRICE = null;
        $scope.newItem.prodInfo.PROD_QUAN = (checkQuanInvalid($scope.newItem.prodInfo.PROD_QUAN))?null:$scope.newItem.prodInfo.PROD_QUAN;
        $scope.newItem.prodInfo.PROD_PAY = null;
    }

    var setMasterRmId =function(){
        // master ID if is connected room
        if(ori_Mastr_RM_TRAN_ID != null && ori_Mastr_RM_TRAN_ID !=""){
            $scope.BookCommonInfo.Master.mastr_RM_TRAN_ID = ori_Mastr_RM_TRAN_ID;
            // if master room has been checked out ,but still some room left
            if(initialString == "checkOut" && $scope.RM_TRAN_ID_SelectedList[ori_Mastr_RM_TRAN_ID]){
                for (var key in $scope.RM_TRAN_ID_SelectedList) {
                    if (!$scope.RM_TRAN_ID_SelectedList[key]) {
                        $scope.BookCommonInfo.Master.mastr_RM_TRAN_ID = key;
                        break;
                    }
                }
            }
        }else{
            // master ID if is connected room
            $scope.BookCommonInfo.Master.mastr_RM_TRAN_ID = RM_TRAN_IDFortheRoom;
        }
    }

    var setAcctShow = function(){
        for( var key in $scope.acct){
            for(var i = 0; i < $scope.acct[key].length; i++){
                if(key == 'AcctDepo' || key == 'exceedPay'){
                    $scope.acct[key][i]['show'] = $scope.RM_TRAN_ID_SelectedList[$scope.acct[key][i]['RM_TRAN_ID']] ;
                }else{
                    $scope.acct[key][i]['show'] = $scope.RM_TRAN_ID_SelectedList[$scope.acct[key][i]['TKN_RM_TRAN_ID']];
                }
            }
        }
    }

    var setExTimeAcct = function(){
            $scope.acct["exceedPay"] = [];
            for (var i = 0; i < $scope.BookRoom.length; i++) {
                if(!$scope.RM_TRAN_ID_SelectedList[$scope.BookRoom[i].RM_TRAN_ID]) continue;
                var expectedOut = ($scope.BookRoom[i].LEAVE_TM != undefined && $scope.BookRoom[i].LEAVE_TM.length == 8) ?
                    $scope.BookRoom[i].LEAVE_TM : defaultTimeString;

                var diff = util.Limit((Number(today) - Number(new Date(util.dateFormat(today) + "T" + expectedOut + ".000Z"))) / 1000 / 60
                - today.getTimezoneOffset());
                if (diff > 0) {
                    $scope.acct["exceedPay"].push({
                        "BILL_TSTMP": util.tstmpFormat(today),
                        "RM_TRAN_ID": $scope.BookRoom[i]["RM_TRAN_ID"],
                        RM_ID: $scope.BookRoom[i]["RM_ID"],
                        "RM_PAY_AMNT": exceedPayMethod(diff),
                        exceedTime: parseInt(diff)
                    });
                }
            }
    }
    /************** ********************************** Initial functions ******************************************* *************/
    var initGetAllAcct= function(){
        newCheckOutFactory.getAllInfo(connRM_TRAN_IDs).success(function(data){
            $scope.BookRoom = data['room'];
            $scope.acct = data['acct'];
            $scope.acct["exceedPay"] = [];
            for (var i = 0; i < $scope.BookRoom.length; i++){
                $scope.BookRoom[i]["selected"] =  ($scope.BookRoom[i].RM_TRAN_ID==RM_TRAN_IDFortheRoom) || initialString == "checkLedger";
                $scope.RM_TRAN_ID_SelectedList[$scope.BookRoom[i].RM_TRAN_ID] = $scope.BookRoom[i]["selected"];
                $scope.TRAN2RMmapping[$scope.BookRoom[i].RM_TRAN_ID] = $scope.BookRoom[i].RM_ID;
                initNewRoomAcct($scope.BookRoom[i]);
            }
            for( var key in $scope.acct){
                for(var i = 0; i < $scope.acct[key].length; i++){
                    $scope.acct[key][i]['transfer'] = false;
                    $scope.acct[key][i]['show'] = $scope.RM_TRAN_ID_SelectedList[$scope.acct[key][i]['TKN_RM_TRAN_ID']]; // initialize the shown acct
                    switch(key){
                        case 'AcctDepo':
                            $scope.acct[key][i]["payAmount"] = $scope.acct[key][i]["DEPO_AMNT"];
                            $scope.acct[key][i]['show'] = $scope.RM_TRAN_ID_SelectedList[$scope.acct[key][i]['RM_TRAN_ID']] ;
                            break;
                        case 'AcctPay':
                            $scope.acct[key][i]["payAmount"] = $scope.acct[key][i]["RM_PAY_AMNT"];
                            break;
                        case 'AcctStore':
                            if ($scope.acct[key][i]["PROD_PRICE"] == undefined){
                                $scope.acct[key][i]["payAmount"] = parseFloat($scope.acct[key][i]["STR_PAY_AMNT"]);
                                break;
                            }else {
                                $scope.acct[key][i]["payAmount"] = (parseFloat($scope.acct[key][i]["PROD_PRICE"])
                                    *parseFloat($scope.acct[key][i]["PROD_QUAN"]));
                            }
                            break;
                        case 'AcctPenalty':
                            $scope.acct[key][i]["payAmount"] = $scope.acct[key][i]["PNLTY_PAY_AMNT"];
                            break;
                        case 'exceedPay':
                            $scope.acct[key][i]["payAmount"] = $scope.acct[key][i]["RM_PAY_AMNT"];
                            $scope.acct[key][i]['show'] = $scope.RM_TRAN_ID_SelectedList[$scope.acct[key][i]['RM_TRAN_ID']] ;
                            break;
                    }
                }
            }

            setMasterRmId();
            if(initialString == 'checkOut') setExTimeAcct();
            setAcctShow();

            $scope.updateSumation();
            // if only check out  connected rooms
            $scope.viewClick=($scope.BookRoom.length>1 && initialString != "checkLedger")?'RoomChoose':'Info';
            $scope.ready=true;

        });
    }

    var initGetRoomProduct = function(){
        merchandiseFactory.productShow().success(function(data){
            $scope.prodInfo = data;
            //for(var i=0; i< data.length; i++){
            //    $scope.prodInfo[i].PROD_QUAN = 0;
            //    $scope.prodInfo[i].PROD_SELECTED=false;
            //}
            $scope.newItem = createNewAddedItem("merchant");
        });
    };
    /************** ********************************** Common initial setting  ******************************************* ********/
    var printerRCtransactions = []; // for printer info
    $scope.newItemID = 0;
    $scope.BookCommonInfo = {selectAll:false,Master:{mastr_RM_TRAN_ID:"",transferable:false,
                             ori_Mastr_RM_TRAN_ID:ori_Mastr_RM_TRAN_ID, payment: paymentFactory.createNewPayment('住房押金'), check:true}};
    $scope.watcher = {member:true, selected:true, selectAll:true, addedItems:true,exceedPay:true};
    $scope.TRAN2RMmapping = {};
    $scope.addedItems=[];
    $scope.RM_TRAN_ID_SelectedList={};
    $scope.newItem={};
    $scope.ready=false;
    focusInSideFactory.tabInit('wholeModal');
    $timeout(function(){
        focusInSideFactory.manual('wholeModal');
    },0)
    $scope.BookRoomMaster = [$scope.BookCommonInfo.Master];
    $scope.payMethodOptions=paymentFactory.checkInPayMethodOptions();

    /************** ********************************** Initialize by conditions ********************************** *************/
    $scope.initialString = initialString;
    if(initialString == "checkOut" || "checkLedger" ){
        initGetAllAcct();
        initGetRoomProduct();
    }
    /************** ********************************** Page Logical Confinement ********************************** *************/
    // select room
    $scope.$watch('BookCommonInfo.selectAll',
        function(newValue, oldValue) {
            if(newValue == oldValue || !$scope.watcher.selectAll ) return;
            $scope.watcher.selected=false;    // prevent watcher of selected to activated multiple times
                for (var i = 0; i < $scope.BookRoom.length; i++){
                    $scope.BookRoom[i].selected = newValue ;
                }
                for( var key in $scope.acct){
                    for(var i = 0; i < $scope.acct[key].length; i++){
                        $scope.acct[key][i]['show'] = newValue;
                    }
                }
                // update $scope.RM_TRAN_ID_SelectedList to all true
                for(var key in $scope.RM_TRAN_ID_SelectedList ){
                    $scope.RM_TRAN_ID_SelectedList[key] = true;
                }
            $timeout(function() {
                $scope.watcher.selected=true;
            }, 0);
        },
        true
    );

    $scope.$watch('newItem.prodInfo.PROD_QUAN',
        function(newValue, oldValue) {
            if(newValue == oldValue) return;
            if(filterAlert.isNotEmpty($scope.newItem.prodInfo.PROD_PRICE)!=null
                || checkQuanInvalid(newValue)){
                $scope.newItem.prodInfo.PROD_PAY = null;
            }else{
                $scope.newItem.prodInfo.PROD_PAY = util.Limit(newValue * $scope.newItem.prodInfo.PROD_PRICE);
            }
        },
        true
    );

    // item, category
    //$scope.changeCategory = function(item){
    //    item.paymentRequest = null;
    //}


    $scope.addItem = function(newItem){
        $scope.newAddedIds = [];
        // insert to addedItems list
        addNewItemsInList(newItem,$scope.newAddedIds);
        // newItem category stay same
        $scope.newItem = createNewAddedItem(newItem.itemCategory);
        // fire the transition
        $timeout(function() {
            $scope.ItemScrollTo = "newItem"+$scope.newItemID.toString();
        }, 0);
    }



    $scope.deleteItem = function(item,group,index){
        group.splice(index,1);
        $scope.updateSumation();
    }


    $scope.$watch('addedItems',
        function(newValue,oldValue){
          if(newValue == oldValue || !$scope.watcher.addedItems ) return;
          $scope.updateSumation();
        },
        true
    );

    $scope.$watch('acct.exceedPay',
        function(newValue,oldValue){
            if(newValue == oldValue || !$scope.watcher.exceedPay ) return;
            for(var i = 0; i < $scope.acct.exceedPay.length; i++){
                $scope.acct.exceedPay[i]["payAmount"] = $scope.acct.exceedPay[i]["RM_PAY_AMNT"];
            }
            $scope.updateSumation();
        },
        true
    );

    $scope.addNewPayByMethod = function(singleRoom){
        singleRoom.payment.payByMethods.push(createNewPayByMethod());
    }

/************** ********************************** page change  ********************************** *************/
    $scope.confirmRoom = function(target){
        // clean out
        for(var i = $scope.addedItems.length-1; i>=0; i--){
            if(!$scope.RM_TRAN_ID_SelectedList[$scope.addedItems[i].RM_TRAN_ID]) $scope.addedItems.splice(i,1);
        }
        $scope.addedItems=[];
        setMasterRmId();
        if(initialString == 'checkOut') setExTimeAcct();
        setAcctShow();
        $scope.updateSumation();
        $scope.viewClick=target;
    }

    $scope.backward = function(page){
        $scope.viewClick = page;
    }

    $scope.confirm = function(page){
        $scope.viewClick = page;
        // Master's paymentRequested has already bound to summation
        $scope.BookCommonInfo.Master.payment.payByMethods[0].payAmount =  $scope.BookCommonInfo.Master.payment.paymentRequest;
        $scope.BookCommonInfo.Master.payment.payInDue = 0;
        $scope.BookCommonInfo.Master.payment.payByMethods[0].payMethod = "现金";
    }
    /************** ********************************** submit  ********************************** *************/

    $scope.checkOTSubmit = function(){
        $scope.submitLoading = true;
        var submitObj = {RoomArray:$scope.submitPrepRoom(),
                        MasterRoomNpay:$scope.BookCommonInfo.Master,
                        editAcct:$scope.submitPrepEditAcct(),
                        addAcct:$scope.submitPrepAddAcct(),
                        addDepoArray:$scope.submitPrepAddDepo()};

        /***********  for printer   *************/
        var rooms = [];
        for (var i = 0; i < $scope.BookRoom.length; i++){
            var rm = $scope.BookRoom[i];
            if(rm.selected && $scope.BookCommonInfo.Master.mastr_RM_TRAN_ID == rm.RM_TRAN_ID){
                rooms.push(rm);
            }
        }
        var pms ={HTL_NM:null,EMP_NM:null};
        sessionFactory.getUserInfo().success(function(data){
            pms.HTL_NM = data.HTL_NM;
            pms.EMP_NM = data.EMP_NM;
            /***********  for printer   *************/
            newCheckOutFactory.checkOT(submitObj).success(function(data){
                    $scope.submitLoading = false;
                    printer.receipt(pms, rooms[0], rooms[0].Customers[0],printerRCtransactions);
                    show("办理成功!");
                    $modalInstance.close("checked")
                    //util.closeCallback();
            });
        });
    }

    $scope.checkLedgerSubmit = function(){
        $scope.submitLoading = true;
        var submitObj = {addAcct:$scope.submitPrepAddAcct(),
            depoDeduction:{RM_TRAN_ID:$scope.BookCommonInfo.Master.mastr_RM_TRAN_ID,DPST_RMN_DEDCUTION:$scope.submitPrepDepoDeduction()} };
        newCheckOutFactory.checkLedgerOT(submitObj).success(function(data){
                $scope.submitLoading = false;
                show("办理成功!");
                $modalInstance.close("checked");
                //util.closeCallback();
            });
    }


    $scope.submitPrepEditAcct = function(){
        var editAcct = {RoomAcct:[],PenaltyAcct:[],RoomStoreTran:[],RoomDepositAcct:[]};
        for( var key in $scope.acct){
            for(var i = 0; i < $scope.acct[key].length; i++){
                var ac = $scope.acct[key][i];
                if(!ac.show || ac.transfer) continue;
                switch(key){
                    case 'AcctDepo':
                        editAcct.RoomDepositAcct.push({RM_DEPO_ID:ac.RM_DEPO_ID,RM_TRAN_ID:$scope.BookCommonInfo.Master.mastr_RM_TRAN_ID,
                            FILLED: 'T' });
                        break;
                    case 'AcctPay':
                        editAcct.RoomAcct.push({RM_BILL_ID:ac.RM_BILL_ID, TKN_RM_TRAN_ID:$scope.BookCommonInfo.Master.mastr_RM_TRAN_ID,
                            FILLED: 'T' });
                        break;
                    case 'AcctPenalty':
                        editAcct.PenaltyAcct.push({PEN_BILL_ID:ac.PEN_BILL_ID, TKN_RM_TRAN_ID:$scope.BookCommonInfo.Master.mastr_RM_TRAN_ID,
                            FILLED: 'T' });
                        break;
                    case 'AcctStore':
                        editAcct.RoomStoreTran.push({STR_TRAN_ID:ac.STR_TRAN_ID, TKN_RM_TRAN_ID:$scope.BookCommonInfo.Master.mastr_RM_TRAN_ID,
                            FILLED: 'T' });
                        break;
                }
                printer.tranPush2printer(key,ac,printerRCtransactions);
            }
        }
        return editAcct;
    }

    $scope.submitPrepAddAcct = function(){
        var addAcct = {RoomAcct:[],PenaltyAcct:[],RoomStore:[]};
        for(var i = 0; i < $scope.addedItems.length; i++){
            var ac = $scope.addedItems[i];
            switch(ac.itemCategory){
                case 'merchant':
                    addAcct.RoomStore.push({
                        RoomStoreTran:   {TKN_RM_TRAN_ID:$scope.BookCommonInfo.Master.mastr_RM_TRAN_ID,
                            RM_TRAN_ID:ac.RM_TRAN_ID,
                            RM_ID: $scope.TRAN2RMmapping[ac.RM_TRAN_ID],
                            FILLED: (!ac.transfer && initialString == "checkOut" )?'T':'F'},
                        StoreTransaction:{STR_TRAN_TSTMP:ac.TSTMP,
                            STR_PAY_AMNT:ac.PAY_AMNT,
                            STR_PAY_METHOD:'房间挂账',
                            RMRK:ac.RMRK,
                            EMP_ID:null},
                        ProductInTran:   {PROD_ID: ac.PROD_ID,
                            PROD_QUAN: ac.PROD_QUAN}
                    });
                    break;
                case 'penalty':
                    addAcct.PenaltyAcct.push({RM_TRAN_ID:ac.RM_TRAN_ID,TKN_RM_TRAN_ID:$scope.BookCommonInfo.Master.mastr_RM_TRAN_ID,
                        BRK_EQPMT_RMRK: ac.RMRK, PNLTY_PAY_AMNT: ac.PAY_AMNT,PAYER_NM:ac.PAYER,PAYER_PHONE:ac.PAYER_PHONE,
                        BILL_TSTMP:ac.TSTMP,FILLED: (!ac.transfer && initialString == "checkOut")?'T':'F' });
                    break;
                case 'newAcct':
                    addAcct.RoomAcct.push({RM_TRAN_ID:ac.RM_TRAN_ID,TKN_RM_TRAN_ID:$scope.BookCommonInfo.Master.mastr_RM_TRAN_ID,
                        SUB_CAT:'手录',RMRK: ac.RMRK, RM_PAY_AMNT: ac.PAY_AMNT,BILL_TSTMP:ac.TSTMP,
                        FILLED: (!ac.transfer && initialString == "checkOut")?'T':'F' });
                    break;
            }
            printer.tranPush2printer(ac.itemCategory,ac,printerRCtransactions);

        }
        for(var i = 0; i < $scope.acct['exceedPay'].length; i++){
            var ac = $scope.acct['exceedPay'][i];
            var exceedPay = {RM_TRAN_ID:ac.RM_TRAN_ID,TKN_RM_TRAN_ID:$scope.BookCommonInfo.Master.mastr_RM_TRAN_ID,
                SUB_CAT:'超时',RMRK: '', RM_PAY_AMNT: ac.RM_PAY_AMNT,BILL_TSTMP:ac.BILL_TSTMP,
                FILLED: (!ac.transfer && initialString == "checkOut" )?'T':'F' };
            addAcct.RoomAcct.push(exceedPay);
            printer.tranPush2printer('AcctPay',exceedPay,printerRCtransactions);
        }
        return addAcct;
    }

    $scope.submitPrepDepoDeduction = function(){
        var depoDeduction = 0;
        for (var i = 0; i < $scope.addedItems.length; i++) {
            depoDeduction = depoDeduction + $scope.addedItems[i].PAY_AMNT;
        }
        return depoDeduction;
    }

    $scope.submitPrepAddDepo = function(){
        var today = new Date();
        var addDepoArray =[];
        for(var i = 0; i < $scope.BookCommonInfo.Master.payment.payByMethods.length; i++){
            var ac = $scope.BookCommonInfo.Master.payment.payByMethods[i];
            var depo = {RM_TRAN_ID:$scope.BookCommonInfo.Master.mastr_RM_TRAN_ID,DEPO_AMNT:ac.payAmount,
                PAY_METHOD:ac.payMethod,DEPO_TSTMP:util.tstmpFormat(today),SUB_CAT:'存入',RMRK:"",FILLED:'T'}
            addDepoArray.push(depo);
        }
        printer.tranPush2printer('AcctDepo',depo,printerRCtransactions);
        return addDepoArray;
    }

    $scope.submitPrepRoom = function(){
        var RoomArray = [];
        for (var i = 0; i < $scope.BookRoom.length; i++){
            var rm = $scope.BookRoom[i];
            if(rm.selected){
                RoomArray.push({
                    RM_TP:rm.RM_TP,
                    RM_TRAN_ID:rm.RM_TRAN_ID,
                    oriCHECK_OT_DT:rm.CHECK_OT_DT
                });
            }
        }
        return RoomArray;
    }
})



/************************                       singleRoom sub controller                      ***********************/
.controller('checkOutSelectController', function ($scope) {
    $scope.$watch('singleRoom.selected',
        function(newValue, oldValue) {
            if(newValue == oldValue || !$scope.watcher.selected ) return;
            $scope.$parent.RM_TRAN_ID_SelectedList[$scope.singleRoom.RM_TRAN_ID] = newValue;
        },
        true
    );
})
/************************                       single Master Pay sub controller                      ***********************/
.controller('chotSingleMasterPayCtrl', function ($scope) {
    $scope.$watch('singlePay.payAmount',
        function(newValue, oldValue) {
            $scope.$parent.updatePayInDue($scope.$parent.BookCommonInfo.Master);
        },
        true
    );
});