<?php 
namespace App\gtphpsdk\web;
use App\gtphpsdk\lib\GeetestLib;
class VerifyLoginServlet{

    public function returnResult()
    {

       
        $GtSdk = new GeetestLib();
        //session('gtserver');
        if (session('gtserver') == 1) {

            $result = $GtSdk->validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode']);
            if ($result == TRUE) {

                return TRUE;
            } else if ($result == FALSE) {

                return FALSE;
            } else {

                return FALSE;
            }
        } else {
            if ($GtSdk->get_answer($_POST['geetest_validate'])) {

                return TRUE;
            } else {
                
                return FALSE;
            }

        }
    }
}
?>