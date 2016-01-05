<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

/*服务*/
use App\Services\Contracts\ButtonContract;

/*仓库*/
use MenuRepository;
use PermissionRepository;

/**
 * 菜单管理
 * 
 * @author		wen.zhou@bioon.com
 * 
 * @date		2015-10-15 16:47:10
 */
class MenuController extends Controller
{   
    /*当前登录用户*/
    protected $current_user;

    /*添加菜单*/
    protected $admin_add_menus;

    /*管理菜单*/
    protected $admin_manage_menus;

    /*查看菜单列表*/
    protected $admin_list_menus;

    /*修改菜单*/
    protected $admin_update_menus;

    /*删除菜单*/
    protected $admin_delete_menus;

	public function __construct(){
	   	$this->current_user = auth()->user();

        $this->admin_add_menus = config('backend.menu.admin_add_menus');
        $this->admin_manage_menus = config('backend.menu.admin_manage_menus');
        $this->admin_list_menus = config('backend.menu.admin_list_menus');
        $this->admin_update_menus = config('backend.menu.admin_update_menus');
        $this->admin_delete_menus = config('backend.menu.admin_delete_menus');

        $this->middleware('permission:' . $this->admin_manage_menus);
        $this->middleware('permission:' . $this->admin_list_menus, ['only' => ['getShow', 'getMenulist']]);

        $this->middleware('permission:' . $this->admin_update_menus, ['only' => ['getUpdate', 'postUpdate']]);
        $this->middleware('permission:' . $this->admin_add_menus, ['only' => ['getAdd', 'postAdd']]);
        $this->middleware('permission:' . $this->admin_delete_menus, ['only' => ['getDelete']]);
	}

    public function getIndex(){
        return redirect('menu/show');
    }

	/**
	 * 显示菜单列表
	 * 
	 * @param		
	 * 
	 * @author		wen.zhou@bioon.com
	 * 
	 * @date		2015-10-15 16:47:28
	 * 
	 * @return		
	 */
    public function getShow(ButtonContract $buttonContract){

        $add_button = $buttonContract->createAddModalButton($this->admin_add_menus, route('menu.add.get'), '添加菜单')->getReturnStr();

    	return view('admin.menu.show')->with(compact('add_button'));
    }

    /**
     * 获取菜单列表
     * 
     * @param		
     * 
     * @author		wen.zhou@bioon.com
     * 
     * @date		2015-10-16 13:33:19
     * 
     * @return		
     */
    public function getMenulist(){
    	$draw = request('sEcho', 1);
    	$length = request('iDisplayLength', 10);
    	$start = request('iDisplayStart', 0);
    	$search = request('sSearch', '');

        $data = [
            'search' => $search,
            'start' => $start,
            'length' => $length,
        ];

        /*获取菜单总量*/
    	$count = MenuRepository::count();
        /*获取父类菜单总量*/
        $filter_count  = MenuRepository::parent_count($data);
        
        /*获取 菜单列表*/
        $menus = MenuRepository::menuList($data);

    	$returnData = [
    		"draw" => $draw,
			"recordsTotal" => $count,
			"recordsFiltered" => $filter_count,
			"data" => $menus
    	];
    	return response()->json($returnData);
    }

    /**
     * 获取修改菜单界面
     * 
     * @param        
     * 
     * @author        wen.zhou@bioon.com
     * 
     * @date        2015-10-19 18:05:04
     * 
     * @return        
     */
    public function getUpdate(){
        $id = request('id', 0);
        $returnData = [];
        if(!empty($id)){
            $menu = MenuRepository::menuInfo($id);

            if(!empty($menu)){
                /*权限*/
                $permissions = PermissionRepository::permissions();

                /*父级菜单*/
                $menus = MenuRepository::parentMenus();

                $returnData['status'] = true;
                $returnData['msg'] = "获取成功";
                $returnData['menu'] = $menu;
                $returnData['menu_permissions'] = $permissions;
                $returnData['menu_menus'] = $menus;

            }else{
                $returnData['status'] = false;
                $returnData['msg'] = "获取失败";
            }
        }else{
            $returnData['status'] = false;
            $returnData['msg'] = "获取数据错误";
        }

        return view('admin.menu.update')->with($returnData);
    }

