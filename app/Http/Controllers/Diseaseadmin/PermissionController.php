<?php

namespace App\Http\Controllers\Diseaseadmin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Bican\Roles\Models\Permission;

use Auth;

use App\Menu;
/**
 * 权限管理类
 * 
 * @author        wen.zhou@bioon.com
 * 
 * @date        2015-10-20 09:51:27
 */

class PermissionController extends Controller
{
    protected $current_user;
	public function __construct(){
        $this->current_user = Auth::user();

        $this->middleware('permission:show.permission.manage');

        $this->middleware('permission:show.permission.list', ['only' => ['getIndex', 'getShow', 'getPermissionlist']]);

        $this->middleware('permission:update.permissions', ['only' => ['getUpdate', 'postUpdate']]);
        $this->middleware('permission:add.permissions', ['only' => ['getAdd', 'postAdd']]);
        $this->middleware('permission:delete.permissions', ['only' => ['getDelete']]);
	}
    
    public function getIndex(){
        return redirect('permission/show');
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

        if($this->current_user->can('add.permissions')){
            $is_add = true;
        }

        $returnData = [
            'is_add' => $is_add,
        ];

        return view('admin.permission.show')->with($returnData);
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
    public function getPermissionlist(){
        $draw = request('sEcho', 1);
        $length = request('iDisplayLength', 10);
        $start = request('iDisplayStart', 0);
        $search = request('sSearch', '');

        /*设置搜索条件*/
        $permission = new Permission;
        /*获取权限总量*/
        $count = $permission->count();
        if(!empty($search)){
            $permission = $permission->where('name', 'like', "%{$search}%");
        }
        /*设置偏移*/
        $permission = $permission->offset($start);
        /*设置limit*/
        $permission = $permission->limit($length);
        
        /*获取权限集*/
        $result_permissions = $permission->get();
        $permissions = $result_permissions->toArray();

        foreach($result_permissions as $key => $result_role){
            $permissions[$key]['update'] = $this->current_user->can('update.permissions');
            $permissions[$key]['delete'] = $this->current_user->can('delete.permissions');
        }

        $returnData = [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => $permissions
        ];

        return response()->json($returnData);
    }

    /**
     * 获取权限修改界面
     * 
     * @param        
     * 
     * @author        wen.zhou@bioon.com
     * 
     * @date        2015-10-19 17:48:05
     * 
     * @return        
     */
    public function getUpdate(){
        $id = request('id', 0);

        $returnData = [];
        if(is_numeric($id) || empty($id)){
            $permission = Permission::where('id', '=', $id)->first()->toArray();
            if(!empty($permission)){
                $returnData = [
                    'status' => true,
                    'msg' => '数据获取成功',
                    'permission' => $permission
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
        return view('admin.permission.update')->with($returnData);
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
            $permission = Permission::where('id', '=', $id)->first();
            if(!empty($permission)){
                // 修改菜单
                $permission->name = request('name', $permission->name);
                $permission->description = request('description', $permission->description);
                $permission->model = request('model', $permission->model);
                $permission->slug = request('slug', $permission->slug);

                $update_bool = $permission->save();

                /*修改成功*/
                if($update_bool){
                    $returnData['permission'] = $permission->toArray();
                    $returnData['status'] = true;
                    $returnData['msg'] = "权限修改成功";
                }else{
                    $returnData['status'] = false;
                    $returnData['msg'] = '权限修改失败';
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
     * 获取权限添加界面
     * 
     * @param        
     * 
     * @author        wen.zhou@bioon.com
     * 
     * @date        2015-10-19 17:55:19
     * 
     * @return        
     */
    public function getAdd(){
        return view('admin.permission.add');
    }

    /**
     * 新建菜单
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
            $returnData['msg'] = '权限(slug)不能为空';
        }else{
            $permission = new Permission;
            $permission->name = request('name', '');
            $permission->description = request('description', '');
            $permission->model = request('model', '');
            $permission->slug = request('slug', '');

            $add_bool = $permission->save();

            /*修改成功*/
            if($add_bool){
                $returnData['status'] = true;
                $returnData['msg'] = "权限添加成功";
            }else{
                $returnData['status'] = false;
                $returnData['msg'] = "权限添加失败";
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
            $delete_bool = Permission::destroy($id);

            if($delete_bool){
                $returnData = [
                    'status' => true,
                    'msg' => '权限删除成功'
                ];
            }else{
                $returnData = [
                    'status' => false,
                    'msg' => '权限删除失败'
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
