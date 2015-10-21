<?php  
	namespace App\Services;

	use App\Services\Contracts\MenuContract;

	use Auth, Redis;
	use App\Menu;

	/**
	 * 用户-菜单
	 * 
	 * @param		
	 * 
	 * @author		wen.zhou@bioon.com
	 * 
	 * @date		2015-10-15 13:22:31
	 * 
	 * @return		
	 */
	class UserMenuService implements MenuContract{
		public function __construct(){

		}

		/**
		 * 获取用户菜单
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2015-10-15 13:22:25
		 * 
		 * @return		
		 */
		
		public function getUserMenu(){
			$user = Auth::user();
			$menus = Menu::all();

			$user_menu = [];  //用户可以访问的菜单

			// if(Redis::command('HEXISTS', ['menu', $user->id])){
			// 	$user_menu = json_decode(Redis::command('HGET', ['menu', $user->id]), true);
			// }else{
				foreach($menus as $menu){
					if($user->can($menu->slug)){
						$user_menu[$menu->id] = $menu->toArray();
					}
				}

				$user_menu = $this->menuLevelDeal($user_menu);
			// 	Redis::command('HSET', ['menu', $user->id, json_encode($user_menu)]);
			// }

			return $user_menu;
		}

		/**
		 * 菜单 父类子类处理
		 * 
		 * @param		$menus  目前是二级分类
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2015-10-15 13:26:18
		 * 
		 * @return		
		 */
		public function menuLevelDeal($menus){
			if(!empty($menus)){
				foreach ($menus as $menu){
				    $menus[$menu['parent_id']]['son'][$menu['id']] = &$menus[$menu['id']];
				}
			}

			$user = Auth::user();

			$user_menu = isset($menus[0]['son']) ? $menus[0]['son'] : $menus;
			
			return $user_menu;
		}
	}
?>