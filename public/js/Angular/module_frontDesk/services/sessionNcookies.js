/**
 * Created by charlie on 7/13/15.
 */

app.factory('sessionFactory',function($http){
    var userInfo = null;
    return {
        getUserInfo: function(){
            if(userInfo != null){
                return { // unify the call back
                    success: function(exec){
                        exec(userInfo);
                    }
                };
            }else{
                if(uni.userInfo != null){
                    userInfo = uni.userInfo;
                    return { // unify the call back
                        success: function(exec){
                            exec(userInfo);
                        }
                    };
                }else{
                    return $http({
                        method: 'GET',
                        heasders: {'content-Type':'application/json'},
                        url: 'getUserInfo'
                    }).success(function(data){
                        if(data == null){
                            window.location.assign(window.location.href.substring(0, window.location.href.lastIndexOf('#'))+'logout');
                        }else{
                            userInfo = data;
                        }
                    });
                }
            }
        },
        getShiftOptions: function(HTL_ID){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'getShiftOptions/'+HTL_ID
            })
        },
        putShiftChosen: function(shift){
            return $http({
                method: 'POST',
                heasders: {'content-Type':'application/json'},
                url: 'putShiftChosen',
                data: {shift:shift}
            }).success(function(data){
                userInfo  = data;
            });
        }
    }
});