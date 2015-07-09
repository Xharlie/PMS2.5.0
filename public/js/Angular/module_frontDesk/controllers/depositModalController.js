/**
 * Created by charlie on 7/7/15.
 */

app.controller('depositModalController', function($scope, $http,paymentFactory,newCheckInFactory,
                                                       $modalInstance, roomST,initialString) {

/********************************************     validation       ***************************************************/
    $scope.hasError = function(btnPass){
        if(eval("$scope."+btnPass)==null) eval("$scope."+btnPass+"=0");
        eval("$scope."+btnPass+"++");
    }
    $scope.noError = function(btnPass){
        eval("$scope."+btnPass+"--");
    }
    $scope.payError = 0;

/********************************************     common initial setting     *****************************************/
    $scope.initialString = initialString;
    $scope.BookCommonInfo = {
        Master: { payment: paymentFactory.createNewPayment('住房押金'), check: true}
    };

// for payment module to work in ng-repeat
    $scope.BookRoomMaster = [$scope.BookCommonInfo.Master];
    $scope.payMethodOptions = paymentFactory.checkInPayMethodOptions();

/************** ********************************** submit  ********************************** *************/

    function testFail(){
        return false;
    }

    $scope.submit = function(){
        if (testFail()) return;
        $scope.submitLoading = true;
        newCheckInFactory.deposit(JSON.stringify({SubmitInfo:roomST[0],pay:$scope.BookCommonInfo.Master.payment})).success(function(data){
            $scope.submitLoading = false;
            show("办理成功!");
            var room = roomST[0]
            room.payment = $scope.BookCommonInfo.Master.payment;
            room.GuestsInfo =[{}];
            room.GuestsInfo[0].Name = roomST[0]["customers"][0].CUS_NAME;
            room.GuestsInfo[0].MemberId = roomST[0]["customers"][0].MEM_ID;
            room.GuestsInfo[0].Phone = roomST[0]["customers"][0].PHONE;
            room.GuestsInfo[0].SSN = roomST[0]["customers"][0].SSN;
            room.GuestsInfo[0].MEM_TP = roomST[0]["customers"][0].MEM_TP;
            room.GuestsInfo[0].Points = roomST[0]["customers"][0].POINTS;
            room.GuestsInfo[0].DOB = roomST[0]["customers"][0].DOB;
            room.GuestsInfo[0].Address = roomST[0]["customers"][0].ADDRSS;
            room.GuestsInfo[0].RemarkInput = roomST[0]["customers"][0].RMRK;
            var pms ={HTL_NM:"",EMP_NM:""};
            printer.deposit(pms,room,room.GuestsInfo[0]);
            $modalInstance.close("checked");
            util.closeCallback();
        });
    }
});