<?php

namespace Database\Seeders;

use App\Models\Setup\Permission;
use App\Models\Setup\Role;
use Illuminate\Database\Seeder;

class DefaultRoleSeeder extends Seeder
{
    public function run(): void
    {
        $permission = Permission::query()->updateOrCreate(
            ['code' => 'NEW_USER'],
            [
                'name' => 'New user permission',
                'created_by' => 'seeder',
                'updated_by' => 'seeder',
                'active' => true,
            ]
        );

        $role = Role::query()->updateOrCreate(
            ['code' => 'NEW_USER_ROLE'],
            [
                'name' => 'New User',
                'created_by' => 'seeder',
                'updated_by' => 'seeder',
                'active' => true,
            ]
        );

        $role->permissions()->syncWithoutDetaching([
            $permission->id => [
                'created_by' => 'seeder',
                'updated_by' => 'seeder',
                'active' => true,
            ],
        ]);
    }
}
