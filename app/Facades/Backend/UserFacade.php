<?php 
	namespace App\Facades\Backend;

	use Illuminate\Support\Facades\Facade;

	class UserFacade extends Facade{
		protected static function getFacadeAccessor(){
			return 'userrepository';
		}
	}
?>