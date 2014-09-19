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

         $allInfo = DB::table('RoomTran')
                    ->whereIn('RoomTran.RM_TRAN_ID',$info)
                    ->leftjoin('Rooms','Rooms.RM_TRAN_ID','=','RoomTran.RM_TRAN_ID')
                    ->leftjoin('RoomsTypes','Rooms.RM_TP','=','RoomsTypes.RM_TP')
                    ->get();
         $allInfoArray = json_decode(json_encode($allInfo));
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

    function getProductNM(){
        $name = DB::table('ProductInfo')->lists('PROD_NM');
        return $name;
    }

    function getProductPrice($NM){
        $price = DB::table('ProductInfo')
            ->where("PROD_NM",$NM)
            ->get();
        return Response::json($price);
    }

    function checkOutSubmit(){
        $info = Input::all();
        $MASTER_RM_ID = $info[1];
        foreach($info[0] as $room){
            if (count($room['RoomConsume']) !=0){
                $RoomStoreTran = array(
                    'RM_TRAN_ID'=> $room['RM_TRAN_ID'],
                    'RM_ID'=> $room['RM_ID']
                );
                $STR_TRAN_ID = DB::table('RoomStoreTran')->insertGetId($RoomStoreTran);
                $STR_PAY_AMNT = 0;
                foreach ($room['RoomConsume'] as $RoomConsume){
                    $ProductInTran = array(
                        'STR_TRAN_ID' => $STR_TRAN_ID,
                        'PROD_ID' => $RoomConsume['PROD_ID'],
                        'PROD_QUAN' => $RoomConsume['PROD_QUAN']
                    );
                    DB::table('ProductInTran')->insert($ProductInTran);
                    DB::update('update ProductInfo set PROD_AVA_QUAN = PROD_AVA_QUAN - ? where PROD_ID = ?',
                        array($RoomConsume['PROD_QUAN'],$RoomConsume['PROD_ID']));

                    $STR_PAY_AMNT += (float)$RoomConsume['STR_PAY_AMNT'];
                }
                $StoreTransaction = array(
                    'STR_TRAN_ID' => $STR_TRAN_ID,
                    'STR_TRAN_TSTAMP' => new DateTime(),
                    'STR_PAY_METHOD' => $room['RoomConsume'][0]['STR_PAY_METHOD'],
                    'STR_PAY_AMNT' => $STR_PAY_AMNT
                );
                DB::table('StoreTransaction')->insert($StoreTransaction);
            }

            if(count($room['penalty'])!=0){
                foreach ($room['penalty'] as $penalty){
                    $PenaltyAcct = array(
                        "RM_TRAN_ID" => $room['RM_TRAN_ID'],
                        "BRK_EQPMT_RMRK" => $penalty['RMRK'],
                        "PNLTY_PAY_AMNT" => $penalty['PAY_AMNT'],
                        "PNLTY_PAY_METHOD" => $penalty['PAY_METHOD'],
                        "BILL_TSTMP" => new DateTime(),
                        "PAYER_NM" => $penalty['PAYER'],
                        "PAYER_PHONE" => $penalty['PAYER_PHONE']
                    );
                }
            }

            $realPay = array(
                "DEPO_AMNT" => -1*$room['Sumation'],
                "RM_TRAN_ID" => $room['RM_TRAN_ID'],
                "PAY_METHOD" => $room['payMethod'],
                "DEPO_TSTMP" => new DateTime(), //DateTime::createFromFormat('Y-m-d H:i:s',new DateTime()),
                "RMRK" => "结算金额"
            );

            if($room["postPayMethod"]=="平账,可顺利退房"){
                $RM_DEPO_ID = DB::table('RoomDepositAcct')->insertGetId($realPay);
            }elseif($room["postPayMethod"]=="客人赊账" || $room["postPayMethod"]=="欠款转入主房"){
                $realPay["DEPO_AMNT"] = $room["realMoneyOut"];
                $RM_DEPO_ID = DB::table('RoomDepositAcct')->insertGetId($realPay);
                $compensatePay = array(
                    "DEPO_AMNT" => abs($room['Sumation'])-abs($room['realMoneyOut']) ,
                    "RM_TRAN_ID" => $room['RM_TRAN_ID'],
                    "PAY_METHOD" => $room['payMethod'],
                    "DEPO_TSTMP" => new DateTime(), //DateTime::createFromFormat('Y-m-d H:i:s',new DateTime()),
                    "RMRK" => $room["postPayMethod"]
                );
                $RM_DEPO_ID1 = DB::table('RoomDepositAcct')->insertGetId($compensatePay);
                if($room["postPayMethod"]=="欠款转入主房"){
                    $MasterPay = array(
                        "DEPO_AMNT" =>abs($room['realMoneyOut']) - abs($room['Sumation']),
                        "RM_TRAN_ID" => $MASTER_RM_ID,
                        "PAY_METHOD" => "连房欠款转入",
                        "DEPO_TSTMP" => new DateTime(), //DateTime::createFromFormat('Y-m-d H:i:s',new DateTime()),
                        "RMRK" => "房单".$room['RM_TRAN_ID']."欠款转入"
                    );
                    $RM_DEPO_ID1 = DB::table('RoomDepositAcct')->insertGetId($MasterPay);
                }


            }elseif($room["postPayMethod"]=="存入下次客人消费" || $room["postPayMethod"]=="余额转入主房"){
                $realPay["DEPO_AMNT"] = -1*$room["realMoneyOut"];
                $RM_DEPO_ID = DB::table('RoomDepositAcct')->insertGetId($realPay);
                $compensatePay = array(
                    "DEPO_AMNT" => abs($room['realMoneyOut'])-abs($room['Sumation']),
                    "RM_TRAN_ID" => $room['RM_TRAN_ID'],
                    "PAY_METHOD" => $room['payMethod'],
                    "DEPO_TSTMP" => new DateTime(), //DateTime::createFromFormat('Y-m-d H:i:s',new DateTime()),
                    "RMRK" => $room["postPayMethod"]
                );
                $RM_DEPO_ID1 = DB::table('RoomDepositAcct')->insertGetId($compensatePay);
                if($room["postPayMethod"]=="余额转入主房"){
                    $MasterPay = array(
                        "DEPO_AMNT" =>abs($room['Sumation'])-abs($room['realMoneyOut']) ,
                        "RM_TRAN_ID" => $MASTER_RM_ID,
                        "PAY_METHOD" => "连房余额转入",
                        "DEPO_TSTMP" => new DateTime(), //DateTime::createFromFormat('Y-m-d H:i:s',new DateTime()),
                        "RMRK" => "房单".$room['RM_TRAN_ID']."余额转入"
                    );
                    $RM_DEPO_ID1 = DB::table('RoomDepositAcct')->insertGetId($MasterPay);
                }
            }

            DB::table('Rooms')->where('RM_ID',$room["RM_ID"])
                ->update(array("RM_CONDITION"=>'Preparing','RM_TRAN_ID'=>null));
        }


    }

}
//$scope.BookRoom[i]["newFee"] = {"RMRK":"","PAY_METHOD":"","PAY_AMNT":""};