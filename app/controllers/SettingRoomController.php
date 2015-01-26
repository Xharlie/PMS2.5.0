<?php
/**
 * Created by PhpStorm.
 * User: Xharlie
 * Date: 11/15/14
 * Time: 12:18 PM
 */
class SettingRoomController extends BaseController{
    public function getRoomTp(){
        $roomTpShow = DB::table('RoomsTypes')
            ->select('RoomsTypes.RM_TP as RM_TP','RoomsTypes.SUGG_PRICE as SUGG_PRICE' ,
                'RoomsTypes.RM_PROD_RMRK as RM_PROD_RMRK', 'RoomsTypes.RM_QUAN as RM_QUAN',
                'RoomsTypes.CUS_QUAN as CUS_QUAN')
            ->get();
        return Response::json($roomTpShow);
    }

    public function getRooms(){
        $roomsShow = DB::table('Rooms')
                        ->select('Rooms.RM_ID as RM_ID','Rooms.RM_TRAN_ID as RM_TRAN_ID' ,
                            'Rooms.RM_CONDITION as RM_CONDITION', 'Rooms.RM_TP as RM_TP',
                            'Rooms.RM_CHNG_TSTMP as RM_CHNG_TSTMP','Rooms.FLOOR as FLOOR',
                            'Rooms.FLOOR_ID as FLOOR_ID','Rooms.PHONE as PHONE',
                            'Rooms.RMRK as RMRK')
                        ->get();
        return Response::json($roomsShow);
    }

    public function addRoomTp(){
        $newTp = Input::all();
        DB::table('RoomsTypes')->insert($newTp);
    }

    public function addRooms(){
        $newRm = Input::all();
        DB::table('Rooms')->insert($newRm);
    }


    public function editRoomTp(){
        $info = Input::all();
        $oldTpNm = $info[0];
        $editTp = $info[1];
        $newTpArray = array(
            "RM_TP" => $editTp["RM_TP"],
            "SUGG_PRICE" => $editTp["SUGG_PRICE"],
            "RM_PROD_RMRK" => $editTp["RM_PROD_RMRK"],
            "RM_QUAN" => $editTp["RM_QUAN"],
            "CUS_QUAN" => $editTp["CUS_QUAN"],
        );
        DB::table('RoomsTypes')->where('RM_TP','=',$oldTpNm)
            ->update($newTpArray);
        if($oldTpNm != $editTp["RM_TP"]){
            DB::table('Rooms')->where('RM_TP',$oldTpNm)
                ->update(array(
                    "RM_TP"=>$editTp["RM_TP"]
                ));
            DB::table('TempRmSetting')->where('RM_TP',$oldTpNm)
                ->update(array(
                    "RM_TP"=>$editTp["RM_TP"]
                ));
        }
    }

    public function deleteRoomTp($RM_TP){
        DB::table('RoomsTypes')->where('RM_TP', '=', $RM_TP)->delete();
    }

    public function deleteRooms($RM_ID){
        DB::table('Rooms')->where('RM_ID', '=', $RM_ID)->delete();
    }

    public function editRooms(){
        $info = Input::all();
        $oldRMNm = $info[0];
        $editRm = $info[1];
        $newRmArray = array(
            "RM_ID" => $editRm["RM_ID"],
            "RM_TP" => $editRm["RM_TP"],
            "PHONE" => $editRm["PHONE"],
            "RMRK" => $editRm["RMRK"],
            "FLOOR" => $editRm["FLOOR"],
            "FLOOR_ID" => $editRm["FLOOR_ID"]
        );
        DB::table('Rooms')->where('RM_ID','=',$oldRMNm)
            ->update($newRmArray);

    }

    public function addFloors(){
        $newFl = Input::all();
        $newFLOOR_ID = intval(DB::table('Rooms')->max('FLOOR_ID'))+1;
        return json_encode($newFLOOR_ID);
    }

    public function deleteFloors($FLOOR_ID){
        DB::table('Rooms')
            ->where('FLOOR_ID', '=', $FLOOR_ID)
            ->update(array('FLOOR' => "待定",'FLOOR_ID' => "-1"));
    }

    public function editFloors(){
        $floor = Input::all();
        $newFlArray = array(
            "FLOOR" => $floor["FLOOR"],
            "FLOOR_ID" => $floor["FLOOR_ID"],
        );
        DB::table('Rooms')->where('FLOOR_ID','=',$floor["FLOOR_ID"])
            ->update($newFlArray);

    }


    public function getTempPlan(){
        $tempRoomShow = DB::table('TempRmSetting')
            ->get();
        return Response::json($tempRoomShow);
    }

    public function editPlan(){
        $info = Input::all();
        $oldPlanId = $info[0];
        $editPlan = $info[1];
        $newPlanArray = array(
            "RM_TP" => $editPlan["RM_TP"],
            "PLAN_COV_MIN" => $editPlan["PLAN_COV_MIN"],
            "PLAN_COV_PRCE" => $editPlan["PLAN_COV_PRCE"],
            "PNLTY_PR_MIN" => $editPlan["PNLTY_PR_MIN"]
        );
        DB::table('TempRmSetting')->where('PLAN_ID','=',$oldPlanId)
            ->update($newPlanArray);
    }

    public function deletePlan($PLAN_ID){
        DB::table('TempRmSetting')->where('PLAN_ID', '=', $PLAN_ID)->delete();
    }


    public function addPlan(){
        $newPlan = Input::all();
        $PLAN_ID = DB::table('TempRmSetting')->insertGetId($newPlan);
        return Response::json($PLAN_ID);
    }

}