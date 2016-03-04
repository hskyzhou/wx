<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use SoapBox\Formatter\Formatter;

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
        \Log::info(request());
        // \Log::info(request()->all());

        $str = '<xml>
<ToUserName><![CDATA[gh_88c164149eee]]></ToUserName>
<FromUserName><![CDATA[oYCsEj6T9NqEpY1wvgddqhSVUWkk]]></FromUserName>
<CreateTime>1457072778</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[涓夊浗鏉€]]></Content>
<MsgId>6258079929803722299</MsgId>
<Encrypt><![CDATA[HA4avQ1ziXHFLPI2IScAh8GFB7ccFCrC1+GhbmpLp57OLL+SNmpNSDeFVNKLcfZtGn+gAx+pXo8aaCS93fVtkQi8B/307EEMuJoDKEkwFY3Z73nLvCpCNijElzQoGkk0ceCJHD5YkMklLSsy2vVwIBGLNW4tq8SE7LMOAOMko75Kl5U9uk/j4xBNRI7LRQQ3qcLgFNZvta7tzL+l+GM9WozTDsKoPLEN5GBU5fZcuNA2fOV8kmoNcGkJxgreEyKTe2XDiZQ3WZddsuz4RVIF2EVIMOE742t1S0O+1QDxbB9hbctQGe4l/PmNTWo7bZjUe39C48vj8ehemUYVhE1mo8AKTiNCleicFpBnzjK0uGQ/2fKY0kzFQox+6XNAiDAI8Abv7fRj0MF835UU/02jiBodMf7NjU596vEXxIZALbxG9OR8qFWovEZ5RcebNqcqAN56JkN1P/nb3bA+StTN9g==]]></Encrypt>
</xml>';

        // $xml = simplexml_load_string($str);

        // dd($xml->ToUserName);
        // foreach($xml->children() as $child){
        //     echo $child, '<br />';
        // }

        $formatter = Formatter::make($str, Formatter::XML);

        $xmlArr = $formatter->toArray();

        print $xmlArr['ToUserName'];
    }

}
