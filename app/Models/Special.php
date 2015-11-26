<?php
/**
 * 专题数据库操作类
 * Created by PhpStorm.
 * User: xu.gao
 * Date: 2015/11/24
 * Time: 13:25
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Special extends Model{

    protected $table = 'special';

    /**
     * 关联关系
     * 系列分类
     */
    public function cates(){

        return $this->belongsTo('App\Models\Cates','cate_id','cate_id');
    }
    /**
     * 获取专题列表
     * 分页
     */
    public static function getSpecialList(){


    }


}