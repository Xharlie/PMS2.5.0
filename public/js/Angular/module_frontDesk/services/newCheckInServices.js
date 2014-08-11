appCheckIn.factory('newCheckInFactory',function($http){
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
        HistoCustomer: function($SSN){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showHistoCustomer/'+$SSN
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
        submit: function(SubmitInfo){
            return $http({
                method: 'POST',
                heasders: {'content-Type':'application/json'},
                url: 'submitCheckIn',
                data: SubmitInfo
            });
        }

// ,
//
//        CheckInPost: function(postArray){
//
//        }


    }
});


