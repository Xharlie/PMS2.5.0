/**
 * Created by charlie on 7/17/15.
 */
var structure = {
    //updateElement: function(ori,upt){
    //    if (typeof(ori) === "object" && typeof(upt) === "object"){
    //        for(var key in upt){
    //            if((key in ori) && ori[key] != null && upt[key]!=null && typeof(ori[key]) === "object" && typeof(upt[key]) === "object" ){
    //                this.updateElement(ori[key],upt[key]);
    //            }else{
    //                ori[key] = upt[key];
    //            }
    //        }
    //        if(Array.isArray(ori)) {
    //            for (var i = ori.length - 1; i >= 0; i--) {
    //                if (ori[i] == null || i >= upt.length) {
    //                    ori.splice(i, 1);
    //                }
    //            }
    //        }else{
    //            for(var key in ori){
    //                if(!(key in upt) && key!="object" && key!='$$hashKey'){
    //                    delete ori[key];
    //                }
    //            }
    //        }
    //    }else if(ori !== upt) {
    //            ori = upt;
    //    }
    //
    //},
    sortByRm : function(sortee){
        if(!sortee.sort) return;
        sortee.sort(function(a, b) {
            return parseInt(a.RM_ID) - parseInt(b.RM_ID);
        });
    }
}
