<?php  
	namespace App\Services\Contracts;

	interface MenuContract{

		/*获取用户的菜单*/
		public function getUserMenu();

		/*处理菜单层级关系*/
		public function menuLevelDeal($menu);
	}
?>