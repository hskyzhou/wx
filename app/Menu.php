<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public function parentmenu(){
    	return $this->hasOne('App\Menu', 'id', 'parent_id');
    }
}
