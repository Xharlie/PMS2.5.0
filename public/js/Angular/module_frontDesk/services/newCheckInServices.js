app.factory('newCheckInFactory',function($http){
    return{
        getSingleRoomInfo: function(RM_ID){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'getSingleRoomInfo/'+RM_ID
            });
        },
        getRoomInfo: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'getRoomInfo'
            });
        },

        getRMInfoWithAvail:function(CHECK_IN_DT,CHECK_OT_DT){
            return $http({
                method: 'POST',
                heasders: {'content-Type':'application/json'},
                url: 'getRMInfoWithAvail',
                data: {CHECK_IN_DT:CHECK_IN_DT, CHECK_OT_DT:CHECK_OT_DT}
            });
        },

        getRoomAndRoomType: function(RM_CONDITION){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'getRoomAndRoomType/'+RM_CONDITION
            });
        },

        searchMember: function(comparer, columns){
            return $http({
                method: 'POST',
                heasders: {'content-Type':'application/json'},
                url: 'searchMembers',
                data: {comparer:comparer, columns:columns}
            });
        },

        searchTreaties: function(comparer, columns){
            return $http({
                method: 'POST',
                heasders: {'content-Type':'application/json'},
                url: 'searchTreaties',
                data: {comparer:comparer, columns:columns}
            });
        },

/******************************** obselete ***********************************************/
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
        },
        modify: function(ModifyInfo){
            return $http({
                method: 'POST',
                heasders: {'content-Type':'application/json'},
                url: 'submitModify',
                data: ModifyInfo
            });
        },
        cusInRoom: function(RM_ID){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showCusInRoom/'+RM_ID
            });
        },tempPlanGet: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'tempPlanGet'
            });
        }

// ,
//
//        CheckInPost: function(postArray){
//
//        }


    }
});


