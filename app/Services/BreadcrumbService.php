<?php
	namespace App\Services;

	use App\Services\Contracts\BreadcrumbContract;

	use App\Menu;

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

			/*获取菜单条件*/
			$menu = Menu::where('url', '=', $currentPath);

			$breadcrumbs = [];

			while(!$menu->get()->isEmpty()){
				$selected_menu = $menu->first();

				/*设置breadcrumbs*/
				$click = ($selected_menu->url != '#' && $selected_menu->url != '') ? true : false;

				array_unshift($breadcrumbs, [
					'value' => $selected_menu->name,
					'url' => $selected_menu->url,
					'click' => $click,
				]);

				$menu = $selected_menu->parentmenu();
			}
			
			return $breadcrumbs;
		}
	}