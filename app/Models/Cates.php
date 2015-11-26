<?php
/**
 * 系列数据库操作类
 * Created by PhpStorm.
 * User: xu.gao
 * Date: 2015/11/24
 * Time: 14:10
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Cates extends Model{

    protected $table = 'cates';

    /**
     * 关联专题分类
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function specials(){

        return $this->hasMany('App\Models\Special');
    }

}