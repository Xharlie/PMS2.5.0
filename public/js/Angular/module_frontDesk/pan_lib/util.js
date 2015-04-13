var util = {
    closeCallback :function() {
        window.location.reload();
    },
    openTab : function(uri,name,spec,closeCallback) {
        var win = window.open(uri,uri,name,spec);
        var interval = window.setInterval(function() {
            try {
                if (win == null || win.closed) {
                    closeCallback();
                    clearInterval(interval);
                }
            }
            catch (e) {
            }
        }, 1000);
        return win;
    },
    toLocal : function(utcDateTime){
        return (new Date(utcDateTime.getTime()-utcDateTime.getTimezoneOffset()*60000));
    },
    dateFormat : function(date){
        if(date instanceof Date){
            var yyyy = date.getFullYear().toString();
            var mm = (date.getMonth()+1).toString();
            var dd  = date.getDate().toString();
            return yyyy+"-" + (mm[1]?mm:"0"+mm[0])+"-" + (dd[1]?dd:"0"+dd[0]);
        }else {
            return date;
        }
    },
    birthDateFormat : function(date){
        var mm = (date.getMonth()+1).toString();
        var dd  = date.getDate().toString();
        return (mm[1]?mm:"0"+mm[0])+"-" + (dd[1]?dd:"0"+dd[0]);
    },
    tstmpFormat: function(date){
        var YYYY = (date.getFullYear()).toString();
        var MM = (date.getMonth()+1).toString();
        var DD  = date.getDate().toString();
        var hh = (date.getHours()).toString();
        var mm = (date.getMinutes()).toString();
        var ss = (date.getSeconds()).toString();
        return (YYYY+"-"+(MM[1]?MM:"0"+MM[0])+"-" + (DD[1]?DD:"0"+DD[0])+" "+(hh[1]?hh:"0"+hh[0])+":"+(mm[1]?mm:"0"+mm[0])+":"+(ss[1]?ss:"0"+ss[0]));
    },
    timeFormat: function(date){
        if(typeof(date) == 'number')date = new Date(date);
        var hh = (date.getHours()).toString();
        var mm = (date.getMinutes()).toString();
        var ss = (date.getSeconds()).toString();
        return (hh[1]?hh:"0"+hh[0])+":"+(mm[1]?mm:"0"+mm[0])+":"+(ss[1]?ss:"0"+ss[0]);
    },
    time2DateTime: function(date,time){
        if(!(typeof time === 'string') || time.length != 8) {
            alert("invalid arrive time "+ time )
            return null;
        }
        var hh = time.substring(0,2);
        var mm = time.substring(3,5);
        var ss = time.substring(7,9);
        return date.setHours(hh,mm,ss);
    },
    Limit : function(num){
        return Number(parseFloat(num).toFixed(2));
    },
    isNum : function(testee){
        return (!isNaN(testee) && testee!=null && testee.toString().trim()!="" );
    },
    deepCopy: function(copyee){
        return  JSON.parse(JSON.stringify(copyee));
    },
    VarMaxSize : 15,
    VarItemPerPage : 10,
    //--------------------   房态页面  ---------------------------
    avaIconAction : [{icon:"glyphicon glyphicon-send",action:"入住办理"},
        {icon:"glyphicon glyphicon-wrench",action:"房间维修"}]
    ,       //空房菜单
    dirtIconAction : [{icon:"glyphicon glyphicon-send",action:"入住办理"},
        {icon:"glyphicon glyphicon-thumbs-up",action:"清洁完毕"},
        {icon:"glyphicon glyphicon-wrench",action:"房间维修"}]
    ,     //脏房菜单
    mendIconAction : [{icon:"glyphicon glyphicon-send",action:"入住办理"},
        {icon:"glyphicon glyphicon-cog",action:"维修完毕"}]
    ,     //维修房菜单
    infoIconAction :  [{icon:"glyphicon glyphicon-barcode",action:"制门卡"},
        {icon:"glyphicon glyphicon-send",action:"客人修改"},
        {icon:"glyphicon glyphicon-shopping-cart",action:"商品购买"},
        {icon:"glyphicon glyphicon-pencil",action:"房间更改"},
        {icon:"glyphicon glyphicon-list-alt  ",action:"房价调整"},
        {icon:"glyphicon glyphicon-arrow-left",action:"退房办理"}]
    //--------------------   预定页面  ---------------------------
    ,
    resvIconAction : [{icon:"glyphicon glyphicon-send",action:"预定入住"},
        {icon:"glyphicon glyphicon-pencil",action:"预定修改"},
        {icon:"glyphicon glyphicon-pencil",action:"取消预定"}]

    //--------------------   会员页面  ---------------------------
    ,
    memberIconAction : [{icon:"glyphicon glyphicon-tree-deciduous",action:"积分调整"},
        {icon:"glyphicon glyphicon-open",action:"等级调整"},
        {icon:"glyphicon glyphicon-pencil",action:"修改资料"}]

    // --------------------   商品页面  ---------------------------
    ,
    merchIconAction : [{icon:"glyphicon glyphicon-shopping-cart",action:"商品购买"}]
    //
    // --------------------   商品历史  ---------------------------
    ,
    merchIconAction : [{icon:"glyphicon glyphicon-barcode",action:"制门卡"},
        {icon:"glyphicon glyphicon-send",action:"客人修改"},
        {icon:"glyphicon glyphicon-shopping-cart",action:"商品购买"},
        {icon:"glyphicon glyphicon-pencil",action:"房间更改"},
        {icon:"glyphicon glyphicon-list-alt  ",action:"房价调整"},
        {icon:"glyphicon glyphicon-arrow-left",action:"退房办理"}]
    //

    //--------------------- input check --------------------------
    ,
    isSSN : function(Num){
        var SNum = Num.toString();
        return (SNum.length == 18) && (!isNaN(SNum.substring(0,17))) &&
            (!isNaN(SNum.substring(17,18)) || (SNum.substring(17,18)).toUpperCase() == 'X');
    }  // all 18 are number or only last digit is X
    ,
    isName : function(Name){
        var SName = Name.toString();
        return (isNaN(SName)) && (SName.search(/\d+/) == -1 || !isNaN(SName.substring(SName.search(/\d+/))));
    }  // only number shown at the trail is allowed, but not all numbers for every char

}

var show =function(showee){
    alert(JSON.stringify(showee));
}

//
//    ['基本信息','basicInfo'],["账单查看",'viewAccounting'],["叫醒服务",'wakeUp'],["退房办理",'checkOut'],
//    ["商品购买",'shopping']];               //有人房间菜单

