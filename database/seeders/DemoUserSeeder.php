<?php

namespace Database\Seeders;

use App\Models\Setup\AdminHierarchy;
use App\Models\Setup\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminHierarchy = AdminHierarchy::query()
            ->find(392);

        if (! $adminHierarchy) {
            throw new RuntimeException('Admin area with id 392 was not found. Seed admin data first.');
        }

        $user = User::updateOrCreate(
            ['email' => 'demo@ttpb.go.tz'],
            [
                'first_name' => 'Demo',
                'middle_name' => 'QA',
                'last_name' => 'User',
                'phone' => '255700000020',
                'admin_area_id' => $adminHierarchy->id,
                'password' => Hash::make('ChangeMe123!'),
            ]
        );

        $superAdminRole = Role::query()->where('code', 'SUPER_ADMIN')->first();
        if ($superAdminRole) {
            $user->roles()->syncWithoutDetaching([
                $superAdminRole->id => [
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
