<?php 
	namespace App\Facades\Backend;

	use Illuminate\Support\Facades\Facade;

	class MenuFacade extends Facade{
		protected static function getFacadeAccessor(){
			return 'menurepository';
		}
	}
?>