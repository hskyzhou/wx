<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use SoapBox\Formatter\Formatter;

use Log;

class IndexController extends Controller
{
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
                    'Content' => "欢迎关注python爱好者关注好"
                ];
                break;
            
            /*取消订阅公众账号*/
            case 'unsubscribe':
                exit;
                break;
            default:
                # code...
                break;
        }

        $returnText = $this->setReturnText($returnData);

        echo $returnText;
        exit;
    }


    protected function setReturnText($data){
        $template = "
            <xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            </xml>
        ";

        return sprintf($template, $data['ToUserName'], $data['FromUserName'], time(), $data['Content']);
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
