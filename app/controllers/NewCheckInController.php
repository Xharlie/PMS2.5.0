<?php


class NewCheckInController extends BaseController{

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
            ->leftjoin('RoomsTypes', 'RoomsTypes.RM_TP', '=', 'Rooms.RM_TP')
            ->select('Rooms.RM_ID as RM_ID','Rooms.RM_TRAN_ID as RM_TRAN_ID' ,
                'Rooms.RM_CONDITION as RM_CONDITION', 'Rooms.RM_TP as RM_TP',
                'RoomTran.CHECK_IN_DT as CHECK_IN_DT','RoomTran.CHECK_OT_DT as CHECK_OT_DT'
                ,'RoomsTypes.SUGG_PRICE as SUGG_PRICE')
            ->get();
        return Response::json($roomUnAvail);
    }

    public function showHistoCustomer($SSN){
        $histoCustomer = DB::table('HistoryCustm')
            ->where("HistoryCustm.SSN",$SSN)
            ->select('HistoryCustm.NM as NM','HistoryCustm.MEM_ID as MEM_ID'
                ,'HistoryCustm.TIMES as TIMES')
            ->get();
        return Response::json($histoCustomer);
    }

    public function showMemberBySSN($SSN){
        $memberBySSN = DB::table('MemberInfo')
            ->where("MemberInfo.SSN",$SSN)
            ->leftjoin('MemberType','MemberInfo.MEM_TP','=','MemberType.MEM_TP')
            ->select('MemberInfo.MEM_TP as MEM_TP','MemberInfo.MEM_ID as MEM_ID'
                ,'MemberType.DISCOUNT_RATE as DISCOUNT_RATE','MemberInfo.SSN as SSN'
                ,'MemberInfo.MEM_NM as MEM_NM')
            ->get();
        return Response::json($memberBySSN);
    }

    public function showMemberByID($MEM_ID){
        $memberByID = DB::table('MemberInfo')
            ->where("MemberInfo.MEM_ID",$MEM_ID)
            ->leftjoin('MemberType','MemberInfo.MEM_TP','=','MemberType.MEM_TP')
            ->select('MemberInfo.MEM_TP as MEM_TP','MemberInfo.MEM_ID as MEM_ID'
                ,'MemberType.DISCOUNT_RATE as DISCOUNT_RATE','MemberInfo.SSN as SSN'
                ,'MemberInfo.MEM_NM as MEM_NM','MemberInfo.PROV as PROV',
                'MemberInfo.PHONE as PHONE','MemberInfo.POINTS as POINTS')
            ->get();
        return Response::json($memberByID);
    }

    public function showTreatyByID($TREATY_ID){
        $treatyByID = DB::table('Treaty')
            ->where("Treaty.TREATY_ID",$TREATY_ID)
            ->select('Treaty.TREATY_ID as TREATY_ID','Treaty.CORP_NM as CORP_NM',
                'Treaty.TREATY_TP as TREATY_TP','Treaty.CORP_PHONE as CORP_PHONE',
                'Treaty.CONTACT_NM as CONTACT_NM','Treaty.CONTACT_PHONE as CONTACT_PHONE',
                'Treaty.RMARK as RMARK','Treaty.DISCOUNT as DISCOUNT')
            ->get();
        return Response::json($treatyByID);
    }

    public function showTreatyByCorp($CORP_NM){
        $treatyByCORP = DB::table('Treaty')
            ->where("Treaty.CORP_NM",'LIKE','%'.$CORP_NM.'%')
            ->select('Treaty.TREATY_ID as TREATY_ID','Treaty.CORP_NM as CORP_NM',
                'Treaty.TREATY_TP as TREATY_TP','Treaty.CORP_PHONE as CORP_PHONE',
                'Treaty.CONTACT_NM as CONTACT_NM','Treaty.CONTACT_PHONE as CONTACT_PHONE',
                'Treaty.RMARK as RMARK','Treaty.DISCOUNT as DISCOUNT')
            ->get();
        return Response::json($treatyByCORP);
    }
    public function submit(){
        $info = Input::all();
      //  $RoomTranTotal = array();
      //  $RoomsTotal = array();
        $CustomerArray = array();
        $CONN_RM_TRAN_ID = "";
        foreach($info as $room){

            $RoomTranArray = array(
                "RM_ID" => $room["roomSelect"],
                "CHECK_IN_DT" => $room["CHECK_IN_DT"],
                "CHECK_OT_DT" => $room["CHECK_OT_DT"],
            //    "RSRV_PAID_DYS" => $room,
                "RM_AVE_PRCE" => $room["finalPrice"],
                "DPST_RMN" => $room["deposit"],
                "CHECK_TP" => $this->roomSourceWord($room["roomSource"]),
            );
            if($CONN_RM_TRAN_ID != ""){
                $RoomTranArray["CONN_RM_TRAN_ID"] = $CONN_RM_TRAN_ID;
            }
            if($room["roomSource"]==1){
                $RoomTranArray["MEM_ID"] = $room["MEM_ID"];
            }elseif($room["roomSource"]==3){
                $RoomTranArray["TREATY_ID"]= $room["TREATY_ID"];
            }
           // array_push($RoomTranTotal,$RoomTranArray);
            $RM_TRAN = DB::table('RoomTran')->insertGetId($RoomTranArray);
            if ($room["roomSelect"] == $room["Conn_RM_ID"]){
                $CONN_RM_TRAN_ID = $RM_TRAN;
                DB::table('RoomTran')->where('RM_TRAN_ID',$RM_TRAN)
                                     ->update(array("CONN_RM_TRAN_ID" => $CONN_RM_TRAN_ID));
            }

            $RoomsArray = array(
                "RM_TRAN_ID" => $RM_TRAN,
                "RM_CONDITION" => "Occupied"
            );
            DB::table('Rooms')->where('RM_ID',$room["roomSelect"])
                              ->update($RoomsArray);


            foreach($room["GuestsInfo"]  as $gust){
                $customer = array(
                    "SSN" => $gust["SSNinput"],
                    "CUS_NAME" => $gust["NameInput"],
                    "RM_ID" => $room["roomSelect"],
                    "RM_TRAN_ID" => $RM_TRAN,
                    "PROVNCE" =>  $gust["Province"],
                    "PHONE"=>$gust["Phone"],
                    "RMRK" =>$gust["RemarkInput"],
                    "MEM_TP"=>$gust["MEM_TP"]
                );
                if($gust["MemberId"] != ""){
                    $customer["MEM_ID"]=$gust["MemberId"];
                }
                if($gust["Points"] != ""){
                    $customer["POINTS"]=$gust["Points"];
                }
                array_push($CustomerArray,$customer);
            }
            $this->roomDepositIn($RM_TRAN,$room['deposit'],$room['payMethod']);
        }
        DB::table('Customers')->insert($CustomerArray);
        return Response::json($info);
    }

    public function roomSourceWord($num){
        switch ($num){
            case "0":
                return "散客";
                break;
            case "1":
                return "会员卡";
                break;
            case "2":
                return "普通预定";
                break;
            case "3":
                return "协议";
                break;
            default:
                return "未知";
        }
    }

    public function roomDepositIn($RM_TRAN_ID,$Amount,$PayMethod){
        $date = new DateTime();
        $DepoArray = array(
            "RM_TRAN_ID" => $RM_TRAN_ID,
            "DEPO_AMNT"=>$Amount,
            "PAY_METHOD" => $PayMethod,
            "DEPO_TSTAMP"=> $date
        );
        $RM_DEPO_ID = DB::table('RoomDepositAcct')->insertGetId($DepoArray);
    }
    /*
roomSelect:room.roomSelect, roomType:room.roomType, CHECK_IN_DT:$scope.dateFormat(room.CHECK_IN_DT)
,CHECK_OT_DT:$scope.dateFormat(room.CHECK_OT_DT),finalPrice: room.finalPrice, roomSource:room.roomSource,
GuestsInfo: room.GuestsInfo
      <option value="0">散客</option>>
                <option value="1">会员卡</option>
                <option value="2">普通预定</option>
                <option value="3">协议</option>


    */

}