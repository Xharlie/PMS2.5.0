/**
 * Created by charlie on 4/18/15.
 */
app.directive('focusInside', function() {
    return{
        link:function ($scope, element, attr) {
            $scope.$on('focusOn', function (e, name) {
                if (name == attr.focusOn) {
                    element[0].focus();
                }
            });
        }
    }
});

app.factory('focusInSideFactory', function ($rootScope, $timeout) {
    return {
        tabInit: function (containerId) {
            $(document).on('keydown', function (e) {
                if ((e.which == 9) || (e.keyCode == 9)) {
                    e.preventDefault();
                    nextFocus(containerId);
                }
            });
        },
        manual: function(containerId){
            nextFocus(containerId);
        }
    }
        function nextFocus (containerId){
            var firstFocusable = null;
            var toBeFocused = null;
            var startYet = false;
            var pass = 1;

            function findElement2Focus(tagName,startElement,containerElement){
                if(traverse(tagName,containerElement,startElement) == 'go' && firstFocusable != null && toBeFocused == null) {
                    toBeFocused = firstFocusable;
                }
                $(toBeFocused).focus();
            }

            function traverse(tagName,thisElement, startElement){
                if($(thisElement).prop('tagName') == tagName ){
                    if (firstFocusable == null) {
                        firstFocusable = thisElement;
                        //show('firstInput'+ $(thisElement).attr('id'))
                    }
                    if(thisElement == startElement) {
                        startYet = true;
                        //show('startFound'+ $(thisElement).attr('id'))
                    }else if(startYet && !$(thisElement).prop('disabled') && $(thisElement).is(':visible')){
                        toBeFocused = thisElement;
                        //show('got it');
                        return 'stop';
                    }
                    return 'go';
                }else if($(thisElement).children().length>0){
                    var childs = $(thisElement).children();
                    for( var i = 0; i < childs.length; i++){
                        var result = traverse(tagName,childs[i], startElement);
                        //show(result)
                        //show(pass++);
                        if( result == 'stop'){
                            return 'stop';
                        }
                    }
                }
                return "go"
            }

            $timeout(function() {
                var containerElement = document.getElementById(containerId);
                var focusedElement = document.activeElement;
                findElement2Focus('INPUT',focusedElement,containerElement);
            });
        }

});