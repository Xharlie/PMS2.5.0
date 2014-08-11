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

Route::get('/', function()
{
	return View::make('full');
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


/* for Database */
Route::get('/showReservation', 'ReservationController@showResv');

Route::get('/showRoomStatus', 'RoomStatusController@showRoom');

Route::get('/showOccupied/{RM_TRAN_ID}', 'RoomStatusController@showOccupied');

Route::get('/showEmpty/{RM_TP}', 'RoomStatusController@showEmpty');

Route::get('/showCustomer', 'CustomerController@showCustomer');

Route::get('/showMember', 'CustomerController@showMember');

Route::filter('checkInFilter', function($RM_ID,$RM_TP){
        if (is_null($RM_ID)){
            return "Room Id not been past.....";
        }
    }
);

Route::get('/showSoldOut/{checkInDt}/{checkOtDt}', 'NewCheckInController@showSoldOut');

Route::get('/showRoomQuan', 'NewCheckInController@showRoomQuan');

Route::get('/showRoomUnAvail', 'NewCheckInController@showRoomUnAvail');

Route::get('/showHistoCustomer/{SSN}', 'NewCheckInController@showHistoCustomer');

Route::get('/showMemberBySSN/{SSN}', 'NewCheckInController@showMemberBySSN');

Route::get('/showMemberByID/{MEM_ID}', 'NewCheckInController@showMemberByID');

Route::get('/showTreatyByID/{TREATY_ID}', 'NewCheckInController@showTreatyByID');

Route::get('/showTreatyByCorp/{CORP_NM}', 'NewCheckInController@showTreatyByCorp');

Route::post('/submitCheckIn','NewCheckInController@submit');




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