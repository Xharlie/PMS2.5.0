<?php
/**
 * Created by PhpStorm.
 * User: Xharlie
 * Date: 6/19/14
 * Time: 10:28 PM
 */

class RoomStatusController extends BaseController{

    public function getRoomAndRoomType($RM_CONDITION){
        $roomAndRoomType = DB::table('RoomsTypes')
            ->leftjoin('Rooms','Rooms.RM_TP','=','RoomsTypes.RM_TP')
            ->where('Rooms.RM_CONDITION','=',$RM_CONDITION)
            ->select('Rooms.RM_ID as RM_ID','Rooms.RM_CONDITION as RM_CONDITION', 'Rooms.RM_TP as RM_TP',
                'RoomsTypes.SUGG_PRICE as SUGG_PRICE')
            ->get();
        return Response::json($roomAndRoomType);
    }

    public function getAllRoomTypes(){
        $allRoomTypes = DB::table('RoomsTypes')
            ->get();
        return Response::json($allRoomTypes);
    }

    public function showRoom(){
        $roomShow = DB::table('Rooms')
            ->leftjoin('RoomTran', 'Rooms.RM_TRAN_ID','=','RoomTran.RM_TRAN_ID')
            ->leftjoin('RoomTran as Conn', 'Conn.RM_TRAN_ID','=','RoomTran.CONN_RM_TRAN_ID')
            ->select('Rooms.RM_ID as RM_ID','Rooms.RM_TRAN_ID as RM_TRAN_ID' ,
                'Rooms.RM_CONDITION as RM_CONDITION', 'Rooms.RM_TP as RM_TP',
                'Rooms.FLOOR_ID as FLOOR_ID','Rooms.FLOOR as FLOOR',
                'RoomTran.CHECK_IN_DT as CHECK_IN_DT','RoomTran.CHECK_OT_DT as CHECK_OT_DT',
                'RoomTran.RM_AVE_PRCE as RM_AVE_PRCE','RoomTran.DPST_RMN as DPST_RMN',
                'RoomTran.RSRV_PAID_DYS as RSRV_PAID_DYS','RoomTran.CONN_RM_TRAN_ID as CONN_RM_TRAN_ID',
                'RoomTran.LEAVE_TM as LEAVE_TM','RoomTran.IN_TM as IN_TM','RoomTran.CHECK_TP as CHECK_TP',
                'RoomTran.TMP_PLAN_ID as TMP_PLAN_ID','RoomTran.TREATY_ID as TREATY_ID','RoomTran.WKC_TSTMP as WKC_TSTMP',
                'RoomTran.MEM_ID as MEM_ID',DB::raw('COALESCE(Conn.DPST_RMN,RoomTran.DPST_RMN) as CONN_DPST_RMN'))
            ->orderBy('Rooms.RM_ID', 'ASC')
            ->get();

        $cusShow = DB::table('Rooms')
            ->join('Customers','Customers.RM_TRAN_ID','=','Rooms.RM_TRAN_ID')
            ->select('Customers.SSN as SSN','Customers.CUS_NAME as CUS_NAME',
                'Customers.MEM_ID as MEM_ID','Customers.TREATY_ID as TREATY_ID',
                'Customers.PHONE as PHONE','Customers.DOB as DOB','Customers.ADDRSS as ADDRSS',
                'Customers.POINTS as POINTS','Customers.MEM_TP as MEM_TP',
                'Customers.RMRK as RMRK','Customers.RM_ID as RM_ID')
            ->orderBy('Rooms.RM_ID', 'ASC')
            ->get();
        $cLen = count($cusShow);
        $rLen = count($roomShow);
        $cP = 0;
        $rP = 0;
        while($rP < $rLen && $cP < $cLen){
            $rID = $roomShow[$rP]->RM_ID;
            $cID = $cusShow[$cP]->RM_ID;
            if( $rID == $cID  ){
                if(!property_exists($roomShow[$rP], 'customers')){
                    $roomShow[$rP]->customers = array();
                }
                array_push($roomShow[$rP]->customers, $cusShow[$cP]);
                $cP++;
            }else if(  $rID > $cID ){
                $cP++;
            }else{
                $rP++;
            }
        }
        return Response::json($roomShow);
    }

    public function showOccupied($RM_TRAN_ID){
        $occupiedShow = DB::table('Customers')
            ->where('Customers.RM_TRAN_ID',$RM_TRAN_ID)
            ->get();
        return $occupiedShow;
    }

    public function showEmpty($RM_TP){
        $emptyShow = DB::table('RoomsTypes')
            ->where('RoomsTypes.RM_TP','=',$RM_TP)
            ->get();
        return Response::json($emptyShow);
    }

