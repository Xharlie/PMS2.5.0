/**
 * Created by charlie on 7/10/15.
 */

app.controller('roomSourceCtrl', function ($scope,newCheckInFactory) {
/***********************************************************  Memebers  ***************************************************************/
    $scope.Members =[];

    /******** ************ MemberCheck ******* *************/
    $scope.memCheck = function(checkInput){
        if(util.isName(checkInput)){
            newCheckInFactory.searchMember(checkInput,["MEM_NM"]).success(function(data){
                updateMembers(data,null);
            });
        }else if(util.isSSN(checkInput)){
            newCheckInFactory.searchMember(checkInput,["SSN"]).success(function(data){
                updateMembers(data,null);
            });
        }else{
            newCheckInFactory.searchMember(checkInput,["MEM_ID","PHONE"]).success(function(data){
                updateMembers(data,null);
            });
        }
    }

    var updateMembers = function(data,initFlag){
        $scope.Members = data;
        if (data.length<1){
            alert("查不到");
            $scope.BookCommonInfo.Member = "";
            return;
        }
        $scope.BookCommonInfo.Member = $scope.Members[0];
        for(var i = 0 ; i < $scope.Members.length; i++){
            $scope.Members[i]["summary"] = "<table>"+
            "<tr>" +  "<td>" + "证件:" + "</td>" + "<td>" + $scope.Members[i].SSN + "</td>" + "</tr>"+
            "<tr>" + "<td>" + "级别:" + "</td>" + "<td>" + $scope.Members[i].MEM_TP + "</td>" + "</tr>"+
            "<tr>" + "<td>" + "折扣:" + "</td>" + "<td>" + $scope.Members[i].DISCOUNT_RATE + "</td>" + "</tr>"+
            "<tr>" + "<td>" + "电话:" + "</td>" + "<td>" + $scope.Members[i].PHONE + "</td>" + "</tr>"+
            "<tr>" + "<td>" + "积分:" + "</td>" + "<td>" + $scope.Members[i].POINTS + "</td>" + "</tr>"+
            "</table>";
            if(initFlag!=null && roomST[0]["MEM_ID"] == $scope.Members[i]["MEM_ID"])    $scope.BookCommonInfo.Member = $scope.Members[i];
        }
    }


/***********************************************************  Treaties  ***************************************************************/

    $scope.Treaties =[];

    /********** ********** TreatyCheck ******* *************/
    $scope.treatyCheck = function(checkInput){
        newCheckInFactory.searchTreaties(checkInput,["TREATY_ID","CORP_NM"]).success(function(data){
            updateTreaties(data,null);
        });
    }

    var updateTreaties= function(data,initFlag){
        $scope.Treaties = data;
        if (data.length<1){
            alert("查不到");
            $scope.BookCommonInfo.Treaty = "";
            return;
        }
        $scope.BookCommonInfo.Treaty = $scope.Treaties[0];
        for(var i = 0 ; i < $scope.Treaties.length; i++){
            $scope.Treaties[i]["summary"] = "<table>"+
            "<tr>" +  "<td>" + "类型:" + "</td>" + "<td>" + $scope.Treaties[i].TREATY_TP + "</td>" + "</tr>"+
            "<tr>" + "<td>" + "公司电话:" + "</td>" + "<td>" + $scope.Treaties[i].CORP_PHONE + "</td>" + "</tr>"+
            "<tr>" + "<td>" + "联系人:" + "</td>" + "<td>" + $scope.Treaties[i].CONTACT_NM + "</td>" + "</tr>"+
            "<tr>" + "<td>" + "备注:" + "</td>" + "<td>" + $scope.Treaties[i].RMARK + "</td>" + "</tr>"+
            "<tr>" + "<td>" + "优惠:" + "</td>" + "<td>" + $scope.Treaties[i].DISCOUNT + "</td>" + "</tr>"+
            "</table>";
            if(initFlag!=null && roomST[0]["TREATY_ID"] == $scope.Treaties[i]["TREATY_ID"]) $scope.BookCommonInfo.Treaty = $scope.Treaties[i];
        }
    }
})