/**
 * Created by Xharlie on 8/6/14.
 */
appCheckOut.controller('newCheckOutController', function($scope, $http, newCheckOutFactory,$modal){
    $scope.BookRoom = [];
    $scope.MASTER_RM_ID = "";
    $scope.currentDate = new Date();
    var pathArray = window.location.href.split("/");
    $scope.RoomConsumePayMethod = ['现金','储蓄卡','信用卡'];
    $scope.PenaltyPayMethod = ['现金','储蓄卡','信用卡'];
    $scope.RoomNumArray = pathArray.slice(pathArray.indexOf('newCheckOut')+1);
    if ($scope.RoomNumArray[$scope.RoomNumArray.length-1][0] == 'M'){
        $scope.MASTER_RM_ID = $scope.RoomNumArray[$scope.RoomNumArray.length-1].slice(1);
        $scope.RoomNumArray = $scope.RoomNumArray.slice(0,$scope.RoomNumArray.length-1);
    }

    newCheckOutFactory.getAllInfo($scope.RoomNumArray).success(function(data){
        $scope.test = JSON.stringify(data);
        $scope.BookRoom = data;
        for (var i = 0; i < $scope.BookRoom.length; i++){
            $scope.BookRoom[i]['realMoneyOut'] = 0;
            $scope.BookRoom[i]["DEPO_SUM"] = 0;
            $scope.BookRoom[i]["Acct_SUM"] = 0;
            $scope.BookRoom[i]["Store_SUM"] = 0;
            $scope.BookRoom[i]["newRMProduct"] = {"newRProductNM":"",
                                                  "newRProductQUAN":"",
                                                  "newRProductPAY":"",
                                                  "newRProductPAYmethod":"",
                                                  "PROD_ID":""};
            $scope.BookRoom[i]["RoomConsume"] = [];
            $scope.BookRoom[i]["newConsumeSum"] = 0;
            $scope.BookRoom[i]["newFee"] = {"RMRK":"","PAY_METHOD":"","PAY_AMNT":"", "PAYER":"","PAYER_PHONE":""};
            $scope.BookRoom[i]["penalty"] = [];
            $scope.BookRoom[i]["newFeeSum"] = 0;
            $scope.BookRoom[i]["DAYS_STAY"] = Math.round( (new Date($scope.BookRoom[i]["CHECK_OT_DT"]).getTime()
                                             -new Date($scope.BookRoom[i]["CHECK_IN_DT"]).getTime())/86400000);

            var now = new Date();
            var diff = new Date(new Date($scope.dateFormat(now)+"T"+"13:00:00")-now);

            if (now.getHours()>13){
                $scope.BookRoom[i]["extraTime"] = {"TSTMP":$scope.dateTimeFormat(now),"RM_TRAN_ID":$scope.BookRoom[i]["RM_TRAN_ID"],
                    "extrFine":"","timeExtra":$scope.timeFormat(diff)};
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

            $scope.BookRoom[i]["Sumation"]= parseFloat($scope.BookRoom[i]["DEPO_SUM"] -
                $scope.BookRoom[i]["Acct_SUM"] -
                $scope.BookRoom[i]["Store_SUM"] -
                $scope.BookRoom[i]["newConsumeSum"] -
                $scope.BookRoom[i]["newFeeSum"]).toFixed(2);


      //      $scope.BookRoom[]
//            <td>{{singleRoom.extraTime.TSTMP}}</td>
//                <td>{{singleRoom.extraTime.RM_TRAN_ID}}</td>
//            <td>{{}}</td>
//            <td><input ng-model="singleRoom.extraTime.extr"
//            ng-init="singleRoom.extraTime.extr=singleRoom.AcctPay[singleRoom.AcctPay.length-1].RM_PAY_AMNT"/>元
//            </td>

        }

    });

    $scope.dateTimeFormat = function(date){
        var YYYY = date.getFullYear().toString();
        var MM = (date.getMonth()+1).toString();
        var DD  = date.getDate().toString();
        var hh = date.getHours().toString();
        var mm = date.getMinutes().toString();
        var ss = date.getSeconds().toString();
        return YYYY+"-" + (MM[1]?MM:"0"+MM[0])+"-" + (DD[1]?DD:"0"+DD[0])+" "+
            (hh[1]?hh:"0"+hh[0])+":"+(mm[1]?mm:"0"+mm[0])+":"+(ss[1]?ss:"0"+ss[0]);
    }

    $scope.dateFormat = function(date){
        var yyyy = date.getFullYear().toString();
        var mm = (date.getMonth()+1).toString();
        var dd  = date.getDate().toString();
        return yyyy+"-" + (mm[1]?mm:"0"+mm[0])+"-" + (dd[1]?dd:"0"+dd[0]);
    }

    $scope.timeFormat = function(time){
        var hh = time.getHours().toString();
        var mm = time.getMinutes().toString();
        var ss = time.getSeconds().toString();
        return (hh[1]?hh:"0"+hh[0])+":"+(mm[1]?mm:"0"+mm[0])+":"+(ss[1]?ss:"0"+ss[0]);
    }

    newCheckOutFactory.getProductNM().success(function(data){
        $scope.prodNM = data;
  //      alert(data);
    });

    for (var i = 0; i < $scope.RoomNumArray.length; i++){

    }

    $scope.extrFineChange = function(singleRoom){
        singleRoom.Acct_SUM = 0;
        for (var j = 0; j < singleRoom.AcctPay.length; j++){
            singleRoom.Acct_SUM += $scope.toFloat(singleRoom.AcctPay[j]['RM_PAY_AMNT']);
        }
        singleRoom.Acct_SUM += $scope.toFloat(singleRoom.extraTime['extrFine']);
    };

    $scope.addNewRMProduct = function(singleRoom){
        if(!isNaN(singleRoom.newRMProduct.newRProductQUAN) && parseFloat(singleRoom.newRMProduct.newRProductQUAN) % 1 === 0
            && parseFloat(singleRoom.newRMProduct.newRProductQUAN) > 0 && singleRoom.newRMProduct.newRProductNM.trim() != "" &&
            singleRoom.newRMProduct.newRProductPAY != undefined){
            singleRoom.RoomConsume.push({
                "PROD_NM": singleRoom.newRMProduct.newRProductNM.trim(),
                "PROD_QUAN":singleRoom.newRMProduct.newRProductQUAN,
                "STR_PAY_AMNT":singleRoom.newRMProduct.newRProductPAY,
                "STR_TRAN_TSTAMP":"新添加" ,
                "RM_TRAN_ID":singleRoom.RM_TRAN_ID,
                "STR_PAY_METHOD":singleRoom.newRMProduct.newRProductPAYmethod,
                "PROD_ID":singleRoom.newRMProduct.newRProductID
                });
            singleRoom.newConsumeSum += parseFloat(singleRoom.newRMProduct.newRProductPAY);
            $scope.calculateSum(singleRoom);
            singleRoom.newRMProduct["newRProductNM"]="";
            singleRoom.newRMProduct["newRProductQUAN"]="";
            singleRoom.newRMProduct["newRProductPAY"]="";
            $scope.RoomConsumePayMethod = [singleRoom.newRMProduct.newRProductPAYmethod];
        }
    }

    $scope.deleteNewRMProduct = function(singleRoom,Rstore){
            singleRoom.RoomConsume.splice(singleRoom.RoomConsume.indexOf(Rstore),1);
            singleRoom.newConsumeSum -= parseFloat(Rstore.STR_PAY_AMNT);
            $scope.calculateSum(singleRoom);
            if (singleRoom.RoomConsume.length == 0){
                $scope.RoomConsumePayMethod = ['现金','储蓄卡','信用卡'];
            }
    }

    $scope.getPrice = function(singleRoom){
        if(!isNaN(singleRoom.newRMProduct.newRProductQUAN) && parseFloat(singleRoom.newRMProduct.newRProductQUAN) % 1 === 0
            && parseFloat(singleRoom.newRMProduct.newRProductQUAN) > 0 && singleRoom.newRMProduct.newRProductNM.trim() != "" ){
            if($scope.prodNM.indexOf(singleRoom.newRMProduct.newRProductNM.trim())>-1){
                newCheckOutFactory.getProductPrice(singleRoom.newRMProduct.newRProductNM.trim()).success(function(data){
                    var price = parseFloat(data[0]["PROD_PRICE"]);
                    singleRoom.newRMProduct.newRProductPAY = (price * parseFloat(singleRoom.newRMProduct.newRProductQUAN)).toFixed(2)+"元";
                    singleRoom.newRMProduct.newRProductID = data[0]["PROD_ID"];
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
                "PAY_AMNT":singleRoom.newFee.PAY_AMNT,
                "PAYER": singleRoom.newFee.PAYER,
                "PAYER_PHONE":singleRoom.newFee.PAYER_PHONE
            });
            singleRoom.newFeeSum += parseFloat(singleRoom.newFee.PAY_AMNT);
            $scope.calculateSum(singleRoom);
            singleRoom.newFee= {"RMRK":"","PAY_METHOD":"","PAY_AMNT":"", "PAYER":"","PAYER_PHONE":""};
            $scope.PenaltyPayMethod = [singleRoom.newFee.PAY_METHOD];
        }
    }

    $scope.deleteFee = function(singleRoom,fee){
        singleRoom.penalty.splice(singleRoom.penalty.indexOf(fee),1);
        singleRoom.newFeeSum -= parseFloat(fee.PAY_AMNT);
        $scope.calculateSum(singleRoom);
        if (singleRoom.penalty.length == 0){
            $scope.PenaltyPayMethod = ['现金','储蓄卡','信用卡'];
        }
    }

    $scope.calculateSum = function(singleRoom){
        singleRoom.Sumation= parseFloat(singleRoom.DEPO_SUM -
                                singleRoom.Acct_SUM -
                                singleRoom.Store_SUM -
                                singleRoom.newConsumeSum -
                                singleRoom.newFeeSum).toFixed(2) ;
    }

    $scope.checkOTSubmit = function(){
        var modalInstance = $modal.open({
            templateUrl: 'checkOTModalContent',
            controller: 'checkOTModalInstanceCtrl',
            resolve: {
                BookRoom: function () {
                    return $scope.BookRoom;
                },
                MASTER_RM_ID: function () {
                    return $scope.MASTER_RM_ID;
                }
            }
        });
    };

    $scope.toFloat = function(value){
        if (value == undefined || isNaN(parseFloat(value))){
            return 0;
        }
        return parseFloat(value);
    };
    $scope.twoDigit = function(digit){
        return digit.toFixed(2);
    };
    $scope.Abs = function(num){
        return Math.abs(num);
    };
});


appCheckOut.controller('checkOTModalInstanceCtrl',function ($scope, $modalInstance, checkOTModalFactory, $http, BookRoom, MASTER_RM_ID) {

    $scope.BookRoom = BookRoom;
    $scope.MASTER_RM_ID = MASTER_RM_ID;

    $scope.checkAmount = function(singleRoom){
        singleRoom.postPayOptions=['平账,可顺利退房'];
        singleRoom.err = "通过";
        singleRoom.postPayMethod = singleRoom.postPayOptions[0];
        if(isNaN(singleRoom.realMoneyOut)){
            singleRoom.err = "请输入数字";
        }else if(singleRoom.realMoneyOut<0){
            singleRoom.err = "金额不可为负数";
        }else if(singleRoom.realMoneyOut > Math.abs(singleRoom.Sumation)){
            singleRoom.err = "金额不可超过应"+((singleRoom.Sumation>=0)?"退金额":"补交金额");
        }else if(singleRoom.realMoneyOut != $scope.Abs(singleRoom.Sumation) && singleRoom.Sumation>0){
            singleRoom.adjustInfo = "欠客人"+$scope.twoDigit(Math.abs(singleRoom.Sumation)-singleRoom.realMoneyOut)+"元";
            singleRoom.postPayOptions=["存入下次客人消费"];
            if ($scope.MASTER_RM_ID != ''){
                singleRoom.postPayOptions.push('余额转入主房');
            }
            singleRoom.postPayMethod = singleRoom.postPayOptions[0];
        }else if(singleRoom.realMoneyOut != $scope.Abs(singleRoom.Sumation) && singleRoom.Sumation<0){
            singleRoom.adjustInfo = "客人欠款"+$scope.twoDigit(Math.abs(singleRoom.Sumation)-singleRoom.realMoneyOut)+"元";
            singleRoom.postPayOptions=["客人赊账"];
            if ($scope.MASTER_RM_ID != ''){
                singleRoom.postPayOptions.push('欠款转入主房');
            }
            singleRoom.postPayMethod = singleRoom.postPayOptions[0];
        }

    };
    $scope.twoDigit = function(digit){
        return digit.toFixed(2);
    };
    $scope.Abs = function(num){
        return Math.abs(num);
    };
    $scope.cancel = function(){
        $modalInstance.dismiss('cancel');
    };
    $scope.confirm = function(){
        checkOTModalFactory.checkOT($scope.BookRoom,$scope.MASTER_RM_ID).success(function(data){
            $modalInstance.dismiss('cancel');
        });
    };

});