    public function showSoldOut($checkInDt, $checkOtDt){
        $soldOutShow = DB::table('RoomOccupation')
            ->leftjoin('RoomsTypes', 'RoomsTypes.RM_TP', '=', 'RoomOccupation.RM_TP')
            ->whereRaw("RoomOccupation.DATE between '" . $checkInDt . "' and '". $checkOtDt."'")
            ->select(DB::raw('RoomOccupation.DATE as DATE, RoomOccupation.RM_TP as RM_TP, RoomOccupation.RESV_QUAN as
            RESV_QUAN, RoomOccupation.CHECK_QUAN as CHECK_QUAN, RoomsTypes.RM_QUAN as RM_QUAN'))
            ->orderBy('RoomOccupation.DATE')
            ->get();
        return Response::json($soldOutShow);
    }

    public function showRoomQuan(){
        $roomQuanShow = DB::table('RoomsTypes')
            ->select('RoomsTypes.RM_TP as RM_TP','RoomsTypes.RM_QUAN as RM_QUAN')
            ->get();
        return Response::json($roomQuanShow);
    }

    public function showRoomUnAvail(){
        $roomUnAvail = DB::table('Rooms')
            ->leftjoin('RoomTran', 'Rooms.RM_TRAN_ID','=','RoomTran.RM_TRAN_ID')
            ->leftjoin('RoomsTypes', 'RoomTypes.RM_TP', '=', 'Rooms.RM_TP')
            ->select('Rooms.RM_ID as RM_ID','Rooms.RM_TRAN_ID as RM_TRAN_ID' ,
                'Rooms.RM_CONDITION as RM_CONDITION', 'Rooms.RM_TP as RM_TP',
                'RoomTran.CHECK_IN_DT as CHECK_IN_DT','RoomTran.CHECK_OT_DT as CHECK_OT_DT'
                ,'RoomsTypes.SUGG_PRICE as SUGG_PRICE')
            ->get();
        return Response::json($roomUnAvail);
    }

    public function change2Mending($RM_ID){
        DB::table('Rooms')->where('RM_ID',$RM_ID)
            ->update(array(
                "RM_CONDITION" => "维修"
            ));
    }

    public function change2Mended($RM_ID){
        DB::table('Rooms')->where('RM_ID',$RM_ID)
            ->update(array(
                "RM_CONDITION" => "脏房"
            ));
    }

    public function Change2Cleaned($RM_ID){
        DB::table('Rooms')->where('RM_ID',$RM_ID)
            ->update(array(
                "RM_CONDITION" => "空房"
            ));
    }

    public function showAccounting($RM_TRAN_ID){
        $acctInfo = array();
        $acctInfo[0] = DB::table('RoomDepositAcct')->where('RM_TRAN_ID',$RM_TRAN_ID)
            ->select('RoomDepositAcct.RM_TRAN_ID as RM_TRAN_ID','RoomDepositAcct.DEPO_AMNT as DEPO_AMNT',
                'RoomDepositAcct.PAY_METHOD as PAY_METHOD', 'RoomDepositAcct.DEPO_TSTMP as DEPO_TSTMP')
            ->get();
        $acctInfo[1]=  DB::table('RoomAcct')->where('RM_TRAN_ID',$RM_TRAN_ID)
            ->select('RoomAcct.RM_TRAN_ID as RM_TRAN_ID','RoomAcct.RM_PAY_AMNT as RM_PAY_AMNT',
                'RoomAcct.RM_PAY_METHOD as RM_PAY_METHOD', 'RoomAcct.BILL_TSTMP as BILL_TSTMP')
            ->get();
        $acctInfo[2] = DB::table('RoomStoreTran')->where('RM_TRAN_ID',$RM_TRAN_ID)
            ->leftjoin("StoreTransaction",'RoomStoreTran.STR_TRAN_ID', '=', 'StoreTransaction.STR_TRAN_ID')
            ->select('StoreTransaction.STR_PAY_METHOD as STR_PAY_METHOD',
                'StoreTransaction.STR_PAY_AMNT as STR_PAY_AMNT', 'StoreTransaction.STR_TRAN_TSTAMP as STR_TRAN_TSTAMP',
                'RoomStoreTran.RM_TRAN_ID as RM_TRAN_ID','RoomStoreTran.RM_ID as RM_ID')
            ->get();

        return Response::json($acctInfo);
    }

    public function getConnect($RM_TRAN_ID){
        $ConnectSelf = DB::table('RoomTran')
            ->where("RM_TRAN_ID",$RM_TRAN_ID)
            ->pluck('CONN_RM_TRAN_ID');
        if ($ConnectSelf == null){
            return json_encode($ConnectSelf);
        }else{
            $connectRooms = DB::table('RoomTran')
                ->where("CONN_RM_TRAN_ID",$ConnectSelf)
                ->join("Rooms","Rooms.RM_TRAN_ID","=","RoomTran.RM_TRAN_ID")
                ->select('RoomTran.RM_ID as RM_ID','RoomTran.RM_TRAN_ID as RM_TRAN_ID' ,
                    'RoomTran.CONN_RM_TRAN_ID as CONN_RM_TRAN_ID')
                ->get();
            return json_encode($connectRooms);
        }
    }
}