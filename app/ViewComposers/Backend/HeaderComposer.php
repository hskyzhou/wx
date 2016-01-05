<?php 
	namespace App\ViewComposers\Backend;

	use Illuminate\Contracts\View\View;

	class HeaderComposer{
		protected $current_user;

		public function __construct(){
			$this->current_user = auth()->user();
		}

		public function compose(View $view){

			$view->with('login_avatar', asset('admin/images/default.jpg'));
			$view->with('login_name', $this->current_user->name);
		}
	}


?>