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
            Log::info('equal');
            if($echostr){
                Log::info($echostr);
                echo $echostr;
                exit;
            }
        }else{
            Log::info('not equal');
            echo '';
            exit;
        }

        Log::info('go on');
        return $next($request);
    }
}
