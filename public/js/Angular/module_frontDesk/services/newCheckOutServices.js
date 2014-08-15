/**
 * Created by Xharlie on 8/6/14.
 */
appCheckOut.factory('newCheckOutFactory',function($http){
    return{
        getAllInfo: function(Room_Array){
            return $http({
                method: 'POST',
                heasders: {'content-Type':'application/json'},
                url: 'checkOutGetInfo',
                data: Room_Array
            })
        },
        getProductNM: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'getProductNM'
            });
        },
        getProductPrice: function(NM){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'getProductPrice/'+NM
            });
        }
    }
});