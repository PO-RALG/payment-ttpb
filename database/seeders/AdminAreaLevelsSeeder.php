<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\LazyCollection;

class AdminAreaLevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/admin_area_levels.csv');

        if (! file_exists($path)) {
            throw new \RuntimeException("CSV seed data not found at {$path}");
        }

        Schema::disableForeignKeyConstraints();

        $this->rowsFromCsv($path)
            ->map(function (array $row) {
                return [
                    'id' => (int) $row['id'],
                    'name' => $this->nullIfEmpty($row['name'] ?? null),
                    'name_sw' => $this->nullIfEmpty($row['name_sw'] ?? null),
                    'order_id' => $this->nullableInt($row['order_id'] ?? null),
                    'created_at' => $this->nullIfEmpty($row['created_at'] ?? null),
                    'updated_at' => $this->nullIfEmpty($row['updated_at'] ?? null),
                    'deleted_at' => $this->nullIfEmpty($row['deleted_at'] ?? null),
                ];
            })
            ->chunk(100)
            ->each(function (LazyCollection $chunk) {
                DB::table('admin_area_levels')->upsert(
                    $chunk->all(),
                    ['id'],
                    ['name', 'name_sw', 'order_id', 'created_at', 'updated_at', 'deleted_at']
                );
            });

        Schema::enableForeignKeyConstraints();
    }

    private function rowsFromCsv(string $path): LazyCollection
    {
        return LazyCollection::make(function () use ($path) {
            $handle = fopen($path, 'rb');

            if ($handle === false) {
                throw new \RuntimeException("Unable to open {$path} for reading");
            }

            $header = fgetcsv($handle);

            if ($header === false) {
                fclose($handle);
                return;
            }

            while (($row = fgetcsv($handle)) !== false) {
                yield array_combine($header, $row);
            }

            fclose($handle);
        });
    }

    private function nullIfEmpty(?string $value): ?string
    {
        return $value !== null && $value !== '' ? $value : null;
    }

    private function nullableInt(?string $value): ?int
    {
        return $value === null || $value === '' ? null : (int) $value;
    }
}
