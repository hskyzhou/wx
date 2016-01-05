<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BaseModelTrait;

class Menu extends Model
{
	use BaseModelTrait;
	
    public function parentmenu(){
    	return $this->hasOne('App\Menu', 'id', 'parent_id');
    }
}
