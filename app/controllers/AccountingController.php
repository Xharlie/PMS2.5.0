<?php
/**
 * Created by PhpStorm.
 * User: Xharlie
 * Date: 8/29/14
 * Time: 5:08 PM
 */

class AccountingController extends BaseController{

    public function Summerize(){
        $sum = array();
        $sum['cash'] = $this->cashSumGet();
        $sum['cards'] = $this->cardsNumSumGet();
        $sum['store'] = $this->storeDetailGet();
        return Response::json($sum);
    }

    public function storeDetailGet(){
        $today = (new DateTime())->getTimeStamp();
        $twoDaysAgo = (new DateTime("-100 days"))->getTimeStamp();
        $productSellSum =  DB::table('StoreTransaction')
            ->whereRaw("UNIX_TIMESTAMP(StoreTransaction.STR_TRAN_TSTAMP) BETWEEN ".$twoDaysAgo." AND ".$today)
            ->join('ProductInTran','ProductInTran.STR_TRAN_ID','=','StoreTransaction.STR_TRAN_ID')
            ->leftjoin('ProductInfo','ProductInfo.PROD_ID','=','ProductInTran.PROD_ID')
            ->select(DB::raw('ProductInTran.PROD_ID as PROD_ID, ProductInfo.PROD_NM as PROD_NM,sum(ProductInTran.PROD_QUAN) as PROD_SUM_QUAN'))
            ->groupBy('ProductInTran.PROD_ID')
            ->get();
        return $productSellSum;
    }

    public function cashSumGet(){
        $today = (new DateTime())->getTimeStamp();
        $twoDaysAgo = (new DateTime("-100 days"))->getTimeStamp();
        $DepoAcct =  DB::table('RoomDepositAcct')
            ->whereRaw("UNIX_TIMESTAMP(RoomDepositAcct.DEPO_TSTMP) BETWEEN ".$twoDaysAgo." AND ".$today)
            ->where("PAY_METHOD","=","现金")
            ->sum('DEPO_AMNT');

        $StoreAcct =  DB::table('StoreTransaction')
            ->whereRaw("UNIX_TIMESTAMP(StoreTransaction.STR_TRAN_TSTAMP) BETWEEN ".$twoDaysAgo." AND ".$today)
            ->where("STR_PAY_METHOD","=","现金")
            ->sum('STR_PAY_AMNT');
        $cashGet = $DepoAcct + $StoreAcct;
        return $cashGet;
    }

    public function cardsNumSumGet(){
        $today = (new DateTime("+1 days"))->getTimeStamp();
        $twoDaysAgo = (new DateTime("-100 days"))->getTimeStamp();
        $cardSum =  DB::table('RoomTran')
            ->whereRaw("UNIX_TIMESTAMP(RoomTran.CHECK_IN_DT) BETWEEN ".$twoDaysAgo." AND ".$today)
            ->sum('CARDS_NUM');
        return $cardSum;
    }

