/**
 * Created by Xharlie on 8/17/14.
 */
appResv.factory('newResvFactory',function($http){
    return{
        RoomSoldOut: function(checkInDt,checkOtDt){
            var promise = $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showSoldOut/'+checkInDt+"/"+checkOtDt
            }).then(function(response){
                return response.data;
            });

            return promise;
        },
        RoomQuan: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showRoomQuan'
            });
        },
        RoomUnAvail: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showRoomUnAvail'
            });
        },
        MemberBySSN: function(SSN){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showMemberBySSN/'+SSN
            });
        },

        MemberByID: function(MEM_ID){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showMemberByID/'+MEM_ID
            });
        },

        TreatyByID: function(TREATY_ID){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showTreatyByID/'+TREATY_ID
            });
        },
        TreatyByCORP: function(CORP_NM){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showTreatyByCorp/'+CORP_NM
            });
        },
        resvSubmit: function(SubmitInfo){
            return $http({
                method: 'POST',
                heasders: {'content-Type':'application/json'},
                url: 'submitResv',
                data: SubmitInfo
            });
        }

    }
});