<?php 
namespace App\gtphpsdk\web;
use App\gtphpsdk\lib\GeetestLib;
class VerifyLoginServlet{

    public function returnResult($request)
    {

       
        $GtSdk = new GeetestLib();
        //session('gtserver');
        if (session('gtserver') == 1) {

            $result = $GtSdk->validate($request->input('geetest_challenge'), $request->input('geetest_validate'), $request->input('geetest_seccode'));
            if ($result == TRUE) {

                return TRUE;
            } else if ($result == FALSE) {

                return FALSE;
            } else {

                return FALSE;
            }
        } else {
            if ($GtSdk->get_answer($request->input('geetest_validate'))) {

                return TRUE;
            } else {

                return FALSE;
            }

        }
    }
}
?>