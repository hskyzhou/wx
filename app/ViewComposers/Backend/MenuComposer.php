<?php 
	namespace App\ViewComposers\Backend;

	use Illuminate\Contracts\View\View;

	use App\Services\Contracts\MenuContract;
	use App\Services\Contracts\BreadcrumbContract;

	class MenuComposer{
		// protected $current_user;
		protected $menuCon;

		public function __construct(MenuContract $menuCon){
			// $this->current_user = auth()->user();
			$this->menuCon = $menuCon;
		}

		public function compose(View $view){
			/*获取当前用户左侧菜单*/
			$user_menus = $this->menuCon->getUserMenu();

			$view->with('menus', $user_menus);
		}
	}


?>