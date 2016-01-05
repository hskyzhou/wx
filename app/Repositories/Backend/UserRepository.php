<?php 
	namespace App\Repositories\Backend;

	use App\User;

	use Bican\Roles\Models\Role;
	use Bican\Roles\Models\Permission;

	use App\Services\Contracts\ButtonContract;

	/**
	 * 用户仓库
	 * 
	 * @param		
	 * 
	 * @author		wen.zhou@bioon.com
	 * 
	 * @date		2016-01-05 10:01:50
	 * 
	 * @return		
	 */
	
	class UserRepository{
		/*当前应用*/
		protected $app;

		/*当前登录用户*/
		protected $current_user;

		/*修改用户*/
		protected $admin_update_users;

		/*删除用户*/
		protected $admin_delete_users;

		/*按钮服务*/
		protected $btnContract;

		public function __construct($app){
			$this->app = $app;

			$this->current_user = auth()->user();

			$this->admin_update_users = config('backend.user.admin_update_users');
			$this->admin_delete_users = config('backend.user.admin_delete_users');

			$this->btnContract = $this->app->make(ButtonContract::class);
		}

		/*=========================================获取数据===============================*/
		/**
		 * 用户列表
		 * 
		 * @param		$data   array
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-05 10:04:11
		 * 
		 * @return		
		 */
		public function userlist($data){
	        /*设置搜索条件*/
	        $user = new User;

	        /*设置偏移*/
	        $user = $user->offset($data['start']);
	        /*设置limit*/
	        $user = $user->limit($data['length']);
	        
	        if(!empty($data['search'])){
	            $user = $user->where('name', 'like', "%{$data['search']}%");
	        }
	        
	        /*用户角色*/
	        $result_users = $user->with('roles')->get();

	        $users = $result_users->toArray();
	        /*判断用户是否用删除，修改用户-- 是否显示 修改，删除按钮*/
	        foreach($result_users as $key => $result_user){
	        	/*设置当前管理者是否拥有修改用户权限*/
	        	$update = false;
	        	if($this->current_user->can($this->admin_update_users)){
	        		if($result_user->id === $this->current_user->id){
		        		$update = true;
		        	}else{
		        		if($this->current_user->allowed($this->admin_update_users, $result_user, true, 'creator_id')){
		        			$update = true;
		        		}
		        	}
	        	}

	        	/*判断是否具有删除权限*/
	        	$delete = false;
	        	if($this->current_user->can($this->admin_delete_users)){
		        	if($this->current_user->allowed($this->admin_delete_users, $result_user, true, 'creator_id')){
		        		$delete = true;
		        	}
	        	}

	        	$id = $result_user['id'];

    			if($update){
    				$this->btnContract
	    				->createUpdateModalButton($this->admin_update_users, route('user.update.get', ['id' => $id]));
    			}

    			if($delete){
    				$this->btnContract->createDeleteButton($this->admin_delete_users, 'user_delete', $id);	
    			}

	        	$users[$key]['button'] = $this->btnContract->getReturnStr();
	        }

	        return $users;
		}

		/**
		 * 获取总量
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-05 10:06:00
		 * 
		 * @return		
		 */
		public function count(){
			return User::count();
		}

		/**
		 * 获取 用户 详情
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-05 10:40:24
		 * 
		 * @return		
		 */
		public function userInfo($id){
			return User::where('id', '=', $id)->first();
		}

		/*=========================================添加数据===============================*/
		/**
		 * 添加用户
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-05 10:24:49
		 * 
		 * @return		
		 */
		public function addUser($data){
			$returnData = [
				'status' => false,
				'msg' => '用户创建失败'
			];

			$user = new User;
			$user->name = $data['name'];
			$user->email = $data['email'];
			$user->password = $data['password'];
			$user->creator_id = $data['creator_id'];

			if($user->save()){
				$returnData = [
					'status' => true,
					'msg' => '用户创建成功',
					'data' => $user,
				];
			}

			return $returnData;
		}

		/**
		 * 用户添加 权限
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-05 10:31:02
		 * 
		 * @return		
		 */
		public function userAddPer($user, $add_permissions){
			$returnData = [
				'status' => true
			];

			/*对于获取的权限进行处理*/
		    $arr_permissions = explode(',', $add_permissions);

		    /*判断权限在库中*/
		    $permissions = Permission::whereIn('slug', $arr_permissions)->get();

		    /*用户添加单独权限*/
		    foreach($permissions as $permission){
		        $user->attachPermission($permission);
		    }

		    return $returnData;
		}

		/**
		 * 用户 添加 角色
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-05 10:34:27
		 * 
		 * @return		
		 */
		public function userAddRole($user, $add_roles){
			$returnData = [
				'status' => true,
			];
			/*判断角色是否存在*/
			$roles = Role::whereIn('id', $add_roles)->get();
			
			foreach($roles as $role){
			    $user->attachRole($role);
			}

			return $returnData;
		}

		/*=========================================修改数据===============================*/
		/**
		 * 修改用户
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-05 10:53:40
		 * 
		 * @return		
		 */
		public function upUser($user, $data){
			/*设置返回值*/
			$returnData = [
				'status' => false,
				'msg' => '修改失败',
			];

			$user->name = $data['name'];
			$user->email = $data['email'];
			$user->password = $data['password'];

			if($user->save()){
				$returnData = [
					'status' => true,
					'msg' => '修改成功',
					'data' => $user,
				];
			}

			return $returnData;
		}


		/*=========================================删除数据===============================*/
		/**
		 * 删除 用户
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2016-01-05 11:04:44
		 * 
		 * @return		
		 */
		public function delUser($id){
			return User::destroy($id);
		}



	}
?>