    public function accountingGetAll(){
        $today = (new DateTime("+1 days"))->getTimeStamp();
        $twoDaysAgo = (new DateTime("-100 days"))->getTimeStamp();
        $DepoAcct =  DB::table('RoomDepositAcct')
            ->whereRaw("UNIX_TIMESTAMP(RoomDepositAcct.DEPO_TSTMP) BETWEEN ".$twoDaysAgo." AND ".$today)
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
            '' as 'CONSUME_PAY_AMNT',RoomDepositAcct.DEPO_AMNT as 'SUBMIT_PAY_AMNT',RoomDepositAcct.RMRK as RMRK,false as CON,true as PAY "));

        $PenalAcct =  DB::table('PenaltyAcct')
            ->whereRaw("UNIX_TIMESTAMP(PenaltyAcct.BILL_TSTMP) BETWEEN ".$twoDaysAgo." AND ".$today)
            ->leftjoin('RoomTran','RoomTran.RM_TRAN_ID','=','PenaltyAcct.RM_TRAN_ID')
            ->select(DB::raw("PenaltyAcct.BILL_TSTMP as TSTMP,'损坏罚金' as CLASS,
            PenaltyAcct.PEN_BILL_ID as ACCT_ID,RoomTran.RM_ID as RM_ID,
            PenaltyAcct.RM_TRAN_ID as RM_TRAN_ID,PenaltyAcct.PAYER_NM as PAYER_NM,PenaltyAcct.PAYER_PHONE as PAYER_PHONE,
            '' as PAY_METHOD,PenaltyAcct.PNLTY_PAY_AMNT as 'CONSUME_PAY_AMNT','' as 'SUBMIT_PAY_AMNT',
            PenaltyAcct.BRK_EQPMT_RMRK as RMRK,true as CON,false as PAY "));

        $BillAcct =  DB::table('RoomAcct')
            ->whereRaw("UNIX_TIMESTAMP(RoomAcct.BILL_TSTMP) BETWEEN ".$twoDaysAgo." AND ".$today)
            ->leftjoin('RoomTran','RoomTran.RM_TRAN_ID','=','RoomAcct.RM_TRAN_ID')
            ->select(DB::raw("RoomAcct.BILL_TSTMP as TSTMP,'夜核房费' as CLASS,
            RoomAcct.RM_BILL_ID as ACCT_ID,RoomTran.RM_ID as RM_ID,
            RoomAcct.RM_TRAN_ID as RM_TRAN_ID,'' as PAYER_NM,'' as PAYER_PHONE,
            '' as PAY_METHOD,RoomAcct.RM_PAY_AMNT as 'CONSUME_PAY_AMNT','' as 'SUBMIT_PAY_AMNT',RoomAcct.RMRK as RMRK
                ,true as CON,false as PAY" ));

        $StoreAcct =  DB::table('StoreTransaction')
            ->whereRaw("UNIX_TIMESTAMP(StoreTransaction.STR_TRAN_TSTAMP) BETWEEN ".$twoDaysAgo." AND ".$today)
            ->leftjoin('RoomStoreTran','RoomStoreTran.STR_TRAN_ID','=','StoreTransaction.STR_TRAN_ID')
            ->select(DB::raw("StoreTransaction.STR_TRAN_TSTAMP as TSTMP,'商品' as CLASS,
            StoreTransaction.STR_TRAN_ID as ACCT_ID,RoomStoreTran.RM_ID as RM_ID,
            RoomStoreTran.RM_TRAN_ID as RM_TRAN_ID,''as PAYER_NM,'' as PAYER_PHONE,
            StoreTransaction.STR_PAY_METHOD as PAY_METHOD,StoreTransaction.STR_PAY_AMNT as 'CONSUME_PAY_AMNT',
            CASE
                WHEN RoomStoreTran.RM_ID is Null THEN StoreTransaction.STR_PAY_AMNT
                ELSE ''
            END AS 'SUBMIT_PAY_AMNT',StoreTransaction.RMRK as RMRK,true as CON,
            CASE
                WHEN RoomStoreTran.RM_ID is Null THEN true
                ELSE false
            END AS PAY "));
        $allAcct = $DepoAcct->union($BillAcct)->union($PenalAcct)->union($StoreAcct)->get();
        return Response::json($allAcct);
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
        if($info["CLASS"] == "RoomDepositAcct"){
            $InsertArray = array(
                "DEPO_TSTMP" => date('Y-m-d H:i:s'),
                "ORGN_ACCT_ID" => $info["ACCT_ID"],
                "RM_TRAN_ID" => $info["RM_TRAN_ID"],
                "DEPO_AMNT" => $info["Amount"],
                "PAY_METHOD" => $info["payMethod"]
            );
            $RM_DEPO_ID = DB::table('RoomDepositAcct')->insertGetId($InsertArray);

        }elseif($info["CLASS"]  == "PenaltyAcct"){

            $InsertArray = array(
                "BILL_TSTMP" => date('Y-m-d H:i:s'),
                "ORGN_ACCT_ID" => $info["ACCT_ID"],
                "RM_TRAN_ID" => $info["RM_TRAN_ID"],
                "PNLTY_PAY_AMNT" => $info["Amount"],
                "PAY_METHOD" => $info["payMethod"],
                "BRK_EQPMT_RMRK"=>$info["RMRK"],
                "PAYER_NM"=>$info["PAYER_NM"],
                "PAYER_PHONE"=>$info["PAYER_PHONE"]
            );
            $PEN_BILL_ID = DB::table('PenaltyAcct')->insertGetId($InsertArray);

        }elseif($info["CLASS"]  == "RoomAcct"){

            $InsertArray = array(
                "BILL_TSTMP" => date('Y-m-d H:i:s'),
                "ORGN_ACCT_ID" => $info["ACCT_ID"],
                "RM_TRAN_ID" => $info["RM_TRAN_ID"],
                "PAY_METHOD" => $info["payMethod"],
                "BRK_EQPMT_RMRK"=>$info["RMRK"],
                "PAYER_NM"=>$info["PAYER_NM"]
            );
            $RM_BILL_ID = DB::table('RoomAcct')->insertGetId($InsertArray);

        }elseif($info["CLASS"]  == "StoreTransaction"){

            $InsertArray = array(
                "STR_TRAN_TSTAMP" => date('Y-m-d H:i:s'),
                "ORGN_ACCT_ID" => $info["ACCT_ID"],
                "STR_PAY_AMNT" => $info["Amount"],
                "STR_PAY_METHOD" => $info["payMethod"],
                "RMRK"=>$info["RMRK"]
            );
            $STR_TRAN_ID = DB::table('StoreTransaction')->insertGetId($InsertArray);

            if(!is_null($info["RM_TRAN_ID"])){
                $Insert = array(
                    "RM_TRAN_ID" => $info["RM_TRAN_ID"],
                    "STR_TRAN_ID" =>$STR_TRAN_ID,
                    "RM_ID" => $info["RM_ID"]
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