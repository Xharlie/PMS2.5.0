<?php
/**
 * Created by PhpStorm.
 * User: Xharlie
 * Date: 8/6/14
 * Time: 11:53 PM
 */
class NewCheckOutController extends BaseController{
    function checkOutGetInfo(){
        $info = Input::all();
        $allInfoArray = DB::table('RoomTran')
            ->where('RoomTran.HTL_ID','=',Session::get('userInfo.HTL_ID'))
            ->whereIn('RoomTran.RM_TRAN_ID',$info)
            ->leftjoin('Rooms','Rooms.RM_TRAN_ID','=','RoomTran.RM_TRAN_ID')
            ->leftjoin('RoomsTypes','Rooms.RM_TP','=','RoomsTypes.RM_TP')
            ->get();

        $acct["AcctPay"] = array();
        $acct["AcctDepo"] = array();
        $acct["AcctStore"] = array();
        $acct["AcctPenalty"] = array();

        for ($i = 0; $i < count($allInfoArray); $i++){

            $allInfoArray[$i]->Customers= DB::table('Customers')
                ->where('Customers.HTL_ID','=',Session::get('userInfo.HTL_ID'))
                ->where('Customers.RM_TRAN_ID',$allInfoArray[$i]->RM_TRAN_ID)
                ->get();

            $acctPay = DB::table('RoomAcct')
                ->join('RoomTran', function($join) use($allInfoArray,$i)
                {
                    $join->where('RoomTran.HTL_ID','=',Session::get('userInfo.HTL_ID'))
                        ->where('RoomAcct.HTL_ID', '=', Session::get('userInfo.HTL_ID'))
                        ->where('RoomAcct.TKN_RM_TRAN_ID','=',$allInfoArray[$i]->RM_TRAN_ID)
                        ->on('RoomTran.RM_TRAN_ID','=','RoomAcct.RM_TRAN_ID');
                })
                ->where(function($query)
                {
                    $query->where('RoomAcct.FILLED', '!=', 'T')
                        ->orWhereNull('RoomAcct.FILLED');
                })
                ->select('RoomAcct.RM_BILL_ID as RM_BILL_ID','RoomAcct.RM_TRAN_ID as RM_TRAN_ID',
                    'RoomAcct.RM_PAY_AMNT as RM_PAY_AMNT','RoomAcct.BILL_TSTMP as BILL_TSTMP',
                    'RoomAcct.ORGN_ACCT_ID as ORGN_ACCT_ID','RoomAcct.RM_PAY_METHOD as RM_PAY_METHOD',
                    'RoomAcct.EMP_ID as EMP_ID','RoomAcct.TKN_RM_TRAN_ID as TKN_RM_TRAN_ID',
                    'RoomAcct.FILLED as FILLED','RoomAcct.RMRK as RMRK',
                    'RoomAcct.SUB_CAT as SUB_CAT','RoomTran.RM_ID')
                ->get();

            $acctRoomDepo= DB::table('RoomDepositAcct')
                ->join('RoomTran', function($join) use($allInfoArray,$i)
                {
                    $join->where('RoomTran.HTL_ID','=',Session::get('userInfo.HTL_ID'))
                        ->where('RoomDepositAcct.HTL_ID', '=', Session::get('userInfo.HTL_ID'))
                        ->where('RoomDepositAcct.RM_TRAN_ID','=',$allInfoArray[$i]->RM_TRAN_ID)
                        ->on('RoomTran.RM_TRAN_ID','=','RoomDepositAcct.RM_TRAN_ID');
                })
                ->where(function($query)
                {
                    $query->where('RoomDepositAcct.FILLED', '!=', 'T')
                        ->orWhereNull('RoomDepositAcct.FILLED');
                })
                ->select('RoomDepositAcct.RM_DEPO_ID as RM_DEPO_ID','RoomDepositAcct.RM_TRAN_ID as RM_TRAN_ID',
                    'RoomDepositAcct.DEPO_AMNT as DEPO_AMNT','RoomDepositAcct.DEPO_TSTMP as DEPO_TSTMP',
                    'RoomDepositAcct.REF_ID as REF_ID','RoomDepositAcct.PAY_METHOD as PAY_METHOD',
                    'RoomDepositAcct.ORGN_ACCT_ID as ORGN_ACCT_ID','RoomDepositAcct.RMRK as RMRK',
                    'RoomDepositAcct.EMP_ID as EMP_ID','RoomDepositAcct.FILLED as FILLED',
                    'RoomDepositAcct.SUB_CAT as SUB_CAT','RoomTran.RM_ID')
                ->get();

            $acctRoomStore = DB::table('RoomStoreTran')
                ->join('RoomTran', function($join) use($allInfoArray,$i)
                {
                    $join->where('RoomTran.HTL_ID','=',Session::get('userInfo.HTL_ID'))
                        ->where('RoomStoreTran.HTL_ID', '=', Session::get('userInfo.HTL_ID'))
                        ->where('RoomStoreTran.TKN_RM_TRAN_ID','=',$allInfoArray[$i]->RM_TRAN_ID)
                        ->on('RoomTran.RM_TRAN_ID','=','RoomStoreTran.RM_TRAN_ID');
                })
                ->leftjoin('StoreTransaction','StoreTransaction.STR_TRAN_ID','=','RoomStoreTran.STR_TRAN_ID')
                ->leftjoin('ProductInTran','ProductInTran.STR_TRAN_ID','=','RoomStoreTran.STR_TRAN_ID')
                ->leftjoin('ProductInfo','ProductInfo.PROD_ID','=','ProductInTran.PROD_ID')
                ->where(function($query)
                {
                    $query->where('RoomStoreTran.FILLED', '!=', 'T')
                        ->orWhereNull('RoomStoreTran.FILLED');
                })
                ->select('RoomStoreTran.STR_TRAN_ID as STR_TRAN_ID','RoomStoreTran.RM_TRAN_ID as RM_TRAN_ID',
                    'RoomStoreTran.TKN_RM_TRAN_ID as TKN_RM_TRAN_ID','RoomStoreTran.FILLED as FILLED',
                    'StoreTransaction.STR_TRAN_TSTMP as STR_TRAN_TSTMP','StoreTransaction.STR_PAY_METHOD as STR_PAY_METHOD',
                    'StoreTransaction.STR_PAY_AMNT as STR_PAY_AMNT','StoreTransaction.ORGN_ACCT_ID as ORGN_ACCT_ID',
                    'ProductInTran.PROD_QUAN as PROD_QUAN','ProductInfo.PROD_NM as PROD_NM',
                    'StoreTransaction.EMP_ID as EMP_ID','StoreTransaction.RMRK as RMRK',
                    'RoomTran.RM_ID')
                ->get();

            $acctPenalty = DB::table('PenaltyAcct')
                ->join('RoomTran', function($join) use($allInfoArray,$i)
                {
                    $join->where('RoomTran.HTL_ID','=',Session::get('userInfo.HTL_ID'))
                        ->where('PenaltyAcct.HTL_ID', '=', Session::get('userInfo.HTL_ID'))
                        ->where('PenaltyAcct.TKN_RM_TRAN_ID','=',$allInfoArray[$i]->RM_TRAN_ID)
                        ->where('PenaltyAcct.FILLED', '!=', 'T')
                        ->on('RoomTran.RM_TRAN_ID','=','PenaltyAcct.RM_TRAN_ID');
                })
                ->where(function($query)
                {
                    $query->where('PenaltyAcct.FILLED', '!=', 'T')
                        ->orWhereNull('PenaltyAcct.FILLED');
                })
                ->select('PenaltyAcct.PEN_BILL_ID as PEN_BILL_ID','PenaltyAcct.RM_TRAN_ID as RM_TRAN_ID',
                    'PenaltyAcct.PNLTY_PAY_AMNT as PNLTY_PAY_AMNT','PenaltyAcct.BILL_TSTMP as BILL_TSTMP',
                    'PenaltyAcct.ORGN_ACCT_ID as ORGN_ACCT_ID','PenaltyAcct.PAY_METHOD as PAY_METHOD',
                    'PenaltyAcct.EMP_ID as EMP_ID','PenaltyAcct.TKN_RM_TRAN_ID as TKN_RM_TRAN_ID',
                    'PenaltyAcct.FILLED as FILLED','PenaltyAcct.BRK_EQPMT_RMRK as BRK_EQPMT_RMRK','RoomTran.RM_ID')
                ->get();
            $acct["AcctPay"] = array_merge($acct["AcctPay"],(array)$acctPay);
            $acct["AcctDepo"] = array_merge($acct["AcctDepo"],(array)$acctRoomDepo);
            $acct["AcctStore"] = array_merge($acct["AcctStore"],(array)$acctRoomStore);
            $acct["AcctPenalty"] = array_merge($acct["AcctPenalty"],(array)$acctPenalty);
        }
        return Response::json(array("room"=>$allInfoArray,"acct"=>$acct));
    }


