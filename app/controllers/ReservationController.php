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
                        ->select('Reservations.RESV_DAY_PAY as RESV_DAY_PAY',
                                'Reservations.RESVER_PHONE as RESVER_PHONE', 'Reservations.RESVER_NAME as RESVER_NAME',
                                'Reservations.RESV_WAY as RESV_WAY','Reservations.RESV_TMESTMP as RESV_TMESTMP',
                                'Reservations.CHECK_IN_DT as CHECK_IN_DT','Reservations.CHECK_OT_DT as CHECK_OT_DT',
                                'ReservationRoom.RM_TP as RM_TP','ReservationRoom.RM_QUAN as RM_QUAN','Reservations.TREATY_ID as TREATY_ID',
                                'Reservations.MEMBER_ID as MEMBER_ID','Reservations.RMRK as RMRK')
                        ->get();
        return Response::json($resvShow);
    }

    public function storeResv(){

    }

    public function deleteResv(){

    }

    public function submitResv(){
        $info = Input::all();
        foreach($info as $room){
            $ReservationsArray = array(
                "RESV_TMESTMP" => DateTime::createFromFormat('Y-m-d H:i:s', $room["RESV_TMESTMP"]),
                "RESV_WAY"=>$room['roomSource'],
                "CHECK_IN_DT"=> $room["CHECK_IN_DT"],
                "CHECK_OT_DT"=> $room["CHECK_OT_DT"],
                "RESVER_NAME"=> $room["name"],
                "RESVER_PHONE" => $room["Phone"],
                "RESVER_EMAIL" => $room["Email"],
                "RMRK"=> $room["RMRK"],
                "RESV_DAY_PAY"=>$room["RESV_DAY_PAY"]
            );
            if($ReservationsArray["RESV_WAY"] == "1"){
                $ReservationsArray["MEMEBER_ID"] = $room["roomSourceID"];
            }elseif($ReservationsArray["RESV_WAY"] == "3"){
                $ReservationsArray["TREATY_ID"] = $room["roomSourceID"];
            }
            $RESV_ID = DB::table('Reservations')->insertGetId($ReservationsArray);
            $ReservationRoomArray = array(
                "RESV_ID"=>$RESV_ID,
                "RM_TP"=>$room["RM_TP"],
                "RM_QUAN"=>$room["RM_QUAN"]
            );
            DB::table("ReservationRoom")->insert($ReservationRoomArray);
            $begin = new DateTime($room["CHECK_IN_DT"]);
            $end = new DateTime($room["CHECK_OT_DT"]);

            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);

            foreach ( $period as $dt ){
                $dtMatch = $dt->format( "Y-m-d" );

                $has = DB::table('Occupancy')
                    ->where('Occupancy.OCP_DATE','=',$dtMatch)
                    ->where('Occupancy.RM_TP','=',$room["RM_TP"])
                    ->get();
                $hasArray = json_decode(json_encode($has));
                if(count($hasArray) >= 1){
                    DB::update('update Occupancy set RESV_QUAN = RESV_QUAN - ? where OCP_DATE = ? and RM_TP = ?',
                        array($room["RM_QUAN"],$dtMatch,$room["RM_TP"]));
                }else{
                    $OccArray = array(
                        "OCP_DATE" => $dtMatch,
                        "RM_TP"=>$room["RM_TP"],
                        "RESV_QUAN"=> $room["RM_QUAN"]
                    );
                    $RESV_ID = DB::table('Occupancy')->insert($OccArray);
                }
            }
        }
    }
} 