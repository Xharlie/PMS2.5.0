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
                        element.data('resetText', element.html());
                        //element.html(attrs.loadingText);
                        element.html(attrs.loadingText+"<img src="+ attrs.loading + " />");
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