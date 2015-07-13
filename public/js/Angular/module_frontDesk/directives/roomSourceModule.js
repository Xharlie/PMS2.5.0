/**
 * Created by charlie on 7/10/15.
 */

app.directive('roomSource', function() {
    return {
        restrict: 'A',
        replace: true,
        controller: 'roomSourceCtrl',
        scope: {
            disable: '=disable',
            BookCommonInfo:'=bookCommonInfo',
            check:'=check',
            caption:'=caption',
            roomST:'=roomST'
        },
        templateUrl: 'parts/roomSource'   //初步为hardcoding,可进一步优化为function，实现dynamic调用
    };
});
