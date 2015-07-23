/**
 * Created by charlie on 7/16/15.
 */
app.controller('wakeUpCallModalController', function($scope, $http,$modalInstance,newCheckInFactory,initialString,RM_TRAN_IDFortheRoom,WKC_TSTMP){
    /********************************************     validation     ***************************************************/
    $scope.hasError = function(btnPass){
        if(eval("$scope."+btnPass)==null) eval("$scope."+btnPass+"=0");
        eval("$scope."+btnPass+"++");
    }
    $scope.noError = function(btnPass){
        eval("$scope."+btnPass+"--");
    }
    /********************************************       utility      ***************************************************/
    var today = new Date();
    var tomorrow = new Date(today.getTime()+86400000);
    var dateTime = new Date((tomorrow).setHours(6,0,0));
    $scope.dateFormat = function(rawDate){
        return util.dateFormat(rawDate);
    }
    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };
    /********************************************         init       ***************************************************/
    function initWakeUp(){
        $scope.BookCommonInfo.WKC_DT = tomorrow;
        $scope.BookCommonInfo.WKC_TM = dateTime;
    }
    function editWakeUp(){
        $scope.BookCommonInfo.WKC_DT = new Date(WKC_TSTMP);
        $scope.BookCommonInfo.WKC_TM = new Date(WKC_TSTMP);
    }
    /********************************************    submit          ***************************************************/
    $scope.submit = function(msg){
        var submitInfo = {
            'RM_TRAN_ID':RM_TRAN_IDFortheRoom,
            'WKC_TSTMP':(msg == 'cancel')?null:util.dateFormat($scope.BookCommonInfo.WKC_DT)+' '+util.timeFormat($scope.BookCommonInfo.WKC_TM)
        }
        newCheckInFactory.setWakeUpCall(submitInfo).success(function(data){
            show('设置成功!');
            $modalInstance.close("checked");
        });
    }
    /********************************************    common initial setting     ***************************************************/
    $scope.BookCommonInfo = {
        WKC_DT:null,
        WKC_TM:null
    }
    $scope.initialString = initialString;
    if(initialString == "initWakeUp"){
        $scope.initialString=initialString;
        initWakeUp();
    }else{
        $scope.initialString='editWakeUp';
        editWakeUp();
    }
});