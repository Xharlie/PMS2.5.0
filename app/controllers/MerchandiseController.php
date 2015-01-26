<?php
/**
 * Created by PhpStorm.
 * User: Xharlie
 * Date: 7/16/14
 * Time: 6:37 PM
 */

class MerchandiseController extends BaseController{


    public function showProduct(){
        $productShow = DB::table('ProductInfo')
            ->select('ProductInfo.PROD_ID as PROD_ID','ProductInfo.PROD_TP as PROD_TP',
                'ProductInfo.PROD_NM as PROD_NM', 'ProductInfo.PROD_COST as PROD_COST',
                'ProductInfo.PROD_PRICE as PROD_PRICE','ProductInfo.PROD_AVA_QUAN as PROD_AVA_QUAN')
            ->get();
        return json_encode($productShow,JSON_NUMERIC_CHECK);
    }


    public function showMerchanRoom(){
        $roomShow = DB::table('Rooms')
            ->select('Rooms.RM_ID as RM_ID','Rooms.RM_TRAN_ID as RM_TRAN_ID',
                'Rooms.RM_CONDITION as RM_CONDITION', 'Rooms.RM_TP as RM_TP')
            ->get();
        return json_encode($roomShow);
    }

    public function buySubmit(){
        $info = Input::all();

        $storeTransactionArray = array(
            "STR_TRAN_TSTAMP" => date('Y-m-d H:i:s'),
            "STR_PAY_AMNT" => $info["STR_PAY_AMNT"],
        );

        $STR_TRAN_ID = DB::table('StoreTransaction')->insertGetId($storeTransactionArray);

        if (($info["RM_TRAN_ID"])!=""){
            DB::table('RoomStoreTran')->insert(
                array(
                    "RM_TRAN_ID" => $info["RM_TRAN_ID"],
                    "STR_TRAN_ID" => $STR_TRAN_ID,
                    "RM_ID" => $info["RM_ID"],
                ));
        }

        $productsArray = array();
        foreach($info["products"] as $product){
            DB::update('update ProductInfo set PROD_AVA_QUAN = PROD_AVA_QUAN - ? where PROD_ID = ?',array($product["AMOUNT"],$product["PROD_ID"]));

            $productArray = array(
              "STR_TRAN_ID" => $STR_TRAN_ID,
              "PROD_ID" => $product["PROD_ID"],
              "PROD_QUAN" => $product["AMOUNT"],
            );
            array_push($productsArray,$productArray);
        }
        DB::table('ProductInTran')->insert($productsArray);
        return $info;
    }

    public function showHistoPurchase(){
        $histoPurchase = DB::table('StoreTransaction')
            ->leftjoin('RoomStoreTran', 'RoomStoreTran.STR_TRAN_ID', '=', 'StoreTransaction.STR_TRAN_ID')
            ->select('StoreTransaction.STR_TRAN_ID as STR_TRAN_ID','StoreTransaction.STR_PAY_METHOD as STR_PAY_METHOD',
                'StoreTransaction.STR_PAY_AMNT as STR_PAY_AMNT', 'StoreTransaction.STR_TRAN_TSTAMP as STR_TRAN_TSTAMP',
                'RoomStoreTran.RM_TRAN_ID as RM_TRAN_ID','RoomStoreTran.RM_ID as RM_ID')
            ->get();
        return json_encode($histoPurchase,JSON_NUMERIC_CHECK);
    }

    public function showHistoProduct($STR_TRAN_ID){
        $histoProduct = DB::table('ProductInTran')
            ->where('ProductInTran.STR_TRAN_ID',$STR_TRAN_ID)
            ->leftjoin('ProductInfo','ProductInfo.PROD_ID','=','ProductInTran.PROD_ID')
            ->select('ProductInTran.PROD_ID as PROD_ID','ProductInfo.PROD_NM as PROD_NM',
                'ProductInfo.PROD_PRICE as PROD_PRICE','ProductInfo.PROD_AVA_QUAN as PROD_AVA_QUAN',
                'ProductInfo.PROD_TP as PROD_TP','ProductInTran.PROD_QUAN as PROD_QUAN')
            ->get();
        return json_encode($histoProduct,JSON_NUMERIC_CHECK);

    }

}