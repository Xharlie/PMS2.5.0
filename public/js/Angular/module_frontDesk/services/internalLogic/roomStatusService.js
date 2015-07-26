/**
 * Created by charlie on 7/23/15.
 */

app.factory('roomStatusInterFactory',function($http,roomStatusFactory){

    function roomCount(room,summary){
        if(room.RM_TP in summary){
            summary[room.RM_TP]['total']++;
            summary[room.RM_TP][room.RM_CONDITION]++;
        }else{
            summary[room.RM_TP] = {total:1,'空房':0,'有人':0,'维修':0,'脏房':0};
            summary[room.RM_TP][room.RM_CONDITION]++;
        }
    }

    function floorify(room,floors){
        if(room.FLOOR_ID in floors){
            floors[room.FLOOR_ID].rooms.push(room);
        }else{
            floors[room.FLOOR_ID] = {FLOOR_ID:room.FLOOR_ID,FLOOR:room.FLOOR,rooms:[room]};
        }
    }

    function assignCondition(roomST){
        switch(roomST.RM_CONDITION){
            case '空房':
                roomST.menuType = 'small-menu';
                roomST.menuIconAction = util.avaIconAction;
                roomST.blockClass = ["room-empty"];
                break;
            case '有人':
                roomST.menuType = "large-menu";
                roomST.menuIconAction = util.infoIconAction;
                roomST.blockClass = ["room-full"];
                break;
            case '脏房':
                roomST.menuType = "small-menu";
                roomST.menuIconAction = util.dirtIconAction;
                roomST.blockClass = ["room-dirty"];
                break;
            case '维修':
                roomST.menuType = "small-menu";
                roomST.menuIconAction = util.mendIconAction;
                roomST.blockClass = ["room-disabled"];
                break;
        }
    }

    function buildMasterRelation(roomST,master2BranchID,master2BranchStyle){
        roomST['connLightUp'] = [];
        if(roomST['CONN_RM_TRAN_ID'] in master2BranchStyle){
            master2BranchID[roomST.CONN_RM_TRAN_ID].push(roomST.RM_TRAN_ID);
        }else{
            master2BranchID[roomST.CONN_RM_TRAN_ID]=[roomST.RM_TRAN_ID];
            master2BranchStyle[roomST.CONN_RM_TRAN_ID] = {};
        }
        master2BranchStyle[roomST.CONN_RM_TRAN_ID][roomST.RM_ID]=roomST.blockClass;
    }

    function infoCenterify(roomST,infoCenterQueue){
        roomST.alertInfo=[];
        if(parseFloat(roomST.DPST_RMN) < 0){
            var depositAlert = {
                RM_ID: roomST.RM_ID,
                RM_TRAN_ID: roomST.RM_TRAN_ID,
                MSG: roomST.RM_ID.toString() + '号房欠费' + (parseFloat(roomST.DPST_RMN) * (-1)).toString() + '元',
                //DUE: parseFloat(roomST.DPST_RMN) * (-1)
                iconClass: icons.depositAlertIcon
            }
            roomST.alertInfo.push(depositAlert);
            infoCenterQueue.depositAlert.push(depositAlert);
        }
        if( Number(new Date(roomST.CHECK_OT_DT+' '+roomST.LEAVE_TM)) - Number(new Date()) < 1800000 ){
            var leaveAlert = {
                RM_ID: roomST.RM_ID,
                RM_TRAN_ID: roomST.RM_TRAN_ID,
                MSG: roomST.RM_ID.toString() + '号房应于' + roomST.LEAVE_TM + '离开',
                //TSTMP: roomST.CHECK_OT_DT+' '+roomST.LEAVE_TM
                iconClass: icons.leaveAlertIcon
            }
            roomST.alertInfo.push(leaveAlert);
            infoCenterQueue.leaveAlert.push(leaveAlert);
        }
        if( roomST.WKC_TSTMP!=null &&
            ( Number(new Date(roomST.WKC_TSTMP)) - Number(new Date()) < 1800000 ) ){
            var wakeUpAlert = {
                RM_ID: roomST.RM_ID,
                RM_TRAN_ID: roomST.RM_TRAN_ID,
                MSG: roomST.RM_ID.toString() + '号房要求于'+roomST.WKC_TSTMP + '早叫',
                //TSTMP: roomST.WKC_DT+' '+roomST.WKC_TM
                iconClass: icons.wakeUpAlertIcon
            }
            roomST.alertInfo.push(wakeUpAlert);
            infoCenterQueue.wakeUpAlert.push(wakeUpAlert);
        }
    }

    return {
        roomStatusPackaging: function(data){
            structure.sortByRm(data);
            var master2BranchStyle = {};
            var master2BranchID = {};
            var roomSummary = {};
            var roomFloor = {};
            var roomStatusInfo = data;
            var infoCenterQueue = {depositAlert:[],leaveAlert:[],wakeUpAlert:[]};
            for (var i=0; i<roomStatusInfo.length; i++){
                assignCondition(roomStatusInfo[i]);
                if(roomStatusInfo[i]['CONN_RM_TRAN_ID'] != null){
                    buildMasterRelation(roomStatusInfo[i],master2BranchID,master2BranchStyle);
                }
                roomCount(roomStatusInfo[i],roomSummary);
                floorify(roomStatusInfo[i],roomFloor);
                infoCenterify(roomStatusInfo[i],infoCenterQueue);
            }
            for (var i=0; i<roomStatusInfo.length; i++){
                if(roomStatusInfo[i]['CONN_RM_TRAN_ID'] != null){
                    roomStatusInfo[i]['connLightUp'] = master2BranchStyle[roomStatusInfo[i]['CONN_RM_TRAN_ID']];
                    roomStatusInfo[i]['connRM_TRAN_IDs'] = master2BranchID[roomStatusInfo[i]['CONN_RM_TRAN_ID']];
                }else{
                    roomStatusInfo[i]['connRM_TRAN_IDs'] = [roomStatusInfo[i]['RM_TRAN_ID']];
                }
            }
            return {roomStatusInfo:roomStatusInfo,
                master2BranchStyle:master2BranchStyle,
                master2BranchID:master2BranchID,
                roomSummary:roomSummary,
                roomFloor:roomFloor,
                infoCenterQueue:infoCenterQueue}
        },
        updateAllRoom: function(ori,data){
            var upt =this.roomStatusPackaging(data);
            ori.roomStatusInfo = upt.roomStatusInfo;
            ori.master2BranchStyle = upt.master2BranchStyle;
            ori.master2BranchID = upt.master2BranchID;
            ori.roomSummary = upt.roomSummary;
            ori.roomFloor = upt.roomFloor;
            ori.infoCenterQueue = upt.infoCenterQueue;
        }
    }
});


