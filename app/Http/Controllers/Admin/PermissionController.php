<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

/*服务*/
use App\Services\Contracts\ButtonContract;

/*仓库*/
use PermissionRepository;

/*Request*/
use App\Http\Requests\Backend\PermissionRequest;

/**
 * 权限管理类
 * 
 * @author        wen.zhou@bioon.com
 * 
 * @date        2015-10-20 09:51:27
 */

class PermissionController extends Controller
{
    /*当前用户*/
    protected $current_user;

    /*添加权限*/
    protected $admin_add_permissions;

    /*管理权限*/
    protected $admin_manage_permissions;

    /*查看权限列表*/
    protected $admin_list_permissions;

    /*修改权限*/
    protected $admin_update_permissions;

    /*删除权限*/
    protected $admin_delete_permissions;

	public function __construct(){
        $this->current_user = auth()->user();

        $this->admin_add_permissions = config('backend.permission.admin_add_permissions');
        $this->admin_manage_permissions = config('backend.permission.admin_manage_permissions');
        $this->admin_list_permissions = config('backend.permission.admin_list_permissions');
        $this->admin_update_permissions = config('backend.permission.admin_update_permissions');
        $this->admin_delete_permissions = config('backend.permission.admin_delete_permissions');

        $this->middleware('permission:' . $this->admin_manage_permissions);
        $this->middleware('permission:' . $this->admin_list_permissions, ['only' => ['getIndex', 'getShow', 'getPermissionlist']]);
        $this->middleware('permission:' . $this->admin_update_permissions, ['only' => ['getUpdate', 'postUpdate']]);
        $this->middleware('permission:' . $this->admin_add_permissions, ['only' => ['getAdd', 'postAdd']]);
        $this->middleware('permission:' . $this->admin_delete_permissions, ['only' => ['getDelete']]);
	}
    
    public function getIndex(){
        return redirect('permission/show');
    }

    /**
     * 显示权限列表
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
        $add_button = $buttonContract
                        ->createAddModalButton($this->admin_add_permissions, route('permission.add.get'), '添加权限')
                        ->getReturnStr();

        return view('admin.permission.show')->with(compact('add_button'));
    }

    /**
     * 获取权限列表
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

        /*获取数据*/
        $data = [
            'search' => $search,
            'start' => $start,
            'length' => $length
        ];
        /*获取总量*/
        $count = PermissionRepository::count();
        /*获取权限列表*/
        $permissions = PermissionRepository::permissionlist($data);

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
        if(!empty($id)){

            $permission = PermissionRepository::perInfo($id);

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
    public function postUpdate(PermissionRequest $perRequest){
        $returnData = [];

        $id = request('id', '');

        if(!empty($id)){
            /*获取 权限对象*/
            $permission = PermissionRepository::perInfo($id);

            if(!empty($permission)){
                // 修改权限
                $data = [
                    'name' => request('name', $permission->name),
                    'description' => request('description', $permission->description),
                    'model' => request('model', $permission->model),
                    'slug' => request('slug', $permission->slug),
                ];
                
                /*修改权限*/
                $upPerData = PermissionRepository::upPer($permission, $data);

                $returnData['status'] = $upPerData['status'];
                $returnData['msg'] = $upPerData['msg'];

                if($upPerData['status']){
                    $returnData['permission'] = $upPerData['data']->toArray();
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
    public function postAdd(PermissionRequest $perRequest){
        $returnData = [
            'status' => false,
            'msg' => '权限添加失败'
        ];

        /*设置数据*/
        $data = [
            'name' => request('name', ''),
            'description' => request('description', ''),
            'model' => request('model', ''),
            'slug' => request('slug', ''),
        ];
        /*添加权限*/
        $addPerData = PermissionRepository::addPer($data);

        $returnData['status'] = $addPerData['status'];
        $returnData['msg'] = $addPerData['msg'];

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
            $delete_bool = PermissionRepository::delPer($id);

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
