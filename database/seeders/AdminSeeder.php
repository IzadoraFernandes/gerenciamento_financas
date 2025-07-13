<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Cria ou encontra a permissão
        $permission = Permission::firstOrCreate([
            'name' => 'access_admin',
            'guard_name' => 'web',
        ]);

        // Cria ou encontra a role
        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        // Vincula a permissão à role
        if (! $role->hasPermissionTo('access_admin')) {
            $role->givePermissionTo($permission);
        }

        // Cria o usuário admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@teste.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('12345678'),
            ]
        );

        // Atribui a role ao usuário
        if (! $admin->hasRole('admin')) {
            $admin->assignRole($role);
        }

        echo "Usuário admin criado e configurado com sucesso.\n";
    }
}
