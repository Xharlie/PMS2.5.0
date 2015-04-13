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
            ->leftjoin('RoomsTypes', 'RoomsTypes.RM_TP', '=', 'Rooms.RM_TP')
            ->select('Rooms.RM_ID as RM_ID','Rooms.RM_CONDITION as RM_CONDITION', 'Rooms.RM_TP as RM_TP',
                'RoomsTypes.SUGG_PRICE as SUGG_PRICE')
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
//
//    public function showHistoCustomer($SSN){
//        $histoCustomer = DB::table('HistoryCustm')
//            ->where("HistoryCustm.SSN",$SSN)
//            ->select('HistoryCustm.NM as NM','HistoryCustm.MEM_ID as MEM_ID'
//                ,'HistoryCustm.TIMES as TIMES')
//            ->get();
//        return Response::json($histoCustomer);
//    }



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
        $info = Input::get('SubmitInfo');
        $RM_TRAN_ID = Input::get('RM_TRAN_ID');
        $ori_RM_TRAN_ID = $RM_TRAN_ID;

        $initialString = Input::get('initialString');
        $moneyInvolved = Input::get('moneyInvolved');
        $CustomerArray = array();
        $DepositArray = array();
        try {
            DB::beginTransaction();   //////  Important !! TRANSACTION Begin!!!
            foreach($info as $room){
                /*-------------------------  get original RoomTran                    -------------------------------*/
                $oriRec = DB::table('RoomTran')
                    ->join('Rooms', function($join) use ($RM_TRAN_ID)
                    {
                        $join->where('Rooms.RM_TRAN_ID', '=', $RM_TRAN_ID);
                        $join->on('Rooms.RM_TRAN_ID', '=', 'RoomTran.RM_TRAN_ID');
                    })
                    ->select("Rooms.RM_TRAN_ID as RM_TRAN_ID","Rooms.RM_ID as RM_ID","Rooms.RM_TP as RM_TP",
                            "RoomTran.CHECK_IN_DT as CHECK_IN_DT","RoomTran.DPST_RMN as DPST_RMN",
                             "RoomTran.CHECK_OT_DT as CHECK_OT_DT","RoomTran.CONN_RM_TRAN_ID as CONN_RM_TRAN_ID")
                    ->take(1)
                    ->get();
                $ori = $oriRec[0];

                $old_CONN_RM_TRAN_ID = $ori->CONN_RM_TRAN_ID;
                $new_CONN_RM_TRAN_ID = $old_CONN_RM_TRAN_ID ;

            /*-------------------------------------------  RoomTran    -----------------------------------------------*/
                    // 1 prepare RoomTran array no matter new or old
                $RoomTranArray = $this->roomTranPrepare($room,$ori->CONN_RM_TRAN_ID);
                    // if add more money,
                if ($moneyInvolved){
                    $RoomTranArray["DPST_RMN"] = $RoomTranArray["DPST_RMN"] + $ori->DPST_RMN;
                }
                    // 2 not  within one day  nor temp   -------------------------------*/
                if($ori->CHECK_IN_DT != (new DateTime())->format('Y-m-d')){    // if today just check in, or even temp room
                        // 2.1. update the original roomTran to terminate
                    DB::table('RoomTran')
                        ->where('RM_TRAN_ID',$ori_RM_TRAN_ID)
                        ->update(array("CHECK_OT_DT"=>$room["CHECK_IN_DT"],
                                "FILLED" => "T",
                                "DPST_RMN"=>0)
                          );
                        // 2.2  insert new roomTran and change to the new RM_TRAN_ID to be used everywhere, RoomTran should be changed later
                    $RM_TRAN_ID = $this->roomTranIn($room,$RoomTranArray,$ori->CONN_RM_TRAN_ID);
                        // 2.3  if ori is a master room, change all connected room's master to new rm_tran_id
                    if($ori->CONN_RM_TRAN_ID == $ori_RM_TRAN_ID){
                        DB::table('RoomTran')
                            ->where('CONN_RM_TRAN_ID',$ori_RM_TRAN_ID)
                            ->update(array('CONN_RM_TRAN_ID'=> $RM_TRAN_ID)
                            );
                        $new_CONN_RM_TRAN_ID = $RM_TRAN_ID;
                    }
                    // 3 within one day or temp
                }else{
                    // if just today's then change the record
                    DB::table('RoomTran')
                        ->where('RM_TRAN_ID',$RM_TRAN_ID)
                        ->update($RoomTranArray);
                }
            /*---------------------- account attribute to the $new_RM_TRAN_ID */
                $new_TKN_RM_TRAN_ID = $RM_TRAN_ID; //
                if($ori->CONN_RM_TRAN_ID != null){
                    $new_TKN_RM_TRAN_ID = $old_CONN_RM_TRAN_ID;
                }
                $old_TKN_RM_TRAN_ID = $ori_RM_TRAN_ID; //
                if($ori->CONN_RM_TRAN_ID != null){
                    $old_TKN_RM_TRAN_ID = $new_CONN_RM_TRAN_ID;
                }

            /*-------------------------------------------  Rooms    -----------------------------------------------*/
                // 1-------------------------     if RM_ID has been changed,cover both RM_TRAN_ID changed or not
                if($ori->RM_ID!=$room["roomSelect"]){
                    // update old room to empty
                    DB::table('Rooms')
                        ->where('RM_ID',$ori->RM_ID)
                        ->update(array("RM_TRAN_ID"=>null,
                            "RM_CONDITION"=>"空房"));
                    // update new room to be occupied
                    DB::table('Rooms')
                        ->where('RM_ID',$room["roomSelect"])
                        ->update(array("RM_TRAN_ID"=>$RM_TRAN_ID,
                            "RM_CONDITION"=>"有人"));
                // 2-------------------------     if RM_ID has not been changed, but RM_TRAN_ID changed
                }else if($ori_RM_TRAN_ID!=$RM_TRAN_ID){
                    DB::table('Rooms')
                        ->where('RM_ID',$room["roomSelect"])
                        ->update(array("RM_TRAN_ID"=>$RM_TRAN_ID));
                }
            /*-------------------------------------------  Customers    -----------------------------------------------*/
                // 1 if CUSTOMER has been changed prepare Customer    -------------------------------*/
                if($initialString="editRoom"){  //condition under development
                    // delete the old customers
                    DB::table('Customers')
                        ->where('RM_TRAN_ID',$ori_RM_TRAN_ID)
                        ->delete();
                    // prepare new customers
                    $this->customerIn($room,$CustomerArray,$RM_TRAN_ID);
                }
            /*-------------------------------------------  RoomDepositAcct    -----------------------------------------------*/
                // 1 CHANGE all deposit to that room to new room
                if($new_TKN_RM_TRAN_ID!=$old_TKN_RM_TRAN_ID){
                    DB::table('RoomDepositAcct')
                        ->where('RM_TRAN_ID',$old_TKN_RM_TRAN_ID)
                        ->update(array("RM_TRAN_ID"=>$new_TKN_RM_TRAN_ID));
                }
                    // 2. if moneyInvolved add them to DepositArray    cover new RM_TRAN_ID or not
                if ($moneyInvolved){
                    // perpare the roomDeposit
                    $RMRK="房屋修改";
                    $this->roomDepositIn($room,$new_TKN_RM_TRAN_ID,$DepositArray,$RMRK);
                }

            /*-------------------------------------------  RoomAcct   FILLED OR NOT  -----------------------------------------------*/
                if($new_TKN_RM_TRAN_ID!=$old_TKN_RM_TRAN_ID){
                    DB::table('RoomAcct')
                        ->where('TKN_RM_TRAN_ID',$old_TKN_RM_TRAN_ID)
                        ->update(array("TKN_RM_TRAN_ID"=>$new_TKN_RM_TRAN_ID));
                }

            /*-------------------------------------------  PenaltyAcct   FILLED OR NOT -----------------------------------------------*/
                if($new_TKN_RM_TRAN_ID!=$old_TKN_RM_TRAN_ID){
                    DB::table('PenaltyAcct')
                        ->where('TKN_RM_TRAN_ID',$old_TKN_RM_TRAN_ID)
                        ->update(array("TKN_RM_TRAN_ID"=>$new_TKN_RM_TRAN_ID));
                }
            /*-------------------------------------------  RoomStoreTran  FILLED OR NOT  -----------------------------------------------*/
                if($new_TKN_RM_TRAN_ID!=$old_TKN_RM_TRAN_ID){
                    DB::table('RoomStoreTran')
                        ->where('TKN_RM_TRAN_ID',$old_TKN_RM_TRAN_ID)
                        ->update(array("TKN_RM_TRAN_ID"=>$new_TKN_RM_TRAN_ID));
                }

            /*-------------------------     RoomOccupation       -------------------------------*/
                // delete the origin room to be occupied from today to the old expected check out date
                if($ori->RM_TP!=$room["roomType"] || $ori->CHECK_OT_DT!=$room["CHECK_OT_DT"]){
                    DB::update('update RoomOccupation set CHECK_QUAN = CHECK_QUAN - ? where RM_TP = ? and DATE >=  ? and DATE < ?  ',
                        array(1,$ori->RM_TP,$room["CHECK_IN_DT"], $ori->CHECK_OT_DT) );
                // increase the new room from today to new expected check out date
                    $this->roomOccupationCheckChange($room);
                }

            }

            // if CUSTOMER has been changed insert new them
            if($initialString="editRoom"){ //condition under development
                DB::table('Customers')->insert($CustomerArray);
            }
            // if money involved
            if ($moneyInvolved){
                DB::table('RoomDepositAcct')->insert($DepositArray);
            }
        }catch (Exception $e){
            DB::rollback();
            $message=($e->getLine())."&&".$e->getMessage();
            throw new Exception($message);
            return Response::json($message);
        }finally{
            DB::commit();
            return Response::json("success!");
        }
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
        try {
            DB::beginTransaction();   //////  Important !! TRANSACTION Begin!!!
            foreach($info as $room){
                /*------------------------- insert to roomTran and get its RM_TRAN_ID---------------------------------*/
                $RoomTranArray = $this->roomTranPrepare($room,$CONN_RM_TRAN_ID);
                $RM_TRAN_ID = $this->roomTranIn($room,$RoomTranArray,$CONN_RM_TRAN_ID);
                /*------------------------- insert to rooms     ----    --------------------------------*/
                $this->roomsIn($room["roomSelect"],$RM_TRAN_ID);
               /*------------------------- insert to customers array  and wait to insert in---------------------------------*/
                $this->customerIn($room,$CustomerArray,$RM_TRAN_ID);
                /*------------------------- insert to roomDesposit and wait to insert in  ---------------------------------*/
                if ($room["CONN_RM_ID"] =="" || $room["CONN_RM_ID"] == $room["roomSelect"]){  // if no master room or master room is this room
                    $RMRK = "存入押金";
                    $this->roomDepositIn($room,$RM_TRAN_ID,$DepositArray,$RMRK);
                }
                /*------------------------- change Room Availablilty Check Quan---------------------------------*/
                $this->roomOccupationCheckChange($room);
            }
            DB::table('RoomDepositAcct')->insert($DepositArray);
            DB::table('Customers')->insert($CustomerArray);
            /*--------------------------------------- delete Room Availablilty Resv Quan------------------------------------------*/
            if ($reserve != null){
                $this->roomOccupationResvChange($room,$reserve,$unfilled);

            /*--------------------------------------- change Resv Quan and status ------------------------------------------*/

                $this->reservRoomstatus($room,$reserve,$unfilled);
            }
    //        DB::update("update ReservationRoom set CHECKED = ? where RESV_ID = ? and RM_TP = ?",
    //            array('T',$reserveId,$RoomNumArray[0]) );
        }catch (Exception $e){
            DB::rollback();
            $message=($e->getLine())."&&".$e->getMessage();
            throw new Exception($message);
            return Response::json($message);
        }finally{
            DB::commit();
            return Response::json("success!");
        }

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


    public function roomTranIn(&$room,&$RoomTranArray,&$CONN_RM_TRAN_ID){
        // array_push($RoomTranTotal,$RoomTranArray);
        $RM_TRAN_ID = DB::table('RoomTran')->insertGetId($RoomTranArray);
        // if encounter the master room rm tran id(should be the first one to be encountered,
        // get the master room tran id and update previous ones)
        if ($room["roomSelect"] == $room["CONN_RM_ID"]){
            $CONN_RM_TRAN_ID = $RM_TRAN_ID;
            DB::table('RoomTran')->where('RM_TRAN_ID',$RM_TRAN_ID)
                ->update(array("CONN_RM_TRAN_ID" => $CONN_RM_TRAN_ID));
        }
        return $RM_TRAN_ID;
    }

    public function roomTranPrepare(&$room,&$CONN_RM_TRAN_ID){
        $DPST_RMN = ($room["pay"]["paymentRequest"]=="" || $room["pay"]["paymentRequest"] == null)?0:$room["pay"]["paymentRequest"];
        $RoomTranArray = array(
            "RM_ID" => $room["roomSelect"],
            "CHECK_IN_DT" => $room["CHECK_IN_DT"],
            "CHECK_OT_DT" => $room["CHECK_OT_DT"],
            "RM_AVE_PRCE" => $room["finalPrice"],
            "DPST_RMN" => $DPST_RMN,
            //    "RSRV_PAID_DYS" => $room,
//                "DPST_FIXED" => $room["fixedDeposit"],
            "CHECK_TP" => $room["roomSource"],
            "LEAVE_TM" => (new DateTime($room["leaveTime"]))->format('H:i:s'),
//                "CARDS_NUM" => $room["CARDS_NUM"],
            "FILLED" => "F"
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
        return $RoomTranArray;
    }

    public function roomsIn($roomSelect,$RM_TRAN_ID){
        $RoomsArray = array(
            "RM_TRAN_ID" => $RM_TRAN_ID,
            "RM_CONDITION" => "有人"
        );
        DB::table('Rooms')->where('RM_ID',$roomSelect)->update($RoomsArray);
    }

//    public function terminateOriRoom($RM_TRAN_ID){
//
//    }

    public function customerIn(&$room,&$CustomerArray,&$RM_TRAN_ID){
        foreach($room["GuestsInfo"]  as $gust){
            $customer = array(
                "SSN" => $gust["SSN"],
                "RM_TRAN_ID" => $RM_TRAN_ID,
                "CUS_NAME" => $gust["Name"],
                "RM_ID" => $room["roomSelect"],
                "PHONE"=>$gust["Phone"],
                "RMRK" =>$gust["RemarkInput"],
            );
            if($gust["MemberId"] != "" || $gust["MemberId"] != null){
                $customer["MEM_ID"]= $gust["MemberId"];
                $customer["MEM_TP"]= $gust["MEM_TP"];
            }
            if($gust["Points"] != "" || $gust["Points"] != null ){
                $customer["POINTS"] = $gust["Points"];
            }
            array_push($CustomerArray,$customer);
        }
    }

    public function roomDepositIn(&$room,&$RM_TRAN_ID,&$DepositArray,&$RMRK){
        $date = new DateTime();
        foreach ($room["pay"]["payByMethods"] as $payByMethod){
            $depo = array(
                "RM_TRAN_ID" => $RM_TRAN_ID,
                "DEPO_AMNT"=>$payByMethod["payAmount"],
                "PAY_METHOD" => $payByMethod["payMethod"],
                "DEPO_TSTMP"=> $date,
                "RMRK"=> $RMRK
            );
            array_push($DepositArray,$depo);
        }
    }

    public function roomOccupationCheckChange(&$room){
        $begin = new DateTime($room["CHECK_IN_DT"]);
        $end = new DateTime($room["CHECK_OT_DT"]);
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        DB::update('update RoomOccupation set CHECK_QUAN =CHECK_QUAN + ? where RM_TP = ? and DATE >=  ? and DATE < ? ',
            array(1,$room["roomType"],$room["CHECK_IN_DT"],$room["CHECK_OT_DT"]) );

        foreach ( $period as $dt ){
            $dtMatch = $dt->format( "Y-m-d" );
            $has = DB::table('RoomOccupation')
                ->where('RoomOccupation.DATE','=',$dtMatch)
                ->where('RoomOccupation.RM_TP','=',$room["roomType"])
                ->get();
            $hasArray = json_decode(json_encode($has));
            if(count($hasArray) < 1){
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
                DB::update('update RoomOccupation set RESV_QUAN = RESV_QUAN - ? where RM_TP = ? and DATE >=  ? and DATE < ?  ',
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
            }else{          // IF has 预定 预付 two rows, this won't work, but now, one one can have
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