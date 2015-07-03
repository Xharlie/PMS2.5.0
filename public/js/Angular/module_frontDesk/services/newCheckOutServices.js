/**
 * Created by Xharlie on 8/6/14.
 */
app.factory('newCheckOutFactory',function($http){
    return{
// get all pay for the room
        getAllInfo: function(Room_Array){
            return $http({
                method: 'POST',
                heasders: {'content-Type':'application/json'},
                url: 'checkOutGetInfo',
                data: Room_Array
            })
        },
        checkOT: function(submitObj){
            return $http({
                method: 'POST',
                heasders: {'content-Type':'application/json'},
                url: 'checkOutSubmit',
                data: submitObj
            })
        },
        checkLedgerOT: function(submitObj){
            return $http({
                method: 'POST',
                heasders: {'content-Type':'application/json'},
                url: 'checkLedgerSubmit',
                data: submitObj
            })
        }

//        productShow: function(){
//            return $http({
//                method: 'GET',
//                heasders: {'content-Type':'application/json'},
//                url: 'showProduct'
//            })
//        },
//        getProductNM: function(){
//            return $http({
//                method: 'GET',
//                heasders: {'content-Type':'application/json'},
//                url: 'getProductNM'
//            });
//        },
//        getProductPrice: function(NM){
//            return $http({
//                method: 'GET',
//                heasders: {'content-Type':'application/json'},
//                url: 'getProductPrice/'+NM
//            });
//        },
    }
});
