<?php

namespace App\Http\Controllers\Diseaseadmin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Route;
use App\Menu;

use Bican\Roles\Models\Permission;

use App\Services\Contracts\MenuContract;

use Debugbar;

use Auth;
/**
 * 菜单管理
 * 
 * @author		wen.zhou@bioon.com
 * 
 * @date		2015-10-15 16:47:10
 */
class MenuController extends Controller
{
    protected $current_user;

	public function __construct(){
	   	$this->current_user = Auth::user();
        $this->middleware('permission:show.menu.manage');
        $this->middleware('permission:show.menu.list', ['only' => ['getShow', 'getMenulist']]);

        $this->middleware('permission:update.menus', ['only' => ['getUpdate', 'postUpdate']]);
        $this->middleware('permission:add.menus', ['only' => ['getAdd', 'postAdd']]);
        $this->middleware('permission:delete.menus', ['only' => ['getDelete']]);
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
    public function getShow(){
        $is_add = false;//添加权限

        if($this->current_user->can('add.menus')){
            $is_add = true;
        }

        $returnData = [
            'is_add' => $is_add,
    	];
    	return view('admin.menu.show')->with($returnData);
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
    public function getMenulist(MenuContract $menuCon){
    	$draw = request('sEcho', 1);
    	$length = request('iDisplayLength', 10);
    	$start = request('iDisplayStart', 0);
    	$search = request('sSearch', '');

    	Debugbar::info(request()->all());
        /*设置搜索条件*/
        $menu = new Menu;
        /*获取总量*/
    	$count = $menu->count();
        
        if(!empty($search)){
            $menu = $menu->where('name', 'like', "%{$search}%");
        }
    	// 获取父类菜单的总量
        $filter_count = $menu->where('parent_id', '=', 0)->count();

        /*获取所有菜单并转化为数组*/
    	$id_menus = $menu->get()->keyBy('id')->toArray();
        /*通过menuContract服务处理menu*/
    	$deal_menus = $menuCon->menuLevelDeal($id_menus);
        /*返回结果菜单集*/
    	$result_menus = collect($deal_menus)->slice($start, $length);//通过collect对象
        $menus = $result_menus->toArray();
        foreach($result_menus as $key => $result_menu){
            $menus[$key]['update'] = $this->current_user->can('update.menus');
            $menus[$key]['delete'] = $this->current_user->can('delete.menus');
        }

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
            $menu = Menu::where('id', '=', $id)->first()->toArray();
            if(!empty($menu)){
                /*权限*/
                $permissions = Permission::all('name', 'slug', 'description')->toArray();
                /*父级菜单*/
                $menus = Menu::where('parent_id', '=', '0')->get()->toArray();

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
    		$menu = Menu::where('id', '=', $id)->first();
    		if(!empty($menu)){
    			// 修改菜单
		    	$menu->name = request('name', $menu->name);
		    	$menu->description = request('description', $menu->description);
		    	$menu->url = request('url', $menu->url);
		    	$menu->slug = request('slug', $menu->slug);
		    	$menu->parent_id = request('parent_id', $menu->parent_id);

		    	$update_bool = $menu->save();

		    	/*修改成功*/
		    	if($update_bool){
		    		$returnData = $menu->toArray();
		    		$returnData['csrftoken'] = csrf_token();
		    		$returnData['status'] = true;
		    		$returnData['msg'] = "修改成功";
		    	}else{
                    $returnData['status'] = false;
                    $returnData['msg'] = "修改失败";
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
        $permissions = Permission::all('name', 'slug', 'description')->toArray();
        /*父级菜单*/
        $menus = Menu::where('parent_id', '=', '0')->get()->toArray();

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
	    	$menu = new Menu;
	    	$menu->name = request('name', '');
	    	$menu->description = request('description', '');
	    	$menu->url = request('url', '');
	    	$menu->slug = request('slug', '');
	    	$menu->parent_id = request('parent_id', '');

	    	$add_bool = $menu->save();

	    	/*修改成功*/
	    	if($add_bool){
	    		$returnData = $menu->toArray();
	    		$returnData['csrftoken'] = csrf_token();
	    		$returnData['status'] = true;
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
    		$delete_bool = Menu::destroy($id);
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
