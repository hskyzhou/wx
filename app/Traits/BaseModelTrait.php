<?php 
	namespace App\Traits;
	
	trait BaseModelTrait{
		/**
		 * 获取 infos 表中 id数据
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2015-11-27 15:51:45
		 * 
		 * @return		
		 */
		public function scopeGetById($query, $id){
			return $query->where('id', '=', $id);
		}
	}
?>