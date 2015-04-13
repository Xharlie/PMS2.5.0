<?php

class UserController extends BaseController{

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
            Session::forget('chances');
            $this->loadUserInfo();
            return Redirect::intended('/');
        }
        else
        {
            if (Session::has('chances')){
                Session::put('chances',Session::get("chances")-1);
            }else{
                Session::put('chances',2);
            }
            $message = "用户名或密码错误,还有".(string)(Session::get("chances"))."次机会";
            if (Session::get("chances")==0){
                $message="各级店长注意,已触发警报";
            }
            return Redirect::to('/logon')->with("err", $message);
        }
    }


    public function loadUserInfo(){
        if(!Auth::check()){
            return Redirect::to('/logon')->with("err", "用户名或密码错误");
        }else{
            $id = Auth::id();
            $emp = DB::table('EmployeeSysAccess')
                ->where('id', '=', $id)
                ->first(array('id', 'EMP_NM','EMP_SYS_LVL'));
            Session::put('EMP_ID', $emp->id);
            Session::put('EMP_NM', $emp->EMP_NM);
            Session::put('EMP_SYS_LVL', $emp->EMP_SYS_LVL);
        }
    }

}
