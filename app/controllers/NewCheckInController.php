<?php


class NewCheckInController extends BaseController{
    /*** getClicked room info for single walk in check in    ***/
    public function getSingleRoomInfo($RM_ID){
        $singleRoomInfo = DB::table('Rooms')
            ->join('RoomsTypes', function ($join) {
                $join->on('RoomsTypes.RM_TP','=','RoomsTypes.RM_TP')
                    ->where('Rooms.HTL_ID', '=', Session::get('userInfo.HTL_ID'))
                    ->where('RoomsTypes.HTL_ID', '=', Session::get('userInfo.HTL_ID'));
            })
            ->where("Rooms.RM_ID",$RM_ID)
            ->select('Rooms.RM_ID as RM_ID','Rooms.RM_CONDITION as RM_CONDITION', 'Rooms.RM_TP as RM_TP',
                'RoomsTypes.SUGG_PRICE as SUGG_PRICE')
            ->get();
        return Response::json($singleRoomInfo);
    }

    public function getRoomInfo(){
        $RoomInfo = DB::table('Rooms')
            ->where('Rooms.HTL_ID', '=', Session::get('userInfo.HTL_ID'))
            ->leftjoin('RoomsTypes', function ($join) {
                $join->on('RoomsTypes.RM_TP','=','RoomsTypes.RM_TP')
                    ->where('RoomsTypes.HTL_ID', '=', Session::get('userInfo.HTL_ID'));
            })
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
            ->where('MemberInfo.CRP_ID', '=', Session::get('userInfo.CRP_ID'))
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
            ->where('Treaty.CRP_ID', '=', Session::get('userInfo.CRP_ID'))
            ->whereRaw($rawFilter)
            ->select('Treaty.TREATY_ID as TREATY_ID','Treaty.CORP_NM as CORP_NM',
                'Treaty.TREATY_TP as TREATY_TP','Treaty.CORP_PHONE as CORP_PHONE',
                'Treaty.CONTACT_NM as CONTACT_NM','Treaty.CONTACT_PHONE as CONTACT_PHONE',
                'Treaty.RMARK as RMARK','Treaty.DISCOUNT as DISCOUNT')
            ->get();
        return Response::json($treaties);
    }

    public function setWakeUpCall(){
        $submitInfo = Input::all();
        try {
            DB::beginTransaction();   //////  Important !! TRANSACTION Begin!!!
            DB::update('update RoomTran set WKC_TSTMP =  ? where RM_TRAN_ID = ?',
                array($submitInfo['WKC_TSTMP'],$submitInfo['RM_TRAN_ID']) );
        }catch (Exception $e){
            DB::rollback();
            $message=($e->getLine())."&&".$e->getMessage();
            throw new Exception($message);
        }finally{
            DB::commit();
            return Response::json("success!");
        }    }

    public function submitDeposit(){
        $all = Input::all();
        $info = Input::get('SubmitInfo');
        $pay = Input::get('pay');
        $DepositArray = array();
        $RM_TRAN_ID = $info['RM_TRAN_ID'];
        if($info['CONN_RM_TRAN_ID'] != null || $info['CONN_RM_TRAN_ID'] != "") $RM_TRAN_ID = $info['CONN_RM_TRAN_ID'];
        try {
            DB::beginTransaction();   //////  Important !! TRANSACTION Begin!!!
            /*-------------------------  update DPST_RMN of RoomTran                    -------------------------------*/
            DB::update('update RoomTran set DPST_RMN = DPST_RMN + ? where RM_TRAN_ID = ?',
                array($pay['paymentRequest'],$RM_TRAN_ID) );
            /*------------------------- insert to roomDesposit and wait to insert in  ---------------------------------*/
            $SUB_CAT = "补存";
            $this->roomDepositIn($all,$RM_TRAN_ID,$DepositArray,$SUB_CAT,null);
            DB::table('RoomDepositAcct')->insert($DepositArray);
        }catch (Exception $e){
            DB::rollback();
            $message=($e->getLine())."&&".$e->getMessage();
            throw new Exception($message);
        }finally{
            DB::commit();
            return Response::json("success!");
        }
    }

