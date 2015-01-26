/**
 * Created by Xharlie on 9/3/14.
 */
appModify.controller('newModifyWindowController', function($scope, $http, newModifyWindowFactory){
    var pathArray = window.location.href.split("/");
    $scope.InfoArray = pathArray.slice(pathArray.indexOf('newModifyWindow')+1);
    newModifyWindowFactory.getTargetAcct($scope.InfoArray).success(function(data){
        $scope.oldTarget =data[0];
        $scope.oldTarget["PAY_AMNT"]=($scope.oldTarget["CONSUME_PAY_AMNT"] == ""
            || $scope.oldTarget["CONSUME_PAY_AMNT"]== undefined)? $scope.oldTarget["SUBMIT_PAY_AMNT"]:$scope.oldTarget["CONSUME_PAY_AMNT"];
        $scope.CLASS = reverse($scope.oldTarget["CLASS"]);
    });

    $scope.psCheck = function(){
        if ($scope.password == "pushu"){
            $scope.passwordStyle = {'border':'2px solid #bce8f1'};
        }else{
            $scope.passwordStyle = {'border':'2px solid red'};
        }
    };

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

    $scope.styleMarked = {};
    $scope.RMRK ="";
    $scope.Amount="";

    $scope.resetMarkedBorder = function(){
        if ($scope.styleMarked.border != undefined){
            $scope.styleMarked.border = "default";
            $scope.styleMarked={};
        }
    };

    $scope.checkInCheck = function(){
        $scope.err = function(){
                //alert(JSON.stringify($scope.BookRoom[i].AvailQuanFlag));
                if($scope.Amount == "" || isNaN($scope.Amount)  || $scope.Amount < 0 ){
                    $scope.amountStyle={border:"2px solid red"};
                    $scope.styleMarked = $scope.amountStyle;
                    return "请您正确输入金额"
                }else if(($scope.RMRK).trim()  ==""){
                    $scope.rmrkStyle={border:"2px solid red"};
                    $scope.styleMarked = $scope.rmrkStyle;
                    return "请您输入备注";
                }else if($scope.passwordStyle.border == undefined || $scope.passwordStyle.border != '2px solid #bce8f1'){
                    return "密码错误";
                }
                return "通过检测,请提交";
        }();
    }

    $scope.submit= function(){
        if ($scope.err!="通过检测,请提交"){
            return;
        }

        $scope.submitInfo={};
        $scope.submitInfo["PAYER_NM"]=$scope.oldTarget.PAYER_NM;
        $scope.submitInfo["PAYER_PHONE"]=$scope.oldTarget.PAYER_PHONE;
        $scope.submitInfo["CLASS"]=$scope.CLASS;
        $scope.submitInfo["ACCT_ID"]=$scope.oldTarget.ACCT_ID;
        $scope.submitInfo["RM_TRAN_ID"]=$scope.oldTarget.RM_TRAN_ID;
        $scope.submitInfo["Amount"]=$scope.Amount;
        $scope.submitInfo["RMRK"]=$scope.RMRK;
        $scope.submitInfo["payMethod"]=$scope.payMethod;
        $scope.submitInfo["RM_ID"]=$scope.oldTarget.RM_ID;

        if($scope.changePoNe == "减少"){
            $scope.submitInfo["Amount"]="-"+$scope.submitInfo["Amount"];
        }
        alert(JSON.stringify($scope.submitInfo));
        newModifyWindowFactory.submitModifyAcct($scope.submitInfo).success(function(){
            window.close();
        });
    };
});