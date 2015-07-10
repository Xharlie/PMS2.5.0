/**
 * Created by charlie on 7/10/15.
 */

app.directive('roomSource', function() {
    return {
        restrict: 'A',
        replace: true,
        controller: 'paymentCtrl',
        scope: {
            BookRoom: '=bookRoom',
            payMethodOptions:'=payMethodOptions',
            payError: '=payError',
            rooms :'=rooms'
        },
        templateUrl: 'parts/roomSource'   //初步为hardcoding,可进一步优化为function，实现dynamic调用
    };
});
