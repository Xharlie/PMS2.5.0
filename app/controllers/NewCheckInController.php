<?php


class NewCheckInController extends BaseController{

    /*** getClicked room info for single walk in check in    ***/
    public function getSingleRoomInfo($RM_ID){
        $singleRoomInfo = DB::table('Rooms')
            ->join('RoomsTypes', 'RoomsTypes.RM_TP', '=', 'Rooms.RM_TP')
            ->where("Rooms.RM_ID",$RM_ID)
            ->select('Rooms.RM_ID as RM_ID','Rooms.RM_CONDITION as RM_CONDITION', 'Rooms.RM_TP as RM_TP',
                'RoomsTypes.SUGG_PRICE as SUGG_PRICE')
            ->get();
        return Response::json($singleRoomInfo);
    }

    public function getRoomInfo(){
        $RoomInfo = DB::table('Rooms')
            ->join('RoomsTypes', 'RoomsTypes.RM_TP', '=', 'Rooms.RM_TP')
            ->select('Rooms.RM_ID as RM_ID','Rooms.RM_CONDITION as RM_CONDITION', 'Rooms.RM_TP as RM_TP',
                'RoomsTypes.SUGG_PRICE as SUGG_PRICE')
            ->get();
        return Response::json($RoomInfo);
    }

    public function getRMInfoWithAvail(){
        $CHECK_IN_DT = Input::get('CHECK_IN_DT');
        $CHECK_OT_DT = Input::get('CHECK_OT_DT');
        $RoomInfo = DB::table('Rooms')
            ->join('RoomsTypes', 'RoomsTypes.RM_TP','=','Rooms.RM_TP')
            ->leftjoin(DB::raw("(select RoomOccupation.RM_TP AS RM_TP, MAX(RoomOccupation.RESV_QUAN + RoomOccupation.CHECK_QUAN) AS QUAN from RoomOccupation where RoomOccupation.DATE between '$CHECK_IN_DT' AND '$CHECK_OT_DT' group by RM_TP)  mostOccupied"),
                function($join){
                    $join->on('mostOccupied.RM_TP', '=', 'Rooms.RM_TP');
                }
            )
            ->select(DB::raw('Rooms.RM_ID as RM_ID,Rooms.RM_CONDITION as RM_CONDITION,Rooms.RM_TP as RM_TP,RoomsTypes.SUGG_PRICE as SUGG_PRICE,IFNULL(RoomsTypes.RM_QUAN - mostOccupied.QUAN,RoomsTypes.RM_QUAN) AS AVAIL_QUAN '))
            ->get();
        return Response::json($RoomInfo);
    }

    public function searchMembers(){
        $comparer = Input::get('comparer');
        $columns = Input::get('columns');
        $results = [];
        $rawFilter = "";
        foreach($columns as $column){
            $rawFilter = $rawFilter." or `".$column."` like "."'".$comparer."'";
        }
        $rawFilter = substr($rawFilter, 4); // cut first or and the following space
        $members = DB::table('MemberInfo')
            ->whereRaw($rawFilter)
            ->leftjoin('MemberType','MemberInfo.MEM_TP','=','MemberType.MEM_TP')
            ->select('MemberInfo.MEM_TP as MEM_TP','MemberInfo.MEM_ID as MEM_ID'
                ,'MemberType.DISCOUNT_RATE as DISCOUNT_RATE','MemberInfo.SSN as SSN'
                ,'MemberInfo.MEM_NM as MEM_NM','MemberInfo.PROV as PROV',
                'MemberInfo.PHONE as PHONE','MemberInfo.POINTS as POINTS',
                'MemberInfo.TIMES as TIMES')
            ->get();
        return Response::json($members);
    }


    public function searchTreaties(){
        $comparer = Input::get('comparer');
        $columns = Input::get('columns');
        $results = [];
        $rawFilter = "";
        foreach($columns as $column){
            $rawFilter = $rawFilter." or `".$column."` like "."'".$comparer."'";
        }
        $rawFilter = substr($rawFilter, 4); // cut first or and the following space

        $treaties = DB::table('Treaty')
            ->whereRaw($rawFilter)
            ->select('Treaty.TREATY_ID as TREATY_ID','Treaty.CORP_NM as CORP_NM',
                'Treaty.TREATY_TP as TREATY_TP','Treaty.CORP_PHONE as CORP_PHONE',
                'Treaty.CONTACT_NM as CONTACT_NM','Treaty.CONTACT_PHONE as CONTACT_PHONE',
                'Treaty.RMARK as RMARK','Treaty.DISCOUNT as DISCOUNT')
            ->get();
        return Response::json($treaties);
    }

