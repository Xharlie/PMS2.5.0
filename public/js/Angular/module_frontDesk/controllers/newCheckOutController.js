/**
 * Created by Xharlie on 8/6/14.
 */
appCheckOut.controller('newCheckOutController', function($scope, $http, newCheckOutFactory){
    $scope.BookRoom = [];
    $scope.MASTER_RM_ID = "";
    $scope.currentDate = new Date();
    var pathArray = window.location.href.split("/");
    $scope.RoomNumArray = pathArray.slice(pathArray.indexOf('newCheckOut')+1);
    if ($scope.RoomNumArray[$scope.RoomNumArray.length-1][0] == 'M'){
        $scope.MASTER_RM_ID = $scope.RoomNumArray[$scope.RoomNumArray.length-1].slice(1);
        $scope.RoomNumArray = $scope.RoomNumArray.slice(0,$scope.RoomNumArray.length-1);
    }

    newCheckOutFactory.getAllInfo($scope.RoomNumArray).success(function(data){
        $scope.test = JSON.stringify(data);
        $scope.BookRoom = data;
        for (var i = 0; i < $scope.BookRoom.length; i++){
            $scope.BookRoom[i]["DEPO_SUM"] = 0;
            $scope.BookRoom[i]["Acct_SUM"] = 0;
            $scope.BookRoom[i]["Store_SUM"] = 0;
            $scope.BookRoom[i]["newRMProduct"] = {"newRProductNM":"",
                                                  "newRProductQUAN":"",
                                                  "newRProductPAY":"",
                                                  "newRProductPAYmethod":""};
            $scope.BookRoom[i]["RoomConsume"] = [];
            $scope.BookRoom[i]["newConsumeSum"] = 0;
            $scope.BookRoom[i]["newFee"] = {"RMRK":"","PAY_METHOD":"","PAY_AMNT":""};
            $scope.BookRoom[i]["penalty"] = [];
            $scope.BookRoom[i]["newFeeSum"] = 0;
            $scope.BookRoom[i]["DAYS_STAY"] = Math.round( (new Date($scope.BookRoom[i]["CHECK_OT_DT"]).getTime()
                                             -new Date($scope.BookRoom[i]["CHECK_IN_DT"]).getTime())/86400000);


            for (var j = 0; j < $scope.BookRoom[i]['AcctDepo'].length; j++){
                $scope.BookRoom[i]["DEPO_SUM"] += parseFloat($scope.BookRoom[i]['AcctDepo'][j]["DEPO_AMNT"]);

            }
            for (var j = 0; j < $scope.BookRoom[i]['AcctPay'].length; j++){
                $scope.BookRoom[i]["Acct_SUM"] += parseFloat($scope.BookRoom[i]['AcctPay'][j]["RM_PAY_AMNT"]);
            }
            for (var j = 0; j < $scope.BookRoom[i]['AcctStore'].length; j++){
                $scope.BookRoom[i]["Store_SUM"] += (parseFloat($scope.BookRoom[i]['AcctStore'][j]["PROD_PRICE"])
                                                    *parseFloat($scope.BookRoom[i]['AcctStore'][j]["PROD_QUAN"]));
            }

            $scope.BookRoom[i]["Sumation"]= parseFloat($scope.BookRoom[i]["DEPO_SUM"] -
                $scope.BookRoom[i]["Acct_SUM"] -
                $scope.BookRoom[i]["Store_SUM"] -
                $scope.BookRoom[i]["newConsumeSum"] -
                $scope.BookRoom[i]["newFeeSum"]).toFixed(2) ;

        }

    });
    newCheckOutFactory.getProductNM().success(function(data){
        $scope.prodNM = data;
  //      alert(data);
    });

    for (var i = 0; i < $scope.RoomNumArray.length; i++){

    }

    $scope.addNewRMProduct = function(singleRoom){
        if(!isNaN(singleRoom.newRMProduct.newRProductQUAN) && parseFloat(singleRoom.newRMProduct.newRProductQUAN) % 1 === 0
            && parseFloat(singleRoom.newRMProduct.newRProductQUAN) > 0 && singleRoom.newRMProduct.newRProductNM.trim() != "" ){
            singleRoom.RoomConsume.push({
                "PROD_NM": singleRoom.newRMProduct.newRProductNM.trim(),
                "PROD_QUAN":singleRoom.newRMProduct.newRProductQUAN,
                "STR_PAY_AMNT":singleRoom.newRMProduct.newRProductPAY,
                "STR_TRAN_TSTAMP":"新添加" ,
                "RM_TRAN_ID":singleRoom.RM_TRAN_ID,
                "STR_PAY_METHOD":singleRoom.newRMProduct.newRProductPAYmethod}
                );
            singleRoom.newConsumeSum += parseFloat(singleRoom.newRMProduct.newRProductPAY);
            $scope.calculateSum(singleRoom);
            singleRoom.newRMProduct["newRProductNM"]="";
            singleRoom.newRMProduct["newRProductQUAN"]="";
            singleRoom.newRMProduct["newRProductPAY"]="";
        }
    }

    $scope.deleteNewRMProduct = function(singleRoom,Rstore){
            singleRoom.RoomConsume.splice(singleRoom.RoomConsume.indexOf(Rstore),1);
            singleRoom.newConsumeSum -= parseFloat(Rstore.STR_PAY_AMNT);
            $scope.calculateSum(singleRoom);
    }

    $scope.getPrice = function(singleRoom){
        if(!isNaN(singleRoom.newRMProduct.newRProductQUAN) && parseFloat(singleRoom.newRMProduct.newRProductQUAN) % 1 === 0
            && parseFloat(singleRoom.newRMProduct.newRProductQUAN) > 0 && singleRoom.newRMProduct.newRProductNM.trim() != "" ){
            if($scope.prodNM.indexOf(singleRoom.newRMProduct.newRProductNM.trim())>-1){
                newCheckOutFactory.getProductPrice(singleRoom.newRMProduct.newRProductNM.trim()).success(function(data){
                    var price = parseFloat(data[0]["PROD_PRICE"]);
                    singleRoom.newRMProduct.newRProductPAY = price * parseFloat(singleRoom.newRMProduct.newRProductQUAN)+"元";
                    return;
                });
            }
        }
        singleRoom.newRMProduct.newRProductPAY = "";
    }

    $scope.addNewFee = function(singleRoom){
        if( singleRoom.newFee.RMRK!= "" && singleRoom.newFee.PAY_METHOD!= "" &&
            singleRoom.newFee.PAY_METHOD != "" ){
            singleRoom.penalty.push({
                "RM_TRAN_ID": singleRoom.RM_TRAN_ID,
                "RMRK":singleRoom.newFee.RMRK,
                "PAY_METHOD":singleRoom.newFee.PAY_METHOD,
                "PAY_AMNT":singleRoom.newFee.PAY_AMNT
            });
            singleRoom.newFeeSum += parseFloat(singleRoom.newFee.PAY_AMNT);
            $scope.calculateSum(singleRoom);
            singleRoom.newFee= {"RMRK":"","PAY_METHOD":"","PAY_AMNT":""};
        }
    }

    $scope.deleteFee = function(singleRoom,fee){
        singleRoom.penalty.splice(singleRoom.penalty.indexOf(fee),1);
        singleRoom.newFeeSum -= parseFloat(fee.PAY_AMNT);
        $scope.calculateSum(singleRoom);
    }

    $scope.calculateSum = function(singleRoom){
        singleRoom.Sumation= parseFloat(singleRoom.DEPO_SUM -
                                singleRoom.Acct_SUM -
                                singleRoom.Store_SUM -
                                singleRoom.newConsumeSum -
                                singleRoom.newFeeSum).toFixed(2) ;
    }
});
