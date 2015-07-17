/**
 * Created by charlie on 7/17/15.
 */
var structure = {
    updateRooms :function(ori,upt) {
        // if room amount changing, better to refresh
        if(ori.length!=upt.length){
            ori = upt;
            return ;
        }
        this.sortByRm(ori);
        this.sortByRm(upt);
        for(var i =0; i<upt.length; i++){
            // if someone is changing the RM_ID, better to refresh
            if (ori[i].RM_ID != upt[i].RM_ID){
                ori = upt;
                return ;
            }
            // update all new value for the original
            this.updateElement(ori[i],upt[i]);
        }
    }
    ,updateElement: function(ori,upt){
        if (typeof(ori) === "object" && typeof(upt) === "object"){
            for(var key in upt){
                if(key in ori && ori[key] != null && upt[key]!=null && typeof(ori[key]) === "object" && typeof(upt[key]) === "object" ){
                    this.updateElement(ori[key],upt[key]);
                }else{
                    ori[key] = upt[key];
                }
            }
            for(var key in ori){
                if(!(key in upt))ori[key]=null ;
            }
        }else if(typeof(ori) != "object" && typeof(upt) != "object"){
            if(ori != upt) {
                ori = upt;
            }
        }else{
            ori = upt;
        }
    }
    ,sortByRm : function(sortee){
        if(!sortee.sort) return;
        sortee.sort(function(a, b) {
            return parseInt(a.RM_ID) - parseInt(b.RM_ID);
        });
    }

}
