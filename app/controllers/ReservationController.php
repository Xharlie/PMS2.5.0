<?php
/**
 * Created by PhpStorm.
 * User: Xharlie
 * Date: 6/19/14
 * Time: 10:28 PM
 */

class ReservationController extends BaseController{
// status has 预订,预付,Filled,NoShow, Cancelled，NoShowExpired
    public function showResv(){
        $resvShow = DB::table('Reservations')
                        ->join('ReservationRoom', 'Reservations.RESV_ID','=','ReservationRoom.RESV_ID')
                        ->whereNotIn("ReservationRoom.STATUS",["Filled","Cancelled","NoShowExpired"])
                        ->select('ReservationRoom.RESV_DAY_PAY as RESV_DAY_PAY','Reservations.RESV_ID as RESV_ID',
                                'Reservations.RESVER_PHONE as RESVER_PHONE', 'Reservations.RESVER_NAME as RESVER_NAME',
                                'Reservations.RESV_WAY as RESV_WAY','Reservations.RESV_TMESTMP as RESV_TMESTMP',
                                'Reservations.CHECK_IN_DT as CHECK_IN_DT','Reservations.CHECK_OT_DT as CHECK_OT_DT',
                                'Reservations.RESV_LATEST_TIME as RESV_LATEST_TIME',
                                'ReservationRoom.RM_TP as RM_TP','ReservationRoom.RM_QUAN as RM_QUAN',
                                'Reservations.TREATY_ID as TREATY_ID','Reservations.MEMBER_ID as MEMBER_ID',
                                'Reservations.RMRK as RMRK','ReservationRoom.STATUS as STATUS',
                                'Reservations.PRE_PAID as PRE_PAID')
                        ->get();
        return Response::json($resvShow);
    }

    public function storeResv(){

    }

    public function deleteResv(){

    }



    public function submitResv(){
        $newResv = Input::get('newResv');
        $payment = Input::get('payment');
        $begin = new DateTime($newResv["CHECK_IN_DT"]);
        $end = new DateTime($newResv["CHECK_OT_DT"]);
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
    /********************************          prepare Reservations  array            ******************************/
        $ReservationsArray = array();
        $this->intoReseravtion($newResv,$ReservationsArray);
        $RESV_ID = DB::table('Reservations')->insertGetId($ReservationsArray);

        $ReservationRoomArray = array();
        foreach($newResv["BookRoomByTP"] as $rmTP => $tpInfo){

            /******************************** prepare Room  array******************************/
            $typeArray = array(
                "RESV_ID"=> $RESV_ID,
                "RM_TP"=> $rmTP,
                "RM_QUAN"=> $tpInfo["roomAmount"],
                "RESV_DAY_PAY"=> $tpInfo["finalPrice"],
                "STATUS"=>$newResv["STATUS"],
            );
            array_push($ReservationRoomArray,$typeArray);
            /********************************hange Room  occupation******************************/
            $this->updateRoomOccupation($period,$rmTP,$tpInfo);
        }
        DB::table("ReservationRoom")->insert($ReservationRoomArray);
            /********************************prepare Room pay array******************************/
        if ($newResv["STATUS"] == "预付"){
            $DepositArray = array();
            $this->roomDepositIn($payment,$RESV_ID,$DepositArray);
            DB::table('ReserveDepositAcct')->insert($DepositArray);
        }
        return Response::json('预定成功!');
    }

    public function editResv(){
        $reResv = Input::get('reResv');
        $payment = Input::get('payment');
        $RESV= Input::get('RESV');
        $RESV_ID = $RESV[0]["RESV_ID"];
        $begin = new DateTime($reResv["CHECK_IN_DT"]);
        $end = new DateTime($reResv["CHECK_OT_DT"]);
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
    /********************************          prepare Reservations  array            ******************************/
        $ReservationsArray = array();
        $this->intoReseravtion($reResv,$ReservationsArray);

        DB::table('Reservations')
            ->where('RESV_ID',$RESV_ID)
            ->update($ReservationsArray);


        foreach($RESV as $oriRmTP){
            /********************************          delete  Reservations             ******************************/
            $this->deleteResvRoom($RESV_ID,$oriRmTP);
            /********************************          delete  ResvRoom Occupation      ******************************/
            $this->deleteResvRoomOccupation($oriRmTP);
        }

        $ReservationRoomArray = array();
        foreach($reResv["BookRoomByTP"] as $rmTP => $tpInfo){

            /******************************** prepare Room  array******************************/
            $typeArray = array(
                "RESV_ID"=> $RESV_ID,
                "RM_TP"=> $rmTP,
                "RM_QUAN"=> $tpInfo["roomAmount"],
                "RESV_DAY_PAY"=> $tpInfo["finalPrice"],
                "STATUS"=>$reResv["STATUS"],
            );
            array_push($ReservationRoomArray,$typeArray);
            /********************************hange Room  occupation******************************/
            $this->updateRoomOccupation($period,$rmTP,$tpInfo);
        }

        DB::table("ReservationRoom")->insert($ReservationRoomArray);
            /********************************prepare additional Room pay array******************************/
        if ($payment != null){
            $DepositArray = array();
            $this->roomDepositIn($payment,$RESV_ID,$DepositArray);
            DB::table('ReserveDepositAcct')->insert($DepositArray);
        }
        return Response::json('修改成功!');
    }



