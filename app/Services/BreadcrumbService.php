<?php
	namespace App\Services;

	use App\Services\Contracts\BreadcrumbContract;

	use MenuRepository;
	
	class BreadcrumbService implements BreadcrumbContract{
		/**
		 * 获取后台当前的面包屑导航
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2015-10-21 08:50:36
		 * 
		 * @return		
		 */
		public function getCurrentBreadcrumb(){
			$currentPath = request()->path();

			/*获取菜单*/
			$menu = MenuRepository::menuInfoByUrl($currentPath);

			$breadcrumbs = [];

			if($menu){
				while(!$menu){
					/*设置breadcrumbs*/
					$click = ($menu->url != '#' && $menu->url != '') ? true : false;

					array_unshift($breadcrumbs, [
						'value' => $menu->name,
						'url' => $menu->url,
						'click' => $click,
					]);

					$menu = $selected_menu->parentmenu();
				}
			}
			
			return $breadcrumbs;
		}
	}