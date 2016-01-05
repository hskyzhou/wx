<?php 
	namespace App\Repositories\Backend;

	/*model*/
	use App\Models\Menu;

	use App\Services\Contracts\MenuContract;
	use App\Services\Contracts\ButtonContract;

	class MenuRepository{
		/*当前应用*/
		protected $app;

		/*菜单服务*/
		protected $menuCon;

		/*按钮服务*/
		protected $btnContract;

		/*修改菜单*/
		protected $admin_update_menus;

		/*删除菜单*/
		protected $admin_delete_menus;

		public function __construct($app){
			$this->app = $app;
			
			$this->menuCon = $this->app->make(MenuContract::class);
			$this->btnContract = $this->app->make(ButtonContract::class);


			$this->admin_update_menus = config('backend.menu.admin_update_menus');
			$this->admin_delete_menus = config('backend.menu.admin_delete_menus');

		}

		/*====================================获取数据======================*/
		/**
		 * 获取所有 父级 菜单
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-04 19:39:49
		 * 
		 * @return		array
		 */
		public function parentMenus(){
			return Menu::where('parent_id', '=', '0')->get()->toArray();
		}

		/**
		 * 获取 menu 信息
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-04 19:41:15
		 * 
		 * @return		
		 */
		public function menuInfo($id){
			return Menu::getById($id)->first();
		}

		/**
		 * 获取menu信息  通过 url
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-05 13:20:09
		 * 
		 * @return		
		 */
		public function menuInfoByUrl($url){
			return Menu::where('url', '=', $url)->first();
		}

		/**
		 * 菜单搜索
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-04 19:54:15
		 * 
		 * @return		
		 */
		public function menuList($data){
			$menu = Menu::select()->orderBy('menu_order', 'desc');

		    if(!empty($data['search'])){
				$menu = $menu->where('name', 'like', "%{$data['search']}%");
			}

		    /*获取所有菜单并转化为数组*/
			$id_menus = $menu->get()->keyBy('id')->toArray();
		    /*通过menuContract服务处理menu*/
			$deal_menus = $this->menuCon->menuLevelDeal($id_menus);
		    /*返回结果菜单集*/
			$result_menus = collect($deal_menus)->slice($data['start'], $data['length']);//通过collect对象
		    $menus = $result_menus->toArray();

		    foreach($result_menus as $key => $result_menu){
		    	$id = $result_menu['id'];
		        $menus[$key]['button'] = $this->btnContract
										        ->createUpdateModalButton($this->admin_update_menus, route('menu.update.get', ['id' => $id]))
										        ->createDeleteButton($this->admin_delete_menus, 'menu_delete', $id)
										        ->getReturnStr();
		    }
		    return $menus;
		}

		/**
		 * 获取 菜单总量
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-04 19:55:38
		 * 
		 * @return		
		 */
		public function count(){
			return Menu::count();
		}

		/**
		 * 获取父类菜单总量			
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-04 19:58:08
		 * 
		 * @return		
		 */
		public function parent_count($data){
			$menu = new Menu;

			if(!empty($data['search'])){
				$menu = $menu->where('name', 'like', "%{$data['search']}%");
			}

			return $menu->where('parent_id', '=', 0)->count();
		}

		/**
		 * 获取 所有菜单
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-05 11:26:51
		 * 
		 * @return		
		 */
		public function menuAll(){
			return Menu::orderBy('menu_order', 'desc')->get();
		}

		

		/*====================================添加数据======================*/
		/**
		 * 添加 菜单
		 * 
		 * @param		$data    array	
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-04 18:07:51
		 * 
		 * @return		
		 */
		public function addMenu($data){
			$returnData = [
				'status' => false,
				'msg' => '菜单添加失败',
			];
			/*添加menu*/
			$menu = new Menu;
			$menu->name = request('name', '');
			$menu->description = request('description', '');
			$menu->url = request('url', '');
			$menu->slug = request('slug', '');
			$menu->parent_id = request('parent_id', '');
			$menu->menu_order = request('menu_order', '');

			if($menu->save()){
				$returnData['status'] = true;
				$returnData['data'] = $menu;
				$returnData['msg'] = "菜单添加成功";
			}

			return $returnData;
		}
		

		/*====================================修改数据======================*/
		/**
		 * 修改 菜单
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-04 19:48:16
		 * 
		 * @return		
		 */
		public function upMenu($menu, $data){
			$returnData = [
				'status' => false,
				'msg' => '菜单修改失败',
			];

			$menu->name = $data['name'];
			$menu->description = $data['description'];
			$menu->url = $data['url'];
			$menu->slug = $data['slug'];
			$menu->parent_id = $data['parent_id'];
			$menu->menu_order = $data['menu_order'];

			if($menu->save()){
				$returnData = [
					'status' => true,
					'msg' => '菜单修改成功',
					'data' => $menu
				];
			}

			return $returnData;
		}


		/*====================================删除数据======================*/
		/**
		 * 删除 菜单
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-04 19:28:51
		 * 
		 * @return		
		 */
		public function delMenu($id){
			return Menu::destroy($id);
		}
	}
?>