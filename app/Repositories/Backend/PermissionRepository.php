<?php 
	namespace App\Repositories\Backend;

	use Bican\Roles\Models\Permission;

	use App\Services\Contracts\PermissionContract;

	use App\Services\Contracts\ButtonContract;

	class PermissionRepository{
		/*当前应用*/
		protected $app;

		/*权限服务*/
		protected $perContract;

		/*修改权限*/
		protected $admin_update_permissions;

		/*删除权限*/
		protected $admin_delete_permissions;

		/*按钮服务*/
		protected $btnContract;

		public function __construct($app){
			$this->app = $app;

			$this->perContract = $this->app->make(PermissionContract::class);

			$this->admin_update_permissions = config('backend.permission.admin_update_permissions');
			$this->admin_delete_permissions = config('backend.permission.admin_delete_permissions');

			$this->btnContract = $this->app->make(ButtonContract::class);
		}


		/*====================================获取数据======================*/
		/**
		 * 获取 权限列表
		 * 
		 * @param		$data  array
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-05 09:18:22
		 * 
		 * @return		array
		 */
		public function permissionlist($data){
	 		/*设置搜索条件*/
	 		$permission = new Permission;

	 		if(!empty($data['search'])){
			    $role = $role->where('name', 'like', "%{$data['search']}%");
			}
	 		/*设置偏移*/
	 		$permission = $permission->offset($data['start']);
	 		/*设置limit*/
	 		$permission = $permission->limit($data['length']);
	 		
	 		/*获取权限集*/
	 		$result_permissions = $permission->get();
	 		$permissions = $result_permissions->toArray();

	 		foreach($result_permissions as $key => $result_permission){
		    	$id = $result_permission['id'];
		        $permissions[$key]['button'] = $this->btnContract
										        ->createUpdateModalButton($this->admin_update_permissions, route('permission.update.get', ['id' => $id]))
										        ->createDeleteButton($this->admin_delete_permissions, 'permission_delete', $id)
										        ->getReturnStr();
	 		}

	 		return $permissions;
		}

		/**
		 * 获取权限详情
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-05 09:35:18
		 * 
		 * @return		
		 */
		public function perInfo($id){
			return Permission::where('id', '=', $id)->first();
		}

		/**
		 * 获取所有权限
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-04 19:38:54
		 * 
		 * @return		array
		 */
		public function permissions(){
			return Permission::all('name', 'slug', 'description')->toArray();
		}

		/**
		 * 角色添加中 权限显示
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-04 21:00:22
		 * 
		 * @return		
		 */
		public function permissionInAdd(){
			$all_permissions = Permission::all();

			$deal_permissions = [];

			foreach($all_permissions as $all_permission){
			    array_set($deal_permissions, $all_permission->slug, json_encode(['key' => $all_permission->slug, 'val'=> $all_permission->name . ":" . $all_permission->description]));
			}

			$permissions = $this->perContract->dealArrayToJsTreeAdd($deal_permissions);

			return $permissions;
		}

		/**
		 * 角色修改中使用的权限
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-04 21:39:09
		 * 
		 * @return		
		 */
		public function permissionInUpdate($role){
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
			return $this->perContract->dealArrayToJsTreeUpdate($deal_permissions, $role_permissions);
		}

		/**
		 * 用户 修改中 使用的权限
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-05 10:42:52
		 * 
		 * @return		
		 */
		public function perInUpdateByUser($selected_user){
			/*获取所有权限*/
			$all_permissions = Permission::all();

			/*当前用户所拥有的独立权限*/
			$user_permissions = $selected_user->userPermissions()->get()->keyBy('slug')->keys()->toArray();

			/*处理后的权限*/
			$deal_permissions = [];
			foreach($all_permissions as $all_permission){
			    array_set($deal_permissions, $all_permission->slug, json_encode(['key' => $all_permission->slug, 'val'=> $all_permission->name . ":" . $all_permission->description]));
			}

			/*转成js数据*/
			$permissions = $this->perContract->dealArrayToJsTreeUpdate($deal_permissions, $user_permissions);

			return $permissions;
		}
		
		/**
		 * 获取 权限总量
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-05 09:19:17
		 * 
		 * @return		
		 */
		public function count(){
			return Permission::count();
		}

		/*====================================添加数据======================*/
		/**
		 * 添加权限
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-05 09:30:17
		 * 
		 * @return		
		 */
		public function addPer($data){
			/*设置返回数据*/
			$returnData = [
				'status' => false,
				'msg' => '权限添加失败'
			];

			$per = new Permission;
			$per->name = $data['name'];
			$per->description = $data['description'];
			$per->model = $data['model'];
			$per->slug = $data['slug'];

			/*进行添加*/
			if($per->save()){
				$returnData = [
					'status' => true,
					'msg' => '权限添加成功',
					'data' => $per
				];
			}

			return $returnData;
		}

		/*====================================修改数据======================*/
		/**
		 * 修改权限
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-05 09:39:44
		 * 
		 * @return		
		 */
		public function upPer($per, $data){
			/*设置返回数据*/
			$returnData = [
				'status' => false,
				'msg' => '权限修改失败',
			];

			$per->name = $data['name'];
			$per->description = $data['description'];
			$per->model = $data['model'];
			$per->slug = $data['slug'];

			if($per->save()){
				$returnData = [
					'status' => true,
					'msg' => '权限修改成功',
					'data' => $per,
				];
			}

			return $returnData;
		}


		/*====================================删除数据======================*/
		/**
		 * 删除 权限
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-05 09:44:33
		 * 
		 * @return		
		 */
		public function delPer($id){
			return Permission::destroy($id);
		}
	}
?>