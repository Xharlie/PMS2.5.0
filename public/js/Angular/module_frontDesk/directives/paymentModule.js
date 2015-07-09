/**
 * Created by charlie on 7/6/15.
 */

app.directive('payment', function() {
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
        templateUrl: 'parts/payment'   //初步为hardcoding,可进一步优化为function，实现dynamic调用
    };
});
