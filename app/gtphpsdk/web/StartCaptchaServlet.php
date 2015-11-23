<?php 
/**
 * 使用Get的方式返回：challenge和capthca_id 
 * 此方式以实现前后端完全分离的开发模式 专门实现failback
 * @author Tanxu
 */
namespace App\gtphpsdk\web;
use App\gtphpsdk\lib\GeetestLib;
class   StartCaptchaServlet {

    public function  returnResult()
    {

        $GtSdk = new GeetestLib();
        
        $return = $GtSdk->register();
        if ($return) {

            session(['gtserver' => 1]);
            $result = array(
                'success' => 1,
                'gt' => CAPTCHA_ID,
                'challenge' => $GtSdk->challenge
            );
            echo json_encode($result);
        } else {
            
            session(['gtserver' => 0]);
            $rnd1 = md5(rand(0, 100));
            $rnd2 = md5(rand(0, 100));
            $challenge = $rnd1 . substr($rnd2, 0, 2);
            $result = array(
                'success' => 0,
                'gt' => CAPTCHA_ID,
                'challenge' => $challenge
            );
            
            session(['challenge' => $result['challenge']]);
            echo json_encode($result);

        }

    }
}

 ?>