    public function showHistoCustomer($SSN){
        $histoCustomer = DB::table('HistoryCustm')
            ->where("HistoryCustm.SSN",$SSN)
            ->select('HistoryCustm.NM as NM','HistoryCustm.MEM_ID as MEM_ID'
                ,'HistoryCustm.TIMES as TIMES')
            ->get();
        return Response::json($histoCustomer);
    }



/* obselete

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
                ,'RoomsTypes.SUGG_PRICE as SUGG_PRICE','RoomsTypes.CUS_QUAN as CUS_QUAN')
            ->get();
        return Response::json($roomUnAvail);
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


**************/
    public function submitModify(){
        $info = Input::all();
        $cusArray = array();
        $deleteArray = array();
        foreach($info as $room){
            $RoomTranArray = array(
                "CHECK_IN_DT" => $room["CHECK_IN_DT"],
                "CHECK_OT_DT" => $room["CHECK_OT_DT"]
            );
            $RMTRANID = DB::table('Rooms')
                ->where('Rooms.RM_ID',$room["roomSelect"])
                ->take(1)
                ->lists('RM_TRAN_ID');
            $RM_TRAN_ID = $RMTRANID[0];
            DB::table('RoomTran')->where('RM_TRAN_ID',$RM_TRAN_ID)
                ->update($RoomTranArray);

            array_push($deleteArray, $RM_TRAN_ID);
            foreach($room["GuestsInfo"] as $guest){
                $guestArray = array(
                    "SSN" => $guest["SSN"],
                    "CUS_NAME" => $guest["NameInput"],
                    "RM_ID" => $room["roomSelect"],
                    "RM_TRAN_ID" => $RM_TRAN_ID,
//                    "PROVNCE" =>  $guest["Province"],
                    "PHONE"=>$guest["Phone"],
                    "RMRK" =>$guest["RemarkInput"],
                    "MEM_TP"=>$guest["MEM_TP"]
                );
                if($guest["MemberId"] != ""){
                    $guestArray["MEM_ID"]=$guest["MemberId"];
                }else{
                    $guestArray["MEM_ID"]=null;
                }
                if($guest["Points"] != ""){
                    $guestArray["POINTS"]=$guest["Points"];
                }else{
                    $guestArray["POINTS"]=null;
                }
                array_push($cusArray, $guestArray);
            }
        }

        DB::table('Customers')->whereIn('RM_TRAN_ID', $deleteArray)->delete();
        DB::table('Customers')->insert($cusArray);
    }



    public function submitCheckIn(){
//        date_default_timezone_set('America/New_York');
        $info = Input::get('SubmitInfo');
        $reserve = Input::get('RESV');
        $unfilled = Input::get('unfilled');
      //  $RoomTranTotal = array();
      //  $RoomsTotal = array();
        $CustomerArray = array();
        $CONN_RM_TRAN_ID = "";
        $DepositArray = array();

        foreach($info as $room){
            /*------------------------- insert to roomTran and get its RM_TRAN_ID---------------------------------*/
            $RM_TRAN = $this->roomTranIn($room,$CONN_RM_TRAN_ID);
            /*------------------------- insert to rooms     ----    --------------------------------*/
            $this->roomsIn($room["roomSelect"],$RM_TRAN);
           /*------------------------- insert to customers array  and wait to insert in---------------------------------*/
            $this->customerIn($room,$CustomerArray,$RM_TRAN);
            /*------------------------- insert to roomDesposit and wait to insert in  ---------------------------------*/
            if ($room["CONN_RM_ID"] !="" || $room["CONN_RM_ID"] == $room["roomSelect"]){
                $this->roomDepositIn($room,$RM_TRAN,$DepositArray);
            }
            /*------------------------- change Room Availablilty Check Quan---------------------------------*/
            $this->roomOccupationCheckChange($room);
        }
        DB::table('RoomDepositAcct')->insert($DepositArray);
        DB::table('Customers')->insert($CustomerArray);
        /*--------------------------------------- delete Room Availablilty Resv Quan------------------------------------------*/
        if ($reserve !="null"){
            $this->roomOccupationResvChange($room,$reserve,$unfilled);
        }
        /*--------------------------------------- change Resv Quan and status ------------------------------------------*/

         $this->reservRoomstatus($room,$reserve,$unfilled);
//        DB::update("update ReservationRoom set CHECKED = ? where RESV_ID = ? and RM_TP = ?",
//            array('T',$reserveId,$RoomNumArray[0]) );



        return Response::json("");

    }


//    public function roomSourceWord($num){
//        switch ($num){
//            case "0":
//                return "散客";
//                break;
//            case "1":
//                return "会员卡";
//                break;
//            case "2":
//                return "协议";
//                break;
//            case "3":
//                return "活动码";
//                break;
//            default:
//                return "未知";
//        }
//    }


