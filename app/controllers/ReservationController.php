<?php
/**
 * Created by PhpStorm.
 * User: Xharlie
 * Date: 6/19/14
 * Time: 10:28 PM
 */

class ReservationController extends BaseController{

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
                                'Reservations.RMRK as RMRK','ReservationRoom.STATUS as STATUS')
                        ->get();
        return Response::json($resvShow);
    }

    public function storeResv(){

    }

    public function deleteResv(){

    }



    public function submitResv(){
            $room = Input::all();
            $ReservationsArray = array(
                "RESV_TMESTMP" => DateTime::createFromFormat('Y-m-d H:i:s', $room["RESV_TMESTMP"]),
                "RESV_WAY"=>$room['roomSource'],
                "CHECK_IN_DT"=> $room["CHECK_IN_DT"],
                "CHECK_OT_DT"=> $room["CHECK_OT_DT"],
                "RESVER_NAME"=> $room["name"],
                "RESVER_PHONE" => $room["phone"],
                "RESVER_EMAIL" => $room["email"],
                "RMRK"=> $room["RMRK"]
            );
            if($ReservationsArray["RESV_WAY"] == "会员卡"){
                $ReservationsArray["MEMEBER_ID"] = $room["roomSourceID"];
            }elseif($ReservationsArray["RESV_WAY"] == "协议"){
                $ReservationsArray["TREATY_ID"] = $room["roomSourceID"];
            }
            $RESV_ID = DB::table('Reservations')->insertGetId($ReservationsArray);

            $begin = new DateTime($room["CHECK_IN_DT"]);
            $end = new DateTime($room["CHECK_OT_DT"]);

            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);


            $ReservationRoomArray = array();
            foreach($room["BookRoom"] as $key => $value){
                $typeArray = array(
                    "RESV_ID"=> $RESV_ID,
                    "RM_TP"=> $key,
                    "RM_QUAN"=> $value["roomTypeSelectQuan"],
                    "RESV_DAY_PAY"=> $value["roomNegoPriceOBJ"],
                    "CHECKED"=>"F"
                );
                array_push($ReservationRoomArray,$typeArray);


                foreach ( $period as $dt ){
                    $dtMatch = $dt->format( "Y-m-d" );

                    $has = DB::table('RoomOccupation')
                        ->where('RoomOccupation.DATE','=',$dtMatch)
                        ->where('RoomOccupation.RM_TP','=',$key)
                        ->get();
                    $hasArray = json_decode(json_encode($has));
                    if(count($hasArray) >= 1){
                        DB::update('update RoomOccupation set RESV_QUAN = RESV_QUAN + ? where DATE = ? and RM_TP = ?',
                            array($value["roomTypeSelectQuan"],$dtMatch,$key) );
                    }else{
                        $OccArray = array(
                            "DATE" => $dtMatch,
                            "RM_TP"=>$key,
                            "RESV_QUAN"=> $value["roomTypeSelectQuan"],
                            "CHECK_QUAN" => 0
                        );
                        DB::table('RoomOccupation')->insert($OccArray);
                    }
                }
            }
            DB::table("ReservationRoom")->insert($ReservationRoomArray);
            return Response::json('yeah!');

        }
}


