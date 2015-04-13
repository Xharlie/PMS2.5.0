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
                    ->whereIn('RoomTran.RM_TRAN_ID',$info)
                    ->leftjoin('Rooms','Rooms.RM_TRAN_ID','=','RoomTran.RM_TRAN_ID')
                    ->leftjoin('RoomsTypes','Rooms.RM_TP','=','RoomsTypes.RM_TP')
                    ->get();
         $acct["AcctPay"] = array();
         $acct["AcctDepo"] = array();
         $acct["AcctStore"] = array();
         $acct["AcctPenalty"] = array();
         for ($i = 0; $i < count($allInfoArray); $i++){

             $allInfoArray[$i] = (array)$allInfoArray[$i];

             $allInfoArray[$i]["Customers"]= DB::table('Customers')
                 ->where('Customers.RM_TRAN_ID',$allInfoArray[$i]["RM_TRAN_ID"])
                 ->get();

             $acctPay = DB::table('RoomAcct')
                                        ->join('RoomTran','RoomTran.RM_TRAN_ID','=','RoomAcct.RM_TRAN_ID')
                                        ->where('TKN_RM_TRAN_ID','=',$allInfoArray[$i]["RM_TRAN_ID"])
                                        ->where('RoomAcct.FILLED', '=', 'F')
                                        ->get();

             $acctRoomDepo= DB::table('RoomDepositAcct')
                                        ->join('RoomTran','RoomTran.RM_TRAN_ID','=','RoomDepositAcct.RM_TRAN_ID')
                                        ->where('RoomDepositAcct.RM_TRAN_ID',$allInfoArray[$i]["RM_TRAN_ID"])
                                        ->where('RoomDepositAcct.FILLED', '=', 'F')
                                        ->get();

             $acctRoomStore = DB::table('RoomStoreTran')
                                             ->join('RoomTran','RoomTran.RM_TRAN_ID','=','RoomStoreTran.RM_TRAN_ID')
                                             ->where('RoomStoreTran.TKN_RM_TRAN_ID',$allInfoArray[$i]["RM_TRAN_ID"])
                                             ->leftjoin('StoreTransaction','StoreTransaction.STR_TRAN_ID','=','RoomStoreTran.STR_TRAN_ID')
                                             ->leftjoin('ProductInTran','ProductInTran.STR_TRAN_ID','=','RoomStoreTran.STR_TRAN_ID')
                                             ->leftjoin('ProductInfo','ProductInfo.PROD_ID','=','ProductInTran.PROD_ID')
                                             ->where('RoomStoreTran.FILLED', '=', 'F')
                                             ->get();

             $acctPenalty = DB::table('PenaltyAcct')
                                         ->join('RoomTran','RoomTran.RM_TRAN_ID','=','PenaltyAcct.RM_TRAN_ID')
                                         ->where('TKN_RM_TRAN_ID','=',$allInfoArray[$i]["RM_TRAN_ID"])
                                         ->where('PenaltyAcct.FILLED', '=', 'F')
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
                DB::table('RoomTran')->where('RM_TRAN_ID',$room["RM_TRAN_ID"])->update($RoomTranUpdateArray);

                /*------------------------- change Room Status ---------------- ---------------------------------*/
                $RoomUpdateArray = array(
                    "RM_TRAN_ID" => null,
                    "RM_CONDITION" => "脏房",
                );
                DB::table('Rooms')->where('RM_TRAN_ID',$room["RM_TRAN_ID"])->update($RoomUpdateArray);

                /*------------------------- change Room Availablilty Check Quan ---------------------------------*/
                if($today < $room["oriCHECK_OT_DT"]){
                    DB::update('update RoomOccupation set CHECK_QUAN = CHECK_QUAN - ? where RM_TP = ? and DATE >= ? and DATE < ?   ',
                        array(1,$room["RM_TP"],$today, $room["oriCHECK_OT_DT"]) );
                }
            }


            /*------------------------- update all Accts  -------------------------------------------------------*/
            foreach($editAcct["RoomAcct"] as $updateArray){
                DB::table('RoomAcct')->where('RM_BILL_ID',$updateArray["RM_BILL_ID"])->update($updateArray);
            }
            foreach($editAcct["PenaltyAcct"] as $updateArray){
                DB::table('PenaltyAcct')->where('PEN_BILL_ID',$updateArray["PEN_BILL_ID"])->update($updateArray);
            }
            foreach($editAcct["RoomStoreTran"] as $updateArray){
                DB::table('RoomStoreTran')->where('STR_TRAN_ID',$updateArray["STR_TRAN_ID"])->update($updateArray);
            }
            foreach($editAcct["RoomDepositAcct"] as $updateArray){
                DB::table('RoomDepositAcct')->where('RM_DEPO_ID',$updateArray["RM_DEPO_ID"])->update($updateArray);
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
                DB::table('RoomTran')->where('CONN_RM_TRAN_ID',$ori_Mastr_RM_TRAN_ID)->update(array("CONN_RM_TRAN_ID"=>$mastr_RM_TRAN_ID));
                /*------------------------- update all connected room master room number-----------------------------*/
                DB::table('RoomAcct')->where('TKN_RM_TRAN_ID',$ori_Mastr_RM_TRAN_ID)->update(array("TKN_RM_TRAN_ID"=>$mastr_RM_TRAN_ID));
                DB::table('PenaltyAcct')->where('TKN_RM_TRAN_ID',$ori_Mastr_RM_TRAN_ID)->update(array("TKN_RM_TRAN_ID"=>$mastr_RM_TRAN_ID));
                DB::table('RoomStoreTran')->where('TKN_RM_TRAN_ID',$ori_Mastr_RM_TRAN_ID)->update(array("TKN_RM_TRAN_ID"=>$mastr_RM_TRAN_ID));
                DB::table('RoomDepositAcct')->where('RM_TRAN_ID',$ori_Mastr_RM_TRAN_ID)->update(array("RM_TRAN_ID"=>$mastr_RM_TRAN_ID));
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

   /* old check out
    function checkOutGetInfo(){
         $info = Input::all();

         $allInfoArray = DB::table('RoomTran')
                    ->whereIn('RoomTran.RM_TRAN_ID',$info)
                    ->leftjoin('Rooms','Rooms.RM_TRAN_ID','=','RoomTran.RM_TRAN_ID')
                    ->leftjoin('RoomsTypes','Rooms.RM_TP','=','RoomsTypes.RM_TP')
                    ->get();
         $allInfoArray["AcctPay"] = [];
         $allInfoArray["AcctDepo"] = [];
         $allInfoArray["AcctStore"] = [];
         for ($i = 0; $i < count($allInfoArray); $i++){

             $allInfoArray[$i] = (array)$allInfoArray[$i];

             $allInfoArray[$i]["AcctPay"]= DB::table('RoomAcct')
                                        ->where('RM_TRAN_ID','=',$allInfoArray[$i]["RM_TRAN_ID"])
                                        ->get();

             $allInfoArray[$i]["AcctDepo"]= DB::table('RoomDepositAcct')
                                         ->where('RoomDepositAcct.RM_TRAN_ID',$allInfoArray[$i]["RM_TRAN_ID"])
                                         ->get();

             $allInfoArray[$i]["AcctStore"]= DB::table('RoomStoreTran')
                                             ->where('RoomStoreTran.RM_TRAN_ID',$allInfoArray[$i]["RM_TRAN_ID"])
                                             ->leftjoin('StoreTransaction','StoreTransaction.STR_TRAN_ID','=','RoomStoreTran.STR_TRAN_ID')
                                             ->leftjoin('ProductInTran','ProductInTran.STR_TRAN_ID','=','RoomStoreTran.STR_TRAN_ID')
                                             ->leftjoin('ProductInfo','ProductInfo.PROD_ID','=','ProductInTran.PROD_ID')
                                             ->get();
             $allInfoArray[$i]["Customers"]= DB::table('Customers')
                                             ->where('Customers.RM_TRAN_ID',$allInfoArray[$i]["RM_TRAN_ID"])
                                             ->get();
         }

         return Response::json($allInfoArray);
     }
*/
//    function getProductNM(){
//        $name = DB::table('ProductInfo')->lists('PROD_NM');
//        return $name;
//    }
//
//    function getProductPrice($NM){
//        $price = DB::table('ProductInfo')
//            ->where("PROD_NM",$NM)
//            ->get();
//        return Response::json($price);
//    }
//
//    function checkOutSubmit(){
//        $info = Input::all();
//        $MASTER_RM_ID = $info[1];
//        foreach($info[0] as $room){
//            if (count($room['RoomConsume']) !=0){
//                $RoomStoreTran = array(
//                    'RM_TRAN_ID'=> $room['RM_TRAN_ID'],
//                    'RM_ID'=> $room['RM_ID']
//                );
//                $STR_TRAN_ID = DB::table('RoomStoreTran')->insertGetId($RoomStoreTran);
//                $STR_PAY_AMNT = 0;
//                foreach ($room['RoomConsume'] as $RoomConsume){
//                    $ProductInTran = array(
//                        'STR_TRAN_ID' => $STR_TRAN_ID,
//                        'PROD_ID' => $RoomConsume['PROD_ID'],
//                        'PROD_QUAN' => $RoomConsume['PROD_QUAN']
//                    );
//                    DB::table('ProductInTran')->insert($ProductInTran);
//                    DB::update('update ProductInfo set PROD_AVA_QUAN = PROD_AVA_QUAN - ? where PROD_ID = ?',
//                        array($RoomConsume['PROD_QUAN'],$RoomConsume['PROD_ID']));
//
//                    $STR_PAY_AMNT += (float)$RoomConsume['STR_PAY_AMNT'];
//                }
//                $StoreTransaction = array(
//                    'STR_TRAN_ID' => $STR_TRAN_ID,
//                    'STR_TRAN_TSTAMP' => new DateTime(),
//                    'STR_PAY_AMNT' => $STR_PAY_AMNT
//                );
//                DB::table('StoreTransaction')->insert($StoreTransaction);
//            }
//
//            if(count($room['penalty'])!=0){
//                foreach ($room['penalty'] as $penalty){
//                    $PenaltyAcct = array(
//                        "RM_TRAN_ID" => $room['RM_TRAN_ID'],
//                        "BRK_EQPMT_RMRK" => $penalty['RMRK'],
//                        "PNLTY_PAY_AMNT" => $penalty['PAY_AMNT'],
//                        "BILL_TSTMP" => new DateTime(),
//                        "PAYER_NM" => $penalty['PAYER'],
//                        "PAYER_PHONE" => $penalty['PAYER_PHONE']
//                    );
//                    DB::table('PenaltyAcct')->insert($PenaltyAcct);
//                }
//            }
//
//
//
//
//            $realPay = array(
//                "DEPO_AMNT" => -1*$room['Sumation'],
//                "RM_TRAN_ID" => $room['RM_TRAN_ID'],
//                "PAY_METHOD" => $room['payMethod'],
//                "DEPO_TSTMP" => new DateTime(), //DateTime::createFromFormat('Y-m-d H:i:s',new DateTime()),
//                "RMRK" => "结算金额"
//            );
//
//            if($room["postPayMethod"]=="平账,可顺利退房"){
//                $RM_DEPO_ID = DB::table('RoomDepositAcct')->insertGetId($realPay);
//            }elseif($room["postPayMethod"]=="客人赊账" || $room["postPayMethod"]=="欠款转入主房"){
//                $realPay["DEPO_AMNT"] = $room["realMoneyOut"];
//                $RM_DEPO_ID = DB::table('RoomDepositAcct')->insertGetId($realPay);
//                $compensatePay = array(
//                    "DEPO_AMNT" => abs($room['Sumation'])-abs($room['realMoneyOut']) ,
//                    "RM_TRAN_ID" => $room['RM_TRAN_ID'],
//                    "PAY_METHOD" => $room['payMethod'],
//                    "DEPO_TSTMP" => new DateTime(), //DateTime::createFromFormat('Y-m-d H:i:s',new DateTime()),
//                    "RMRK" => $room["postPayMethod"]
//                );
//                $RM_DEPO_ID1 = DB::table('RoomDepositAcct')->insertGetId($compensatePay);
//                if($room["postPayMethod"]=="欠款转入主房"){
//                    $MasterPay = array(
//                        "DEPO_AMNT" =>abs($room['realMoneyOut']) - abs($room['Sumation']),
//                        "RM_TRAN_ID" => $MASTER_RM_ID,
//                        "PAY_METHOD" => "连房欠款转入",
//                        "DEPO_TSTMP" => new DateTime(), //DateTime::createFromFormat('Y-m-d H:i:s',new DateTime()),
//                        "RMRK" => "房单".$room['RM_TRAN_ID']."欠款转入"
//                    );
//                    $RM_DEPO_ID1 = DB::table('RoomDepositAcct')->insertGetId($MasterPay);
//                }
//
//
//            }elseif($room["postPayMethod"]=="存入下次客人消费" || $room["postPayMethod"]=="余额转入主房"){
//                $realPay["DEPO_AMNT"] = -1*$room["realMoneyOut"];
//                $RM_DEPO_ID = DB::table('RoomDepositAcct')->insertGetId($realPay);
//                $compensatePay = array(
//                    "DEPO_AMNT" => abs($room['realMoneyOut'])-abs($room['Sumation']),
//                    "RM_TRAN_ID" => $room['RM_TRAN_ID'],
//                    "PAY_METHOD" => $room['payMethod'],
//                    "DEPO_TSTMP" => new DateTime(), //DateTime::createFromFormat('Y-m-d H:i:s',new DateTime()),
//                    "RMRK" => $room["postPayMethod"]
//                );
//                $RM_DEPO_ID1 = DB::table('RoomDepositAcct')->insertGetId($compensatePay);
//                if($room["postPayMethod"]=="余额转入主房"){
//                    $MasterPay = array(
//                        "DEPO_AMNT" =>abs($room['Sumation'])-abs($room['realMoneyOut']) ,
//                        "RM_TRAN_ID" => $MASTER_RM_ID,
//                        "PAY_METHOD" => "连房余额转入",
//                        "DEPO_TSTMP" => new DateTime(), //DateTime::createFromFormat('Y-m-d H:i:s',new DateTime()),
//                        "RMRK" => "房单".$room['RM_TRAN_ID']."余额转入"
//                    );
//                    $RM_DEPO_ID1 = DB::table('RoomDepositAcct')->insertGetId($MasterPay);
//                }
//            }
//
//            DB::table('Rooms')->where('RM_ID',$room["RM_ID"])
//                ->update(array("RM_CONDITION"=>'脏房','RM_TRAN_ID'=>null));
//        }
//
//
//    }

}
//$scope.BookRoom[i]["newFee"] = {"RMRK":"","PAY_METHOD":"","PAY_AMNT":""};