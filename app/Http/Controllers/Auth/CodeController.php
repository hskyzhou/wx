<?php
/**
 * 验证码控制器
 * Created by PhpStorm.
 * User: xu.gao@bioon.com
 * Date: 2015/11/20
 * Time: 16:57
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\gtphpsdk\web\StartCaptchaServlet;
class CodeController extends Controller{


    public function __construct(){


    }

    public function getRefushCode(){


        $start = new StartCaptchaServlet;
        $start->returnResult();
    }


}