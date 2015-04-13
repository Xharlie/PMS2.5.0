/**
 * Created by Xharlie on 3/12/15.
 */

app.controller('modifyAcctModalController', function($scope, $http, $modalInstance,$timeout,initialString, accountingFactory,acct){

    /********************************************     utility     ***************************************************/


    var testFail = function(){
        return false;
    }

    $scope.labelMapping = function(type){
        switch(type){
            case '-1':
                return '减少';
            case '1':
                return '增加';

        }

    }
    /************** ********************************** Initial functions ******************************************* *************/

    var initModifyAcct = function(){
        if(acct.PAY) $scope.modifyAcct.payMethod='现金';
        $scope.oriAmount = util.Limit( (acct.SUBMIT_PAY_AMNT =='')? acct.CONSUME_PAY_AMNT : acct.SUBMIT_PAY_AMNT);
        $scope.oriAmountShow = util.Limit( Math.abs((acct.SUBMIT_PAY_AMNT =='')? acct.CONSUME_PAY_AMNT : acct.SUBMIT_PAY_AMNT));
    }

    /************** ********************************** Common initial setting  ******************************************* ********/

    $scope.oriAcct = acct;
    $scope.oriAmountShow = 0;
    $scope.modifyAcct = {changeType:'-1',payAmount:0,payMethod:'',RMRK:""};

    /************** ********************************** Initialize by conditions ********************************** *************/
    if(initialString == "modifyAcct"){
        initModifyAcct();
    }

    /************** ********************************** page change  ********************************** *************/


    /************** ********************************** pay  ********************************** *************/


    /************** ********************************** submit  ********************************** *************/


    $scope.submit = function(){
        if (testFail()) return;

        var Amount = util.Limit( Number($scope.modifyAcct.changeType)*
                                 Number($scope.modifyAcct.payAmount)*
                                 ((Number($scope.oriAmount)>0)?1:-1)
                                );

        $scope.submitInfo={
            TABLE : $scope.oriAcct.TABLE,
            ORGN_ACCT_ID : $scope.oriAcct.ACCT_ID,
            RM_TRAN_ID : $scope.oriAcct.RM_TRAN_ID,
            TKN_RM_TRAN_ID : $scope.oriAcct.TKN_RM_TRAN_ID,
            Amount : Amount,
            RMRK : $scope.modifyAcct.RMRK,
            payMethod : ($scope.modifyAcct.payMethod=="")?null:$scope.modifyAcct.payMethod,
            RM_ID : $scope.oriAcct.RM_ID,
            FILLED : $scope.oriAcct.FILLED
        };

        accountingFactory.submitModifyAcct($scope.submitInfo).success(function(){
            $modalInstance.close("checked");
            util.closeCallback();
        });
    }

    $scope.close = function(moneyInvolved){
        if (testFail()) return;
        if(!moneyInvolved) $scope.memPay.payment = null;
        customerFactory.editMemberSubmit($scope.BookCommonInfo, $scope.memPay.payment).success(function(data){
//            show(data);
            $modalInstance.close("checked");
            util.closeCallback();
        });
    }


    /*********************************************/

})