    public function intoReseravtion(&$newResv,&$ReservationsArray){
        $ReservationsArray = array(
            "RESV_TMESTMP" => DateTime::createFromFormat('Y-m-d H:i:s', $newResv["RESV_TMESTMP"]),
            "RESV_WAY"=>$newResv['roomSource'],
            "CHECK_IN_DT"=> $newResv["CHECK_IN_DT"],
            "RESV_LATEST_TIME"=> (new DateTime($newResv["RESV_LATEST_TIME"]))->format('H:i:s'),
            "CHECK_OT_DT"=> $newResv["CHECK_OT_DT"],
            "RESVER_NAME"=> $newResv["Name"],
            "RESVER_PHONE" => $newResv["Phone"],
            "RESVER_EMAIL" => $newResv["email"],
            "RMRK"=> $newResv["RMRK"],
            "PRE_PAID"=> $newResv["PRE_PAID"]
        );
        if($ReservationsArray["RESV_WAY"] == "会员卡"){
            $ReservationsArray["MEMEBER_ID"] = $newResv["roomSourceID"];
        }elseif($ReservationsArray["RESV_WAY"] == "协议"){
            $ReservationsArray["TREATY_ID"] = $newResv["roomSourceID"];
        }
    }

    public function updateRoomOccupation(&$period,&$rmTP,&$tpInfo){
        foreach ( $period as $dt ){
            $dtMatch = $dt->format( "Y-m-d" );
            $has = DB::table('RoomOccupation')
                ->where('RoomOccupation.DATE','=',$dtMatch)
                ->where('RoomOccupation.RM_TP','=',$rmTP)
                ->get();
            $hasArray = json_decode(json_encode($has));
            if(count($hasArray) >= 1){
                DB::update('update RoomOccupation set RESV_QUAN = RESV_QUAN + ? where DATE = ? and RM_TP = ?',
                    array($tpInfo["roomAmount"],$dtMatch,$rmTP) );
            }else{
                $OccArray = array(
                    "DATE" => $dtMatch,
                    "RM_TP"=>$rmTP,
                    "RESV_QUAN"=> $tpInfo["roomAmount"],
                    "CHECK_QUAN" => 0
                );
                DB::table('RoomOccupation')->insert($OccArray);
            }
        }
    }

    public function roomDepositIn(&$payment,&$RESV_ID,&$DepositArray){
        $date = new DateTime();
        foreach ($payment["payByMethods"] as $payByMethod){
            $depo = array(
                "RESV_ID" => $RESV_ID,
                "DEPO_AMNT"=>$payByMethod["payAmount"],
                "PAY_METHOD" => $payByMethod["payMethod"],
                "DEPO_TSTMP"=> $date
            );
            array_push($DepositArray,$depo);
        }
    }


    public function deleteResvRoom(&$RESV_ID, &$oriRmTP){
            DB::table("ReservationRoom")
                ->where('RESV_ID', $RESV_ID)
                ->where('RM_TP', $oriRmTP["RM_TP"])
                ->where("STATUS",$oriRmTP["STATUS"])
                ->delete();
    }
//  didn't delete the row even check quan and resv quan both are 0
    public function deleteResvRoomOccupation( &$oriRmTP){
            DB::update('update RoomOccupation set RESV_QUAN = RESV_QUAN - ? where RM_TP = ? and DATE between ? and ?  ',
                array((int)$oriRmTP["RM_QUAN"],$oriRmTP["RM_TP"],$oriRmTP["CHECK_IN_DT"], $oriRmTP["CHECK_OT_DT"]) );
    }
}


