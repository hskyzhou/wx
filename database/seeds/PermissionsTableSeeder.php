<?php

use Illuminate\Database\Seeder;

use Bican\Roles\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		/*菜单*/
        $menuManage = Permission::create([
            'name' => 'Show Menus Manage',
            'slug' => 'show.menu.manage',
            'description' => '显示菜单管理', // optional
        ]);

        $menuList = Permission::create([
            'name' => 'Show Menus List',
            'slug' => 'show.menu.list',
            'description' => '显示菜单列表', // optional
        ]);
        $menuUpdate = Permission::create([
            'name' => 'Update Menus',
            'slug' => 'update.menus',
            'description' => '修改菜单', // optional
        ]);
        $menuAdd = Permission::create([
            'name' => 'Add Menus',
            'slug' => 'add.menus',
            'description' => '添加菜单', // optional
        ]);
        $menuDelete = Permission::create([
            'name' => 'Delete Menus',
            'slug' => 'delete.menus',
            'description' => '删除菜单', // optional
        ]);

        /*角色*/
        $roleManage = Permission::create([
            'name' => 'Show Roles Manage',
            'slug' => 'show.role.manage',
            'description' => '显示角色管理', // optional
        ]);

        $roleList = Permission::create([
            'name' => 'Show Roles List',
            'slug' => 'show.role.list',
            'description' => '显示角色列表', // optional
        ]);

        $roleUpdate = Permission::create([
            'name' => 'Update Roles',
            'slug' => 'update.roles',
            'description' => '修改角色', // optional
        ]);
        $roleAdd = Permission::create([
            'name' => 'Add Roles',
            'slug' => 'add.roles',
            'description' => '添加角色', // optional
        ]);
        $roleDelete = Permission::create([
            'name' => 'Delete Roles',
            'slug' => 'delete.roles',
            'description' => '删除角色', // optional
        ]);

        /*权限*/
        $permissionManage = Permission::create([
            'name' => 'Show Permissions Manage',
            'slug' => 'show.permission.manage',
            'description' => '显示权限管理', // optional
        ]);

        $permissionList = Permission::create([
            'name' => 'Show Permissions List',
            'slug' => 'show.permission.list',
            'description' => '显示权限列表', // optional
        ]);

        $permissionUpdate = Permission::create([
            'name' => 'Update Permissions',
            'slug' => 'update.permissions',
            'description' => '修改权限', // optional
        ]);
        $permissionAdd = Permission::create([
            'name' => 'Add Permissions',
            'slug' => 'add.permissions',
            'description' => '添加权限', // optional
        ]);
        $permissionDelete = Permission::create([
            'name' => 'Delete Permissions',
            'slug' => 'delete.permissions',
            'description' => '删除权限', // optional
        ]);

        /*用户*/
        $userManage = Permission::create([
            'name' => 'Show Users Manage',
            'slug' => 'show.user.manage',
            'description' => '显示用户管理', // optional
        ]);

        $userList = Permission::create([
            'name' => 'Show Users List',
            'slug' => 'show.user.list',
            'description' => '显示用户列表', // optional
        ]);

        $userUpdate = Permission::create([
            'name' => 'Update Users',
            'slug' => 'update.users',
            'description' => '修改用户', // optional
        ]);
        $userAdd = Permission::create([
            'name' => 'Add Users',
            'slug' => 'add.users',
            'description' => '添加用户', // optional
        ]);
        $userDelete = Permission::create([
            'name' => 'Delete Users',
            'slug' => 'delete.users',
            'description' => '删除用户', // optional
        ]);

        /*登录后台*/
        $permissionManage = Permission::create([
            'name' => 'Login Backend',
            'slug' => 'login.backend',
            'description' => '是否允许登录后台', // optional
        ]);

        /*后台个人首页*/
        $adminUserPage = Permission::create([
            'name' => 'Show Admin Page',
            'slug' => 'show.admin.page',
            'description' => '显示后台用户的个人首页', // optional
        ]);

        /*日志*/
        $logManage = Permission::create([
            'name' => 'Show Log Manage',
            'slug' => 'show.log.manage',
            'description' => '显示日志管理', // optional
        ]);

        $logList = Permission::create([
            'name' => 'Show Log All',
            'slug' => 'show.log.all',
            'description' => '显示日志总览', // optional
        ]);

        $logList = Permission::create([
            'name' => 'Show Log List',
            'slug' => 'show.log.list',
            'description' => '显示日志列表', // optional
        ]);
    }
}
