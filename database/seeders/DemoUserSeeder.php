<?php

namespace Database\Seeders;

use App\Models\Setup\AdminHierarchy;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admin_area_levels')->updateOrInsert(
            ['id' => 1],
            [
                'name' => 'National',
                'name_sw' => 'Taifa',
                'order_id' => 1,
                'updated_at' => now(),
                'deleted_at' => null,
            ]
        );

        $adminHierarchy = AdminHierarchy::query()->firstOrCreate(
            ['area_code' => 'HQ'],
            [
                'name' => 'Headquarters',
                'area_type_id' => 1,
                'retired' => false,
            ]
        );

        User::updateOrCreate(
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
    }
}
