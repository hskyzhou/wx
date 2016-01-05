<?php 
	namespace App\ViewComposers\Backend;

	use Illuminate\Contracts\View\View;

	use App\Services\Contracts\MenuContract;
	use App\Services\Contracts\BreadcrumbContract;

	class BreadcrumbComposer{
		// protected $current_user;
		protected $breadcrumbCon;

		public function __construct(BreadcrumbContract $breadcrumbCon){
			$this->breadcrumbCon = $breadcrumbCon;
		}

		public function compose(View $view){
			/*获取面包屑导航*/
			$breadcrumbs = $this->breadcrumbCon->getCurrentBreadcrumb();
			
			$view->with('breadcrumbs', $breadcrumbs);
		}
	}


?>