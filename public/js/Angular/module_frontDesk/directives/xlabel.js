/**
 * Created by charlie on 5/15/15.
 */

app.directive('xlabel', function($document,$parse) {
    return {
        restrict: 'AEC',
        replace: false,
        transclude: true,
        controller: 'xlabelController',
        scope: {
            checkee: '=',
            checker: '@',
            btnPass: '@',
            comparer:'='
        },
        link: function link(scope,element, attrs) {
            var focuser = ($(element).siblings("input:first").length!=0)?
                $(element).siblings("input"):$(element).siblings().first().find("input");
            if(focuser.length ==0){
                focuser = ($(element).siblings("select:first").length!=0)?
                    $(element).siblings("select"):$(element).siblings().first().find("select");
            }
            scope.$watch('checkee', function(newValue, oldValue) {
                if($(element).children("span:last").css("color")=="rgb(255, 0, 0)"){
                    $(element).children("span:last").remove();
                    $(element).children().css( "color", "#aaa" );
                    focuser.css( "border-color", "#aaa" );
                    /* error number -- */
                    scope.$parent.noError(scope.btnPass);
                }
                var alertContent=scope.checkAll(newValue);
                if(alertContent!=null) {
                    $(element).append('<span>(' + alertContent + ')</span>');
                    $(element).children().css("color", "red");
                    focuser.css("border-color", "red");
                    /* error number ++ */
                    scope.$parent.hasError(scope.btnPass);
                }
            },true);
            if(scope.comparer!=null && scope.comparer!=""){
                scope.$watch('comparer', function(newValue, oldValue) {
                    if($(element).children("span:last").css("color")=="rgb(255, 0, 0)"){
                        $(element).children("span:last").remove();
                        $(element).children().css( "color", "#aaa" );
                        focuser.css( "border-color", "#aaa" );
                        /* error number -- */
                        scope.$parent.noError(scope.btnPass);
                    }
                    var alertContent=scope.checkAll(scope.checkee);
                    if(alertContent!=null) {
                        $(element).append('<span>(' + alertContent + ')</span>');
                        $(element).children().css("color", "red");
                        focuser.css("border-color", "red");
                        /* error number ++ */
                        scope.$parent.hasError(scope.btnPass);
                    }
                },true);
            }
        }
    };
});