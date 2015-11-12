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
            'slug' => 'admin.menus.manage',
            'description' => '显示菜单管理', // optional
        ]);

        $menuList = Permission::create([
            'name' => 'Show Menus List',
            'slug' => 'admin.menus.list',
            'description' => '显示菜单列表', // optional
        ]);
        $menuUpdate = Permission::create([
            'name' => 'Update Menus',
            'slug' => 'admin.menus.update',
            'description' => '修改菜单', // optional
        ]);
        $menuAdd = Permission::create([
            'name' => 'Add Menus',
            'slug' => 'admin.menus.add',
            'description' => '添加菜单', // optional
        ]);
        $menuDelete = Permission::create([
            'name' => 'Delete Menus',
            'slug' => 'admin.menus.delete',
            'description' => '删除菜单', // optional
        ]);

        /*角色*/
        $roleManage = Permission::create([
            'name' => 'Show Roles Manage',
            'slug' => 'admin.roles.manage',
            'description' => '显示角色管理', // optional
        ]);

        $roleList = Permission::create([
            'name' => 'Show Roles List',
            'slug' => 'admin.roles.list',
            'description' => '显示角色列表', // optional
        ]);

        $roleUpdate = Permission::create([
            'name' => 'Update Roles',
            'slug' => 'admin.roles.update',
            'description' => '修改角色', // optional
        ]);
        $roleAdd = Permission::create([
            'name' => 'Add Roles',
            'slug' => 'admin.roles.add',
            'description' => '添加角色', // optional
        ]);
        $roleDelete = Permission::create([
            'name' => 'Delete Roles',
            'slug' => 'admin.roles.delete',
            'description' => '删除角色', // optional
        ]);

        /*权限*/
        $permissionManage = Permission::create([
            'name' => 'Show Permissions Manage',
            'slug' => 'admin.permissions.manage',
            'description' => '显示权限管理', // optional
        ]);

        $permissionList = Permission::create([
            'name' => 'Show Permissions List',
            'slug' => 'admin.permissions.list',
            'description' => '显示权限列表', // optional
        ]);

        $permissionUpdate = Permission::create([
            'name' => 'Update Permissions',
            'slug' => 'admin.permissions.update',
            'description' => '修改权限', // optional
        ]);
        $permissionAdd = Permission::create([
            'name' => 'Add Permissions',
            'slug' => 'admin.permissions.add',
            'description' => '添加权限', // optional
        ]);
        $permissionDelete = Permission::create([
            'name' => 'Delete Permissions',
            'slug' => 'admin.permissions.delete',
            'description' => '删除权限', // optional
        ]);

        /*用户*/
        $userManage = Permission::create([
            'name' => 'Show Users Manage',
            'slug' => 'admin.users.manage',
            'description' => '显示用户管理', // optional
        ]);

        $userList = Permission::create([
            'name' => 'Show Users List',
            'slug' => 'admin.users.list',
            'description' => '显示用户列表', // optional
        ]);

        $userUpdate = Permission::create([
            'name' => 'Update Users',
            'slug' => 'admin.users.update',
            'description' => '修改用户', // optional
        ]);
        $userAdd = Permission::create([
            'name' => 'Add Users',
            'slug' => 'admin.users.add',
            'description' => '添加用户', // optional
        ]);
        $userDelete = Permission::create([
            'name' => 'Delete Users',
            'slug' => 'admin.users.delete',
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
            'slug' => 'admin.page.show',
            'description' => '显示后台用户的个人首页', // optional
        ]);

        /*日志*/
        $logManage = Permission::create([
            'name' => 'Show Log Manage',
            'slug' => 'admin.logs.manage',
            'description' => '显示日志管理', // optional
        ]);

        $logList = Permission::create([
            'name' => 'Show Log All',
            'slug' => 'admin.logs.all',
            'description' => '显示日志总览', // optional
        ]);

        $logList = Permission::create([
            'name' => 'Show Log List',
            'slug' => 'admin.logs.list',
            'description' => '显示日志列表', // optional
        ]);
    }
}
