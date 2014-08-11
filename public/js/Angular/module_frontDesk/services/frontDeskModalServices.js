app.factory('cusModalFactory',function($http){
    return{
        OccupiedShow: function(RM_TRAN_ID){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showOccupied/'+RM_TRAN_ID
            });
        },

        EmptyShow: function(RM_TP){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showEmpty/'+RM_TP
            })
        },

        Change2Mending: function(RM_ID){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'change2Mending/'+RM_ID
            })
        },
        Change2Mended:function(RM_ID){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'change2Mended/'+RM_ID
            })
        },
        Change2Cleaned:function(RM_ID){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'Change2Cleaned/'+RM_ID
            })
        },

        MendingShow: function(RM_ID){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showMending'
            })
        },
        getAccounting: function(RM_TRAN_ID){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'roomAccounting/'+RM_TRAN_ID
            })
        },
        getConnect: function(RM_TRAN_ID){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'getConnect/'+RM_TRAN_ID
            })
        }
    };
});

