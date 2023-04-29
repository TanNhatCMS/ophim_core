<?php

namespace Ophim\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Backpack\PermissionManager\app\Models\Permission;
use Backpack\PermissionManager\app\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'Browse actor',
            'Create actor',
            'Update actor',
            'Delete actor',
            'Browse director',
            'Create director',
            'Update director',
            'Delete director',
            'Browse tag',
            'Create tag',
            'Update tag',
            'Delete tag',
            'Browse studio',
            'Create studio',
            'Update studio',
            'Delete studio',
            'Browse catalog',
            'Create catalog',
            'Update catalog',
            'Delete catalog',
            'Browse category',
            'Create category',
            'Update category',
            'Delete category',
            'Browse region',
            'Create region',
            'Update region',
            'Delete region',
            'Browse crawl schedule',
            'Create crawl schedule',
            'Update crawl schedule',
            'Delete crawl schedule',
            'Browse movie',
            'Create movie',
            'Update movie',
            'Delete movie',
            'Browse user',
            'Create user',
            'Update user',
            'Delete user',
            'Browse role',
            'Create role',
            'Update role',
            'Delete role',
            'Browse permission',
            'Create permission',
            'Update permission',
            'Delete permission',
            'Browse episode',
            'Create episode',
            'Update episode',
            'Delete episode',
            'Browse menu',
            'Create menu',
            'Update menu',
            'Delete menu',
            'Delete menu item',
            'Browse plugin',
            'Update plugin',
            'Customize theme',
        ];
        $permissions_list = [
            'Browse actor' => 'Duyệt diễn viên',
            'Create actor' => 'Tạo diễn viên',
            'Update actor' => 'Cập nhật diễn viên',
            'Delete actor' => 'Xóa diễn viên',
            'Browse director' => 'Duyệt Đạo diễn',
            'Create director' => 'Tạo Đạo diễn',
            'Update director' => 'Cập nhật Đạo diễn',
            'Delete director' => 'Xóa Đạo diễn',
            'Browse tag' => 'Duyệt tag',
            'Create tag' => 'Tạo diễn tag',
            'Update tag' => 'Cập nhật tag',
            'Delete tag' => 'Xóa tag',
            'Browse studio' => 'Duyệt studio',
            'Create studio' => 'Tạo studio',
            'Update studio' => 'Cập nhật studio',
            'Delete studio' => 'Xóa studio',
            'Browse catalog' =>  'Duyệt Danh sách',
            'Create catalog' => 'Tạo Danh sách',
            'Update catalog' =>  'Cập nhật Danh sách',
            'Delete catalog' => 'Xóa Danh sách',
            'Browse category' => 'Duyệt Thể loại',
            'Create category' =>  'Tạo Thể loại',
            'Update category' => 'Cập nhật Thể loại',
            'Delete category' => 'Xóa Thể loại',
            'Browse region' => 'Duyệt Quốc gia',
            'Create region' => 'Tạo Quốc gia',
            'Update region' => 'Cập nhật Quốc gia',
            'Delete region' => 'Xoá Quốc gia',
            'Browse crawl schedule' => 'Duyệt lịch trình crawl',
            'Create crawl schedule' => 'Tạo lịch trình crawl',
            'Update crawl schedule' => 'Cập nhật lịch trình crawl',
            'Delete crawl schedule' => 'Xóa lịch trình crawl',
            'Browse movie' => 'Duyệt phim',
            'Create movie' => 'Tạo phim',
            'Update movie' => 'Cập nhật phim',
            'Delete movie' => 'Xóa phim',
            'Browse user' => 'Duyệt người dùng',
            'Create user' => 'Tạo người dùng',
            'Update user' => 'Duyệt người dùng',
            'Delete user' => 'Xóa người dùng',
            'Browse role' => 'Duyệt vai trò',
            'Create role' => 'Tạo vai trò',
            'Update role' => 'Cập nhật vai trò',
            'Delete role' => 'Xóa vai trò',
            'Browse permission' => 'Duyệt Quyền',
            'Create permission' => 'Tạo Quyền',
            'Update permission' => 'Cập nhật Quyền',
            'Delete permission' => 'Xóa Quyền',
            'Browse episode' => 'Duyệt tập phim',
            'Create episode' => 'Tạo tập phim',
            'Update episode' => 'Cập nhật tập phim',
            'Delete episode' => 'Xóa tập phim',
            'Browse menu' => 'Duyệt menu',
            'Create menu' => 'Tạo menu',
            'Update menu' => 'Cập nhật menu',
            'Delete menu' => 'Xóa menu',
            'Delete menu item' => 'Xóa menu item',
            'Browse plugin' => 'Duyệt plugin',
            'Update plugin' => 'Cập nhật plugin',
            'Customize theme' => 'Tùy chỉnh chủ đề',
        ];
        $admin = Role::firstOrCreate([
            'name' => "Admin",
            'guard_name' => 'backpack',
            'title' => "Quản trị viên"
        ]);
        foreach ($permissions_list as $permission => $title) {
            $result = Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'backpack',
                'title' => $title
            ]);
            $admin->givePermissionTo($permission);
            if (!$result) {
                $this->command->info("Insert failed at record $permission.");
                return;
            }
        }
    }
}