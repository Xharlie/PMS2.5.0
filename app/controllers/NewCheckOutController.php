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

}