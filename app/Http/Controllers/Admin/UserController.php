<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Menu;
use Bican\Roles\Models\role;
use Bican\Roles\Models\permission;

use Auth;

use App\Services\Contracts\PermissionContract;
use App\Http\Requests\UserAddUpdateRequest;
/**
 * 用户管理类
 * 
 * @author		wen.zhou@bioon.com
 * 
 * @date		2015-10-20 09:51:47
 */

class UserController extends Controller
{
	public function __construct(){
        $this->middleware('permission:admin.users.manage');
		$this->middleware('permission:admin.users.list', ['only' => ['getIndex', 'getShow', 'getUserlist', 'getPermission']]);

        $this->middleware('permission:admin.users.update', ['only' => ['getUpdate', 'postUpdate']]);
        $this->middleware('permission:admin.users.add', ['only' => ['getAdd', 'postAdd']]);
        $this->middleware('permission:admin.users.delete', ['only' => ['getDelete']]);

	}
    
    public function getIndex(){
        return redirect('user/show');
    }

    /**
     * 显示用户列表--模板
     * 
     * @param        
     * 
     * @author        wen.zhou@bioon.com
     * 
     * @date        2015-10-19 09:33:17
     * 
     * @return        
     */
    public function getShow(){
    	/*当前登录用户*/
    	$current_user = Auth::user();

    	$is_add = false;//添加权限

    	if($current_user->can('admin.users.add')){
    		$is_add = true;
    	}

    	$returnData = [
    		'is_add' => $is_add,
    	];

        return view('admin.user.show')->with($returnData);
    }

    /**
     * 获取用户列表--json数据
     * 
     * @param       
     * 
     * @author      wen.zhou@bioon.com
     * 
     * @date        2015-10-16 13:33:19
     * 
     * @return      
     */
    public function getUserlist(){
        $draw = request('sEcho', 1);
        $length = request('iDisplayLength', 10);
        $start = request('iDisplayStart', 0);
        $search = request('sSearch', '');

        $current_user = Auth::user();
        /*设置搜索条件*/
        $user = new User;

        /*计算总量*/
        $count = $user->count();

        /*设置偏移*/
        $user = $user->offset($start);
        /*设置limit*/
        $user = $user->limit($length);
        
        if(!empty($search)){
            $user = $user->where('name', 'like', "%{$search}%");
        }
        
        /*用户结果集--数组*/
        $result_users = $user->with('roles')->get();
        $users = $result_users->toArray();
        /*判断用户是否用删除，修改用户-- 是否显示 修改，删除按钮*/
        foreach($result_users as $key => $result_user){
        	$update = false;
        	if($current_user->can('admin.users.update')){
        		if($result_user->id === $current_user->id){
	        		$update = true;
	        	}else{
	        		if($current_user->allowed('admin.users.update', $result_user, true, 'creator_id')){
	        			$update = true;
	        		}
	        	}
        	}

        	$delete = false;
        	if($current_user->can('admin.users.delete')){
	        	if($current_user->allowed('admin.users.delete', $result_user, true, 'creator_id')){
	        		$delete = true;
	        	}
        	}
        	$users[$key]['update'] = $update;
        	$users[$key]['delete'] = $delete;
        }
        // dd($result_users);
        $returnData = [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => $users
        ];

        return response()->json($returnData);
    }

    /**
     * 获取修改菜单数据--GET
     *  
     * @param        
     * 
     * @author        wen.zhou@bioon.com
     * 
     * @date        2015-10-19 11:32:47
     * 
     * @return        
     */
    public function getUpdate(PermissionContract $perCon){
        $id = request('id', 0);

        $current_user = Auth::user();

        $returnData = [];
        if(!empty($id)){
            $selected_user = User::find($id);
            if(! $selected_user->isEmpty()){
                /*所有权限*/
                $all_permissions = Permission::all();
                /*所有角色*/
                $all_roles = Role::all()->toArray();

                /*当前用户所拥有的独立权限*/
                $user_permissions = $selected_user->userPermissions()->get()->keyBy('slug')->keys()->toArray();
                /*当前用户的角色*/
                $user_roles = $selected_user->roles()->get()->keyBy('id')->keys()->toArray();

                /*处理后的权限*/
                $deal_permissions = [];

                foreach($all_permissions as $all_permission){
                    array_set($deal_permissions, $all_permission->slug, json_encode(['key' => $all_permission->slug, 'val'=> $all_permission->name . ":" . $all_permission->description]));
                }

                /*转成js数据*/
                $permissions = $perCon->dealArrayToJsTreeUpdate($deal_permissions, $user_permissions);

                /*是否可以修改此用户数据*/
                $is_update = $current_user->allowed('admin.users.update', $selected_user, true, 'creator_id');

                /*此用户是否是自己*/
                $is_owner = $current_user->id === $selected_user->id ? true : false;

                $user = $selected_user->toArray();

                $returnData = [
                    'status' => true,
                    'msg' => '数据获取成功',
                    'all_roles' => $all_roles,
                    'user_roles' => $user_roles,
                    'permissions' => json_encode($permissions),
                    'user' => $user,
                    'is_owner' => $is_owner,
                    'is_update' => $is_update
                ];
            }else{
                $returnData = [
                    'status' => false,
                    'msg' => '没有数据'
                ];
            }
        }else{
            $returnData = [
                'status' => false,
                'msg' => '数据获取失败'
            ];
        }

        return view('admin.user.update')->with($returnData);
    }
    
