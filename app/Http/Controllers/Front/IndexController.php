<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use SoapBox\Formatter\Formatter;

use Log;

use App\Services\Contracts\WxReceiveNormalContracts;
use App\Services\Contracts\WxReceiveTextContracts;

class IndexController extends Controller
{
    protected $wxReceNor;
    protected $wxReceText;

    public function __construct(WxReceiveNormalContracts $wxReceNor, WxReceiveTextContracts $wxReceText){
        $this->wxReceNor = $wxReceNor;
        $this->wxReceText = $wxReceText;
    }
    /**
     * index  function
     * 
     * @param        
     * 
     * @author        wen.zhou@bioon.com
     * 
     * @date        2016-03-04 13:55:51
     * 
     * @return        
     */
    public function index(Request $request){
        $xmlArr = $this->getData();

        switch (strtolower($xmlArr['MsgType'])) {
            case 'text':
                # code...
                break;
            
            case 'event':
                $this->responseEvent($xmlArr);
                break;
            default:
                # code...
                break;
        }
    }

    /**
     * 处理事件
     * 
     * @param        
     * 
     * @author        xezw211@gmail.com
     * 
     * @date        2016-03-08 19:14:40
     * 
     * @return        
     */
    protected function responseEvent($data){
        $returnData = [];
        switch (strtolower($data['Event'])) {
            /*订阅公众账号*/
            case 'subscribe':
                $returnData = [
                    'ToUserName' => $data['FromUserName'],
                    'FromUserName' => $data['ToUserName'],
                    'Content' => trans('label.welcome.text'),
                ];
                $returnText = $this->wxReceNor->sendTextInfo($returnData);
                break;
            
            /*取消订阅公众账号*/
            case 'unsubscribe':
                exit;
                break;
            default:
                # code...
                break;
        }

        echo $returnText;
        exit;
    }

    /**
     * 获取 微信传递的 xml数据
     * 
     * @param        
     * 
     * @author        xezw211@gmail.com
     * 
     * @date        2016-03-08 19:27:04
     * 
     * @return        
     */
    protected function getData(){
        $returnData = [];
        $xmlData = file_get_contents("php://input");
        /*xml数据存在*/
        if($xmlData){
            $formatter = Formatter::make($xmlData, Formatter::XML);
            $returnData = $formatter->toArray();
        }

        return $returnData;
    }
}