    function checkOutSubmit(){
        $RoomArray = Input::get('RoomArray');
        $mastr_RM_TRAN_ID = Input::get('MasterRoomNpay')['mastr_RM_TRAN_ID'];
        $ori_Mastr_RM_TRAN_ID = Input::get('MasterRoomNpay')['ori_Mastr_RM_TRAN_ID'];
        $editAcct = Input::get('editAcct');
        $addAcct = Input::get('addAcct');
        $addDepoArray = Input::get('addDepoArray');
        $today = (new DateTime())->format('Y-m-d');
        try {
            DB::beginTransaction();   //////  Important !! TRANSACTION Begin!!!
            foreach ($RoomArray as $room){
                /*------------------------- change RoomTran -----------------------------------------------------*/
                $RoomTranUpdateArray = array(
                    "CHECK_OT_DT" => $today,
                    "DPST_RMN" => "0",
                    "FILLED" => "T"
                );
                DB::table('RoomTran')
                    ->where('RoomTran.HTL_ID', '=', Session::get('userInfo.HTL_ID'))
                    ->where('RM_TRAN_ID',$room["RM_TRAN_ID"])->update($RoomTranUpdateArray);
                /*------------------------- change Room Status ---------------- ---------------------------------*/
                $RoomUpdateArray = array(
                    "RM_TRAN_ID" => null,
                    "RM_CONDITION" => "脏房",
                );
                DB::table('Rooms')
                    ->where('Rooms.HTL_ID', '=', Session::get('userInfo.HTL_ID'))
                    ->where('Rooms.RM_TRAN_ID',$room["RM_TRAN_ID"])->update($RoomUpdateArray);
                /*------------------------- change Room Availablilty Check Quan ---------------------------------*/
                if($today < $room["oriCHECK_OT_DT"]){
                    DB::update('update RoomOccupation set CHECK_QUAN = CHECK_QUAN - ? where HTL_ID=? and RM_TP = ? and DATE >= ? and DATE < ?',
                        array(1, $room["RM_TP"], Session::get('userInfo.HTL_ID'), $today, $room["oriCHECK_OT_DT"]) );
                }
            }
            /*------------------------- update all Accts  -------------------------------------------------------*/
            foreach($editAcct["RoomAcct"] as $updateArray){
                DB::table('RoomAcct')
                    ->where('RoomAcct.HTL_ID', '=', Session::get('userInfo.HTL_ID'))
                    ->where('RoomAcct.RM_BILL_ID',$updateArray["RM_BILL_ID"])->update($updateArray);
            }
            foreach($editAcct["PenaltyAcct"] as $updateArray){
                DB::table('PenaltyAcct')
                    ->where('PenaltyAcct.HTL_ID', '=', Session::get('userInfo.HTL_ID'))
                    ->where('PEN_BILL_ID',$updateArray["PEN_BILL_ID"])->update($updateArray);
            }
            foreach($editAcct["RoomStoreTran"] as $updateArray){
                DB::table('RoomStoreTran')
                    ->where('RoomStoreTran.HTL_ID', '=', Session::get('userInfo.HTL_ID'))
                    ->where('RoomStoreTran.STR_TRAN_ID',$updateArray["STR_TRAN_ID"])->update($updateArray);
            }
            foreach($editAcct["RoomDepositAcct"] as $updateArray){
                DB::table('RoomDepositAcct')
                    ->where('RoomDepositAcct.HTL_ID', '=', Session::get('userInfo.HTL_ID'))
                    ->where('RoomDepositAcct.RM_DEPO_ID',$updateArray["RM_DEPO_ID"])->update($updateArray);
            }
            /*------------------------- add all new Accts  -------------------------------------------------------*/
            foreach($addDepoArray as $insertArray){
                DB::table('RoomDepositAcct')->insertGetId($insertArray);
            }
            foreach($addAcct["RoomAcct"] as $insertArray){
                DB::table('RoomAcct')->insertGetId($insertArray);
            }
            foreach($addAcct["PenaltyAcct"] as $insertArray){
                DB::table('PenaltyAcct')->insertGetId($insertArray);
            }
            foreach($addAcct["RoomStore"] as $RoomStore){
                $STR_TRAN_ID = DB::table('StoreTransaction')->insertGetId($RoomStore["StoreTransaction"]);
                $RoomStore["RoomStoreTran"]["STR_TRAN_ID"] = $STR_TRAN_ID;
                $RoomStore["ProductInTran"]["STR_TRAN_ID"] = $STR_TRAN_ID;
                DB::table('ProductInTran')->insertGetId($RoomStore["ProductInTran"]);
                DB::table('RoomStoreTran')->insertGetId($RoomStore["RoomStoreTran"]);
            }
            if($ori_Mastr_RM_TRAN_ID != "" && $ori_Mastr_RM_TRAN_ID != $mastr_RM_TRAN_ID){
                /*------------------------- update all connected room master room number-----------------------------*/
                DB::table('RoomTran')
                    ->where('RoomTran.HTL_ID', '=', Session::get('userInfo.HTL_ID'))
                    ->where('RoomTran.CONN_RM_TRAN_ID',$ori_Mastr_RM_TRAN_ID)->update(array("CONN_RM_TRAN_ID"=>$mastr_RM_TRAN_ID));
                /*------------------------- update all connected room master room number-----------------------------*/
                DB::table('RoomAcct')
                    ->where('RoomAcct.HTL_ID', '=', Session::get('userInfo.HTL_ID'))
                    ->where('RoomAcct.TKN_RM_TRAN_ID',$ori_Mastr_RM_TRAN_ID)->update(array("TKN_RM_TRAN_ID"=>$mastr_RM_TRAN_ID));
                DB::table('PenaltyAcct')
                    ->where('PenaltyAcct.HTL_ID', '=', Session::get('userInfo.HTL_ID'))
                    ->where('PenaltyAcct.TKN_RM_TRAN_ID',$ori_Mastr_RM_TRAN_ID)->update(array("TKN_RM_TRAN_ID"=>$mastr_RM_TRAN_ID));
                DB::table('RoomStoreTran')
                    ->where('RoomStoreTran.HTL_ID', '=', Session::get('userInfo.HTL_ID'))
                    ->where('TKN_RM_TRAN_ID',$ori_Mastr_RM_TRAN_ID)->update(array("TKN_RM_TRAN_ID"=>$mastr_RM_TRAN_ID));
                DB::table('RoomDepositAcct')
                    ->where('RoomDepositAcct.HTL_ID', '=', Session::get('userInfo.HTL_ID'))
                    ->where('RM_TRAN_ID',$ori_Mastr_RM_TRAN_ID)->update(array("RM_TRAN_ID"=>$mastr_RM_TRAN_ID));
                /*-------------update DPST_RMN to new RoomTran--------------------******/
                $this->updateDPST_RMN($mastr_RM_TRAN_ID);
            }
        }catch (Exception $e){
            DB::rollback();
            $message=($e->getLine())."&&".$e->getMessage();
            throw new Exception($message);
            return Response::json($message);
        }finally{
            DB::commit();
            return Response::json("办理完毕!");
        }
    }

