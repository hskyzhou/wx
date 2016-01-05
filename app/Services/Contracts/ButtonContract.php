<?php 
	namespace App\Services\Contracts;

	interface ButtonContract{
		/**
		 * 生成 修改按钮
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2015-12-03 19:50:39
		 * 
		 * @return		
		 */
		public function createAddButton($permission, $url = '');

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
		public function createUpdateButton($permission, $url = '');

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
		public function createDeleteButton($permission, $url = '');

		/**
		 * 生成 审核 按钮
		 * 
		 * @param		
		 * 
		 * @author		wen.zhou@bioon.com
		 * 
		 * @date		2015-12-04 15:29:47
		 * 
		 * @return		
		 */
		public function createVerifyButton($permission, $url = '');
		
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
		public function createAddCategoryButton($permission, $id, $name);

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
		public function getReturnStr();
	}
?>