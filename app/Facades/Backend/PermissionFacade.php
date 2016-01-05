<?php 
	namespace App\Facades\Backend;

	use Illuminate\Support\Facades\Facade;

	class PermissionFacade extends Facade{
		protected static function getFacadeAccessor(){
			return 'permissionrepository';
		}
	}
?>