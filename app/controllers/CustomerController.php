<?php
/**
 * Created by PhpStorm.
 * User: Xharlie
 * Date: 6/24/14
 * Time: 12:56 AM
 */
class CustomerController extends BaseController{

    public function showCustomer(){
        $customerShow = DB::table('Rooms')
            ->join('RoomTran', 'RoomTran.RM_TRAN_ID','=','Rooms.RM_TRAN_ID')
            ->join('Customers', 'Rooms.RM_TRAN_ID', '=', 'Customers.RM_TRAN_ID')
            ->select('Rooms.RM_ID as RM_ID','Customers.SSN as SSN','Customers.CUS_NAME as CUS_NM',
                'RoomTran.CHECK_TP as CHECK_TP','Customers.MEM_ID as MEM_ID','Customers.MEM_TP as MEM_TP',
                'Customers.PROVNCE as PROVNCE','Customers.PHONE as PHONE','Customers.RMRK as RMRK',
                'RoomTran.CHECK_IN_DT as CHECK_IN_DT','RoomTran.CHECK_OT_DT as CHECK_OT_DT')
            ->get();
        return Response::json($customerShow);
    }

    public function showMember(){
        $memberShow = DB::table('MemberInfo')
            ->select('MemberInfo.MEM_ID as MEM_ID','MemberInfo.SSN as SSN','MemberInfo.MEM_NM as MEM_NM',
                'MemberInfo.MEM_GEN as MEM_GEN','MemberInfo.MEM_DOB as MEM_DOB','MemberInfo.PROV as PROV',
                'MemberInfo.CITY as CITY','MemberInfo.ADDRS as ADDRS','MemberInfo.PHONE as PHONE',
                'MemberInfo.IN_DT as IN_DT','MemberInfo.EMAIL as EMAIL','MemberInfo.TIMES as TIMES',
                'MemberInfo.POINTS as POINTS','MemberInfo.MEM_TP as MEM_TP')
            ->get();
        return Response::json($memberShow);
    }
}
