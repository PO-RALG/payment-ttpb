<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\LazyCollection;

class AdminAreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/admin_areas.csv');

        if (! file_exists($path)) {
            throw new \RuntimeException("CSV seed data not found at {$path}");
        }

        Schema::disableForeignKeyConstraints();

        $this->rowsFromCsv($path)
            ->map(function (array $row) {
                return [
                    'id' => (int) $row['id'],
                    'name' => $row['name'],
                    'parent_area_id' => $this->nullableInt($row['parent_area_id'] ?? null),
                    'description' => $this->nullIfEmpty($row['description'] ?? null),
                    'boundary_id' => $this->nullableInt($row['boundary_id'] ?? null),
                    'valid_from' => $this->nullIfEmpty($row['valid_from'] ?? null),
                    'valid_until' => $this->nullIfEmpty($row['valid_until'] ?? null),
                    'area_type_id' => (int) $row['area_type_id'],
                    'created_on' => $this->nullIfEmpty($row['created_on'] ?? null),
                    'created_by_user_id' => $this->nullableInt($row['created_by_user_id'] ?? null),
                    'updated_on' => $this->nullIfEmpty($row['updated_on'] ?? null),
                    'updated_by_user_id' => $this->nullableInt($row['updated_by_user_id'] ?? null),
                    'boundary_status_id' => $this->nullableInt($row['boundary_status_id'] ?? null),
                    'retired' => $this->toBool($row['retired'] ?? null, false),
                    'label' => $this->nullIfEmpty($row['label'] ?? null),
                    'area_short_name' => $this->nullIfEmpty($row['area_short_name'] ?? null),
                    'area_hq_id' => $this->nullableInt($row['area_hq_id'] ?? null),
                    'area_code' => $this->nullIfEmpty($row['area_code'] ?? null),
                    'establishment_date_approximated' => $this->toBool($row['establishment_date_approximated'] ?? null, false),
                    'mof_code' => $this->nullIfEmpty($row['mof_code'] ?? null),
                    'created_at' => $this->nullIfEmpty($row['created_at'] ?? null),
                    'updated_at' => $this->nullIfEmpty($row['updated_at'] ?? null),
                    'deleted_at' => $this->nullIfEmpty($row['deleted_at'] ?? null),
                    'ares_code' => $this->nullIfEmpty($row['ares_code'] ?? null),
                ];
            })
            ->chunk(1000)
            ->each(function (LazyCollection $chunk) {
                DB::table('admin_areas')->upsert(
                    $chunk->all(),
                    ['id'],
                    [
                        'name',
                        'parent_area_id',
                        'description',
                        'boundary_id',
                        'valid_from',
                        'valid_until',
                        'area_type_id',
                        'created_on',
                        'created_by_user_id',
                        'updated_on',
                        'updated_by_user_id',
                        'boundary_status_id',
                        'retired',
                        'label',
                        'area_short_name',
                        'area_hq_id',
                        'area_code',
                        'establishment_date_approximated',
                        'mof_code',
                        'created_at',
                        'updated_at',
                        'deleted_at',
                        'ares_code',
                    ]
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

    private function toBool(?string $value, bool $default): bool
    {
        if ($value === null || $value === '') {
            return $default;
        }

        return in_array(strtolower($value), ['1', 't', 'true', 'y', 'yes'], true);
    }
}
