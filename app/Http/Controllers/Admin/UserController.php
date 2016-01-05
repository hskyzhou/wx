<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Services\Contracts\PermissionContract;

/*Request*/
use App\Http\Requests\UserAddUpdateRequest;

/*服务*/
use App\Services\Contracts\ButtonContract;

/*仓库*/
use UserRepository;
use PermissionRepository;
use RoleRepository;

/*底层服务*/
use DB;

/**
 * 用户管理类
 * 
 * @author		wen.zhou@bioon.com
 * 
 * @date		2015-10-20 09:51:47
 */

class UserController extends Controller{
    /*当前用户*/
    protected $current_user;

    /*添加用户*/
    protected $admin_add_users;

    /*管理用户*/
    protected $admin_manage_users;

    /*查看用户列表*/
    protected $admin_list_users;

    /*修改用户*/
    protected $admin_update_users;

    /*删除用户*/
    protected $admin_delete_users;

	public function __construct(){
        $this->current_user = auth()->user();

        $this->admin_add_users = config('backend.user.admin_add_users');
        $this->admin_manage_users = config('backend.user.admin_manage_users');
        $this->admin_list_users = config('backend.user.admin_list_users');
        $this->admin_update_users = config('backend.user.admin_update_users');
        $this->admin_delete_users = config('backend.user.admin_delete_users');

        $this->middleware('permission:' . $this->admin_manage_users);
		$this->middleware('permission:' . $this->admin_list_users, ['only' => ['getIndex', 'getShow', 'getUserlist', 'getPermission']]);
        $this->middleware('permission:' . $this->admin_update_users, ['only' => ['getUpdate', 'postUpdate']]);
        $this->middleware('permission:' . $this->admin_add_users, ['only' => ['getAdd', 'postAdd']]);
        $this->middleware('permission:' . $this->admin_delete_users, ['only' => ['getDelete']]);

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
    public function getShow(ButtonContract $buttonContract){
    	$add_button = $buttonContract->createAddModalButton($this->admin_add_users, route('user.add.get'), '添加用户')->getReturnStr();

        return view('admin.user.show')->with(compact('add_button'));
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

        /*获取数据*/
        $data = [
            'search' => $search,
            'start' => $start,
            'length' => $length,
        ];
        $count = UserRepository::count();
        $users = UserRepository::userlist($data);
        
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

        $returnData = [];

        if(!empty($id)){

            $selected_user = UserRepository::userInfo($id);

            if(! $selected_user->isEmpty()){
                /*所有角色*/
                $all_roles = RoleRepository::roleAll();

                /*当前用户的角色*/
                $user_roles = $selected_user->roles()->get()->keyBy('id')->keys()->toArray();

                /* 用户 修改 使用的权限*/
                $permissions = PermissionRepository::perInUpdateByUser($selected_user);

                /*是否可以修改此用户数据*/
                $is_update = $this->current_user->allowed($this->admin_update_users, $selected_user, true, 'creator_id');

                /*此用户是否是自己*/
                $is_owner = $this->current_user->id === $selected_user->id ? true : false;

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
    public function postUpdate(UserAddUpdateRequest $userRequest){
        $returnData = [];

        $id = request('id', '');

        if(!empty($id)){
            /* 获取 用户 对象*/
            $user = UserRepository::userInfo($id);

            if(!$user->isEmpty()){
                // 修改用户
                $data = [
                    'name' => request('name', $user->name),
                    'email' => request('email', $user->email),
                    'password' => request('password', '') ? bcrypt(request('password')) : $user->password,
                ];

                /*开启事务*/
                DB::beginTransaction();

                /*修改用户*/
                $upUserData = UserRepository::upUser($user, $data);

                /*设置返回数据*/
                $returnData['status'] = $upUserData['status'];
                $returnData['msg'] = $upUserData['msg'];

                if($upUserData['status']){
                    /*用户删除所有权限*/
                    $user->detachAllPermissions();
                    
                    /*获取修改的权限*/
                    $update_permissions = request('permission');
                    if(!empty($update_permissions)){
                        /*用户添加权限*/
                        $userAddPerData = UserRepository::userAddPer($user, $update_permissions);
                        if(!$userAddPerData['status']){
                            DB::rollback();

                            $returnData['status'] = false;
                            $returnData['msg'] = "用户权限添加失败";

                            return response()->json($returnData);
                        }
                    }

                    /*删除所有角色*/
                    $user->detachAllRoles();

                    /*修改角色*/
                    $update_roles = request('role');
                    if(!empty($update_roles)){
                        $userAddRoleData = UserRepository::useraddrole($user, $update_roles);
                        if(!$userAddRoleData['status']){
                            DB::rollback();

                            $returnData['status'] = false;
                            $returnData['msg'] = "用户角色添加失败";

                            return response()->json($returnData);
                        }
                    }
                }else{
                    /*事务回滚*/
                    DB::rollback();
                    $returnData['status'] = false;
                    $returnData['msg'] = '用户修改失败';
                    return response()->json($returnData);
                }

                /*提交事务*/
                DB::commit();
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
    public function getAdd(){
        /*获取给用户的额外权限*/
        $permissions = PermissionRepository::permissionInAdd();

        /*角色*/
        $roles = RoleRepository::roleAll();

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
        $returnData = [];

        /*添加用户*/
        $data = [
            'name' => request('name', ''),
            'email' => request('email', ''),
            'password' => bcrypt(request('password', '')),
            'creator_id' => $this->current_user->id ? $this->current_user->id : 0,
        ];

        /*开启事务*/
        DB::beginTransaction();

        $addUserData = UserRepository::addUser($data);

        $returnData['status'] = $addUserData['status'];
        $returnData['msg'] = $addUserData['msg'];

        if($addUserData['status']){
            $user = $addUserData['data'];

            /*添加权限*/
            $add_permissions = request('permission');
            if(!empty($add_permissions)){
                $userAddPerData = UserRepository::userAddPer($user, $add_permissions);
                if(!$userAddPerData['status']){
                    DB::rollback();

                    $returnData['status'] = false;
                    $returnData['msg'] = "用户权限添加失败";

                    return response()->json($returnData);
                }
            }

            /*添加角色*/
            $add_roles = request('role');
            if(!empty($add_roles)){
                $userAddRoleData = UserRepository::useraddrole($user, $add_roles);
                if(!$userAddRoleData['status']){
                    DB::rollback();

                    $returnData['status'] = false;
                    $returnData['msg'] = "用户角色添加失败";

                    return response()->json($returnData);
                }
            }
        }else{
            /*事务回滚*/
            DB::rollback();
            return response()->json($returnData);
        }

        /*提交事务*/
        DB::commit();

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

        if(!empty($id)){
            $delete_bool = UserRepository::delUser($id);

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
            $user = UserRepository::userInfo($id);
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
