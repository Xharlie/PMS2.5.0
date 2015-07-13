/**
 * Created by charlie on 7/10/15.
 */

app.controller('roomSourceCtrl', function ($scope,newCheckInFactory) {
/************** ********************************** room source check ********************************** *************/
$scope.checkSource = function(source,checkInput){
    checkInput = checkInput.toString().trim();
    if (checkInput == ""){
        return;
    }
    if (source == '会员'){
        $scope.memCheck(checkInput);
    }else if(source == '协议'){
        $scope.treatyCheck(checkInput);
    }
}

/***********************************************************  Memebers  ***************************************************************/

    /******** ************ MemberCheck ******* *************/
    $scope.memCheck = function(checkInput){
        if(util.isName(checkInput)){
            newCheckInFactory.searchMember(checkInput,["MEM_NM"]).success(function(data){
                $scope.BookCommonInfo.Members = data;
            });
        }else if(util.isSSN(checkInput)){
            newCheckInFactory.searchMember(checkInput,["SSN"]).success(function(data){
                $scope.BookCommonInfo.Members = data;
            });
        }else {
            newCheckInFactory.searchMember(checkInput,["MEM_ID","PHONE"]).success(function(data){
                $scope.BookCommonInfo.Members = data;
            });
        }
    }

    $scope.$watch(function(){
            return $scope.BookCommonInfo.Members;
        },
        function(newValue, oldValue) {
            if(newValue == oldValue) return;
            if (newValue.length<1){
                alert("查不到");
                $scope.BookCommonInfo.Member = "";
                return;
            }
            $scope.BookCommonInfo.Member = newValue[0];
            for(var i = 0 ; i < newValue.length; i++){
                newValue[i]["summary"] = "<table>"+
                "<tr>" +  "<td>" + "证件:" + "</td>" + "<td>" + newValue[i].SSN + "</td>" + "</tr>"+
                "<tr>" + "<td>" + "级别:" + "</td>" + "<td>" + newValue[i].MEM_TP + "</td>" + "</tr>"+
                "<tr>" + "<td>" + "折扣:" + "</td>" + "<td>" + newValue[i].DISCOUNT_RATE + "</td>" + "</tr>"+
                "<tr>" + "<td>" + "电话:" + "</td>" + "<td>" + newValue[i].PHONE + "</td>" + "</tr>"+
                "<tr>" + "<td>" + "积分:" + "</td>" + "<td>" + newValue[i].POINTS + "</td>" + "</tr>"+
                "</table>";
                if($scope.BookCommonInfo.initFlag!=null && $scope.roomST[0]["MEM_ID"] == newValue[i]["MEM_ID"])
                    $scope.BookCommonInfo.Member = newValue[i];
            }
        },
        true
    );


/***********************************************************  Treaties  ***************************************************************/

    /********** ********** TreatyCheck ******* *************/
    $scope.treatyCheck = function(checkInput){
        newCheckInFactory.searchTreaties(checkInput,["TREATY_ID","CORP_NM"]).success(function(data){
            $scope.BookCommonInfo.Treaties = data;
        });
    }

    $scope.$watch(function(){
            return $scope.BookCommonInfo.Treaties;
        },
        function(newValue, oldValue) {
            if(newValue == oldValue ) return;
            if (newValue.length<1){
                alert("查不到");
                $scope.BookCommonInfo.Treaty = "";
                return;
            }
            $scope.BookCommonInfo.Treaty = newValue[0];
            for(var i = 0 ; i < newValue.length; i++){
                newValue[i]["summary"] = "<table>"+
                "<tr>" +  "<td>" + "类型:" + "</td>" + "<td>" + newValue[i].TREATY_TP + "</td>" + "</tr>"+
                "<tr>" + "<td>" + "公司电话:" + "</td>" + "<td>" + newValue[i].CORP_PHONE + "</td>" + "</tr>"+
                "<tr>" + "<td>" + "联系人:" + "</td>" + "<td>" + newValue[i].CONTACT_NM + "</td>" + "</tr>"+
                "<tr>" + "<td>" + "备注:" + "</td>" + "<td>" + newValue[i].RMARK + "</td>" + "</tr>"+
                "<tr>" + "<td>" + "优惠:" + "</td>" + "<td>" + newValue[i].DISCOUNT + "</td>" + "</tr>"+
                "</table>";
                if($scope.BookCommonInfo.initFlag!=null && $scope.roomST[0]["TREATY_ID"] == newValue[i]["TREATY_ID"])
                    $scope.BookCommonInfo.Treaty = newValue[i];
            }
        },
        true
    );
})