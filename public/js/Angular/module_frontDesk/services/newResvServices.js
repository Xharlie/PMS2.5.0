/**
 * Created by Xharlie on 8/17/14.
 */
app.factory('newResvFactory',function($http){
    return{
        getRMInfoWithAvail:function(CHECK_IN_DT,CHECK_OT_DT){
            return $http({
                method: 'POST',
                heasders: {'content-Type':'application/json'},
                url: 'getRMInfoWithAvail',
                data: {CHECK_IN_DT:CHECK_IN_DT, CHECK_OT_DT:CHECK_OT_DT}
            });
        },
        resvSubmit: function(newResv,payment){
            return $http({
                method: 'POST',
                heasders: {'content-Type':'application/json'},
                url: 'submitResv',
                data:  {newResv:newResv, payment:payment}
            });
        },
        resvEditSubmit: function(reResv,payment,RESV){
            return $http({
                method: 'POST',
                heasders: {'content-Type':'application/json'},
                url: 'editResv',
                data:  {reResv:reResv, payment:payment,RESV:RESV}
            });
        }

//        RoomSoldOut: function(checkInDt,checkOtDt){
//            var promise = $http({
//                method: 'GET',
//                heasders: {'content-Type':'application/json'},
//                url: 'showSoldOut/'+checkInDt+"/"+checkOtDt
//            }).then(function(response){
//                return response.data;
//            });
//
//            return promise;
//        },
//        RoomQuan: function(){
//            return $http({
//                method: 'GET',
//                heasders: {'content-Type':'application/json'},
//                url: 'showRoomQuan'
//            });
//        },
//        RoomUnAvail: function(){
//            return $http({
//                method: 'GET',
//                heasders: {'content-Type':'application/json'},
//                url: 'showRoomUnAvail'
//            });
//        },
//        MemberBySSN: function(SSN){
//            return $http({
//                method: 'GET',
//                heasders: {'content-Type':'application/json'},
//                url: 'showMemberBySSN/'+SSN
//            });
//        },
//
//        MemberByID: function(MEM_ID){
//            return $http({
//                method: 'GET',
//                heasders: {'content-Type':'application/json'},
//                url: 'showMemberByID/'+MEM_ID
//            });
//        },
//
//        TreatyByID: function(TREATY_ID){
//            return $http({
//                method: 'GET',
//                heasders: {'content-Type':'application/json'},
//                url: 'showTreatyByID/'+TREATY_ID
//            });
//        },
//        TreatyByCORP: function(CORP_NM){
//            return $http({
//                method: 'GET',
//                heasders: {'content-Type':'application/json'},
//                url: 'showTreatyByCorp/'+CORP_NM
//            });
//        },

    }
});