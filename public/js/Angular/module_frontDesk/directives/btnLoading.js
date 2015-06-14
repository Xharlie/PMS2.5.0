/**
 * Created by charlie on 4/16/15.
 */
app.directive('btnLoading',function () {
    return {
        link:function (scope, element, attrs) {
            scope.$watch(
                function () {
                    return scope.$eval(attrs.btnLoading);
                },
                function (loading) {
                    if(loading) {
                        if (!attrs.hasOwnProperty('ngDisabled')) {
                            element.addClass('disabled').attr('disabled', 'disabled');
                        }
                        attrs.resetText=element.html();
                        if(attrs.loadingText == undefined){
                            attrs.loadingText='正在确认, 请您稍后...';
                        }
                        element.html("<img src="+ attrs.loadingGif + " />"+attrs.loadingText);
                    } else {
                        if (!attrs.hasOwnProperty('ngDisabled')) {
                            element.removeClass('disabled').removeAttr('disabled');
                        }
                        element.html(attrs.resetText);
                    }
                }
            );
        }
    };
})