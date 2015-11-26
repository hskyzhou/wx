<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
Route::get('code/getcode','Auth\CodeController@getRefushCode');

/*后台*/
Route::group([/*'prefix' => 'admin', */'namespace' => 'Diseaseadmin', 'middleware' => ['auth', 'after.auth', 'permission:login.backend', 'before.menu']], function () {
	Route::get('/', 'AdminController@getIndex');
    Route::controller('role', 'RoleController');  //角色
    Route::controller('permission', 'PermissionController'); //权限
    Route::controller('user', 'UserController');  // 用户
    Route::controller('menu', 'MenuController'); //菜单
    Route::controller('admin', 'AdminController'); //后台用户首页
    Route::get('log', 'LogviewController@index'); //后台用户首页
});
//注册控制器的路由,加上后台的中间件
Route::group(['middleware'=>['auth', 'after.auth', 'permission:login.backend', 'before.menu']],function() {
    //专题管理控制器
    Route::controller('special', 'SpecialManager\SpecialController'
        , [
            'getAddspec' => 'special.addspec',
            'getSpeciallist'=>'special.speciallist'
        ]
    );
});