    function checkLedgerSubmit(){
        $addAcct = Input::get('addAcct');
        $depoDeduction = Input::get('depoDeduction');
        try {
            DB::beginTransaction();   //////  Important !! TRANSACTION Begin!!!

            /*------------------------- add all new Accts  -------------------------------------------------------*/
            foreach($addAcct["RoomAcct"] as $insertArray){
                DB::table('RoomAcct')->insertGetId($insertArray);
            }
            foreach($addAcct["PenaltyAcct"] as $insertArray){
                DB::table('PenaltyAcct')->insertGetId($insertArray);
            }
            foreach($addAcct["RoomStore"] as $RoomStore){
                $STR_TRAN_ID = DB::table('StoreTransaction')->insertGetId($RoomStore["StoreTransaction"]);
                $RoomStore["RoomStoreTran"]["STR_TRAN_ID"] = $STR_TRAN_ID;
                $RoomStore["ProductInTran"]["STR_TRAN_ID"] = $STR_TRAN_ID;
                DB::table('ProductInTran')->insertGetId($RoomStore["ProductInTran"]);
                DB::table('RoomStoreTran')->insertGetId($RoomStore["RoomStoreTran"]);
            }
            /*-------------update DPST_RMN to RoomTran--------------------******/

//            DB::update('update RoomTran set DPST_RMN =DPST_RMN - ? where RM_TRAN_ID = ?',
//                array($depoDeduction["DPST_RMN_DEDCUTION"],$depoDeduction["RM_TRAN_ID"]) );


            $this->updateDPST_RMN($depoDeduction["RM_TRAN_ID"]);

        }catch (Exception $e){
            DB::rollback();
            $message=($e->getLine())."&&".$e->getMessage();
            throw new Exception($message);
            return Response::json($message);
        }finally{
            DB::commit();
            return Response::json("办理完毕!");
        }
    }