    public function roomTranIn(&$room,&$CONN_RM_TRAN_ID){
        $RoomTranArray = array(
            "RM_ID" => $room["roomSelect"],
            "CHECK_IN_DT" => $room["CHECK_IN_DT"],
            "CHECK_OT_DT" => $room["CHECK_OT_DT"],
            "RM_AVE_PRCE" => $room["finalPrice"],
            "DPST_RMN" => $room["pay"]["paymentRequest"],
            //    "RSRV_PAID_DYS" => $room,
//                "DPST_FIXED" => $room["fixedDeposit"],
            "CHECK_TP" => $room["roomSource"],
            "LEAVE_TM" => (new DateTime($room["leaveTime"]))->format('H:i:s')
//                "CARDS_NUM" => $room["CARDS_NUM"]
        );
        if($CONN_RM_TRAN_ID != ""){
            $RoomTranArray["CONN_RM_TRAN_ID"] = $CONN_RM_TRAN_ID;
        }
        if($room["roomSource"]=="会员"){
            $RoomTranArray["MEM_ID"] = $room["MEM_ID"];
        }elseif($room["roomSource"]=="协议"){
            $RoomTranArray["TREATY_ID"]= $room["TREATY_ID"];
        }
        if($room["TMP_PLAN_ID"]!=''){
            $RoomTranArray["TMP_PLAN_ID"] = $room["TMP_PLAN_ID"];
        }
        // array_push($RoomTranTotal,$RoomTranArray);
        $RM_TRAN = DB::table('RoomTran')->insertGetId($RoomTranArray);
        if ($room["roomSelect"] == $room["CONN_RM_ID"]){
            $CONN_RM_TRAN_ID = $RM_TRAN;
            DB::table('RoomTran')->where('RM_TRAN_ID',$RM_TRAN)
                ->update(array("CONN_RM_TRAN_ID" => $CONN_RM_TRAN_ID));
        }
        return $RM_TRAN;
    }


    public function roomsIn($roomSelect,$RM_TRAN){
        $RoomsArray = array(
            "RM_TRAN_ID" => $RM_TRAN,
            "RM_CONDITION" => "有人"
        );
        DB::table('Rooms')->where('RM_ID',$roomSelect)->update($RoomsArray);
    }

    public function customerIn(&$room,&$CustomerArray,&$RM_TRAN){
        foreach($room["GuestsInfo"]  as $gust){
            $customer = array(
                "SSN" => $gust["SSN"],
                "CUS_NAME" => $gust["Name"],
                "RM_ID" => $room["roomSelect"],
                "RM_TRAN_ID" => $RM_TRAN,
//                "PROVNCE" =>  $gust["Province"],
                "PHONE"=>$gust["Phone"],
                "RMRK" =>$gust["RemarkInput"],
                "MEM_TP"=>$gust["MEM_TP"],
                "MEM_ID"=>$gust["MemberId"],
                "POINTS"=>$gust["Points"]
            );
            if($gust["MemberId"] == ""){
                $customer["MEM_ID"]= null;
            }
            if($gust["Points"] == ""){
                $customer["POINTS"] = null;
            }
            array_push($CustomerArray,$customer);
        }
    }

    public function roomDepositIn(&$room,&$RM_TRAN,&$DepositArray){
        $date = new DateTime();
        foreach ($room["pay"]["payByMethods"] as $payByMethod){
            $depo = array(
                "RM_TRAN_ID" => $RM_TRAN,
                "DEPO_AMNT"=>$payByMethod["payAmount"],
                "PAY_METHOD" => $payByMethod["payMethod"],
                "DEPO_TSTMP"=> $date
            );
            array_push($DepositArray,$depo);
        }
    }

