/**
 * Created by Xharlie on 2/6/15.
 */

app.controller('checkOutModalController', function($scope, $http, newCheckOutFactory, $modalInstance,merchandiseFactory,
                                                   $timeout, RM_TRAN_IDFortheRoom,connRM_TRAN_IDs,ori_Mastr_RM_TRAN_ID,initialString){
    /********************************************     utility     ***************************************************/
    var today = new Date();
    var tomorrow = new Date(today.getTime()+86400000);
    var defaultTimeString="13:00:00";

    var createNewRMProduct = function(){
        var newProduct={"newRProductNM":"", "newRProductQUAN":"", "newRProductPAY":"","PROD_ID":""};
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
                sumation = sumation + util.Limit($scope.addedItems[i].PAY_AMNT);
            }
        }
        $scope.BookCommonInfo.Master.payment.paymentRequest = sumation;
    }

    $scope.updateShowItemsByRooms = function(){
        var size = 0;
        for (key in $scope.RM_TRAN_ID_SelectedList) {
            if ($scope.RM_TRAN_ID_SelectedList[key]) size++;
        }
        for( var key in $scope.acct){
            for(var i = 0; i < $scope.acct[key].length; i++){
                // if not in the check out rooms, means it's a transfer, should show to guest when check out all
                $scope.acct[key][i]['show'] = ($scope.acct[key][i].RM_TRAN_ID in $scope.RM_TRAN_ID_SelectedList) ?
                                               $scope.RM_TRAN_ID_SelectedList[$scope.acct[key][i].RM_TRAN_ID] :
                                               (size == $scope.BookRoom.length);
            }
        }
    }


    $scope.twoDigit = function(limitee){
        return util.Limit(limitee);
    }

    var createNewPayByMethod = function(){
        var payByMethod =  {payAmount:"",payMethod:""};
        return payByMethod;
    }

    var createNewPayment = function(){
        var Payment =  {paymentRequest:"", paymentType:"住房押金", payInDue:"",payByMethods:[createNewPayByMethod()]};
        return Payment;
    }

    var createNewAddedItem = function(){
        var newAddedItem = {RM_TRAN_ID:RM_TRAN_IDFortheRoom, itemCategory:"merchant", paymentRequest:"",RMRK:"",
                             penalty:createItemPenalty(), prodInfo:createProdInfo(),showup:"未选",isopen:false};
        return newAddedItem;
    }

    var createItemPenalty = function(){
        var newPenalty  = { "PENALTY_ITEM": "",
                            "PAY_AMNT": "",
                            "PAYER": "",
                            "PAYER_PHONE": ""};
        return newPenalty;
    }

    var createProdInfo = function(){
            return JSON.parse(JSON.stringify($scope.prodInfo));
    }

    $scope.updateItemCommentSelection = function(item){
        var showUpString="";
        if(item.itemCategory=='merchant'){
            for(var i =0; i<item.prodInfo.length;i++){
                if(item.prodInfo[i].PROD_SELECTED && item.prodInfo[i].PROD_QUAN >0){
                    showUpString=showUpString+item.prodInfo[i].PROD_NM+"X"+item.prodInfo[i].PROD_QUAN+"; " ;
                }
            }
            item.RMRK="";
        }else if(item.itemCategory=='penalty' ){
            if(item.penalty.PAY_AMNT!='' && item.penalty.PENALTY_ITEM!='' && item.penalty.PAYER_PHONE!=''){
                showUpString = "赔偿:"+item.penalty.PENALTY_ITEM;
                item.RMRK = item.penalty.PENALTY_ITEM+" (赔偿"+item.penalty.PAY_AMNT+"元)";
            }
        }
        if (showUpString=="") showUpString = "未选";
        item.showup = showUpString;
    }

    $scope.updateItemPrice = function(item){
        var addUp=0;
        if(item.itemCategory=='merchant'){
            for(var i =0; i<item.prodInfo.length;i++){
                if(item.prodInfo[i].PROD_SELECTED && item.prodInfo[i].PROD_QUAN >0){
                    addUp = addUp + util.Limit(item.prodInfo[i].PROD_PRICE) * parseInt(item.prodInfo[i].PROD_QUAN);
                }
            }
            item.RMRK="";
        }else if(item.itemCategory=='penalty' ){
            if(item.penalty.PAY_AMNT!='' && item.penalty.PENALTY_ITEM!='' && item.penalty.PAYER_PHONE!=''){
                addUp=item.penalty.PAY_AMNT;
            }
        }
        item.paymentRequest = (addUp == 0)? 'n/a' : util.Limit(addUp);
    }

