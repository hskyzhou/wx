<?php

namespace App\Http\Middleware;

use Closure;

use Auth;

use App\Services\Contracts\MenuContract;

use App\Services\Contracts\BreadcrumbContract;

class BeforeAdminMiddleware
{
    protected $menuCon;
    protected $breadcrumbCon;

    public function __construct(MenuContract $menuCon, BreadcrumbContract $breadcrumbCon){
        $this->menuCon = $menuCon;
        $this->breadcrumbCon = $breadcrumbCon;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user_menus = $this->menuCon->getUserMenu();

        $breadcrumbs = $this->breadcrumbCon->getCurrentBreadcrumb();
        $activemenus = $this->breadcrumbCon->getCurrentActiveMenu();
        
        view()->share('breadcrumbs', $breadcrumbs);
        view()->share('activemenus', $activemenus);

        view()->share('menus', $user_menus);

        return $next($request);
    }
}
