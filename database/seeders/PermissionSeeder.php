<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'access_admin',
            'permission_read',
            'permission_create',
            'permission_update',
            'permission_delete',
            'role_read',
            'role_create',
            'role_update',
            'role_delete',
            'user_read',
            'user_create',
            'user_update',
            'user_delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}
