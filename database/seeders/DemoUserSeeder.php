<?php

namespace Database\Seeders;

use App\Models\Setup\AdminHierarchy;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminHierarchy = AdminHierarchy::query()->firstOrCreate(
            ['code' => 'HQ'],
            [
                'name' => 'Headquarters',
                'active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'demo@ttpb.local'],
            [
                'first_name' => 'Demo',
                'middle_name' => 'QA',
                'last_name' => 'User',
                'phone' => '255700000000',
                'admin_hierarchy_id' => $adminHierarchy->id,
                'password' => Hash::make('ChangeMe123!'),
            ]
        );
    }
}
