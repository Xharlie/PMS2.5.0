/**
 * Created by Xharlie on 9/3/14.
 */

appModify.factory('newModifyWindowFactory',function($http){
    return{
        getTargetAcct: function(InfoArray){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'getTargetAcct/'+InfoArray[0]+"/"+InfoArray[1]
            });
        },
        submitModifyAcct: function(SubmitInfo){
            return $http({
                method: 'POST',
                heasders: {'content-Type':'application/json'},
                url: 'submitModifyAcct',
                data: SubmitInfo
            });
        }
    };
});
