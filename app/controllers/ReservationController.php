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
                        ->select('ReservationRoom.GUEST_NM as GUEST_NM','Reservations.RESVER_CARDNM as RESVER_CARDNM',
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
} 