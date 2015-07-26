/**
 * Created by charlie on 7/24/15.
 */
app.controller('infoCenterController', function($scope,infoCenterFactory) {

    /********************************************     utility     ***************************************************/

    var today = new Date();
    var tomorrow = new Date(today.getTime() + 86400000);
    $scope.dateTime = new Date((tomorrow).setHours(12, 0, 0));
    $scope.dateFormat = function (rawDate) {
        return util.dateFormat(rawDate);
    };

    (function getInfoInit() {
        infoCenterFactory.getInfoCenterInfo().success(function(data){
            $scope.alerts = data;
        })
    })();
})
