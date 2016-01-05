<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Services\Contracts\PermissionContract;

/*服务*/
use App\Services\Contracts\ButtonContract;

/*仓库*/
use RoleRepository;
use PermissionRepository;

/*底层服务*/
use DB;

/*Request*/
use App\Http\Requests\Backend\RoleRequest;

/**
 * 角色管理类
 * 
 * @author        wen.zhou@bioon.com
 * 
 * @date        2015-10-19 09:33:24
 * 
 */

class RoleController extends Controller
{
    /*当前登录用户*/
    protected $current_user;

    /*添加角色*/
    protected $admin_add_roles;

    /*管理角色*/
    protected $admin_manage_roles;

    /*查看角色列表*/
    protected $admin_list_roles;

    /*修改角色*/
    protected $admin_update_roles;

    /*删除角色*/
    protected $admin_delete_roles;

	public function __construct(){
        $this->current_user = auth()->user();

        $this->admin_add_roles = config('backend.role.admin_add_roles');
        $this->admin_manage_roles = config('backend.role.admin_manage_roles');
        $this->admin_list_roles = config('backend.role.admin_list_roles');
        $this->admin_update_roles = config('backend.role.admin_update_roles');
        $this->admin_delete_roles = config('backend.role.admin_delete_roles');

        $this->middleware('permission:' . $this->admin_manage_roles);
        $this->middleware('permission:' . $this->admin_list_roles, ['only' => ['getInde', 'getShow', 'getRolelist']]);
        $this->middleware('permission:' . $this->admin_add_roles, ['only' => ['getAdd', 'postAdd']]);
        $this->middleware('permission:' . $this->admin_update_roles, ['only' => ['getUpdate', 'postUpdate']]);
        $this->middleware('permission:' . $this->admin_delete_roles, ['only' => ['getDelete']]);
	}
    
    public function getIndex(){
        return redirect('role/show');
    }

    /**
     * 显示角色列表
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
        $add_button = $buttonContract->createAddModalButton($this->admin_add_roles, route('role.add.get'), '添加角色')->getReturnStr();

        return view('admin.role.show')->with(compact('add_button'));
    }

    /**
     * 获取角色列表
     * 
     * @param       
     * 
     * @author      wen.zhou@bioon.com
     * 
     * @date        2015-10-16 13:33:19
     * 
     * @return      
     */
    public function getRolelist(){
        $draw = request('sEcho', 1);
        $length = request('iDisplayLength', 10);
        $start = request('iDisplayStart', 0);
        $search = request('sSearch', '');

        $data = [
            'search' => $search,
            'start' => $start,
            'length' => $length,
        ];

        /*获取总量*/
        $count = RoleRepository::count();
        /*获取列表数据*/
        $roles = RoleRepository::rolelist($data);

        $returnData = [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => $roles
        ];

        return response()->json($returnData);
    }

    /**
     * 获取修改角色数据--GET
     *  
     * @param        
     * 
     * @author        wen.zhou@bioon.com
     * 
     * @date        2015-10-19 11:32:47
     * 
     * @return        
     */
    public function getUpdate(){
        $id = request('id', 0);

        $returnData = [];
        if(!empty($id)){
            /*获取角色详情*/
            $role = RoleRepository::roleInfo($id);

            if(!empty($role)){
                /*权限修改中使用的权限*/
                $permissions = PermissionRepository::permissionInUpdate($role);

                $returnData = [
                    'status' => true,
                    'msg' => '数据获取成功',
                    'role' => $role->toArray(),
                    'permissions' => json_encode($permissions)
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

        return view('admin.role.update')->with($returnData);
    }
    
    /**
     * 修改角色数据
     * 
     * @param       
     * 
     * @author      wen.zhou@bioon.com
     * 
     * @date        2015-10-16 15:15:49
     * 
     * @return      
     */
    public function postUpdate(RoleRequest $roleRequest){
        $returnData = [
            'status' => true,
            'msg' => '角色添加成功',
        ];

        /*获取id*/
        $id = request('id', '');

        if(!empty($id)){
            /*获取 角色详情*/
            $role = RoleRepository::roleInfo($id);

            if(!empty($role)){
                // 修改角色信息
                $data = [
                    'name' => request('name', $role->name),
                    'description' => request('description', $role->description),
                    'level' => request('level', $role->level),
                    'slug' => request('slug', $role->slug),
                ];

                /*开启事务*/
                DB::beginTransaction();

                $upRoleData = RoleRepository::upRole($role, $data);

                /*设置返回数据*/
                $returnData['status'] = $upRoleData['status'];
                $returnData['msg'] = $upRoleData['msg'];

                /*角色修改成功*/
                if($upRoleData['status']){
                    /*获取 修改后的角色信息*/
                    $update_role = $upRoleData['data'];

                    /*角色删除权限*/
                    $role->detachAllPermissions();
                    
                    /*获取修改的权限*/
                    $update_permissions = request('permission');

                    if(!empty($update_permissions)){
                        $addPermissionData = RoleRepository::roleAddPermission($update_role, $update_permissions);

                        if($addPermissionData['status']){
                            $returnData['role'] = $role->toArray();
                        }else{
                            /*事务回滚*/
                            DB::rollback();
                            $returnData['status'] = false;
                            $returnData['msg'] = "角色权限修改失败";
                            return response()->json($returnData);
                        }
                    }
                }else{
                    // 事务回滚
                    DB::rollback();
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
        /*获取 展示的 权限列表*/
        $permissions = PermissionRepository::permissionInAdd();

        $returnData = [
            'permissions' => json_encode($permissions),
        ];

        return view('admin.role.add')->with($returnData);
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
    public function postAdd(RoleRequest $roleRequest){
        $returnData = [
            'status' => true,
            'msg' => '角色添加成功',
        ];

        $data = [
            'name' => request('name', ''),
            'description' => request('description', ''),
            'level' => request('level', ''),
            'slug' => request('slug', ''),
        ];
        
        /*开启事务*/
        DB::beginTransaction();

        /*添加角色*/
        $addRoleData = RoleRepository::addRole($data);

        /*添加角色成功*/
        if($addRoleData['status']){
            $role = $addRoleData['data'];

            /*角色  添加权限*/
            $permissions = request('permission');

            if(!empty($permissions)){
                $addPermissionData = RoleRepository::roleAddPermission($role, $permissions);

                if($addPermissionData['status']){
                    $returnData['role'] = $role->toArray();
                    $returnData['status'] = true;
                    $returnData['msg'] = "角色权限添加成功";
                }else{
                    /*事务回滚*/
                    DB::rollback();

                    $returnData['status'] = false;
                    $returnData['msg'] = "角色权限添加失败";
                    
                    return response()->json($returnData);
                }
            }
        }else{
            /*事务回滚*/
            DB::rollback();

            $returnData['status'] = false;
            $returnData['msg'] = "角色添加失败";

            return response()->json($returnData);
        }

        /*提交事务*/
        DB::commit();

        return response()->json($returnData);
    }

    /**
     * 删除角色
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
            $delete_bool = RoleRepository::delRole($id);

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

        if(is_numeric($id) && !empty($id)){
            $role = RoleRepository::roleInfo($id);

            $role_permissions = $role->permissions()->get();

            if(!empty($role_permissions)){
                $returnData = [
                    'status' => true,
                    'msg' => '获取成功',
                    'role_permissions' => $role_permissions,
                    'role' => $role
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
        return view('admin.role.permission')->with($returnData);
    }
}
