/**
 * Created by charlie on 7/6/15.
 */
app.controller('paymentCtrl', function ($scope,paymentFactory) {

    /*************************    validation      ************************/

    $scope.hasError = function(btnPass){
        if(eval("$scope."+btnPass)==null) eval("$scope."+btnPass+"=0");
        eval("$scope."+btnPass+"++");
    }
    $scope.noError = function(btnPass){
        eval("$scope."+btnPass+"--");
    }
    /*************************    init      ************************/
    payInit();

    function payInit(){
        for(var i=0; i<$scope.BookRoom.length;i++){
            $scope.BookRoom[i].payment.paymentRequest = util.Limit($scope.BookRoom[i].payment.paymentRequest);
            for(var j=0; j<$scope.BookRoom[i].payment.payByMethods.length;j++){
                $scope.BookRoom[i].payment.payByMethods[i].payAmount = util.Limit($scope.BookRoom[i].payment.payByMethods[i].payAmount);
            }
        }
    }

    /*************************    utility      ************************/

    $scope.addNewPayByMethod = function(singleRoom){
        singleRoom.payment.payByMethods.push(paymentFactory.createNewPayByMethod());
    }

    $scope.updatePayInDue = function(singleRoom){
        var totalDue= parseFloat(singleRoom.payment.paymentRequest);
        for (var i=0; i<singleRoom.payment.payByMethods.length; i++){
            totalDue = totalDue - singleRoom.payment.payByMethods[i].payAmount;
        }
        singleRoom.payment.payInDue = util.Limit(parseFloat(totalDue));
        singleRoom.payment.payInDue = (isNaN(singleRoom.payment.payInDue))? 0.00: singleRoom.payment.payInDue;
    };
    /*************************    for purchasemodal only      ************************/
    $scope.roomIdHit = function(singlePay, room){
        singlePay.RM_TRAN_ID  = room.RM_TRAN_ID;
        singlePay.TKN_RM_TRAN_ID = (room.CONN_RM_TRAN_ID == null)?room.RM_TRAN_ID:room.CONN_RM_TRAN_ID;
    }
    $scope.roomIdClear = function(singlePay){
        singlePay.RM_TRAN_ID = null;
        singlePay.TKN_RM_TRAN_ID = null;
        for(var i =0; i< $scope.rooms.length; i++){
            if(singlePay.roomId == $scope.rooms[i].RM_ID && $scope.rooms[i].RM_CONDITION == "有人"){
                $scope.roomIdHit(singlePay,$scope.rooms[i]);
                return;
            };
        }
        singlePay.roomId = null;
    }
})
/************************                       singleRoomPay  controller                      ***********************/
.controller('singleRoomPayCtrl', function ($scope) {
    $scope.$watch('singleRoom.payment.paymentRequest',
        function(newValue, oldValue) {
            $scope.$parent.updatePayInDue($scope.singleRoom);
        },
        true
    );
})
/************************                       singlePay  controller                      ***********************/
    .controller('singlePayCtrl', function ($scope) {
        $scope.$watch('singlePay.payAmount',
            function(newValue, oldValue) {
                $scope.$parent.$parent.updatePayInDue($scope.$parent.singleRoom);
            },
            true
        );
        /************************                singlePay  for roomNum Error               ***********************/
        $scope.$watch('singlePay.payMethod',
            function(newValue, oldValue) {
                if(newValue == oldValue) return;
                if($scope.singlePay.roomNumError == 1) {
                    $scope.singlePay.roomNumError = 0;
                }
            },
            true
        );
        $scope.$watch('singlePay.roomNumError',
            function(newValue, oldValue) {
                if(newValue == oldValue) return;
                if(oldValue == 0 && newValue ==1) $scope.$parent.$parent.$parent.$parent.payError++;
                if(oldValue == 1 && newValue ==0) $scope.$parent.$parent.$parent.$parent.payError--;
            },
            true
        );
    });