    function updateDPST_RMN($RM_TRAN_ID){
        DB::update('update RoomTran set DPST_RMN = ? where HTL_ID =? and RM_TRAN_ID = ?',
            array($this->calculateDPST_RMN($RM_TRAN_ID),Session::get('userInfo.HTL_ID'),$RM_TRAN_ID) );
    }

    function calculateDPST_RMN($RM_TRAN_ID){
        $RoomTran = DB::table('RoomTran')
            ->where('RoomTran.HTL_ID', '=', Session::get('userInfo.HTL_ID'))
            ->where('RM_TRAN_ID',$RM_TRAN_ID)->first();
        $searchTranID = $RoomTran->RM_TRAN_ID;
        // if  connected room but not main room, no deposit
        if(!is_null($RoomTran->CONN_RM_TRAN_ID) && $RoomTran->RM_TRAN_ID != $RoomTran->CONN_RM_TRAN_ID){
            return 0;
        // if connected room and main room, search by
        }
        $RoomAcct = DB::table('RoomAcct')
            ->where('RoomAcct.HTL_ID', '=', Session::get('userInfo.HTL_ID'))
            ->where('TKN_RM_TRAN_ID',$searchTranID)->where('FILLED','!=','T')
            ->sum('RM_PAY_AMNT');
        $PenaltyAcct = DB::table('PenaltyAcct')
            ->where('PenaltyAcct.HTL_ID', '=', Session::get('userInfo.HTL_ID'))
            ->where('TKN_RM_TRAN_ID',$searchTranID)->where('FILLED','!=','T')
            ->sum('PNLTY_PAY_AMNT');
        $RoomStoreTran = DB::table('RoomStoreTran')
            ->join('StoreTransaction', function($join) use($searchTranID)
            {
                $join->where('RoomStoreTran.HTL_ID','=',Session::get('userInfo.HTL_ID'))
                    ->where('StoreTransaction.HTL_ID', '=', Session::get('userInfo.HTL_ID'))
                    ->where('RoomStoreTran.TKN_RM_TRAN_ID','=',$searchTranID)
                    ->where('RoomStoreTran.FILLED','!=','T')
                    ->on('StoreTransaction.STR_TRAN_ID','=','RoomStoreTran.STR_TRAN_ID');
            })
            ->sum('STR_PAY_AMNT');
        $RoomDepositAcct = DB::table('RoomDepositAcct')
            ->where('RoomDepositAcct.HTL_ID', '=', Session::get('userInfo.HTL_ID'))
            ->where('RM_TRAN_ID',$searchTranID)
            ->where('FILLED','!=','T')
            ->sum('DEPO_AMNT');
        return ($RoomDepositAcct-$RoomStoreTran-$PenaltyAcct-$RoomAcct);
    }
}
