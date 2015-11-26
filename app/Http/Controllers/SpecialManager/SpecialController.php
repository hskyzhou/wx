<?php
/**
 * 专题管理的控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/24
 * Time: 9:56
 */
namespace App\Http\Controllers\SpecialManager;


use App\Http\Controllers\Controller;
use App\Services\SpecialService;
class SpecialController extends Controller{

    private $specialService;

    public function __construct(SpecialService $service){

        $this->specialService = $service;
    }

    /**
     * 专题列表
     * @return \Illuminate\View\View
     */
    public function getSpeciallist(){


                //查询专题列表

           /* $ret = $this->specialService->getCatesBySpecial(1);
            echo '<pre>';
            print_r($ret);
            exit;*/
             return view('special.speciallist');


    }

    /**
     * 添加专题get请求处理
     * @return \Illuminate\View\View
     */
    public function getAddspec(){

       return view('special.addspecial');

    }

}