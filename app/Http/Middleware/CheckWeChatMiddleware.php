<?php

namespace App\Http\Middleware;

use Closure;

use Log;

class CheckWeChatMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $signature = request('signature');
        $timestamp = request('timestamp');
        $nonce = request('nonce');
        $token = weChatToken();
        $echostr = request('echostr', '');


        $tmpArr = [$nonce, $timestamp, $token];
        sort($tmpArr, SORT_STRING);   

        $tmpString = implode($tmpArr);
        $tempString = sha1($tmpString);

        if($tempString == $signature){
            if($echostr){
                echo $echostr;
                exit;
            }
        }else{
            echo '';
            exit;
        }

        return $next($request);
    }
}
