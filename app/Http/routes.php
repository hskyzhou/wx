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
Route::group(['namespace' => 'Admin', 'middleware' => ['auth', 'permission:login.backend']], function ($router) {
	/*角色*/
    $router->group(['prefix' => 'role', 'as' => 'role.'], function($router){
    	$router->get('/', 'RoleController@getIndex');
    	$router->get('index', 'RoleController@getIndex');
    	$router->get('show', 'RoleController@getShow');
    	$router->get('rolelist', 'RoleController@getRolelist');
    	$router->get('update', 'RoleController@getUpdate')->name('update.get');
    	$router->post('update', 'RoleController@postUpdate');
    	$router->get('add', 'RoleController@getAdd')->name('add.get');
    	$router->post('add', 'RoleController@postAdd')->name('add.post');
    	$router->get('delete', 'RoleController@getDelete');
    	$router->get('permission', 'RoleController@getPermission');
    });

    /*权限管理*/
    $router->group(['prefix' => 'permission', 'as' => 'permission.'], function($router){
    	$router->get('/', 'PermissionController@getIndex');
    	$router->get('index', 'PermissionController@getIndex');
    	$router->get('show', 'PermissionController@getShow');
    	$router->get('permissionlist', 'PermissionController@getPermissionlist');
    	$router->get('update', 'PermissionController@getUpdate')->name('update.get');
    	$router->post('update', 'PermissionController@postUpdate');
    	$router->get('add', 'PermissionController@getAdd')->name('add.get');
    	$router->post('add', 'PermissionController@postAdd')->name('add.post');
    	$router->get('delete', 'PermissionController@getDelete');
    });

    /*用户管理*/
    $router->group(['prefix' => 'user', 'as' => 'user.'], function($router){
    	$router->get('/', 'UserController@getIndex');
    	$router->get('index', 'UserController@getIndex');
    	$router->get('show', 'UserController@getShow');
    	$router->get('userlist', 'UserController@getUserlist');
    	$router->get('update', 'UserController@getUpdate')->name('update.get');
    	$router->post('update', 'UserController@postUpdate');
    	$router->get('add', 'UserController@getAdd')->name('add.get');
    	$router->post('add', 'UserController@postAdd')->name('add.post');
    	$router->get('delete', 'UserController@getDelete');
    	$router->get('permission', 'UserController@getPermission');
    });

    /*菜单*/
    $router->group(['prefix' => 'menu', 'as' => 'menu.'], function($router){
    	$router->get('/', 'MenuController@getIndex');
    	$router->get('index', 'MenuController@getIndex');
    	$router->get('show', 'MenuController@getShow');
    	$router->get('menulist', 'MenuController@getMenulist');
    	$router->get('update', 'MenuController@getUpdate')->name('update.get');
    	$router->post('update', 'MenuController@postUpdate');
    	$router->get('add', 'MenuController@getAdd')->name('add.get');
    	$router->post('add', 'MenuController@postAdd')->name('add.post');
    	$router->get('delete', 'MenuController@getDelete');

    });

    Route::controller('admin', 'AdminController'); //后台用户首页
    
    Route::get('log', 'LogviewController@index'); //后台用户首页
});

Route::group(['namespace' => 'Front', 'middleware' => 'check.wechat'], function($router){
    $router->match(['get', 'post'], '/', 'IndexController@index');
});