    public function roomOccupationCheckChange(&$room){
        $begin = new DateTime($room["CHECK_IN_DT"]);
        $end = new DateTime($room["CHECK_OT_DT"]);
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        foreach ( $period as $dt ){
            $dtMatch = $dt->format( "Y-m-d" );
            $has = DB::table('RoomOccupation')
                ->where('RoomOccupation.DATE','=',$dtMatch)
                ->where('RoomOccupation.RM_TP','=',$room["roomType"])
                ->get();
            $hasArray = json_decode(json_encode($has));
            if(count($hasArray) >= 1){
                DB::update('update RoomOccupation set CHECK_QUAN =CHECK_QUAN + 1 where DATE = ? and RM_TP = ?',
                    array($dtMatch,$room["roomType"]) );
            }else{
                $OccArray = array(
                    "DATE" => $dtMatch,
                    "RM_TP"=>$room["roomType"],
                    "RESV_QUAN"=> 0,
                    "CHECK_QUAN" => 1
                );
                DB::table('RoomOccupation')->insert($OccArray);
            }
        }
    }
    public function roomOccupationResvChange(&$room, &$reserve, &$unfilled){
        foreach ( $unfilled as $RM_TP => $value ){
                DB::update('update RoomOccupation set RESV_QUAN = RESV_QUAN - ? where RM_TP = ? and DATE between ? and ?  ',
                    array((int)$value["checked"],$RM_TP,$reserve["CHECK_IN_DT"], $reserve["CHECK_OT_DT"]) );
        }
    }

    public function  reservRoomstatus(&$room, &$reserve, &$unfilled){
        foreach ( $unfilled as $RM_TP => $value ){

            /***********    update or create  checked status rows    ***********/
            $has = DB::table('ReservationRoom')
                ->where('ReservationRoom.RESV_ID','=',$reserve["RESV_ID"])
                ->where('ReservationRoom.RM_TP','=',$RM_TP)
                ->where('ReservationRoom.STATUS','=','Filled')
                ->get();
            $hasArray = json_decode(json_encode($has));
            if(count($hasArray) >= 1){
                DB::update("update ReservationRoom set RM_QUAN = RM_QUAN + ? where STATUS = 'Filled' and RM_TP = ? and RESV_ID = ? ",
                    array($value["checked"], $RM_TP,$reserve["RESV_ID"]) );
            }else{
                $RESV_DAY_PAY_RAW = DB::table('ReservationRoom')
                                ->where('RESV_ID','=', $reserve["RESV_ID"])
                                ->where('RM_TP', '=', $RM_TP)
                                ->pluck('RESV_DAY_PAY');

                DB::table('ReservationRoom')->insert(
                    array('RESV_ID' => $reserve["RESV_ID"],
                          'RM_TP' => $RM_TP,
                          'RM_QUAN' => $value["checked"],
                          'RESV_DAY_PAY' => $RESV_DAY_PAY_RAW,
                          'STATUS' => 'Filled')
                );
            }
            /***********    update or delete  unchecked status rows    ***********/
            if($value["unchecked"] == 0){
                /*    all have checked    */
                DB::table('ReservationRoom')
                    ->where('RM_TP','=', $RM_TP)
                    ->where('RESV_ID','=', $reserve["RESV_ID"])
                    ->whereRaw("STATUS NOT IN ('Cancelled','Filled')")
                    ->delete();
            }else{
                DB::update("update ReservationRoom set RM_QUAN = ? where RM_TP = ? and RESV_ID = ? and STATUS not in ('Cancelled','Filled') ",
                    array($value["unchecked"], $RM_TP,$reserve["RESV_ID"]) );
            }

        }
    }


    public function showCusInRoom($RM_ID){
        $occupiedShow = DB::table('Customers')
            ->join('Rooms', function($join) use ($RM_ID)
            {
                $join->where('Rooms.RM_ID', '=', $RM_ID);
                $join->on('Rooms.RM_TRAN_ID', '=', 'Customers.RM_TRAN_ID');
            })
            ->get();
        return Response::json($occupiedShow);
    }


}