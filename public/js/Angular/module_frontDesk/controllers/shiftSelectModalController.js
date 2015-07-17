/**
 * Created by charlie on 7/16/15.
 */
app.controller('shiftSelectModalController', function($scope, $http, sessionFactory, HTL_ID, $modalInstance){
    sessionFactory.getShiftOptions(HTL_ID).success(function(data){
        $scope.shifts = data;
    })
    $scope.submit=function(shift){
        sessionFactory.putShiftChosen(shift).success(function(data){
            if(data !=null) {
                $modalInstance.close(data);
            }else{
                show('网络出错')
            }
        })
    }
});