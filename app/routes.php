<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Blade::setContentTags('<%', '%>'); 		// for variables and all things Blade
Blade::setEscapedContentTags('<%%', '%%>'); 	// for escaped data



/*------------------------------------Auth--------------------------------------------*/



Route::get('/', array('before' => 'authen',function()
{
    return View::make('full');
}));

Route::group(array('prefix' => 'directiveViews'), function()
{
    Route::get('{all}', function($all)
    {
        return View::make("directiveViews".".".$all);
    });
});


Route::get('/', array('before' => 'authen',function()
{
    return View::make('full');
}));

Route::get('/logon', function()
{
    return View::make('logon',array('err' => Session::get("err")));
});

Route::get('/dialog', function()
{
    return View::make('dialog');
});



Route::post('/logonPost', 'UserController@logon');


Route::get('logout', function()
{
    Auth::logout();
    return Redirect::to('/');
});


Route::get('/newResv', function()
{
    return View::make('new',array( 'pageType' => 'newResv'));
});

Route::post('/newCheckIn/',function(){
            $data['pageType']='newCheckIn';
            $rooms = Input::all();
            return View::make('new', array( 'data'=> $data, 'roomsID'=> $rooms ));
        }
);




Route::group(array('prefix' => 'newCheckIn'), function()
{
    Route::get('{all}', function()
    {
        return View::make('new',array( 'pageType' => 'newCheckIn'));
    })->where('all', '.*');
});

Route::group(array('prefix' => 'newCheckOut'), function()
{
    Route::get('{all}', function()
    {
        return View::make('new',array( 'pageType' => 'newCheckOut'));
    })->where('all', '.*');
});

Route::group(array('prefix' => 'newModifyWindow'), function()
{
    Route::get('{all}', function()
    {
        return View::make('new',array( 'pageType' => 'newModifyWindow'));
    })->where('all', '.*');
});

/* for Database */

// get room and roomTypes info by rm_condition;
Route::get('/getRoomAndRoomType/{RM_CONDITION}', 'RoomStatusController@getRoomAndRoomType');

Route::get('/showReservation', 'ReservationController@showResv');

Route::get('/showRoomStatus', 'RoomStatusController@showRoom');

Route::get('/showOccupied/{RM_TRAN_ID}', 'RoomStatusController@showOccupied');

Route::get('/showCusInRoom/{RM_ID}', 'NewCheckInController@showCusInRoom');

Route::get('/showEmpty/{RM_TP}', 'RoomStatusController@showEmpty');

Route::get('/showCustomer', 'CustomerController@showCustomer');

Route::get('/showMember', 'CustomerController@showMember');

Route::filter('checkInFilter', function($RM_ID,$RM_TP){
        if (is_null($RM_ID)){
            return "Room Id not been past.....";
        }
    }
);

// get clicked room's  info in single check in;
Route::get('/getSingleRoomInfo/{RM_ID}', 'NewCheckInController@getSingleRoomInfo');
// get  all rooms  info in  check in;
Route::get('/getRoomInfo', 'NewCheckInController@getRoomInfo');
// get  all rooms info with availability;
Route::post('/getRMInfoWithAvail', 'NewCheckInController@getRMInfoWithAvail');



Route::get('/showSoldOut/{checkInDt}/{checkOtDt}', 'NewCheckInController@showSoldOut');


Route::get('/showRoomQuan', 'NewCheckInController@showRoomQuan');

// Route::get('/showRoomUnAvail', 'NewCheckInController@showRoomUnAvail');

Route::get('/showHistoCustomer/{SSN}', 'NewCheckInController@showHistoCustomer');

// check in search members
Route::post('/searchMembers', 'NewCheckInController@searchMembers');


Route::get('/showMemberBySSN/{SSN}', 'NewCheckInController@showMemberBySSN');

Route::get('/showMemberByID/{MEM_ID}', 'NewCheckInController@showMemberByID');

// check in search treaties
Route::post('/searchTreaties', 'NewCheckInController@searchTreaties');

Route::get('/showTreatyByID/{TREATY_ID}', 'NewCheckInController@showTreatyByID');

Route::get('/showTreatyByCorp/{CORP_NM}', 'NewCheckInController@showTreatyByCorp');

Route::post('/submitCheckIn','NewCheckInController@submitCheckIn');

Route::post('/submitModify','NewCheckInController@submitModify');




Route::get('/showProduct', 'MerchandiseController@showProduct');

Route::get('/showMerchanRoom', 'MerchandiseController@showMerchanRoom');

Route::post('/buySubmit','MerchandiseController@buySubmit');

Route::get('/showHistoPurchase', 'MerchandiseController@showHistoPurchase');

Route::get('/showHistoProduct/{STR_TRAN_ID}', 'MerchandiseController@showHistoProduct');

Route::get('/change2Mending/{RM_ID}', 'RoomStatusController@change2Mending');

Route::get('/change2Mended/{RM_ID}', 'RoomStatusController@change2Mended');

Route::get('/Change2Cleaned/{RM_ID}', 'RoomStatusController@Change2Cleaned');

Route::get('/roomAccounting/{RM_TRAN_ID}', 'RoomStatusController@showAccounting');

Route::get('/getConnect/{RM_TRAN_ID}', 'RoomStatusController@getConnect');

Route::post('/checkOutGetInfo','NewCheckOutController@checkOutGetInfo');

Route::get('/getProductNM', 'NewCheckOutController@getProductNM');

Route::get('/getProductPrice/{NM}', 'NewCheckOutController@getProductPrice');

Route::post('/checkOutSubmit', 'NewCheckOutController@checkOutSubmit');



Route::post('/submitResv','ReservationController@submitResv');


Route::get('/accountingGetAll','AccountingController@accountingGetAll');


Route::get('/getTargetAcct/{DB}/{ACCT_ID}', 'AccountingController@getTargetAcct');

Route::post('/submitModifyAcct','AccountingController@submitModifyAcct');

Route::get('/summerize','AccountingController@Summerize');



Route::get('/roomTpGet','SettingRoomController@getRoomTp');
Route::get('/roomsGet','SettingRoomController@getRooms');
Route::get('/tempPlanGet','SettingRoomController@getTempPlan');



Route::group(array('prefix' => 'roomTpDelete'), function()
{
    Route::get('{all}','SettingRoomController@deleteRoomTp');
});



Route::group(array('prefix' => 'roomsDelete'), function()
{
    Route::get('{all}','SettingRoomController@deleteRooms');
});

Route::group(array('prefix' => 'floorsDelete'), function()
{
    Route::get('{all}','SettingRoomController@deleteFloors');
});

Route::post('/roomTpAdd','SettingRoomController@addRoomTp');

Route::post('/roomTpEdit','SettingRoomController@editRoomTp');

Route::post('/roomsEdit','SettingRoomController@editRooms');

Route::post('/roomsAdd','SettingRoomController@addRooms');

Route::post('/floorsEdit','SettingRoomController@editFloors');

Route::post('/floorsAdd','SettingRoomController@addFloors');

Route::post('/planEdit','SettingRoomController@editPlan');

Route::get('/planDelete/{PLAN_ID}','SettingRoomController@deletePlan');

Route::post('/planAdd','SettingRoomController@addPlan');

/*------------------------------------------Filter---------------------------------------------*/

Route::filter('authen', function()
{
    if (!Auth::check())
    {
        return Redirect::to('/logon');
    }
});



/*-----------------------------------------Resource--------------------------------------------*/
