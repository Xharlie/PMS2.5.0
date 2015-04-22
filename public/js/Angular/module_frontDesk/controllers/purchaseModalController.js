/**
 * Created by Xharlie on 3/7/15.
 */
app.controller('purchaseModalController', function($scope, $http,$modalInstance, merchandiseFactory,
                                                   $timeout, paymentRequest,buyer_RM_TRAN_ID,owner ){

    /********************************************     utility     ***************************************************/
    var createNewPayByMethod = function(){
        var payByMethod =  {payAmount:"",payMethod:"",rmIdClass:null,RM_TRAN_ID:null,TKN_RM_TRAN_ID:null,roomId:null};
        return payByMethod;
    }

    var createNewPayment = function(){
        var Payment =  {paymentRequest:"", paymentType:"住房押金", payInDue:"",payByMethods:[createNewPayByMethod()]};
        return Payment;
    }

    $scope.addNewPayByMethod = function(singleRoom){
        singleRoom.payment.payByMethods.push(createNewPayByMethod());
    }

    $scope.updatePayInDue = function(singleRoom){
        var totalDue= parseFloat(singleRoom.payment.paymentRequest);
        for (var i=0; i<singleRoom.payment.payByMethods.length; i++){
            totalDue = totalDue - singleRoom.payment.payByMethods[i].payAmount;
        }
        singleRoom.payment.payInDue = util.Limit(parseFloat(totalDue));
        singleRoom.payment.payInDue = (isNaN(singleRoom.payment.payInDue))? 0.00: singleRoom.payment.payInDue;
    };

    $scope.roomIdHit = function(singlePay, room){
        singlePay.rmIdClass='greenNum';
        singlePay.RM_TRAN_ID  = room.RM_TRAN_ID;
        singlePay.TKN_RM_TRAN_ID = (room.CONN_RM_TRAN_ID == null)?room.RM_TRAN_ID:room.CONN_RM_TRAN_ID;
    }

    $scope.roomIdClear = function(singlePay){
            singlePay.rmIdClass='redNum';
            singlePay.RM_TRAN_ID = null;
            singlePay.TKN_RM_TRAN_ID = null;
            for(var i =0; i< $scope.rooms.length; i++){
                if(singlePay.roomId == $scope.rooms[i].RM_ID){
                    if($scope.rooms[i].RM_CONDITION == "有人"){
                        $scope.roomIdHit(singlePay,$scope.rooms[i]);
                    }
                    break;
                }
            }
    }

    var testFail = function(){
        return false;
    };
    /********************************************     init     ***************************************************/
    var commonInit = function(){
        $scope.BookCommonInfo.Master.payment.paymentRequest = paymentRequest;
        $scope.BookCommonInfo.Master.payment.payByMethods[0].payAmount = paymentRequest;
        $scope.BookCommonInfo.Master.payment.payInDue = 0;
        $scope.BookCommonInfo.Master.payment.base = paymentRequest;
        merchandiseFactory.merchanRoomShow().success(function(data){
            $scope.rooms = data;
        });
    }


    var allMethodsInit = function(){
        $scope.BookCommonInfo.Methods = ['现金','银行卡', '信用卡', '房间挂账'];
        $scope.BookCommonInfo.Master.payment.payByMethods[0].payMethod = "现金";
    }

    var roomMethodInit = function(){
        $scope.BookCommonInfo.Methods = ['房间挂账'];
        $scope.BookCommonInfo.Master.payment.payByMethods[0].payMethod = "房间挂账";
    }

    /********************************************     common variables     ***************************************************/
    $scope.viewClick ='Pay';
    $scope.Connected = true;
    $scope.BookCommonInfo={Master:{payment:createNewPayment()},Methods:[]};
    commonInit();
    if(buyer_RM_TRAN_ID == null){
        allMethodsInit();
    }else{
        roomMethodInit();
    }

    /********************************************     submission    ***************************************************/

    $scope.submit = function(){
        if(testFail()) return;
        $scope.submitLoading = true;
        var today = new Date();
        var pay = $scope.BookCommonInfo.Master.payment;
        var RoomStoreTranArray = null;
        var ProductInTran = [];
        var StoreTransactionArray = {
            "STR_TRAN_TSTAMP" : util.tstmpFormat(today),
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

/************************                       singleRoomPay sub controller                      ***********************/
.controller('purchaseSingleRoomPayCtrl', function ($scope) {
    $scope.$watch('singleRoom.payment.paymentRequest',
        function(newValue, oldValue) {
            $scope.$parent.updatePayInDue($scope.singleRoom);
        },
        true
    );
})

/************************                       singlePay sub controller                      ***********************/
.controller('purchaseSinglePayCtrl', function ($scope) {
    $scope.$watch('singlePay.payAmount',
        function(newValue, oldValue) {
            $scope.$parent.$parent.updatePayInDue($scope.$parent.singleRoom);
        },
        true
    );
})

/************************                       single Master Pay sub controller                      ***********************/
.controller('purchaseSingleMasterPayCtrl', function ($scope) {
    $scope.$watch('singlePay.payAmount',
        function(newValue, oldValue) {
            $scope.$parent.updatePayInDue($scope.$parent.BookCommonInfo.Master);
        },
        true
    );
});