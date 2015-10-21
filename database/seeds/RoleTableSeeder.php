<?php

use Illuminate\Database\Seeder;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => '', // optional
            'level' => 1, // optional, set to 1 by default
        ]);

        $userRole = Role::create([
            'name' => 'User',
            'slug' => 'user',
        ]);

        /*管理员初始化所有权限*/
        $all_permissions = Permission::all();
        
        foreach($all_permissions as $all_permission){
            $adminRole->attachPermission($all_permission);
        }

        // 一般用户初始化 菜单管理权限
        // $menuManagePer = Permission::where('slug', '=', 'show.menu.manage')->first();
        // $menuListPer = Permission::where('slug', '=', 'show.menu.list')->first();
        $adminPagePer = Permission::where('slug', '=', 'show.admin.page')->first();
        $loginBackendPer = Permission::where('slug', '=', 'login.backend')->first();

        // $userRole->attachPermission($menuManagePer);
        // $userRole->attachPermission($menuListPer);
        $userRole->attachPermission($adminPagePer);
        $userRole->attachPermission($loginBackendPer);
    }
}
