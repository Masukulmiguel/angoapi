<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'manage-users',
            'manage-plans',
            'manage-payments',
            'manage-api-keys',
            'manage-settings',
            'manage-subscriptions',
            'view-dashboard',
            'manage-api-products',
            'view-audit-logs',
        ];

        foreach ($permissions as $perm) {
            Permission::create(['name' => $perm]);
        }

        $roles = [
            'SUPER_ADMIN' => $permissions,
            'ADMIN' => ['manage-users', 'manage-payments', 'manage-subscriptions', 'view-dashboard'],
            'SUPPORT' => ['view-dashboard', 'manage-subscriptions'],
            'CLIENT' => [],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::create(['name' => $roleName]);

            if (!empty($rolePermissions)) {
                $permissionIds = Permission::whereIn('name', $rolePermissions)->pluck('id')->toArray();
                $rolePermData = [];
                foreach ($permissionIds as $permId) {
                    $rolePermData[] = [
                        'role_id' => $role->id,
                        'permission_id' => $permId,
                    ];
                }
                DB::table('role_has_permissions')->insert($rolePermData);
            }
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
