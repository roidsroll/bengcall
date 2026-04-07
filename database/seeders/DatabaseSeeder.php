<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(MenuSeeder::class);

        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();

        if ($adminRole) {
            User::firstOrCreate(
                ['email' => 'admin@bengcall.test'],
                [
                    'name' => 'Admin',
                    'role_id' => $adminRole->id,
                    'password' => Hash::make('password'),
                    'gender' => 'Laki-laki',
                    'address' => null,
                    'is_active' => true,
                ]
            );
        }

        if ($userRole) {
            User::firstOrCreate(
                ['email' => 'user@bengcall.test'],
                [
                    'name' => 'User',
                    'role_id' => $userRole->id,
                    'password' => Hash::make('password'),
                    'gender' => 'Perempuan',
                    'address' => null,
                    'is_active' => true,
                ]
            );
        }
    }
}
