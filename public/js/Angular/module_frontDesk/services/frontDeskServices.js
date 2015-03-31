
app.factory('settingTempRoomFactory',function($http){
    return{
        tempPlanGet: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'tempPlanGet'
            });
        },
        planEdit:function(info){
            return $http({
                method: 'POST',
                heasders: {'content-Type':'application/json'},
                url: 'planEdit',
                data: info
            });
        },
        planDelete: function(PLAN_ID){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'planDelete/'+PLAN_ID
            });
        },
        planAdd: function(newPlan){
            return $http({
                method: 'POST',
                heasders: {'content-Type':'application/json'},
                url: 'planAdd',
                data: newPlan
            });
        }

    }
})


app.factory('settingRoomFactory',function($http){
    return{
        roomTpGet: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'roomTpGet'
            });
        },
        roomTpAdd: function(newTp){
            return $http({
                method: 'POST',
                heasders: {'content-Type':'application/json'},
                url: 'roomTpAdd',
                data: newTp
            });
        },
        roomTpDelete: function(RM_TP){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'roomTpDelete/'+RM_TP
            });
        },
        roomTpEdit: function(info){
            return $http({
                method: 'POST',
                heasders: {'content-Type':'application/json'},
                url: 'roomTpEdit',
                data: info
            });
        },
        roomsGet: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'roomsGet'
            });
        },
        roomsEdit: function(info){
            return $http({
                method: 'POST',
                heasders: {'content-Type':'application/json'},
                url: 'roomsEdit',
                data: info
            });
        },
        roomsDelete: function(RM_ID){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'roomsDelete/'+RM_ID
            });
        },
        roomsAdd: function(newRM){
            return $http({
                method: 'POST',
                heasders: {'content-Type':'application/json'},
                url: 'roomsAdd',
                data: newRM
            });
        },
        floorsAdd: function(newFL){
            return $http({
                method: 'POST',
                heasders: {'content-Type':'application/json'},
                url: 'floorsAdd',
                data: newFL
            });
        },
        floorsEdit: function(floor){
            return $http({
                method: 'POST',
                heasders: {'content-Type':'application/json'},
                url: 'floorsEdit',
                data: floor
            });
        },
        floorsDelete: function(FLOOR_ID){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'floorsDelete/'+FLOOR_ID
            });
        }
    }
});

app.factory('resrvFactory',function($http){
    return{
        resvShow: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showReservation'
            });
        }
    }
});

app.factory('roomStatusFactory',function($http){
    return{
        roomShow: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showRoomStatus'
            })
        }
    };
});

app.factory('customerFactory',function($http){
    return{
        customerShow: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showCustomer'
            });
        },
        memberShow: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showMember'
            });
        }
//        memberPageShow: function(shift,len){
//            return $http({
//                method: 'GET',
//                heasders: {'content-Type':'application/json'},
//                url: 'showMemberPage/'+shift+'/'+len
//            });
//        }
    }
 });

app.factory('accountingFactory',function($http){
    return{
        accountingGetAll: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'accountingGetAll'
            })
        },
        summerize:function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'summerize'
            })
        }

    };
});







app.factory('merchandiseFactory',function($http){
    return{
        productShow: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showProduct'
            })
        },
        merchanRoomShow: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showMerchanRoom'
            })
        },
        buySubmit: function(buyInfo){
            return $http({
                method: 'POST',
                heasders: {'content-Type':'application/json'},
                url: 'buySubmit',
                data: buyInfo
            });
        },
        histoPurchaseShow: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showHistoPurchase'
            })
        },
        histoProductShow: function(STR_TRAN_ID){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showHistoProduct/'+STR_TRAN_ID
            })
        }
    };
});
