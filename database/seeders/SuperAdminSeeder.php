<?php

namespace Database\Seeders;

use App\Models\Setup\Permission;
use App\Models\Setup\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::query()->updateOrCreate(
            ['code' => 'SUPER_ADMIN'],
            [
                'name' => 'Super Administrator',
                'created_by' => 'seeder',
                'updated_by' => 'seeder',
                'active' => true,
            ]
        );

        $permissions = collect([
            ['code' => 'SYS_USERS_MANAGE', 'name' => 'Manage users'],
            ['code' => 'SYS_ROLES_MANAGE', 'name' => 'Manage roles'],
            ['code' => 'SYS_PERMS_MANAGE', 'name' => 'Manage permissions'],
            ['code' => 'SYS_AUDIT_VIEW', 'name' => 'View audit logs'],
            ['code' => 'SYS_CONFIG_UPDATE', 'name' => 'Update system configuration'],
            ['code' => 'SYS_ORG_MANAGE', 'name' => 'Manage organization units'],
            ['code' => 'SYS_INSTITUTIONS_MANAGE', 'name' => 'Manage institutions'],
            ['code' => 'SYS_LICENSE_MANAGE', 'name' => 'Manage licence categories'],
            ['code' => 'SYS_REGTYPES_MANAGE', 'name' => 'Manage registration types'],
            ['code' => 'SYS_IDENTIFIERS_MANAGE', 'name' => 'Manage identity types'],
            ['code' => 'SYS_NATIONALITIES_MANAGE', 'name' => 'Manage nationalities'],
            ['code' => 'SYS_REPORTS_VIEW', 'name' => 'View sensitive reports'],
        ])->map(function (array $data) {
            return Permission::query()->updateOrCreate(
                ['code' => $data['code']],
                [
                    'name' => $data['name'],
                    'created_by' => 'seeder',
                    'updated_by' => 'seeder',
                    'active' => true,
                ]
            );
        });

        $role->permissions()->sync(
            $permissions->pluck('id')->mapWithKeys(function ($permissionId) {
                return [
                    $permissionId => [
                        'created_by' => 'seeder',
                        'updated_by' => 'seeder',
                        'active' => true,
                    ],
                ];
            })->all()
        );

        $user = User::query()->where('email', 'demo@ttpb.local')->first();

        if ($user) {
            $user->roles()->syncWithoutDetaching([
                $role->id => [
                    'assigned_at' => now(),
                    'assigned_by_user_id' => $user->id,
                    'created_by' => 'seeder',
                    'updated_by' => 'seeder',
                    'active' => true,
                ],
            ]);
        }
    }
}
