<?php 
	namespace App\ViewComposers\Backend;

	use Illuminate\Contracts\View\View;

	class LayoutComposer{

		public function __construct(){
		}

		public function compose(View $view){
			/*项目名称*/
			$project_name = config('backend.backend.project_name');
			$project_small_name = config('backend.backend.project_small_name');
			$default_title = config('backend.backend.default_title');

			$view->with('project_name', $project_name);
			$view->with('project_small_name', $project_small_name);
			$view->with('default_title', $default_title);
		}
	}


?>