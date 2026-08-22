<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => env('ADMIN_NAME', 'Admin'),
            'email' => env('ADMIN_EMAIL', 'admin@angoapi.com'),
            'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
            'status' => User::STATUS_ACTIVE,
        ]);

        $admin->assignRole(Role::where('name', 'SUPER_ADMIN')->first());
    }
}
