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

    public function showMembers(){
        $memberShow = DB::table('MemberInfo')
            ->get();
        return Response::json($memberShow);
    }

    public function showMemberType(){
        $memberShow = DB::table('MemberType')
            ->get();
        return Response::json($memberShow);
    }

    public function addMemberSubmit(){
        $memberInfo = Input::get('memberInfo');
        $acct = Input::get('acct');
        $memTranDepoArray = array();
        try {
            DB::beginTransaction();   //////  Important !! TRANSACTION Begin!!!
            /***************                    Insert to Member info                   ***************/
            $MEM_ID = DB::table('MemberInfo')->insertGetId($memberInfo);
            /***************                    prepare and insert to memTran                    ***************/
            if($acct!=null && $acct!=""){
                $memTranArray = $this->memTranIn($MEM_ID,$acct);
                $MEM_TRAN_ID = DB::table('MemTran')->insertGetId($memTranArray);
                /***************                    prepare and insert to memTranDepo                    ***************/
                foreach($acct["payByMethods"] as $depo){
                    $this->memTranDepoIn($MEM_ID,$MEM_TRAN_ID,$depo,$memTranDepoArray);
                }
                DB::table('MemTranDeposit')->insert($memTranDepoArray);
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


    public function editMemberSubmit(){
        $memberInfo = Input::get('memberInfo');
        $acct = Input::get('acct');
        $memTranDepoArray = array();
        $MEM_ID = $memberInfo["MEM_ID"];
        try {
            DB::beginTransaction();   //////  Important !! TRANSACTION Begin!!!
            /***************                    update the Member info                   ***************/
            DB::table('MemberInfo')
                ->where('MEM_ID','=',$MEM_ID)
                ->update($memberInfo);
            if($acct!=null){
                /***************                    prepare and insert to memTran                    ***************/
                $memTranArray = $this->memTranIn($MEM_ID,$acct);
                $MEM_TRAN_ID = DB::table('MemTran')->insertGetId($memTranArray);
                /***************                    prepare and insert to memTranDepo                    ***************/
                foreach($acct["payByMethods"] as $depo){
                    $this->memTranDepoIn($MEM_ID,$MEM_TRAN_ID,$depo,$memTranDepoArray);
                }
                DB::table('MemTranDeposit')->insert($memTranDepoArray);
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


    public function memTranIn($MEM_ID,&$acct){
        $memTranArrary = array(
             "MEM_ID" =>$MEM_ID,
             "EMP_ID" => null,
             "MEM_TSTMP" => (new DateTime())->format('Y-m-d H:i:s'),
             "FEE_AMNT" => $acct["paymentRequest"],
             "FEE_TP" => $acct["paymentType"],
             "RMRK" => null,
             "FILLED" => "T"
        );
        return $memTranArrary;
    }

    public function memTranDepoIn($MEM_ID,$MEM_TRAN_ID,&$depo,&$memTranDepoArray){
        $memTDArrary = array(
            "MEM_TRAN_ID" =>$MEM_TRAN_ID,
            "PAY_MTHD" => $depo["payMethod"],
            "EMP_ID" => null,
            "MEM_ID" => $MEM_ID,
            "PAY_AMNT" => $depo["payAmount"],
            "RMRK" => null
        );
        array_push($memTranDepoArray,$memTDArrary);
    }


}