    /**
     * 修改菜单数据
     * 
     * @param       
     * 
     * @author      wen.zhou@bioon.com
     * 
     * @date        2015-10-16 15:15:49
     * 
     * @return      
     */
    public function postUpdate(){
        $id = request('id', '');

        $returnData = [
            'csrftoken' => csrf_token()
        ];
        if(!empty($id)){
            $user = User::find($id);
            if(!$user->isEmpty()){
                // 修改菜单
                $user->name = request('name', $user->name);
                $user->email = request('email', $user->email);
                $user->password = request('password', '') ? bcrypt(request('password')) : $user->password;
                $update_bool = $user->save();

                /*修改成功*/
                if($update_bool){
                    /*获取修改的权限*/
                    $update_permissions = request('permission');
                    /*用户删除所有权限*/
                    $user->detachAllPermissions();
                    if(!empty($update_permissions)){
                        /*用户添加权限*/
                        $arr_permissions = explode(',', $update_permissions);
                        $permissions = Permission::whereIn('slug', $arr_permissions)->get();
                        foreach($permissions as $permission){
                            $user->attachPermission($permission);
                        }
                    }
                    /*修改角色*/
                    $update_roles = request('role');
                	/*删除所有角色*/
                	$user->detachAllRoles();
                    if(!empty($update_roles)){
                    	/*用户添加角色*/
                    	$roles = Role::whereIn('id', $update_roles)->get();
                    	foreach($roles as $role){
                    	    $user->attachRole($role);
                    	}
                    }

                    $returnData['status'] = true;
                    $returnData['msg'] = "角色修改成功";
                }else{
                    $returnData['status'] = false;
                    $returnData['msg'] = '角色修改失败';
                }
            }else{
                $returnData['status'] = false;
                $returnData['msg'] = '获取数据失败';
            }
        }else{
            $returnData['status'] = false;
            $returnData['msg'] = '获取数据失败';
        }

        return response()->json($returnData);
    }

    /**
     * 返回新建角色界面
     * 
     * @param        
     * 
     * @author        wen.zhou@bioon.com
     * 
     * @date        2015-10-19 11:55:26
     * 
     * @return        
     */
    public function getAdd(PermissionContract $perCon){
    	/*权限*/
        $all_permissions = Permission::all();

        $deal_permissions = [];

        foreach($all_permissions as $all_permission){
            array_set($deal_permissions, $all_permission->slug, json_encode(['key' => $all_permission->slug, 'val'=> $all_permission->name . ":" . $all_permission->description]));
        }

        $permissions = $perCon->dealArrayToJsTreeAdd($deal_permissions);

        /*角色*/
        $roles = Role::all()->toArray();


        $returnData = [
            'permissions' => json_encode($permissions),
            'roles' => $roles
        ];

        return view('admin.user.add')->with($returnData);
    }
    
    /**
     * 新建角色
     * 
     * @param       
     * 
     * @author      wen.zhou@bioon.com
     * 
     * @date        2015-10-16 16:59:05
     * 
     * @return      
     */
    public function postAdd(UserAddUpdateRequest $userRequest){
    	$current_user = Auth::user();

        $returnData = [
            'csrftoken' => csrf_token()
        ];

        /*添加用户*/
        $user = new User;
        $user->name = request('name', '');
        $user->email = request('email', '');
        $user->password = bcrypt(request('password', ''));
        $user->creator_id = $current_user->id ? $current_user->id : 0;
        $add_bool = $user->save();

        /*添加成功*/
        if($add_bool){
            /*添加权限*/
            $add_permissions = request('permission');
            if(!empty($add_permissions)){
                $arr_permissions = explode(',', $add_permissions);
                $permissions = Permission::whereIn('slug', $arr_permissions)->get();
                // dd($permissions->count());
                foreach($permissions as $permission){
                    $user->attachPermission($permission);
                }
            }
            /*添加角色*/
            $add_roles = request('role');
            if(!empty($add_roles)){
            	$roles = Role::whereIn('id', $add_roles)->get();
            	foreach($roles as $role){
            	    $user->attachRole($role);
            	}
            }
            $returnData['status'] = true;
            $returnData['msg'] = "用户添加成功";
        }else{
            $returnData['status'] = false;
            $returnData['msg'] = "用户添加失败";
        }

        return response()->json($returnData);
    }

    /**
     * 删除菜单
     * 
     * @param       
     * 
     * @author      wen.zhou@bioon.com
     * 
     * @date        2015-10-16 11:53:55
     * 
     * @return      
     */
    public function getDelete(){
        $id = Request('id', 0);

        if(is_numeric($id) && !empty($id)){
            $delete_bool = User::destroy($id);

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

    /**
     * 查看权限
     * 
     * @param        
     * 
     * @author        wen.zhou@bioon.com
     * 
     * @date        2015-10-19 17:25:36
     * 
     * @return        
     */
    public function getPermission(){
        $id = Request('id', 0);

        if(!empty($id)){
            $user = User::find($id);
            $user_permissions = $user->getPermissions();

            if(!$user->isEmpty()){
                $returnData = [
                    'status' => true,
                    'msg' => '获取成功',
                    'user_permissions' => $user_permissions,
                    'user' => $user
                ];
            }else{
                $returnData = [
                    'status' => false,
                    'msg' => '获取失败'
                ];
            }
        }else{
            $returnData = [
                'status' => false,
                'msg' => '数据错误'
            ];
        }
        return view('admin.user.permission')->with($returnData);
    }
}
