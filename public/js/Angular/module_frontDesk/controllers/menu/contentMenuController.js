/**
 * Created by Xharlie on 3/9/15.
 */
/**
 * Created by Xharlie on 12/18/14.
 */

app.controller('contentMenuController',function ($scope, $http,cusModalFactory,$modal,merchandiseFactory) {

/********************************  init  widthes, headers, rows     ************************************/
    $scope.rows =[];
    var init =function(owner){
        merchandiseFactory.histoProductShow(owner.STR_TRAN_ID).success(function(data){
            if(data != null && data.length !=0 ){
                for(var i = 0; i<data.length; i++){
                    $scope.rows.push({'PROD_ID':data[i].PROD_ID,
                                      'PROD_NM':data[i].PROD_NM,
                                      'PROD_PRICE':util.Limit(data[i].PROD_PRICE),
                                      'PROD_QUAN':data[i].PROD_QUAN
                                    });
                }
                $scope.headers=["编号","产品名称","单价","购买数量"];
            }else{
                $scope.headers=["商品未查到"];
            }
        });
    }
    init($scope.owner.owner);
/**********************        operations         ******************/
    $scope.close = function(owner){
        var ind =owner.owner.blockClass.indexOf($scope.blockClass);
        if (ind >=0) owner.owner.blockClass.splice(ind,1);
        if($scope.$parent.$parent.extraCleaner!= undefined) $scope.$parent.$parent.extraCleaner(owner);  // clean associate affected element
    }
    $scope.anchor = function(){
        show('BB');
    }
});

