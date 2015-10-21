<?php
	namespace App\Services;

	use App\Services\Contracts\PermissionContract;

	class PermissionService implements PermissionContract{

		/**
		 * 权限转 树  --添加
		 * 
		 * @param		$permissions 所有的权限
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2015-10-19 17:03:02
		 * 
		 * @return		
		 */
		public function dealArrayToJsTreeAdd($permissions){
			$returnArr = [];
			/*是数组则遍历*/
			if(!is_array($permissions)){
				return $permissions;
			}
			foreach($permissions as $key => $val){
				if(is_array($val)){
					$returnArr[] = [
						'text' => $key,
						'children' => $this->dealArrayToJsTreeAdd($val),
						'id' => $key
					];
				}else{
					$arr_val = json_decode($val, true);
					$returnArr[] = [
						'text' => $arr_val['val'],
						'id' => $arr_val['key']
					];
				}
			}
			return $returnArr;
		}

		/**
		 * 权限转树---修改
		 * 
		 * @param		$permissions  所有权限
		 * @param		$has_permissions  拥有的权限
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2015-10-19 17:03:21
		 * 
		 * @return		
		 */
		public function dealArrayToJsTreeUpdate($permissions, $has_permissions){
			$returnArr = [];
			/*是数组则遍历*/
			if(!is_array($permissions)){
				return $permissions;
			}
			foreach($permissions as $key => $val){
				if(is_array($val)){
					$returnArr[] = [
						'text' => $key,
						'children' => $this->dealArrayToJsTreeUpdate($val, $has_permissions),
						'id' => $key,
						'state' => [
							'opened' => true
						]
					];
				}else{
					$arr_val = json_decode($val, true);
					$selected = in_array($arr_val['key'], $has_permissions) ? true : false;
					$returnArr[] = [
						'text' => $arr_val['val'],
						'id' => $arr_val['key'],
						'state' => [
							'selected' => $selected
						]
					];
				}
			}
			return $returnArr;
		}
	}