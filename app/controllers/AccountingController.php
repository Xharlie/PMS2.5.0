<?php
/**
 * Created by PhpStorm.
 * User: Xharlie
 * Date: 8/29/14
 * Time: 5:08 PM
 */

class AccountingController extends BaseController{

    public function summerize(){
        // get last shift info, last end time is the start time
        $lastShiftId = DB::table('Shifts')->max("SHFT_ID");
        if($lastShiftId != null){
            $lastShift = DB::table('Shifts')->where("SHFT_ID",$lastShiftId)->get()[0];
        }else{
            $lastShift = (Object)Array(
                                "SHFT_END_PSS2_CSH"=> 0,
                                "SHFT_END_TSTMP" => (new DateTime())->format("Y-m-d H:i:s")
                        );
        }
        $sum = array();
        $endTS = "'".(new DateTime())->format("Y-m-d H:i:s")."'";
        $startTS ="'".($lastShift->SHFT_END_TSTMP)."'";
        $lastCash =$lastShift->SHFT_END_PSS2_CSH;
        //  last cash past to this shift should be add
        $sum['cash'] = ($this->cashSumGet($startTS,$endTS)) + $lastCash;
        $sum['cards'] = $this->cardsNumSumGet($startTS,$endTS);
        $sum['store'] = $this->storeDetailGet($startTS,$endTS);
        $sum['receipt'] = $this->receiptNumSumGet($startTS,$endTS);
        $sum['member'] = $this->memberNumSumGet($startTS,$endTS);
        return Response::json($sum);
    }

