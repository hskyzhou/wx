<?php 
namespace App\gtphpsdk\msg;
use App\gtphpsdk\lib\MsgGeetestLib;
class  StartMsgCaptchaServlet{


    public function returnResult()
    {
        $GtMsgSdk = new MsgGeetestLib();
        $_SESSION['gtmsgsdk'] = $GtMsgSdk;
        if ($GtMsgSdk->register()) {
            $_SESSION['gtserver'] = 1;
            $result = array(
                'success' => 1,
                'gt' => CAPTCHA_ID,
                'challenge' => $GtMsgSdk->challenge
            );
            echo json_encode($result);
        } else {
            $_SESSION['gtserver'] = 0;
            $result = array(
                'success' => 0
            );
            echo json_encode($result);

        }
    }
}

 ?>