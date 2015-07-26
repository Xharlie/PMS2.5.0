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


Route::get('/test', function()
{
    return Session::get("test");
});

Route::get('/', function()
{
    return View::make('full',array('userInfo' => Session::get("flashUserInfouserInfo")));
});

Route::group(array('prefix' => 'directiveViews'), function()
{
    Route::get('{all}', function($all)
    {
        return View::make("directiveViews".".".$all);
    });
});

Route::group(array('prefix' => 'parts'), function()
{
    Route::get('{all}', function($all)
    {
        return View::make("parts".".".$all);
    });
});


Route::get('/logon', function()
{
    return View::make('logon',array('err' => Session::get("err")));
});

// getUserInfo
Route::get('/getUserInfo', 'UserController@getUserInfo');


Route::get('/dialog', function()
{
    return View::make('dialog');
});



Route::post('/logonPost', 'UserController@logon');


Route::get('/logout', function()
{
    Session::flush();
    Auth::logout();
    return Redirect::intended('/logon');
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

Route::get('/showComResv/{today}', 'ReservationController@showComResv');

Route::get('/showRoomStatus', 'RoomStatusController@showRoom');

Route::get('/getAllRoomTypes', 'RoomStatusController@getAllRoomTypes');

Route::get('/showOccupied/{RM_TRAN_ID}', 'RoomStatusController@showOccupied');

Route::get('/showCusInRoom/{RM_ID}', 'NewCheckInController@showCusInRoom');

Route::get('/showEmpty/{RM_TP}', 'RoomStatusController@showEmpty');

Route::get('/showCustomer', 'CustomerController@showCustomer');

Route::get('/showMembers', 'CustomerController@showMembers');
// get member type
Route::get('/showMemberType', 'CustomerController@showMemberType');
// adding a member
Route::post('/addMemberSubmit', 'CustomerController@addMemberSubmit');
// edit a member
Route::post('/editMemberSubmit', 'CustomerController@editMemberSubmit');




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
// get  all rooms  info in  check in;
Route::get('/getShiftOptions/{HTL_ID}', 'UserController@getShiftOptions');
// push  selected shift to Session;
Route::post('/putShiftChosen', 'UserController@putShiftChosen');

// get  all rooms info with availability;
Route::post('/getRMInfoWithAvail', 'ReservationController@getRMInfoWithAvail');
// get  get InfoCenter Info;
Route::get('/getInfoCenterInfo', 'InfoCenterController@getInfoCenterInfo');



Route::get('/showSoldOut/{checkInDt}/{checkOtDt}', 'NewCheckInController@showSoldOut');


Route::get('/showRoomQuan', 'NewCheckInController@showRoomQuan');

// Route::get('/showRoomUnAvail', 'NewCheckInController@showRoomUnAvail');
// get history customer for check in ......
Route::get('/showHistoCustomer/{SSN}', 'NewCheckInController@showHistoCustomer');

// check in search members
Route::post('/searchMembers', 'NewCheckInController@searchMembers');


Route::get('/showMemberBySSN/{SSN}', 'NewCheckInController@showMemberBySSN');

Route::get('/showMemberByID/{MEM_ID}', 'NewCheckInController@showMemberByID');

// check in search treaties
Route::post('/searchTreaties', 'NewCheckInController@searchTreaties');

Route::get('/showTreatyByID/{TREATY_ID}', 'NewCheckInController@showTreatyByID');

Route::get('/showTreatyByCorp/{CORP_NM}', 'NewCheckInController@showTreatyByCorp');

// room check in
Route::post('/submitCheckIn','NewCheckInController@submitCheckIn');

// room check in modify
Route::post('/submitModify','NewCheckInController@submitModify');

// setWakeUpCall
Route::post('/setWakeUpCall','NewCheckInController@setWakeUpCall');

// room deposit
Route::post('/submitDeposit','NewCheckInController@submitDeposit');




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

// submit the checkOut room, accts
Route::post('/checkOutSubmit', 'NewCheckOutController@checkOutSubmit');
Route::post('/checkLedgerSubmit', 'NewCheckOutController@checkLedgerSubmit');


// new reservation submit
Route::post('/submitResv','ReservationController@submitResv');
// edit reservation
Route::post('/editResv','ReservationController@editResv');

// acct get all info
Route::post('/accountingGetAll','AccountingController@accountingGetAll');


Route::get('/getTargetAcct/{DB}/{ACCT_ID}', 'AccountingController@getTargetAcct');

Route::post('/submitModifyAcct','AccountingController@submitModifyAcct');

// one key shift
Route::get('/summerize','AccountingController@summerize');
// shift submission
Route::post('/changeShiftSubmit','AccountingController@changeShiftSubmit');



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
//
//Route::filter('authen', function()
//{
//    if (!Auth::check())
//    {
//        return Redirect::to('/logon');
//    }
//});
//use Illuminate\Http\Request;

App::before(function($request){

    if(Request::getUri() == URL::to('logonPost') || Request::getUri() == URL::to('logon')
        || Request::getUri() == URL::to('logout')
        || Request::getUri() == URL::to('test')
    ) return ;



    if (!Auth::check() || !UserController::checkSessionTimeOutNValidity())
    {
        if(Request::ajax()) {
            return 'logout!';
        }else{
            return Redirect::intended('/logout');
        }
    }
});


/*-----------------------------------------Resource--------------------------------------------*/