    public function changeShiftSubmit(){
        $Shiftinfo = Input::all();
        $SHFT_CSH_BOX = $Shiftinfo["SHFT_CSH_BOX"];
        $SHFT_END_PSS2_CSH = $Shiftinfo["SHFT_END_PSS2_CSH"];
        $SHFT_PSS_EMP_ID = $Shiftinfo["SHFT_PSS_EMP_ID"];
        $pssEmpPw = $Shiftinfo["pssEmpPw"];
        $startTS =DB::table('Shifts')->max('SHFT_END_TSTMP');
        try {
            DB::beginTransaction();   ////
            $thisShift = array(
                "SHFT_ST_TSTMP" => $startTS,
                "SHFT_PSS_EMP_ID" => $SHFT_PSS_EMP_ID,
                "SHFT_END_TSTMP" =>  new DateTime(),
                "SHFT_END_PSS2_CSH" => $SHFT_END_PSS2_CSH,
                "SHFT_CSH_BOX" => $SHFT_CSH_BOX
            );
            DB::table('Shifts') ->insert($thisShift);
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

    public function receiptNumSumGet($startTS,$endTS){
        $DepoReceipt =  DB::table('DepositReceipt')
            ->whereRaw("DepositReceipt.DPST_TSTMP BETWEEN ".$startTS." AND ".$endTS)
            ->count('DPST_ID');
        return $DepoReceipt;
    }

    public function memberNumSumGet($startTS,$endTS){
        $MemberNumSum =  DB::table('MemberInfo')
            ->whereRaw("MemberInfo.IN_TSTMP BETWEEN ".$startTS." AND ".$endTS)
            ->count('MEM_ID');
        return $MemberNumSum;
    }

    public function storeDetailGet($startTS,$endTS){
        $productSellSum =  DB::table('StoreTransaction')
            ->whereRaw("StoreTransaction.STR_TRAN_TSTAMP BETWEEN ".$startTS." AND ".$endTS)
            ->join('ProductInTran','ProductInTran.STR_TRAN_ID','=','StoreTransaction.STR_TRAN_ID')
            ->leftjoin('ProductInfo','ProductInfo.PROD_ID','=','ProductInTran.PROD_ID')
            ->select(DB::raw("ProductInTran.PROD_ID as PROD_ID, ProductInfo.PROD_NM as PROD_NM,
                        sum(ProductInTran.PROD_QUAN) as PROD_SUM_QUAN, ProductInfo.PROD_AVA_QUAN as PROD_AVA_QUAN"))
            ->groupBy('ProductInTran.PROD_ID')
            ->get();
        return $productSellSum;
    }

    public function cashSumGet($startTS,$endTS){
        $DepoAcct =  DB::table('RoomDepositAcct')
            ->whereRaw("RoomDepositAcct.DEPO_TSTMP BETWEEN ".$startTS." AND ".$endTS)
            ->where("PAY_METHOD","=","现金")
            ->sum('DEPO_AMNT');

        $StoreAcct =  DB::table('StoreTransaction')
            ->whereRaw("StoreTransaction.STR_TRAN_TSTAMP BETWEEN ".$startTS." AND ".$endTS)
            ->where("STR_PAY_METHOD","=","现金")
            ->sum('STR_PAY_AMNT');
        $cashGet = $DepoAcct + $StoreAcct;
        return $cashGet;
    }

    public function cardsNumSumGet($startTS,$endTS){
        $cardSum =  DB::table('RoomTran')
            ->whereRaw("RoomTran.CHECK_IN_DT BETWEEN ".$startTS." AND ".$endTS)
            ->sum('CARDS_NUM');
        return $cardSum;
    }

    public function accountingGetAll(){
        $startTime = Input::get('startTime');
        $endTime = date('Y-m-d',strtotime(Input::get('endTime'))+86400000);
        $DepoAcct =  DB::table('RoomDepositAcct')
            ->whereRaw("RoomDepositAcct.DEPO_TSTMP BETWEEN '".$startTime."' AND '".$endTime."'")
//            ->whereRaw("UNIX_TIMESTAMP(RoomDepositAcct.DEPO_TSTMP) BETWEEN ".$twoDaysAgo." AND ".$today)
            ->leftjoin('RoomTran','RoomTran.RM_TRAN_ID','=','RoomDepositAcct.RM_TRAN_ID')
            ->select(DB::raw("RoomDepositAcct.DEPO_TSTMP as TSTMP,
            CASE
                WHEN RoomDepositAcct.DEPO_AMNT > 0 THEN '存入押金'
                WHEN RoomDepositAcct.DEPO_AMNT <= 0 THEN '退还押金'
                ELSE 'ERROR'
            END as CLASS,
            'RoomDepositAcct' as 'TABLE',
            RoomDepositAcct.FILLED as FILLED,
            RoomDepositAcct.RM_DEPO_ID as ACCT_ID,
            RoomTran.RM_ID as RM_ID,
            RoomDepositAcct.RM_TRAN_ID as RM_TRAN_ID,
            RoomDepositAcct.RM_TRAN_ID as TKN_RM_TRAN_ID,'' as PAYER_NM,'' as PAYER_PHONE,
            RoomDepositAcct.PAY_METHOD as PAY_METHOD,
            '' as 'CONSUME_PAY_AMNT',RoomDepositAcct.DEPO_AMNT as 'SUBMIT_PAY_AMNT',RoomDepositAcct.RMRK as RMRK,false as CON,true as PAY "));

        $PenalAcct =  DB::table('PenaltyAcct')
            ->whereRaw("PenaltyAcct.BILL_TSTMP BETWEEN '".$startTime."' AND '".$endTime."'")
//            ->whereRaw("UNIX_TIMESTAMP(PenaltyAcct.BILL_TSTMP) BETWEEN ".$twoDaysAgo." AND ".$today)
            ->leftjoin('RoomTran','RoomTran.RM_TRAN_ID','=','PenaltyAcct.TKN_RM_TRAN_ID')
            ->select(DB::raw("PenaltyAcct.BILL_TSTMP as TSTMP,'损坏罚金' as CLASS,
            'PenaltyAcct' as 'TABLE',
            PenaltyAcct.FILLED as FILLED,
            PenaltyAcct.PEN_BILL_ID as ACCT_ID,RoomTran.RM_ID as RM_ID,
            PenaltyAcct.RM_TRAN_ID as RM_TRAN_ID,
            PenaltyAcct.TKN_RM_TRAN_ID as TKN_RM_TRAN_ID,
            PenaltyAcct.PAYER_NM as PAYER_NM,PenaltyAcct.PAYER_PHONE as PAYER_PHONE,
            '' as PAY_METHOD,PenaltyAcct.PNLTY_PAY_AMNT as 'CONSUME_PAY_AMNT','' as 'SUBMIT_PAY_AMNT',
            PenaltyAcct.BRK_EQPMT_RMRK as RMRK,true as CON,false as PAY "));

        $BillAcct =  DB::table('RoomAcct')
            ->whereRaw("RoomAcct.BILL_TSTMP BETWEEN '".$startTime."' AND '".$endTime."'")
//            ->whereRaw("UNIX_TIMESTAMP(RoomAcct.BILL_TSTMP) BETWEEN ".$twoDaysAgo." AND ".$today)
            ->leftjoin('RoomTran','RoomTran.RM_TRAN_ID','=','RoomAcct.TKN_RM_TRAN_ID')
            ->select(DB::raw("RoomAcct.BILL_TSTMP as TSTMP,'夜核房费' as CLASS,
            'RoomAcct' as 'TABLE',
            RoomAcct.FILLED as FILLED,
            RoomAcct.RM_BILL_ID as ACCT_ID,RoomTran.RM_ID as RM_ID,
            RoomAcct.RM_TRAN_ID as RM_TRAN_ID,
            RoomAcct.TKN_RM_TRAN_ID as TKN_RM_TRAN_ID,
            '' as PAYER_NM,'' as PAYER_PHONE,
            '' as PAY_METHOD,RoomAcct.RM_PAY_AMNT as 'CONSUME_PAY_AMNT','' as 'SUBMIT_PAY_AMNT',RoomAcct.RMRK as RMRK
                ,true as CON,false as PAY" ));

        $StoreAcct =  DB::table('StoreTransaction')
            ->whereRaw("StoreTransaction.STR_TRAN_TSTAMP BETWEEN '".$startTime."' AND '".$endTime."'")
//            ->whereRaw("UNIX_TIMESTAMP(StoreTransaction.STR_TRAN_TSTAMP) BETWEEN ".$twoDaysAgo." AND ".$today)
            ->leftjoin('RoomStoreTran','RoomStoreTran.STR_TRAN_ID','=','StoreTransaction.STR_TRAN_ID')
            ->select(DB::raw("StoreTransaction.STR_TRAN_TSTAMP as TSTMP,
             CASE
                WHEN RoomStoreTran.RM_ID is Null THEN '商品现付'
                ELSE  '商品挂账'
            END as CLASS,
            'StoreTransaction' as 'TABLE',
            RoomStoreTran.FILLED as FILLED,
            StoreTransaction.STR_TRAN_ID as ACCT_ID,RoomStoreTran.RM_ID as RM_ID,
            RoomStoreTran.RM_TRAN_ID as RM_TRAN_ID,
            RoomStoreTran.TKN_RM_TRAN_ID as TKN_RM_TRAN_ID,
            ''as PAYER_NM,'' as PAYER_PHONE,
            StoreTransaction.STR_PAY_METHOD as PAY_METHOD,StoreTransaction.STR_PAY_AMNT as 'CONSUME_PAY_AMNT',
            CASE
                WHEN RoomStoreTran.RM_ID is Null THEN StoreTransaction.STR_PAY_AMNT
                ELSE ''
            END AS 'SUBMIT_PAY_AMNT',
            StoreTransaction.RMRK as RMRK,true as CON,
            CASE
                WHEN RoomStoreTran.RM_ID is Null THEN true
                ELSE false
            END AS PAY "));
        $allAcct = $DepoAcct->union($BillAcct)->union($PenalAcct)->union($StoreAcct)->get();
        return Response::json($allAcct);
    }

    public function getRoomAcct($DB,$ACCT_ID){

    }

    public function getTargetAcct($DB,$ACCT_ID){
        $targetAcct="";
        if($DB == "RoomDepositAcct"){
            $targetAcct =  DB::table('RoomDepositAcct')
                ->where("RoomDepositAcct.RM_DEPO_ID","$ACCT_ID")
                ->leftjoin('RoomTran','RoomTran.RM_TRAN_ID','=','RoomDepositAcct.RM_TRAN_ID')
                ->select(DB::raw("RoomDepositAcct.DEPO_TSTMP as TSTMP,
            CASE
                WHEN RoomDepositAcct.DEPO_AMNT > 0 THEN '存入押金'
                WHEN RoomDepositAcct.DEPO_AMNT <= 0 THEN '现金支出'
                ELSE 'ERROR'
            END AS 'CLASS',
            RoomDepositAcct.RM_DEPO_ID as ACCT_ID,RoomTran.RM_ID as RM_ID,
            RoomDepositAcct.RM_TRAN_ID as RM_TRAN_ID,'' as PAYER_NM,'' as PAYER_PHONE,
            RoomDepositAcct.PAY_METHOD as PAY_METHOD,
            '' as 'CONSUME_PAY_AMNT',RoomDepositAcct.DEPO_AMNT as 'SUBMIT_PAY_AMNT',''as RMRK"))
                ->get();
        }elseif($DB == "PenaltyAcct"){
            $targetAcct =  DB::table('PenaltyAcct')
                ->where("PenaltyAcct.PEN_BILL_ID","$ACCT_ID")
                ->leftjoin('RoomTran','RoomTran.RM_TRAN_ID','=','PenaltyAcct.RM_TRAN_ID')
                ->select(DB::raw("PenaltyAcct.BILL_TSTMP as TSTMP,'损坏罚金' as CLASS,
            PenaltyAcct.PEN_BILL_ID as ACCT_ID,RoomTran.RM_ID as RM_ID,
            PenaltyAcct.RM_TRAN_ID as RM_TRAN_ID,PenaltyAcct.PAYER_NM as PAYER_NM,PenaltyAcct.PAYER_PHONE as PAYER_PHONE,
            '' as PAY_METHOD,PenaltyAcct.PNLTY_PAY_AMNT as 'CONSUME_PAY_AMNT','' as 'SUBMIT_PAY_AMNT',
            PenaltyAcct.BRK_EQPMT_RMRK as RMRK"))
                ->get();
        }elseif($DB == "RoomAcct"){
            $targetAcct =  DB::table('RoomAcct')
                ->where("RoomAcct.RM_BILL_ID","$ACCT_ID")
                ->leftjoin('RoomTran','RoomTran.RM_TRAN_ID','=','RoomAcct.RM_TRAN_ID')
                ->select(DB::raw("RoomAcct.BILL_TSTMP as TSTMP,'夜核房费' as CLASS,
            RoomAcct.RM_BILL_ID as ACCT_ID,RoomTran.RM_ID as RM_ID,
            RoomAcct.RM_TRAN_ID as RM_TRAN_ID,'' as PAYER_NM,'' as PAYER_PHONE,
            '' as PAY_METHOD,RoomAcct.RM_PAY_AMNT as 'CONSUME_PAY_AMNT','' as 'SUBMIT_PAY_AMNT',''as RMRK" ))
                ->get();
        }elseif($DB == "StoreTransaction"){
            $targetAcct =  DB::table('StoreTransaction')
                ->where("StoreTransaction.STR_TRAN_ID","$ACCT_ID")
                ->leftjoin('RoomStoreTran','RoomStoreTran.STR_TRAN_ID','=','StoreTransaction.STR_TRAN_ID')
                ->select(DB::raw("StoreTransaction.STR_TRAN_TSTAMP as TSTMP,'商品' as CLASS,
            StoreTransaction.STR_TRAN_ID as ACCT_ID,RoomStoreTran.RM_ID as RM_ID,
            RoomStoreTran.RM_TRAN_ID as RM_TRAN_ID,''as PAYER_NM,'' as PAYER_PHONE,
            StoreTransaction.STR_PAY_METHOD as PAY_METHOD,StoreTransaction.STR_PAY_AMNT as 'CONSUME_PAY_AMNT',
            CASE
                WHEN RoomStoreTran.RM_ID = '0' THEN StoreTransaction.STR_PAY_AMNT
                ELSE ''
            END AS 'SUBMIT_PAY_AMNT','' as RMRK"))
                ->get();
        }
        return Response::json($targetAcct);
    }

    public function submitModifyAcct(){
        $info = Input::all();
        if($info["TABLE"] == "RoomDepositAcct"){
            $InsertArray = array(
                "RM_TRAN_ID" => $info["RM_TRAN_ID"],
                "DEPO_AMNT" => $info["Amount"],
                "PAY_METHOD" => $info["payMethod"],
                "DEPO_TSTMP" => date('Y-m-d H:i:s'),
                "ORGN_ACCT_ID" => $info["ORGN_ACCT_ID"],
                "RMRK" => $info["RMRK"],
                "FILLED" => $info["FILLED"]
            );
            $RM_DEPO_ID = DB::table('RoomDepositAcct')->insertGetId($InsertArray);

        }elseif($info["TABLE"]  == "PenaltyAcct"){

            $InsertArray = array(
                "RM_TRAN_ID" => $info["RM_TRAN_ID"],
                "PNLTY_PAY_AMNT" => $info["Amount"],
                "BILL_TSTMP" => date('Y-m-d H:i:s'),
                "ORGN_ACCT_ID" => $info["ORGN_ACCT_ID"],
                "PAY_METHOD" => $info["payMethod"],
                "TKN_RM_TRAN_ID" => $info["TKN_RM_TRAN_ID"],
                "FILLED" => $info["FILLED"],
                "BRK_EQPMT_RMRK" => $info["RMRK"]
            );
            $PEN_BILL_ID = DB::table('PenaltyAcct')->insertGetId($InsertArray);

        }elseif($info["TABLE"]  == "RoomAcct"){

            $InsertArray = array(
                "RM_TRAN_ID" => $info["RM_TRAN_ID"],
                "RM_PAY_AMNT" => $info["Amount"],
                "BILL_TSTMP" => date('Y-m-d H:i:s'),
                "ORGN_ACCT_ID" => $info["ORGN_ACCT_ID"],
                "RM_PAY_METHOD" => $info["payMethod"],
                "TKN_RM_TRAN_ID" => $info["TKN_RM_TRAN_ID"],
                "FILLED" => $info["FILLED"],
                "RMRK"=>$info["RMRK"]

            );
            $RM_BILL_ID = DB::table('RoomAcct')->insertGetId($InsertArray);

        }elseif($info["TABLE"]  == "StoreTransaction"){

            $InsertArray = array(
                "STR_TRAN_TSTAMP" => date('Y-m-d H:i:s'),
                "STR_PAY_METHOD" => $info["payMethod"],
                "STR_PAY_AMNT" => $info["Amount"],
                "ORGN_ACCT_ID" => $info["ORGN_ACCT_ID"],
                "RMRK"=>$info["RMRK"]
            );
            $STR_TRAN_ID = DB::table('StoreTransaction')->insertGetId($InsertArray);

            if(!is_null($info["RM_TRAN_ID"])){
                $Insert = array(
                    "RM_TRAN_ID" => $info["RM_TRAN_ID"],
                    "STR_TRAN_ID" =>$STR_TRAN_ID,
                    "RM_ID" => $info["RM_ID"],
                    "TKN_RM_TRAN_ID" => $info["TKN_RM_TRAN_ID"],
                    "FILLED" => $info["FILLED"]
                );
                DB::table('RoomStoreTran')->insert($Insert);
            }
        }
    }
}
////
//$scope.submitInfo["CLASS"]=$scope.oldTarget.CLASS;
//        $scope.submitInfo["ACCT_ID"]=$scope.oldTarget.ACCT_ID;
//        $scope.submitInfo["RM_TRAN_ID"]=$scope.oldTarget.RM_TRAN_ID;
//        $scope.submitInfo["Amount"]=$scope.Amount;
//        $scope.submitInfo["RMRK"]=$scope.RMRK;
//        $scope.submitInfo["payMethod"]=$scope.payMethod;
//        $scope.submitInfo["RM_ID"]=$scope.oldTarget.RM_ID;
//
//        $scope.submitInfo["PAYER_NM"]=$scope.oldTarget.PAYER_NM;
//        $scope.submitInfo["PAYER_PHONE"]=$scope.oldTarget.PAYER_PHONE;