<?php

namespace Database\Seeders;

use App\Models\Setup\Gender;
use Illuminate\Database\Seeder;

class GendersSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['code' => 'M', 'name' => 'Male'],
            ['code' => 'F', 'name' => 'Female'],
        ];

        foreach ($items as $item) {
            Gender::query()->firstOrCreate(
                ['code' => $item['code']],
                [
                    'name' => $item['name'],
                    'created_by' => 'seeder',
                    'updated_by' => 'seeder',
                ]
            );
        }
    }
}
