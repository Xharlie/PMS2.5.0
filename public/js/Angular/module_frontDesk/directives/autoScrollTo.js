/**
 * Created by Xharlie on 2/16/15.
 */

app.directive('scrollAnchor', function($document,$parse) {
    return {
        restrict: 'A',
        transclude: true,            // has element inside,
        link: function link(scope,element, attrs) {
            scope.$watch(attrs.scrollAnchor, function(value) {
                if (value) {
                    var pos = $("#" + value,$(element)).position().top
                        + $(element).scrollTop() - $(element).position().top;
                    if(attrs.fadeClass && $parse(attrs.newIds)(scope)){
                        $parse(attrs.newIds)(scope).forEach(function(id) {
                            $("#"+id).addClass( attrs.fadeClass, 0);
                        });
                    }
                    $(element).animate({
                        scrollTop : pos
                    }, 500);
                    if(attrs.fadeClass && $parse(attrs.newIds)(scope)){
                        $parse(attrs.newIds)(scope).forEach(function(id) {
                            $("#"+id).removeClass( attrs.fadeClass, 500, "easeOutBack" );
                        });
                    }
                }
            });
        }
    };
});
