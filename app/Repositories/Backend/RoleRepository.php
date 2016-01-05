<?php 
	namespace App\Repositories\Backend;

	use Bican\Roles\Models\Role;
	use Bican\Roles\Models\Permission;

	use App\Services\Contracts\ButtonContract;

	class RoleRepository{
		/*当前应用*/
		protected $app;

		/*修改角色*/
		protected $admin_update_roles;

		/*删除角色*/
		protected $admin_delete_roles;

		/*按钮服务*/
		protected $btnContract;

		public function __construct($app){
			$this->app = $app;

			$this->admin_update_roles = config('backend.role.admin_update_roles');
			$this->admin_delete_roles = config('backend.role.admin_delete_roles');

			$this->btnContract = $this->app->make(ButtonContract::class);
		}

		/*========================获取数据===============================*/
		/**
		 * 角色 列表
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-04 20:49:22
		 * 
		 * @return		
		 */
		public function rolelist($data){
			/*设置角色条件*/
			$role = new Role;

			if(!empty($data['search'])){
			    $role = $role->where('name', 'like', "%{$data['search']}%");
			}
			/*设置偏移*/
			$role = $role->offset($data['start']);
			/*设置limit*/
			$role = $role->limit($data['length']);
			
			/*获取权限集*/
			$result_roles = $role->get();
			$roles = $result_roles->toArray();

			foreach($result_roles as $key => $result_role){
	        	$id = $result_role['id'];
	            $roles[$key]['button'] = $this->btnContract
	    								        ->createUpdateModalButton($this->admin_update_roles, route('role.update.get', ['id' => $id]))
	    								        ->createDeleteButton($this->admin_delete_roles, 'role_delete', $id)
	    								        ->getReturnStr();
			}

			return $roles;
		}

		/**
		 * 获取 总量
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-04 20:49:44
		 * 
		 * @return		
		 */
		public function count(){
			return Role::count();
		}

		/**
		 * 获取 角色 详情
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-04 21:36:48
		 * 
		 * @return		
		 */
		public function roleInfo($id){
			return Role::where('id', '=', $id)->first();
		}

		/**
		 * 获取 所有的角色
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-05 10:20:38
		 * 
		 * @return		
		 */
		public function roleAll(){
			return Role::all();
		}


		/*==========================添加数据=========================================*/
		/**
		 * 添加 角色
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-04 21:06:00
		 * 
		 * @return		
		 */
		public function addRole($data){
			$returnData = [
				'status' => false,
				'msg' => '添加角色失败'
			];

			$role = new Role;
			$role->name = $data['name'];
			$role->description = $data['description'];
			$role->level = $data['level'];
			$role->slug = $data['slug'];

			if($role->save()){
				$returnData['status'] = true;
				$returnData['data'] = $role;
				$returnData['msg'] = "添加角色成功";
			}

			return $returnData;
		}

		/**
		 * 角色 添加 权限
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-04 21:12:14
		 * 
		 * @return		
		 */
		public function roleAddPermission($role, $add_permissions){
			$returnData = [
				'status' => true
			];

		    $arr_permissions = explode(',', $add_permissions);

		    $permissions = Permission::whereIn('slug', $arr_permissions)->get();
		    foreach($permissions as $permission){
		        $role->attachPermission($permission);
		    }

		    return $returnData;
		}

		/*=============================修改数据==========================*/
		/**
		 * 修改角色
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-04 21:45:55
		 * 
		 * @return		
		 */
		public function upRole($role, $data){
			$returnData = [
				'status' => false,
				'msg' => '角色修改失败'
			];

			$role->name = $data['name'];
			$role->description = $data['description'];
			$role->level = $data['level'];
			$role->slug = $data['slug'];

			if($role->save()){
				$returnData['status'] = true;
				$returnData['data'] = $role;
				$returnData['msg'] = "角色修改成功";
			}

			return $returnData;
		}

		/*=============================删除数据==========================*/
		/**
		 * 删除 角色
		 * 
		 * @param		$id  角色id
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-05 09:10:16
		 * 
		 * @return		boolean
		 */
		public function delRole($id){
			return Role::destroy($id);
		}
	}
?>