/**
 * Created by Xharlie on 3/7/15.
 */
app.controller('purchaseModalController', function($scope, $http,$modalInstance, merchandiseFactory, paymentFactory,
                                                   focusInSideFactory,$timeout, paymentRequest,buyer_RM_TRAN_ID,owner ){

    /********************************************     validation     ***************************************************/
    $scope.hasError = function(btnPass){
        if(eval("$scope."+btnPass)==null) eval("$scope."+btnPass+"=0");
        eval("$scope."+btnPass+"++");
    }
    $scope.noError = function(btnPass){
        eval("$scope."+btnPass+"--");
    }
    $scope.payError = 0;
    /********************************************     utility     ***************************************************/


    var testFail = function(){
        return false;
    };

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };

    /********************************************     init     ***************************************************/
    var commonInit = function(){
        merchandiseFactory.merchanRoomShow().success(function(data){
            $scope.BookCommonInfo={Master:{payment:paymentFactory.createNewPayment('商品付款'),check:true}};
            $scope.BookRoomMaster = [$scope.BookCommonInfo.Master];
            $scope.BookCommonInfo.Master.payment.paymentRequest = paymentRequest;
            $scope.BookCommonInfo.Master.payment.payByMethods[0].payAmount = paymentRequest;
            $scope.BookCommonInfo.Master.payment.payInDue = 0;
            $scope.BookCommonInfo.Master.payment.base = paymentRequest;
            $scope.rooms = data;
            if(buyer_RM_TRAN_ID == null){
                allMethodsInit();
            }else{
                roomMethodInit();
            }
            $scope.viewClick ='Pay';
        });
    }


    var allMethodsInit = function(){
        $scope.payMethodOptions=paymentFactory.dumbPurchasePayMethodOptions();
        $scope.BookCommonInfo.Master.payment.payByMethods[0].payMethod = "现金";
    }

    var roomMethodInit = function(){
        $scope.payMethodOptions=paymentFactory.roomPurchasePayMethodOptions();
        $scope.BookCommonInfo.Master.payment.payByMethods[0].payMethod = "房间挂账";
        for(var i=0; i<$scope.rooms.length; i++){
            if(buyer_RM_TRAN_ID == $scope.rooms[i].RM_TRAN_ID){
                $scope.BookCommonInfo.Master.payment.payByMethods[0].RM_TRAN_ID  = $scope.rooms[i].RM_TRAN_ID;
                $scope.BookCommonInfo.Master.payment.payByMethods[0].roomId  = $scope.rooms[i].RM_ID;
                $scope.BookCommonInfo.Master.payment.payByMethods[0].TKN_RM_TRAN_ID
                    = ($scope.rooms[i].CONN_RM_TRAN_ID == null)?$scope.rooms[i].RM_TRAN_ID:$scope.rooms[i].CONN_RM_TRAN_ID;
            }
        }
    }

    /********************************************     common variables     ***************************************************/
    commonInit();
    focusInSideFactory.tabInit('wholeModal');
    $timeout(function(){
        focusInSideFactory.manual('wholeModal');
    },0)
    /********************************************     submission    ***************************************************/

    $scope.submit = function(){
        if(testFail()) return;
        $scope.submitLoading = true;
        var today = new Date();
        var pay = $scope.BookCommonInfo.Master.payment;
        var RoomStoreTranArray = null;
        var ProductInTran = [];
        var StoreTransactionArray = {
            "STR_TRAN_TSTMP" : util.tstmpFormat(today),
            "STR_PAY_METHOD" : pay.payByMethods[0].payMethod,
            "STR_PAY_AMNT" : pay.paymentRequest
        };

        if(StoreTransactionArray.STR_PAY_METHOD == "房间挂账"){
            RoomStoreTranArray = {
                "RM_TRAN_ID" : pay.payByMethods[0].RM_TRAN_ID,
                "RM_ID" : pay.payByMethods[0].roomId,
                "TKN_RM_TRAN_ID" : pay.payByMethods[0].TKN_RM_TRAN_ID,
                "FILLED" : "F"
            }
        }
        for(var i = 0; i< owner.length; i++){
            ProductInTran.push({
                "PROD_ID" : owner[i]["PROD_ID"],
                "PROD_QUAN" :  owner[i]["AMOUNT"]
            });
        }
        merchandiseFactory.buySubmit(StoreTransactionArray,RoomStoreTranArray,ProductInTran).success(function(data){
            show("办理成功!");
            $scope.submitLoading = false;
            $modalInstance.close("checked");
            util.closeCallback();
        });
    };

})
