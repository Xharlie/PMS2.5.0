/**
 * Created by Xharlie on 2/24/15.
 */
app.controller('addMemberModalController', function($scope, $http, $modalInstance,$timeout,initialString, customerFactory,member){

    /********************************************     utility     ***************************************************/

    var createNewPayByMethod = function(){
        var payByMethod =  {payAmount:"",payMethod:""};
        return payByMethod;
    }

    var createNewPayment = function(){
        var Payment =  {paymentRequest:"", paymentType:"入会", payInDue:"",payByMethods:[createNewPayByMethod()]};
        return Payment;
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

    /************** ********************************** Initial functions ******************************************* *************/
    var initAddMember = function(){
        $scope.BookCommonInfo.MEM_ID = "待定";
        $scope.BookCommonInfo.POINTS = 0;
        customerFactory.showMemberType().success(function(data){
            $scope.MemberTPs = data;
            $scope.BookCommonInfo.MEM_TP = $scope.MemberTPs[0].MEM_TP;
            delete $scope.BookCommonInfo.MEM_ID;  // insert has auto assign
        });
    }

    var initGetMemberInfo = function(member){
        customerFactory.showMemberType().success(function(data){
            $scope.MemberTPs = data;
            $scope.BookCommonInfo.MEM_TP = member.MEM_TP;
            $scope.BookCommonInfo.MEM_ID = member.MEM_ID;
            $scope.BookCommonInfo.POINTS = member.POINTS;
            $scope.BookCommonInfo.MEM_NM = member.MEM_NM;
            $scope.BookCommonInfo.SSN_TP = member.SSN_TP;
            $scope.BookCommonInfo.SSN = member.SSN;
            $scope.BookCommonInfo.PHONE = member.PHONE;
            $scope.BookCommonInfo.RMRK = member.RMRK;
            for(var i =0; i < $scope.MemberTPs.length; i++){
                if($scope.MemberTPs[i].MEM_TP == member.MEM_TP){
                    member.MEM_IN_FEE = $scope.MemberTPs[i].MEM_IN_FEE;
                    break;
                }
            }
        });
    }
    /************** ********************************** Common initial setting  ******************************************* ********/
    $scope.viewClick = 'Info';
    $scope.initialstring = initialString;
    $scope.BookCommonInfo = {
        MEM_TP:"",MEM_ID:"",
        POINTS:"",MEM_NM:"",
        SSN_TP:"",SSN:"",
        PHONE:"",RMRK:""
    };
    $scope.memPay = {payment : createNewPayment()};
    $scope.MemberTPs = "";
//    $scope.watcher = {member:true, selected:true, selectAll:true, isopen:true, addedItems:true,exceedPay:true};

    /************** ********************************** Initialize by conditions ********************************** *************/
    if(initialString == "addMember"){
        initAddMember();
    }else{
        initGetMemberInfo(member);
    }

    /************** ********************************** page change  ********************************** *************/
    $scope.confirm = function(target){
        if(initialString == "addMember"){
            for(var i =0; i < $scope.MemberTPs.length; i++){
                if($scope.MemberTPs[i].MEM_TP == $scope.BookCommonInfo.MEM_TP){
                    $scope.memPay.payment.paymentRequest = $scope.MemberTPs[i].MEM_IN_FEE;
                    $scope.memPay.payment.payByMethods[0].payAmount =  $scope.memPay.payment.paymentRequest;
                    $scope.memPay.payment.payInDue = 0;
                    $scope.memPay.payment.payByMethods[0].payMethod = "现金";
                    break;
                }
            }
            $scope.viewClick=target;
        }else if(initialString == "levelAdjustment"){
            var diff = 0;
            for(var i =0; i < $scope.MemberTPs.length; i++){
                if($scope.MemberTPs[i].MEM_TP == $scope.BookCommonInfo.MEM_TP){         // find the info of membertype that user choosing
                    if(member.MEM_IN_FEE != $scope.MemberTPs[i].MEM_IN_FEE){            // if the new type's in fee is different with old in fee
                        diff = $scope.MemberTPs[i].MEM_IN_FEE - member.MEM_IN_FEE;
                        $scope.memPay.payment.paymentRequest = diff;
                        $scope.memPay.payment.payByMethods[0].payAmount =  $scope.memPay.payment.paymentRequest;
                        $scope.memPay.payment.payInDue = 0;
                        $scope.memPay.payment.payByMethods[0].payMethod = "现金";
                        $scope.memPay.payment.paymentType = "等级修改"+member.MEM_TP+"到"+$scope.BookCommonInfo.MEM_TP;
                    }
                    break;
                }
            }
            if(diff != 0){
                $scope.viewClick = "Pay";
            }else{
                $scope.editSubmit(false);
            }
        }else{
            $scope.editSubmit(false);
        }

    }

    $scope.backward = function(target){
        $scope.viewClick=target;
    }

    /************** ********************************** pay  ********************************** *************/

    $scope.addNewPayByMethod = function(singleRoom){
        singleRoom.payment.payByMethods.push(createNewPayByMethod());
    }
    /************** ********************************** submit  ********************************** *************/




    $scope.submit = function(){
        if (testFail()) return;
        $scope.submitLoading = true;
        if($scope.memPay.payment.paymentRequest == 0 ||$scope.memPay.payment.paymentRequest =="" ){
            $scope.memPay.payment = null;
        }
        $scope.BookCommonInfo["IN_TSTMP"] = util.tstmpFormat(new Date());
        customerFactory.addMemberSubmit($scope.BookCommonInfo, $scope.memPay.payment).success(function(data){
            $scope.submitLoading = false;
            $modalInstance.close("checked");
            util.closeCallback();
        });
    }

    $scope.editSubmit = function(moneyInvolved){
        if (testFail()) return;
        $scope.submitLoading = true;
        if(!moneyInvolved) $scope.memPay.payment = null;
        customerFactory.editMemberSubmit($scope.BookCommonInfo, $scope.memPay.payment).success(function(data){
            $scope.submitLoading = false;
            $modalInstance.close("checked");
            util.closeCallback();
        });
    }


    /*********************************************/

})
/************************                       singlePay sub controller                      ***********************/
.controller('memSinglePayCtrl', function ($scope) {
    $scope.$watch('singlePay.payAmount',
        function(newValue, oldValue) {
            $scope.$parent.updatePayInDue($scope.$parent.memPay);
        },
        true
    )
});
