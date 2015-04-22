/**
 * Created by Xharlie on 12/17/14.
 */
app.directive('contentMenu', function($document,$parse) {
    return {
        restrict: 'A',
        replace: true,
        controller: 'contentMenuController',
        scope: {
            iconNAction: '=iconNAction',
            owner:'=owner',
            blockClass: '=blockClass'
        },
        link: function link(scope,element, attrs) {
            $document.on("click", function(event){
                    if ( $(event.target).attr("id")!=$(element).attr('id')){
                        // 设置执行完任务后自动关闭menu,所以在predicate里,没有:&& element.find(event.target).length <= 0
                        $document.unbind( "click" );
                        scope.$apply(function () {
                            scope.close(scope.owner);
                        });
                        scope.$destroy();
                        $(element).remove();
                        delete element;
                    };
                }
            );
        },
        templateUrl: 'directiveViews/contentMenu'   //初步为hardcoding,可进一步优化为function，实现dynamic调用
    };
});

app.directive('smallMenu', function($document,$parse) {
    return {
        restrict: 'A',
        replace: true,
        controller: 'smallMenuController',
        scope: {
            iconNAction: '=iconNAction',
            owner:'=owner',
            blockClass: '=blockClass'
        },
        link: function link(scope,element, attrs) {
            $document.on("click", function(event){
                    if ( $(event.target).attr("id")!=$(element).attr('id')){
                    // 设置执行完任务后自动关闭menu,所以在predicate里,没有:&& element.find(event.target).length <= 0
                        $document.unbind( "click" );
                        scope.$apply(function () {
                            scope.close(scope.owner);
                        });
                        scope.$destroy();
                        $(element).remove();
                        delete element;
                    };
                }
            );
        },
        templateUrl: 'directiveViews/smallMenu'   //初步为hardcoding,可进一步优化为function，实现dynamic调用
    };
});



app.directive('largeMenu', function($document) {
    return {
        restrict: 'A',
        replace: true,
        controller: 'largeMenuController',
        scope: {
            iconNAction: '=iconNAction',
            owner:'=owner',
            blockClass: '=blockClass'
        },
        link: function link(scope,element, attrs) {
            $document.on("click", function(event){
                    if (element.find(event.target).length <= 0 && $(event.target).attr("id")!=$(element).attr('id')){
                        $document.unbind( "click" );
                        scope.$apply(function () {
                            scope.close(scope.owner);
                        });
                        scope.$destroy();
                        $(element).remove();
                        delete element;
                    };
                }
            );
        },
        templateUrl: 'directiveViews/largeMenu'   //初步为hardcoding,可进一步优化为function，实现dynamic调用
    };
});


app.directive('popMenu', function ($compile,$parse) {
    return {
        restrict: 'A',
        transclude: true,
        scope: {
            iconNAction: '=iconNAction',
            menuType : '=menuType',
            owner: '=owner',
            notShow: '=notShow',
            blockClass: '=blockClass'
        },
        link: function link(scope,element,attrs) {
            var delay = 200, clicks = 0, timer = null;
            var fn = $parse(attrs['sglclick']);
            element.on('click', function (event) {
                clicks++;  //count clicks
                if(clicks === 1) {
                    timer = setTimeout(function() {
                        scope.$apply(function () {
                            fn();
                        });
                        if(!scope.notShow) {
                            addMenu(scope,element, attrs,event);
                        }
                        clicks = 0;             //after action performed, reset counter
                    }, delay);
                } else {
                    clearTimeout(timer);    //prevent single-click action
                    clicks = 0;             //after action performed, reset counter
                }
            });

            function addMenu(scope,element, attrs,event){
                var menu = document.createElement('div');
                menu.setAttribute(scope.menuType,"");
                menu.setAttribute("icon-n-action",'iconNAction');
                menu.setAttribute("owner",'owner');
                menu.setAttribute("block-class",'blockClass');
                var top = event.pageY; // 当 position:absoulte;
                var left = event.pageX; // 当 position:absoulte;
                document.body.appendChild(menu);
                $(menu).css({left: left, top: top});
                $compile(menu)(scope);
            }
        }
    };
});