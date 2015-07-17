<?php

class UserController extends BaseController{

    private $chanceMax = 3;

    public function logon(){
        if(Auth::check()){
            Auth::logout();
        }

        $userdata = array(
            'username' => Input::get('usr'),
            'password' => Input::get('pwd')
        );
        /* Try to authenticate the credentials */
        if(Auth::attempt($userdata))
        {
            $userInfo = $this->loadUserInfo(Auth::id());
            $this->chanceManagement(true);
//            return Redirect::intended('/')->with($userInfo);
            return Redirect::intended('/')->with("flashUserInfo", $userInfo);
        }
        else
        {
            return Redirect::intended('/logon')->with(
                "err",
                $this->chanceManagement(false)
            );
        }
    }


    public function loadUserInfo($id){
        /*****************************     get employee info     ******************************/

        $emp = DB::table('EmployeeSysAccess')
            ->join('HotelEmployeeMapping','HotelEmployeeMapping.EMP_ID','=','EmployeeSysAccess.id')
            ->join('HotelInfo','HotelInfo.HTL_ID','=','HotelEmployeeMapping.HTL_ID')
            ->where('id', '=', $id)
            ->first(array('EmployeeSysAccess.id as id', 'EmployeeSysAccess.EMP_NM as EMP_NM',
                'EmployeeSysAccess.username as username','EmployeeSysAccess.EMP_SYS_LVL as EMP_SYS_LVL',
                'HotelEmployeeMapping.HTL_ID as HTL_ID','HotelInfo.HTL_NM as HTL_NM'));
        // add condition
        $userInfo = array(
            'HTL_ID'=>$emp->HTL_ID,
            'HTL_NM'=>$emp->HTL_NM,
            'username' => $emp->username,
            'id' => $emp->id,
            'EMP_NM' => $emp->EMP_NM,
            'EMP_SYS_LVL' => $emp->EMP_SYS_LVL,
            'ST_TM' => (new DateTime()) ->format('Y-m-d H:i:s')  // start time of this username
        );
        /*****************************     one user has one session     ******************************/
        DB::update("update EmployeeSysAccess set SESSION_ID = ? where id = ?",
            array(Session::getId(), $id) );

        Session::put('userInfo', $userInfo);
        Session::put('LAST_ACTIVITY', time());
//        return $userInfo;
        return Session::all();
    }

    public function putLogonRecord($userInfo){
        /*****************************   LogonRecord recording      ******************************/
        $LGN_ID = DB::table('LogonRecord')->insertGetId(
            array(
                'EMP_ID' => $userInfo['id'],
                'HTL_ID' => $userInfo['HTL_ID'],
                'SHFT_ID' => $userInfo['SHFT_ID'],
                'LGN_TM' => $userInfo['ST_TM']
            )
        );
        return $LGN_ID;
    }

    public static function checkSessionTimeOutNValidity(){
        if(Session::get('userInfo')==null)  return UserController::terminateSession();
        $emp = DB::table('EmployeeSysAccess')
            ->where('id', '=', Session::get('userInfo')['id'])
            ->first(array('SESSION_ID'));
        $SESSION_ID = $emp->SESSION_ID ;
//        return Redirect::to('/test')->with("test", $SESSION_ID);
        if ($SESSION_ID != Session::getId()
            || !(Session::get('LAST_ACTIVITY'))
            || (time() - Session::get('LAST_ACTIVITY')) > 2*3600)           // 2 hours idle time
        {
            // Delete session data created by this app:
            return UserController::terminateSession();
        }else{
            // update last activity of session
            Session::put('LAST_ACTIVITY', time());
        }
    }

    public static function terminateSession(){
        Session::flush();
        return Redirect::intended('/logout');
    }

    public function chanceManagement($success){
        if($success){
            Session::put('trialsRemain',$this->chanceMax);
        }else{
            Session::put('trialsRemain',Session::get('trials')-1);
            $message = "用户名或密码错误,还有".(string)(Session::get("trialsRemain"))."次机会";
            if (Session::get("trialsRemain")<=0){
                $message="各级店长注意,已触发警报";
            }
            return $message;
        }
    }

    public function getUserInfo(){
//        $userInfo = Session::get('userInfo');
        $userInfo = Session::get("userInfo");
        return $userInfo;
    }

    public function getShiftOptions($HTL_ID){
        $shifts = DB::table('ShiftDefinition')
            ->where('HTL_ID', '=', $HTL_ID)
            ->select('SHFT_ID','SHFT_NM')
            ->get();
        return Response::json($shifts);
    }

    public function putShiftChosen(){
        $shift = Input::get('shift');
        Session::put('userInfo.SHFT_NM',$shift['SHFT_NM']);
        Session::put('userInfo.SHFT_ID',$shift['SHFT_ID']);
        /***************** fill the logonRecord table after all info gotten, and put the logon id  ******************/
        Session::put('LGN_ID',$this->putLogonRecord(Session::get('userInfo')));

        return Response::json(Session::get('userInfo'));
    }
}
