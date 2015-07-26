<?php
/**
 * Created by PhpStorm.
 * User: charlie
 * Date: 7/25/15
 * Time: 12:22 AM
 */
class InfoCenterController extends BaseController{
    /*** getClicked room info for single walk in check in    ***/
    public function getInfoCenterInfo(){
        $InfoCenterInfo = DB::table('InfoCenter')
            ->where('InfoCenter.HTL_ID','=',Session::get('userInfo.HTL_ID'))
            ->select('InfoCenter.MSG_INDX AS MSG_INDX','InfoCenter.MSG_TP AS MSG_TP',
                'InfoCenter.MSG_CNTXT AS MSG_CNTXT','InfoCenter.MSG_AMNT AS MSG_AMNT',
                'InfoCenter.MSG_SET_TSTMP AS MSG_SET_TSTMP','InfoCenter.MSG_ALRT_TSTMP AS MSG_ALRT_TSTMP',
                'InfoCenter.MSG_SHOW_TSTMP AS MSG_SHOW_TSTMP','InfoCenter.MSG_STATUS AS MSG_STATUS',
                'InfoCenter.RM_ID AS RM_ID','InfoCenter.HTL_ID AS HTL_ID')
            ->orderBy('InfoCenter.MSG_INDX', 'ASC')
            ->get();
        return Response::json($InfoCenterInfo);
    }
}