    /**
     * 修改菜单数据
     * 
     * @param		
     * 
     * @author		wen.zhou@bioon.com
     * 
     * @date		2015-10-16 15:15:49
     * 
     * @return		
     */
    public function postUpdate(){
    	$id = request('id', '');
    	$returnData = [];
    	if(!empty($id)){
    		$menu = MenuRepository::menuInfo($id);

    		if(!empty($menu)){
    			// 修改菜单
                $data = [
                    'name' => request('name', $menu->name),
                    'description' => request('description', $menu->description),
                    'url' => request('url', $menu->url),
                    'slug' => request('slug', $menu->slug),
                    'parent_id' => request('parent_id', $menu->parent_id),
                    'menu_order' => request('menu_order', $menu->menu_order),
                ];

                /*修改菜单*/
                $upMenuData = MenuRepository::upMenu($menu, $data);

                /*设置返回数据*/
                $returnData['status'] = $upMenuData['status'];
                $returnData['msg'] = $upMenuData['msg'];

                if($upMenuData['status']){
                    $returnData['data'] = $upMenuData['data']->toArray();
                }
    		}
    	}else{
            $returnData['status'] = false;
            $returnData['msg'] = "获取数据错误";
        }

    	return response()->json($returnData);
    }

    /**
     * 获取添加界面
     * 
     * @param        
     * 
     * @author        wen.zhou@bioon.com
     * 
     * @date        2015-10-19 18:18:55
     * 
     * @return        
     */
    public function getAdd(){
        /*权限*/
        $permissions = PermissionRepository::permissions();
        /*父级菜单*/
        $menus = MenuRepository::parentMenus();

        $returnData = [
            'menu_permissions' => $permissions,
            'menu_menus' => $menus
        ];

        return view('admin.menu.add')->with($returnData);
    }

    /**
     * 新建菜单
     * 
     * @param		
     * 
     * @author		wen.zhou@bioon.com
     * 
     * @date		2015-10-16 16:59:05
     * 
     * @return		
     */
    public function postAdd(){
    	$name = request('name', '');
    	$returnData = [];
    	if(empty($name)){
    		$returnData = [
    			'status' => false,
    			'msg' => '菜单名称不能为空',
    			'csrftoken' => csrf_token()
    		];
    	}else{
            $data = [
                'name' => request('name', ''),
                'description' => request('description', ''),
                'url' => request('url', ''),
                'slug' => request('slug', ''),
                'parent_id' => request('parent_id', ''),
                'menu_order' => request('menu_order', 1),
            ];

            /*添加菜单*/
            $addMenuData = MenuRepository::addMenu($data);

            if($addMenuData['status']){
                $returnData = $addMenuData['data']->toArray();
                $returnData['csrftoken'] = csrf_token();
                $returnData['status'] = $addMenuData['status'];
                $returnData['msg'] = "添加成功";
            }
    	}

    	return response()->json($returnData);
    }

    /**
     * 删除菜单
     * 
     * @param		
     * 
     * @author		wen.zhou@bioon.com
     * 
     * @date		2015-10-16 11:53:55
     * 
     * @return		
     */
    public function getDelete(){
    	$id = Request('id', 0);

    	if(is_numeric($id) && !empty($id)){

    		$delete_bool = MenuRepository::delMenu($id);
    		
            if($delete_bool){
    			$returnData = [
    				'status' => true,
    				'msg' => '删除成功'
    			];
    		}else{
    			$returnData = [
    				'status' => false,
    				'msg' => '删除失败'
    			];
    		}
    	}else{
    		$returnData = [
    			'status' => false,
    			'msg' => '数据错误'
    		];
    	}
		return response()->json($returnData);
    }
}
