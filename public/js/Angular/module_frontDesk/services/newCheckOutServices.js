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
        productShow: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showProduct'
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

app.factory('checkOTModalFactory',function($http){
    return{
        checkOT: function(RoomNumArray,MASTER_RM_ID){
            return $http({
                method: 'POST',
                heasders: {'content-Type':'application/json'},
                url: 'checkOutSubmit',
                data: [RoomNumArray,MASTER_RM_ID]
            })
        }
    };
});