    public function submitModify(){
        $info = Input::get('SubmitInfo');
        $RM_TRAN_ID = Input::get('RM_TRAN_ID');
        $today = Input::get('today');
        $initialString = Input::get('initialString');
        $CustomerArray = array();
        try {
            DB::beginTransaction();   //////  Important !! TRANSACTION Begin!!!
            foreach($info as $room){
                /*-------------------------  get original RoomTran                    -------------------------------*/
                $oriRec = DB::table('RoomTran')
                    ->join('Rooms', function($join) use ($RM_TRAN_ID)
                    {
                        $join->where('Rooms.RM_TRAN_ID', '=', $RM_TRAN_ID)
                             ->on('Rooms.RM_TRAN_ID', '=', 'RoomTran.RM_TRAN_ID');
                    })
                    ->select("Rooms.RM_TRAN_ID as RM_TRAN_ID","Rooms.RM_ID as RM_ID","Rooms.RM_TP as RM_TP",
                            "RoomTran.CHECK_IN_DT as CHECK_IN_DT","RoomTran.DPST_RMN as DPST_RMN","RoomTran.TMP_PLAN_ID as TMP_PLAN_ID",
                             "RoomTran.CHECK_OT_DT as CHECK_OT_DT","RoomTran.CONN_RM_TRAN_ID as CONN_RM_TRAN_ID")
                    ->take(1)
                    ->get();
                $ori = $oriRec[0];
                $CONN_RM_TRAN_ID = $ori->CONN_RM_TRAN_ID;

            /*-------------------------------------------  RoomTran    -----------------------------------------------*/
                    //  prepare RoomTran array , dpst_rmn is new added amount
                $RoomTranArray = array(
                    "RM_ID" => $room["roomSelect"],
                    "CHECK_OT_DT" => $room["CHECK_OT_DT"],
                    "RM_AVE_PRCE" => $room["finalPrice"],
                    "CHECK_TP" => $room["roomSource"],
                    "LEAVE_TM" => (new DateTime($room["leaveTime"]))->format('H:i:s'),
                    "FILLED" => "F",
                    "HTL_ID" => Session::get('userInfo.HTL_ID')
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

                DB::table('RoomTran')
                    ->where('RM_TRAN_ID',$RM_TRAN_ID)
                    ->update($RoomTranArray);
                /*------------------------ if temp room, change master room's DPST_RMN -------------------------*/
                if($room["TMP_PLAN_ID"]!='' && $ori->TMP_PLAN_ID == null){
                    $TKN_ID = $CONN_RM_TRAN_ID;
                    if($CONN_RM_TRAN_ID=="") $TKN_ID = $RM_TRAN_ID;
                    $RMRK="钟点房".$room["TMP_PLAN_ID"];
                    $SUB_CAT="钟点房";
                    // master room, or itself deposit change
                    DB::update('update RoomTran set DPST_RMN = DPST_RMN - ? where RM_TRAN_ID = ?',
                        array($room["finalPrice"],$TKN_ID) );
                    $this->roomAcctIn($RM_TRAN_ID,$room['finalPrice'],$TKN_ID,$SUB_CAT,$RMRK);
                }
            /*-------------------------------------------  Rooms    -----------------------------------------------*/
                // 1-------------------------     if RM_ID has been changed,cover both RM_TRAN_ID changed or not
                if($ori->RM_ID!=$room["roomSelect"]) {
                    // update old room to empty
                    DB::table('Rooms')
                        ->where('Rooms.HTL_ID', '=', Session::get('userInfo.HTL_ID'))
                        ->where('RM_ID', $ori->RM_ID)
                        ->update(array("RM_TRAN_ID" => null,
                            "RM_CONDITION" => "脏房"));
                    // update new room to be occupied
                    DB::table('Rooms')
                        ->where('RM_ID', $room["roomSelect"])
                        ->update(array("RM_TRAN_ID" => $RM_TRAN_ID,
                            "RM_CONDITION" => "有人"));
                }
            /*-------------------------------------------  Customers    -----------------------------------------------*/
                //  if CUSTOMER has been changed prepare Customer    -------------------------------*/
                if($initialString="editRoom"){  //condition under development
                    // delete the old customers
                    DB::table('Customers')
                        ->where('RM_TRAN_ID',$RM_TRAN_ID)
                        ->delete();
                    // prepare new customers
                    $this->customerIn($room,$CustomerArray,$RM_TRAN_ID);
                }
            /*-------------------------     RoomOccupation       -------------------------------*/
                // delete the origin room to be occupied from today to the old expected check out date
                if($ori->RM_TP!=$room["roomType"] || $ori->CHECK_OT_DT!=$room["CHECK_OT_DT"]){
                    DB::update('update RoomOccupation set CHECK_QUAN = CHECK_QUAN - ? where HTL_ID = ? and RM_TP = ? and DATE >=  ? and DATE < ?  ',
                        array(1,Session::get('userInfo.HTL_ID'),$ori->RM_TP,$today, $ori->CHECK_OT_DT) );
                // increase the new room from today to new expected check out date
                    $room["CHECK_IN_DT"] = $today;
                    $this->roomOccupationCheckChange($room);
                }
            }
            DB::table('Customers')->insert($CustomerArray);

        }catch (Exception $e){
            DB::rollback();
            $message=($e->getLine())."&&".$e->getMessage();
            throw new Exception($message);
        }finally{
            DB::commit();
            return Response::json("success!");
        }
    }

    public function submitCheckIn(){
        $info = Input::get('SubmitInfo');
        $reserve = Input::get('RESV');
        $unfilled = Input::get('unfilled');
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
                    $SUB_CAT= "存入";
                    $this->roomDepositIn($room,$RM_TRAN_ID,$DepositArray,$SUB_CAT,null);
                }
                /*------------------------- change Room Availablilty Check Quan---------------------------------*/
                $this->roomOccupationCheckChange($room);
                /*------------------------ if temp room, change master room's DPST_RMN -------------------------*/
                if($room["TMP_PLAN_ID"]!=''){
                    $TKN_ID = $CONN_RM_TRAN_ID;
                    if($CONN_RM_TRAN_ID=="") $TKN_ID = $RM_TRAN_ID;
                    $RMRK="钟点房".$room["TMP_PLAN_ID"];
                    $SUB_CAT='钟点房';
                    // master room, or itself deposit change
                    DB::update('update RoomTran set DPST_RMN = DPST_RMN - ? where RM_TRAN_ID = ? and HTL_ID = ?',
                        array($room["finalPrice"],$TKN_ID,Session::get('userInfo.HTL_ID')) );
                    $this->roomAcctIn($RM_TRAN_ID,$room['finalPrice'],$TKN_ID,$SUB_CAT,$RMRK);
                }
            }
            DB::table('RoomDepositAcct')->insert($DepositArray);
            DB::table('Customers')->insert($CustomerArray);
            if ($reserve != null){
            /*--------------------------------------- delete Room Availablilty Resv Quan------------------------------------------*/
                $this->roomOccupationResvChange($room,$reserve,$unfilled);
            /*--------------------------------------- change Resv Quan and status ------------------------------------------*/
                $this->reservRoomstatus($room,$reserve,$unfilled);
            }
        }catch (Exception $e){
            DB::rollback();
            $message=($e->getLine())."&&".$e->getMessage();
            throw new Exception($message);
            return Response::json($message);
        }finally{
            DB::commit();
            return Response::json(array(
                'RM_TRAN_ID' => $RM_TRAN_ID,
                'CONN_RM_TRAN_ID' =>$CONN_RM_TRAN_ID
            ));
        }

    }

    public function roomAcctIn(&$RM_TRAN_ID,&$RM_PAY_AMNT,&$TKN_RM_TRAN_ID,$SUB_CAT,$RMRK){
        $InsertArray = array(
            "RM_TRAN_ID" => $RM_TRAN_ID,
            "RM_PAY_AMNT" => $RM_PAY_AMNT,
            "BILL_TSTMP" => date('Y-m-d H:i:s'),
            "TKN_RM_TRAN_ID" => $TKN_RM_TRAN_ID,
            "FILLED" => 'F',
            "SUB_CAT" => $SUB_CAT,
            "RMRK"=>$RMRK,
            "EMP_ID"=>Session::get('userInfo.EMP_ID'),
            "HTL_ID"=>Session::get('userInfo.HTL_ID')

        );
        return DB::table('RoomAcct')->insertGetId($InsertArray);
    }

    public function roomTranIn(&$room,&$RoomTranArray,&$CONN_RM_TRAN_ID){
        // array_push($RoomTranTotal,$RoomTranArray);
        $RM_TRAN_ID = DB::table('RoomTran')->insertGetId($RoomTranArray);
        // if encounter the master room rm tran id(should be the first one to be encountered,
        // get the master room tran id and update previous ones)
        if ($room["roomSelect"] == $room["CONN_RM_ID"]){
            $CONN_RM_TRAN_ID = $RM_TRAN_ID;
            DB::table('RoomTran')
                ->where('RoomTran.HTL_ID','=',Session::get('userInfo.HTL_ID'))
                ->where('RM_TRAN_ID',$RM_TRAN_ID)
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
            "CHECK_TP" => $room["roomSource"],
            "LEAVE_TM" => $room["leaveTime"],
            "FILLED" => "F",
            "HTL_ID"=>Session::get('userInfo.HTL_ID')
        );

        if($room["inTime"] != null &&  $room["inTime"] != ""){
            $RoomTranArray["IN_TM"] = $room["inTime"];
        }
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
        DB::table('Rooms')
            ->where('Rooms.HTL_ID', '=', Session::get('userInfo.HTL_ID'))
            ->where('RM_ID',$roomSelect)->update($RoomsArray);
    }

    public function customerIn(&$room,&$CustomerArray,&$RM_TRAN_ID){
        foreach($room["GuestsInfo"]  as $gust){
            $customer = array(
                "SSN" => $gust["SSN"],
                "RM_TRAN_ID" => $RM_TRAN_ID,
                "CUS_NAME" => $gust["Name"],
                "RM_ID" => $room["roomSelect"],
                "PHONE"=>$gust["Phone"],
                "RMRK" =>$gust["RemarkInput"],
                "HTL_ID"=>Session::get('userInfo.HTL_ID')
            );
            if($gust["MemberId"] != "" || $gust["MemberId"] != null){
                $customer["MEM_ID"]= $gust["MemberId"];
                $customer["MEM_TP"]= $gust["MEM_TP"];
            }
            if($gust["Points"] != "" || $gust["Points"] != null ){
                $customer["POINTS"] = $gust["Points"];
            }
            if($gust["DOB"] != "" || $gust["DOB"] != null ){
                $customer["DOB"] = $gust["DOB"];
            }
            if($gust["Address"] != "" || $gust["Address"] != null ){
                $customer["ADDRSS"] = $gust["Address"];
            }
            array_push($CustomerArray,$customer);
        }
    }

    public function roomDepositIn(&$room,&$RM_TRAN_ID,&$DepositArray,$SUB_CAT,$RMRK){
        $date = new DateTime();
        foreach ($room["pay"]["payByMethods"] as $payByMethod){
            $depo = array(
                "RM_TRAN_ID" => $RM_TRAN_ID,
                "REF_ID" => null,
                "DEPO_AMNT"=>$payByMethod["payAmount"],
                "PAY_METHOD" => $payByMethod["payMethod"],
                "DEPO_TSTMP"=> $date,
                "SUB_CAT"=> $SUB_CAT,
                "RMRK"=> $RMRK,
                "FILLED"=>'F',
                "HTL_ID"=>Session::get('userInfo.HTL_ID')
            );
            /**********     if  paid by  resvDeposit      *************/
            if($payByMethod['payMethod'] == '预定金' && $payByMethod['payRefID'] !=null){
                $depo['REF_ID'] = $payByMethod['payRefID'];
                DB::update('update Reservations set PRE_PAID_RMN = PRE_PAID_RMN - ? where HTL_ID = ?, RESV_ID = ?',
                    array($payByMethod["payAmount"],Session::get('userInfo.HTL_ID'),$payByMethod['payRefID']) );
                DB::table('ReserveDepositAcct')->insert(
                    array(
                        'RESV_ID'=>$payByMethod['payRefID'],
                        'DEPO_AMNT'=>$payByMethod["payAmount"] * (-1),
                        'PAY_METHOD'=> $payByMethod["payMethod"],
                        'DEPO_TSTMP'=>$date,
                        'RMRK'=> '转入房间押金',
                        "EMP_ID"=>Session::get('userInfo.EMP_ID'),
                        "HTL_ID"=>Session::get('userInfo.HTL_ID')
                    )
                );
            }
            array_push($DepositArray,$depo);

        }
    }

    public function roomOccupationCheckChange(&$room){
        $begin = new DateTime($room["CHECK_IN_DT"]);
        $end = new DateTime($room["CHECK_OT_DT"]);
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        DB::update('update RoomOccupation set CHECK_QUAN =CHECK_QUAN + ? where HTL_ID = ? AND RM_TP = ? and DATE >=  ? and DATE < ? ',
            array(1,$room["roomType"],Session::get('userInfo.HTL_ID'),$room["CHECK_IN_DT"],$room["CHECK_OT_DT"]) );

        foreach ( $period as $dt ){
            $dtMatch = $dt->format( "Y-m-d" );
            $has = DB::table('RoomOccupation')
                ->where('RoomOccupation.HTL_ID','=',Session::get('userInfo.HTL_ID'))
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
                DB::update('update RoomOccupation set RESV_QUAN = RESV_QUAN - ? where HTL_ID = ?,RM_TP = ? and DATE >=  ? and DATE < ?  ',
                    array((int)$value["checked"],Session::get('userInfo.HTL_ID'),$RM_TP,$reserve["CHECK_IN_DT"], $reserve["CHECK_OT_DT"]) );
        }
    }

    public function  reservRoomstatus(&$room, &$reserve, &$unfilled){
        foreach ( $unfilled as $RM_TP => $value ){

            /***********    update or create  checked status rows    ***********/
            $has = DB::table('ReservationRoom')
                ->where('ReservationRoom.HTL_ID','=',Session::get('userInfo.HTL_ID'))
                ->where('ReservationRoom.RESV_ID','=',$reserve["RESV_ID"])
                ->where('ReservationRoom.RM_TP','=',$RM_TP)
                ->where('ReservationRoom.STATUS','=','Filled')
                ->get();
            $hasArray = json_decode(json_encode($has));
            if(count($hasArray) >= 1){
                DB::update("update ReservationRoom set RM_QUAN = RM_QUAN + ? where HTL_ID=? AND STATUS = 'Filled' and RM_TP = ? and RESV_ID = ? ",
                    array($value["checked"],Session::get('userInfo.HTL_ID'), $RM_TP,$reserve["RESV_ID"]) );
            }else{
                $RESV_DAY_PAY_RAW = DB::table('ReservationRoom')
                    ->where('ReservationRoom.HTL_ID','=',Session::get('userInfo.HTL_ID'))
                    ->where('RESV_ID','=', $reserve["RESV_ID"])
                    ->where('RM_TP', '=', $RM_TP)
                    ->pluck('RESV_DAY_PAY');

                DB::table('ReservationRoom')->insert(
                    array(
                        'RESV_ID' => $reserve["RESV_ID"],
                        'RM_TP' => $RM_TP,
                        'RM_QUAN' => $value["checked"],
                        'RESV_DAY_PAY' => $RESV_DAY_PAY_RAW,
                        'STATUS' => 'Filled',
                        'HTL_ID' => Session::get('userInfo.HTL_ID')
                    )
                );
            }
            /***********    update or delete  unchecked status rows    ***********/
            if($value["unchecked"] == 0){
                /*    all have checked    */
                DB::table('ReservationRoom')
                    ->where('ReservationRoom.HTL_ID','=',Session::get('userInfo.HTL_ID'))
                    ->where('RM_TP','=', $RM_TP)
                    ->where('RESV_ID','=', $reserve["RESV_ID"])
                    ->whereRaw("STATUS NOT IN ('Cancelled','Filled')")
                    ->delete();
            }else{          // IF has 预定 预付 two rows, this won't work, but now, one one can have
                DB::update("update ReservationRoom set RM_QUAN = ? where  HTL_ID=? AND RM_TP = ? and RESV_ID = ? and STATUS not in ('Cancelled','Filled') ",
                    array($value["unchecked"], Session::get('userInfo.HTL_ID'),$RM_TP,$reserve["RESV_ID"]) );
            }

        }
    }


    public function showCusInRoom($RM_ID){
        $occupiedShow = DB::table('Customers')
            ->join('Rooms', function($join) use ($RM_ID)
            {
                $join->where('Customers.HTL_ID','=',Session::get('userInfo.HTL_ID'))
                     ->where('Rooms.HTL_ID','=',Session::get('userInfo.HTL_ID'))
                     ->where('Rooms.RM_ID', '=', $RM_ID)
                     ->on('Rooms.RM_TRAN_ID', '=', 'Customers.RM_TRAN_ID');
            })
            ->get();
        return Response::json($occupiedShow);
    }


}