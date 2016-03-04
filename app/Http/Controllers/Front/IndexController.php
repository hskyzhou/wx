<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

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
        // dd($request);
        \Log::info(request()->all());
    }

}
