/**
 * Created by Xharlie on 2/6/15.
 */

app.controller('checkOutModalController', function($scope, $http, newCheckOutFactory, $modalInstance,merchandiseFactory,
                                                   $timeout, RM_IDFortheRoom,connRM_TRAN_IDs,initialString){
    /********************************************     utility     ***************************************************/
    var today = util.toLocal(new Date());
    var tomorrow = new Date(today.getTime()+86400000);

    var createNewRMProduct = function(){
        var newProduct={"newRProductNM":"", "newRProductQUAN":"", "newRProductPAY":"","PROD_ID":""};
        return newProduct;
    }
    var createNewFee = function(){
        var newFee = {"RMRK":"","PAY_AMNT":"", "PAYER":"","PAYER_PHONE":""};
        return newFee;
    }
    var initNewRoomAcct = function(room){
        room['realMoneyOut'] = 0;
        room["DEPO_SUM"] = 0;
        room["Acct_SUM"] = 0;
        room["Store_SUM"] = 0;
        room["newRMProduct"] = {"newRProductNM":"","newRProductQUAN":"","newRProductPAY":"","PROD_ID":""};
        room["RoomConsume"] = [];
        room["newConsumeSum"] = 0;
        room["newFee"] = {"RMRK":"","PAY_AMNT":"", "PAYER":"","PAYER_PHONE":""};
        room["penalty"] = [];
        room["newFeeSum"] = 0.00;
        room["DAYS_STAY"] = Math.round( (new Date(room["CHECK_OT_DT"]).getTime()
            -new Date(room["CHECK_IN_DT"]).getTime())/86400000);
    }
//
//    var adjustSelected = function(){
//        var sumation =0;
//        for (var i = 0; i < $scope.BookRoom.length; i++){
//            sumation = sumation + $scope.BookRoom["Sumation"];
//            $scope.singleRoom['AcctDepo'].push();
//        }
//        $scope.singleRoom["sumation"]=sumation;
//    }

    $scope.updateSumation=function(){
        var sumation=0;
        for (var i = 0; i < $scope.BookRoom.length; i++){
            if($scope.BookRoom[i].selected){
                sumation = sumation + $scope.BookRoom[i]["Sumation"];
            }
        }
        $scope.BookCommonInfo.payment.paymentRequest = sumation;
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
        var newAddedItem =  {RM_ID:RM_IDFortheRoom, itemCategory:"merchant", paymentRequest:"",RMRK:"",
                             penalty:createItemPenalty(), prodInfo:createProdInfo(),showup:"未选",isopen:false};
        return newAddedItem;
    }

    var createItemPenalty = function(){
        var newtemDetail = {"PENALTY_ITEM":"",
                            "PAY_AMNT": "",
                            "PAYER": "",
                            "PAYER_PHONE": ""};
        return newtemDetail;
    }

    var createProdInfo = function(){
            return JSON.parse(JSON.stringify($scope.prodInfo));
    }

    /************** ********************************** Initial functions ******************************************* *************/
    var initGetAllAcct= function(){
        newCheckOutFactory.getAllInfo(connRM_TRAN_IDs).success(function(data){
            $scope.BookRoom = data;
            for (var i = 0; i < $scope.BookRoom.length; i++){
                initNewRoomAcct($scope.BookRoom[i]);
                var diff = new Date(Number(today) - Number(new Date(util.tstmpFormat(today)+" 13:00:00"))+18000000);
                if (today.getHours()>13){
                    $scope.BookRoom[i]["extraTime"] = {"TSTMP":util.tstmpFormat(today),"RM_TRAN_ID":$scope.BookRoom[i]["RM_TRAN_ID"],
                        "extrFine":"","timeExtra":util.tstmpFormat(diff)};
                }
                for (var j = 0; j < $scope.BookRoom[i]['AcctDepo'].length; j++){
                    $scope.BookRoom[i]["DEPO_SUM"] += parseFloat($scope.BookRoom[i]['AcctDepo'][j]["DEPO_AMNT"]);
                }
                for (var j = 0; j < $scope.BookRoom[i]['AcctPay'].length; j++){
                    $scope.BookRoom[i]["Acct_SUM"] += parseFloat($scope.BookRoom[i]['AcctPay'][j]["RM_PAY_AMNT"]);
                }
                for (var j = 0; j < $scope.BookRoom[i]['AcctStore'].length; j++){
                    if ($scope.BookRoom[i]['AcctStore'][j]["PROD_PRICE"] == undefined){
                        $scope.BookRoom[i]["Store_SUM"] += parseFloat($scope.BookRoom[i]['AcctStore'][j]["STR_PAY_AMNT"]);
                        continue;
                    }
                    $scope.BookRoom[i]["Store_SUM"] += (parseFloat($scope.BookRoom[i]['AcctStore'][j]["PROD_PRICE"])
                        *parseFloat($scope.BookRoom[i]['AcctStore'][j]["PROD_QUAN"]));
                }
                $scope.BookRoom[i]["Sumation"]= util.Limit($scope.BookRoom[i]["DEPO_SUM"] -
                    $scope.BookRoom[i]["Acct_SUM"] -
                    $scope.BookRoom[i]["Store_SUM"] -
                    $scope.BookRoom[i]["newConsumeSum"] -
                    $scope.BookRoom[i]["newFeeSum"]);

                $scope.BookRoom[i]["selected"] = ($scope.BookRoom[i].RM_ID==RM_IDFortheRoom);
                $scope.BookRoom[i]["DEPO_SUM"]["transfer"] = false;
                $scope.BookRoom[i]["Acct_SUM"]["transfer"] = false;
                $scope.BookRoom[i]["Store_SUM"]["transfer"] = false;
                $scope.BookRoom[i]["newConsumeSum"]["transfer"] = false;
                $scope.BookRoom[i]["newFeeSum"]["transfer"] = false;
            }
            $scope.updateSumation();
        });
    }

    var initGetRoomProduct = function(){
        merchandiseFactory.productShow().success(function(data){
            $scope.prodInfo = data;
            for(var i=0; i< data.length; i++){
                $scope.prodInfo[i].PROD_QUAN = 0;
                $scope.prodInfo[i].PROD_SELECTED=false;
            }
            $scope.addedItems=[createNewAddedItem()];
        });
    };
    /************** ********************************** Common initial setting  ******************************************* ********/
    $scope.viewClick='Info';
    $scope.BookCommonInfo = {selectAll:false,payment:createNewPayment()};
    $scope.watcher = {member:true, selected:true, selectAll:true,isopen:true};

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
            $scope.watcher.selected=false;
                for (var i = 0; i < $scope.BookRoom.length; i++){
                    $scope.BookRoom[i].selected = newValue ;
                }
            $timeout(function() {
                $scope.updateSumation();
                $scope.watcher.selected=true;
            }, 0);
        },
        true
    );
    // item, category
    $scope.changeCategory = function(item,category){
        item.itemCategory = category;
        item.paymentRequest = "";
    }

    $scope.updateItem = function(item){
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
        $timeout(function() {
            item.showup = showUpString;
        }, 0);
    }



})
/************************                       singleRoom sub controller                      ***********************/
.controller('checkOutSelectController', function ($scope) {
    $scope.$watch('singleRoom.selected',
        function(newValue, oldValue) {
            if(newValue == oldValue || !$scope.watcher.selected ) return;
            $scope.$parent.updateSumation();
        },
        true
    );
}).controller('itemController', function ($scope) {
    $scope.$watch('item.isopen',
        function(newValue, oldValue) {
            if(newValue == oldValue || !$scope.watcher.isopen ) return;
            if(!newValue){
                $scope.$parent.updateItem($scope.item);
            }
        },
        true
    );
});