<?php 
	namespace App\Facades\Backend;

	use Illuminate\Support\Facades\Facade;

	class RoleFacade extends Facade{
		protected static function getFacadeAccessor(){
			return 'rolerepository';
		}
	}
?>