//    var syncToList = function(item){
//        addNewItemsInList(item);
//    }

    var addNewItemsInList = function(item,newIds){
        if(item.itemCategory == "merchant"){
            for(var i=0; i<item.prodInfo.length; i++){
                var prod = item.prodInfo[i];
                if(prod.PROD_SELECTED && prod.PROD_QUAN !=0){
                    $scope.addedItems.push({
                        "itemCategory": "merchant",
                        "ID": ++$scope.newItemID,
                        "transfer": false,
                        "TSTMP": util.tstmpFormat(today),
                        "RM_ID": $scope.TRAN2RMmapping[item.RM_TRAN_ID],
                        "showUp":prod.PROD_NM+"X"+prod.PROD_QUAN,
                        "RM_TRAN_ID":item.RM_TRAN_ID,
                        "PAY_AMNT": util.Limit(prod.PROD_PRICE * prod.PROD_QUAN),
                        "PROD_ID":prod.PROD_ID,
                        "PROD_QUAN": prod.PROD_QUAN,
                        "RMRK": item.RMRK
                    })
                    newIds.push("newItem"+$scope.newItemID.toString());
                }
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
        }
    }

    $scope.updatePayInDue = function(singleRoom){
        var totalDue= parseFloat(singleRoom.payment.paymentRequest);
        for (var i=0; i<singleRoom.payment.payByMethods.length; i++){
            totalDue = totalDue - singleRoom.payment.payByMethods[i].payAmount;
        }
        singleRoom.payment.payInDue = util.Limit(parseFloat(totalDue));
        singleRoom.payment.payInDue = (isNaN(singleRoom.payment.payInDue))? 0.00: singleRoom.payment.payInDue;
    };

    var testFail = function(){
        return false;
    }

    // to be implemented
    var exceedPayMethod = function(diff){
        return util.Limit(diff/10);
    }

    /************** ********************************** Initial functions ******************************************* *************/
    var initGetAllAcct= function(){
        newCheckOutFactory.getAllInfo(connRM_TRAN_IDs).success(function(data){
            $scope.BookRoom = data['room'];
            $scope.acct = data['acct'];
            $scope.acct["exceedPay"] = [];
            for (var i = 0; i < $scope.BookRoom.length; i++){
                initNewRoomAcct($scope.BookRoom[i]);
                var expectedOut = ($scope.BookRoom[i].LEAVE_TM != undefined && $scope.BookRoom[i].LEAVE_TM.length==8)?
                                   $scope.BookRoom[i].LEAVE_TM : defaultTimeString;

                var diff = util.Limit((Number(today) - Number(new Date(util.dateFormat(today)+"T"+expectedOut+".000Z")))/1000/60);
                if (diff > 0){
                    $scope.acct["exceedPay"].push({"BILL_TSTMP":util.tstmpFormat(today),"RM_TRAN_ID":$scope.BookRoom[i]["RM_TRAN_ID"],
                        RM_ID:$scope.BookRoom[i]["RM_ID"],"RM_PAY_AMNT":exceedPayMethod(diff),exceedTime:parseInt(diff)});
                }

                $scope.BookRoom[i]["selected"] = ($scope.BookRoom[i].RM_TRAN_ID==RM_TRAN_IDFortheRoom);
                $scope.RM_TRAN_ID_SelectedList[$scope.BookRoom[i].RM_TRAN_ID] = ($scope.BookRoom[i].RM_TRAN_ID==RM_TRAN_IDFortheRoom);
                $scope.TRAN2RMmapping[$scope.BookRoom[i].RM_TRAN_ID] = $scope.BookRoom[i].RM_ID;

            }
            for( var key in $scope.acct){
                for(var i = 0; i < $scope.acct[key].length; i++){
                    $scope.acct[key][i]['transfer'] = false;
                    $scope.acct[key][i]['show'] = $scope.RM_TRAN_ID_SelectedList[$scope.acct[key][i]['RM_TRAN_ID']] ; // initialize the shown acct
                    switch(key){
                        case 'AcctDepo':
                            $scope.acct[key][i]["payAmount"] = $scope.acct[key][i]["DEPO_AMNT"];
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
                            break;
                    }
                }
            }
            $scope.updateSumation();
            $scope.viewClick=($scope.BookRoom.length>1)?'RoomChoose':'Info';
            $scope.ready=true;
        });
    }

    var initGetRoomProduct = function(){
        merchandiseFactory.productShow().success(function(data){
            $scope.prodInfo = data;
            for(var i=0; i< data.length; i++){
                $scope.prodInfo[i].PROD_QUAN = 0;
                $scope.prodInfo[i].PROD_SELECTED=false;
            }
                $scope.newItem = createNewAddedItem();

        });
    };
    /************** ********************************** Common initial setting  ******************************************* ********/

    $scope.newItemID = 0;
    $scope.BookCommonInfo = {selectAll:false,Master:{mastr_RM_TRAN_ID:"",
                             ori_Mastr_RM_TRAN_ID:ori_Mastr_RM_TRAN_ID, payment:createNewPayment()}};
    $scope.watcher = {member:true, selected:true, selectAll:true, isopen:true, addedItems:true,exceedPay:true};
    $scope.TRAN2RMmapping = {};
    $scope.addedItems=[];
    $scope.RM_TRAN_ID_SelectedList={};
    $scope.newItem={};
    $scope.ready=false;


    /************** ********************************** Initialize by conditions ********************************** *************/
    if(initialString == "checkOut"){
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
                $scope.updateSumation();
                $scope.watcher.selected=true;
            }, 0);
        },
        true
    );

    $scope.$watch('newItem.isopen',
        function(newValue, oldValue) {
            if(newValue == oldValue || !$scope.watcher.isopen ) return;
            if(!newValue){  // when closeing
                $scope.updateItemCommentSelection($scope.newItem);
                $scope.updateItemPrice($scope.newItem);
            }
        },
        true
    );

    // item, category
    $scope.changeCategory = function(item,category){
        item.itemCategory = category;
        item.paymentRequest = "";
    }


    $scope.addItem = function(newItem){
        // clean transition effect Ids
        delete $scope.newAddedIds;
        $scope.newAddedIds = [];
        // insert to addedItems list
        addNewItemsInList(newItem,$scope.newAddedIds);
        delete $scope.newItem;
        $scope.newItem = createNewAddedItem();
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
        $scope.viewClick=target;
    }

    $scope.backward = function(page){
        $scope.viewClick = page;
    }

    $scope.confirm = function(page){
        $scope.viewClick = page;
        // master ID if is connected room
        if(ori_Mastr_RM_TRAN_ID != null && ori_Mastr_RM_TRAN_ID!=""){
            $scope.BookCommonInfo.Master.mastr_RM_TRAN_ID = ori_Mastr_RM_TRAN_ID;
            // if master room has been checked out ,but still some room left
            if($scope.RM_TRAN_ID_SelectedList[ori_Mastr_RM_TRAN_ID]){
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

        // Master's paymentRequested has already bound to summation
        $scope.BookCommonInfo.Master.payment.payByMethods[0].payAmount =  $scope.BookCommonInfo.Master.payment.paymentRequest;
        $scope.BookCommonInfo.Master.payment.payInDue = 0;
        $scope.BookCommonInfo.Master.payment.payByMethods[0].payMethod = "现金";
    }
    /************** ********************************** submit  ********************************** *************/

    $scope.submit = function(){
        $scope.submitLoading = true;
        var today = new Date();
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
            }
        }
        var addAcct = {RoomAcct:[],PenaltyAcct:[],RoomStore:[]};
        for(var i = 0; i < $scope.addedItems.length; i++){
            var ac = $scope.addedItems[i];
            switch(ac.itemCategory){
                case 'merchant':
                    if(addAcct.RoomStore)
                    addAcct.RoomStore.push({
                                            RoomStoreTran:   {TKN_RM_TRAN_ID:$scope.BookCommonInfo.Master.mastr_RM_TRAN_ID,
                                                              RM_TRAN_ID:ac.RM_TRAN_ID,
                                                              RM_ID: $scope.TRAN2RMmapping[ac.RM_TRAN_ID],
                                                              FILLED: (!ac.transfer)?'T':'F'},
                                            StoreTransaction:{STR_TRAN_TSTAMP:ac.TSTMP,
                                                              STR_PAY_AMNT:ac.PAY_AMNT,
                                                              RMRK:ac.RMRK,
                                                              UID:null,
                                                              EMP_ID:null},
                                            ProductInTran:   {PROD_ID: ac.PROD_ID,
                                                              PROD_QUAN: ac.PROD_QUAN}
                                           });
                    break;
                case 'penalty':
                    addAcct.PenaltyAcct.push({RM_TRAN_ID:ac.RM_TRAN_ID,TKN_RM_TRAN_ID:$scope.BookCommonInfo.Master.mastr_RM_TRAN_ID,
                                           BRK_EQPMT_RMRK: ac.RMRK, PNLTY_PAY_AMNT: ac.PAY_AMNT,PAYER_NM:ac.PAYER,PAYER_PHONE:ac.PAYER_PHONE,
                                           BILL_TSTMP:ac.TSTMP,FILLED: (!ac.transfer)?'T':'F' });
                    break;
                case 'exceedPay':
                    addAcct.RoomAcct.push({RM_TRAN_ID:ac.RM_TRAN_ID,TKN_RM_TRAN_ID:$scope.BookCommonInfo.Master.mastr_RM_TRAN_ID,
                                           RMRK: '超时房费', RM_PAY_AMNT: ac.RM_PAY_AMNT,BILL_TSTMP:ac.BILL_TSTMP,
                                           FILLED: (!ac.transfer)?'T':'F' });
                    break;
            }
        }
        var addDepoArray =[];
        for(var i = 0; i < $scope.BookCommonInfo.Master.payment.payByMethods.length; i++){
            var me = $scope.BookCommonInfo.Master.payment.payByMethods[i];
            addDepoArray.push({RM_TRAN_ID:$scope.BookCommonInfo.Master.mastr_RM_TRAN_ID,DEPO_AMNT:me.payAmount,
                               PAY_METHOD:me.payMethod,DEPO_TSTMP:util.tstmpFormat(today),RMRK:"结算金额"});
        }
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

        newCheckOutFactory.checkOT(RoomArray,$scope.BookCommonInfo.Master,editAcct,addAcct,addDepoArray).success(function(data){
            $scope.submitLoading = false;
            show("办理成功!");
            $modalInstance.close("checked");
            util.closeCallback();

        });

    }
})



/************************                       singleRoom sub controller                      ***********************/
.controller('checkOutSelectController', function ($scope) {
    $scope.$watch('singleRoom.selected',
        function(newValue, oldValue) {
            if(newValue == oldValue || !$scope.watcher.selected ) return;
            $scope.$parent.RM_TRAN_ID_SelectedList[$scope.singleRoom.RM_TRAN_ID] = newValue;
            $scope.$parent.updateShowItemsByRooms();
            $scope.$parent.updateSumation();
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