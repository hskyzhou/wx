<?php

namespace App\Http\Controllers\Diseaseadmin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Menu;

use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;

use App\Services\Contracts\PermissionContract;

use Debugbar;

use Auth;
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

	public function __construct(){
        $this->current_user = Auth::user();

        $this->middleware('permission:show.role.manage');

        $this->middleware('permission:show.role.list', ['only' => ['getInde', 'getShow', 'getRolelist']]);

        $this->middleware('permission:add.roles', ['only' => ['getAdd', 'postAdd']]);
        $this->middleware('permission:update.roles', ['only' => ['getUpdate', 'postUpdate']]);
        $this->middleware('permission:delete.roles', ['only' => ['getDelete']]);
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
    public function getShow(){
        $is_add = false;//添加权限

        if($this->current_user->can('add.roles')){
            $is_add = true;
        }

        $returnData = [
            'is_add' => $is_add,
        ];

        return view('admin.role.show')->with($returnData);
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

        Debugbar::info(request()->all());
        /*设置角色条件*/
        $role = new Role;
        /*获取角色总量*/
        $count = $role->count();

        if(!empty($search)){
            $role = $role->where('name', 'like', "%{$search}%");
        }
        /*设置偏移*/
        $role = $role->offset($start);
        /*设置limit*/
        $role = $role->limit($length);
        
        /*获取权限集*/
        $result_roles = $role->get();
        $roles = $result_roles->toArray();

        foreach($result_roles as $key => $result_role){
            $roles[$key]['update'] = $this->current_user->can('update.roles');
            $roles[$key]['delete'] = $this->current_user->can('delete.roles');
        }

        $returnData = [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => $roles
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
        if(is_numeric($id) || empty($id)){
            $role = Role::where('id', '=', $id)->first();
            if(!empty($role)){
                /*所有权限*/
                $all_permissions = Permission::all();
                /*当前角色所拥有的权限*/
                $role_permissions = $role->permissions()->get()->keyBy('slug')->keys()->toArray();

                /*处理后的权限*/
                $deal_permissions = [];

                foreach($all_permissions as $all_permission){
                    array_set($deal_permissions, $all_permission->slug, json_encode(['key' => $all_permission->slug, 'val'=> $all_permission->name . ":" . $all_permission->description]));
                }

                /*转成js数据*/
                $permissions = $perCon->dealArrayToJsTreeUpdate($deal_permissions, $role_permissions);

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
            $role = Role::where('id', '=', $id)->first();
            if(!empty($role)){
                // 修改菜单
                $role->name = request('name', $role->name);
                $role->description = request('description', $role->description);
                $role->level = request('level', $role->level);
                $role->slug = request('slug', $role->slug);

                $update_bool = $role->save();

                /*修改成功*/
                if($update_bool){
                    /*获取修改的权限*/
                    $update_permissions = request('permission');
                    if(!empty($update_permissions)){
                        /*角色删除权限*/
                        $role->detachAllPermissions();

                        /*角色添加权限*/
                        $arr_permissions = explode(',', $update_permissions);
                        $permissions = Permission::whereIn('slug', $arr_permissions)->get();
                        foreach($permissions as $permission){
                            $role->attachPermission($permission);
                        }
                    }

                    $returnData['role'] = $role->toArray();
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
        $all_permissions = Permission::all();

        $deal_permissions = [];

        foreach($all_permissions as $all_permission){
            array_set($deal_permissions, $all_permission->slug, json_encode(['key' => $all_permission->slug, 'val'=> $all_permission->name . ":" . $all_permission->description]));
        }

        // dd($deal_permissions);
        $permissions = $perCon->dealArrayToJsTreeAdd($deal_permissions);

        // dd($permissions);
        // var_dump(json_encode($permissions[0]));
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
    public function postAdd(){
        $slug = request('slug', '');
        $returnData = [
            'csrftoken' => csrf_token()
        ];
        if(empty($slug)){
            $returnData['status'] = false;
            $returnData['msg'] = '角色(slug)不能为空';
        }else{
            $role = new Role;
            $role->name = request('name', '');
            $role->description = request('description', '');
            $role->level = request('level', '');
            $role->slug = request('slug', '');
            $add_bool = $role->save();

            /*添加成功*/
            if($add_bool){
                /*添加权限*/
                $add_permissions = request('permission');
                if(!empty($add_permissions)){
                    $arr_permissions = explode(',', $add_permissions);
                    $permissions = Permission::whereIn('slug', $arr_permissions)->get();
                    // dd($permissions->count());
                    foreach($permissions as $permission){
                        $role->attachPermission($permission);
                    }
                }
                $returnData['role'] = $role->toArray();
                $returnData['status'] = true;
                $returnData['msg'] = "角色添加成功";
            }else{
                $returnData['status'] = false;
                $returnData['msg'] = "角色添加失败";
            }
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
            $delete_bool = Role::destroy($id);

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
            $role = Role::find($id);

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
