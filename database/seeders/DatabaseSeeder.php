<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Chama o AdminSeeder
        $this->call(AdminSeeder::class);
        $this->call(PermissionSeeder::class);

    }
}
