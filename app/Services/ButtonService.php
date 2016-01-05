<?php 
	namespace App\Services;

	use App\Services\Contracts\ButtonContract;

	class ButtonService implements ButtonContract{

		
		/**
		 * 返回字符串
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2015-12-04 15:09:46
		 * 
		 * @return		
		 */
		public $returnStr;

		/*当前用户*/
		protected $current_user;

		public function __construct(){
			$this->current_user = auth()->user();
		}

		/**
		 * 生成 添加按钮
		 * 
		 * @param		$current_user     App\User
		 * @param  		$permission  	  String
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2015-12-03 19:50:39
		 * 
		 * @return		
		 */
		public function createAddButton($permission, $url = '', $name='添加'){
			if($this->current_user->can($permission)){
				$this->returnStr .= "<a href='{$url}' class='btn btn-success'><i class='fa fa-plus'></i>{$name}</a> ";
			}
			return $this;
		}

		/**
		 * 生成 添加按钮  -- modal
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2015-12-08 10:55:32
		 * 
		 * @return		
		 */
		public function createAddModalButton($permission, $url = '', $name='添加'){
			if($this->current_user->can($permission)){
				$this->returnStr .= "<a href='{$url}' data-target='#contentmodal' data-toggle='modal' class='btn btn-success'><i class='fa fa-plus'></i>{$name}</a> ";
			}
			return $this;
		}
		
		/**
		 * 生成 修改 按钮
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2015-12-03 19:51:04
		 * 
		 * @return		
		 */
		public function createUpdateButton($permission, $url = '', $name='修改'){
			if($this->current_user->can($permission)){
				$this->returnStr .= "<a href='{$url}' class='btn btn-info btn-xs'><i class='fa fa-pencil'></i>{$name}</a> ";
			}
			return $this;
		}

		/**
		 * 生成 修改按钮 modal
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2015-12-08 10:56:22
		 * 
		 * @return		
		 */
		public function createUpdateModalButton($permission, $url = '', $name='修改'){
			if($this->current_user->can($permission)){
				$this->returnStr .= "<a href='{$url}' data-target='#contentmodal' data-toggle='modal' class='btn btn-info btn-xs'><i class='fa fa-pencil'></i>{$name}</a> ";
			}
			return $this;
		}
		

		/**
		 * 生成 删除 按钮
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2015-12-03 19:51:25
		 * 
		 * @return		
		 */
		public function createDeleteButton($permission, $class='', $hashid='', $name='删除'){
			if($this->current_user->can($permission)){
				$this->returnStr .= "<a href='' class='btn btn-danger btn-xs {$class}' data-id='{$hashid}'><i class='fa fa-remove'></i>{$name}</a> ";
			}
			return $this;
		}

		/**
		 * 生成 审核 按钮
		 * 
		 * @param					
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2015-12-04 15:28:14
		 * 
		 * @return		
		 */
		public function createVerifyButton($permission, $url = '', $name='审核'){
			if($this->current_user->can($permission)){
				$this->returnStr .= "<a href='{$url}' class='btn btn-primary btn-xs'><i class='fa fa-hand-o-up'></i>{$name}</a> ";
			}
			return $this;
		}

		/**
		 * 生成 多个删除 按钮
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2015-12-04 16:12:07
		 * 
		 * @return		
		 */
		public function createMoreDeleteButton($permission, $id, $name='删除'){
			if($this->current_user->can($permission)){
				$this->returnStr .= "<a class='btn btn-danger' id='{$id}'><i class='fa fa-remove'></i>{$name}</a> ";
			}
			return $this;
		}

		/**
		 * 生成 批量添加栏目 按钮
		 * 
		 * @param					
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2015-12-04 15:28:14
		 * 
		 * @return		
		 */
		public function createAddCategoryButton($permission, $id, $name){
			if($this->current_user->can($permission)){
				$this->returnStr .= "<a class='btn btn-info' id='{$id}'><i class='fa fa-plus'></i>{$name}</a> ";
			}
			return $this;
		}

		/**
		 * 生成 批量添加栏目 按钮
		 * 
		 * @param					
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2015-12-04 15:28:14
		 * 
		 * @return		
		 */
		public function createMergeButton($permission, $id, $name){
			if($this->current_user->can($permission)){
				$this->returnStr .= "<a class='btn btn-info' id='{$id}'><i class='fa fa-plus'></i>{$name}</a> ";
			}
			return $this;
		}

		/**
		 * 生成 恢复 按钮
		 * 
		 * @param					
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2015-12-04 15:28:14
		 * 
		 * @return		
		 */
		public function createRestoreButton($permission, $class,  $hashid, $name='恢复'){
			if($this->current_user->can($permission)){
				$this->returnStr .= "<a class='btn btn-info btn-xs $class' data-id='{$hashid}'><i class='fa fa-plus'></i>{$name}</a> ";
			}
			return $this;
		}

		/**
		 * 创建管理按钮
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2015-12-11 09:07:34
		 * 
		 * @return		
		 */
		public function createManageButton($permission, $url, $name="管理"){
			if($this->current_user->can($permission)){
				$this->returnStr .= "<a href='{$url}' class='btn btn-info btn-xs'><i class='fa fa-bars'></i>{$name}</a> ";
			}
			return $this;
		}

		/**
		 * 创建 锁定 按钮
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2015-12-11 09:25:46
		 * 
		 * @return		
		 */
		public function createLockButton($permission, $class, $hashid, $name="锁定"){
			if($this->current_user->can($permission)){
				$this->returnStr .= "<a class='btn btn-danger btn-xs $class' data-id='{$hashid}'><i class='fa fa-lock'></i>{$name}</a> ";
			}
			return $this;
		}

		/**
		 * 创建解锁按钮
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2015-12-14 16:12:15
		 * 
		 * @return		
		 */
		public function createUnlockButton($permission, $class, $hashid, $name="解锁"){
			if($this->current_user->can($permission)){
				$this->returnStr .= "<a class='btn btn-warning btn-xs $class' data-id='{$hashid}'><i class='fa fa-lock'></i>{$name}</a> ";
			}
			return $this;
		}

		/**
		 * 创建 评论  按钮
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2015-12-25 16:30:41
		 * 
		 * @return		
		 */
		public function createCommentButton($permission, $class, $hashid, $name="评论"){
			if($this->current_user->can($permission)){
				$this->returnStr .= "<a class='btn btn-warning btn-xs $class' data-id='{$hashid}'><i class='fa fa-lock'></i>{$name}</a> ";
			}
			return $this;
		}

		/**
		 * 创建 评论 modal  按钮
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2015-12-25 16:30:41
		 * 
		 * @return		
		 */
		public function createCommentModalButton($permission, $url, $name="评论"){
			if($this->current_user->can($permission)){
				$this->returnStr .= "<a href='{$url} data-target='#contentmodal' data-toggle='modal' class='btn btn-warning btn-xs'><i class='fa fa-lock'></i>{$name}</a> ";
			}
			return $this;
		}
		

		/**
		 * 返回按钮数据
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2015-12-04 15:30:21
		 * 
		 * @return		
		 */
		public function getReturnStr(){
			$tempStr = $this->returnStr;
			$this->returnStr = '';
			return $tempStr;
		}

	}
?>