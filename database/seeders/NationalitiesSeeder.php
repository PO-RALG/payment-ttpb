<?php

namespace Database\Seeders;

use App\Models\Setup\Nationality;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class NationalitiesSeeder extends Seeder
{
    public function run(): void
    {
        $response = Http::timeout(30)->get(
            'https://restcountries.com/v3.1/all',
            ['fields' => 'cca2,cca3,idd,name,demonyms']
        );

        if (! $response->successful()) {
            throw new RuntimeException('Failed to fetch nationalities from Rest Countries API.');
        }

        $countries = $response->json();

        if (! is_array($countries) || empty($countries)) {
            throw new RuntimeException('Rest Countries API returned an empty dataset.');
        }

        foreach ($countries as $country) {
            $code = $this->stringValue($country['cca2'] ?? null);
            $iso3 = $this->stringValue($country['cca3'] ?? null);
            $name = $this->resolveNationalityName($country);

            if ($code === '' || $name === '') {
                continue;
            }

            Nationality::query()->updateOrCreate(
                ['code' => strtoupper($code)],
                [
                    'iso3_code' => strtoupper($iso3),
                    'phone_code' => $this->resolvePhoneCode($country['idd'] ?? null),
                    'name' => $name,
                    'is_active' => true,
                    'created_by' => 'seeder',
                    'updated_by' => 'seeder',
                ]
            );
        }
    }

    private function resolveNationalityName(array $country): string
    {
        $male = $this->stringValue($country['demonyms']['eng']['m'] ?? null);
        $female = $this->stringValue($country['demonyms']['eng']['f'] ?? null);

        if ($male !== '') {
            return $male;
        }

        if ($female !== '') {
            return $female;
        }

        return $this->stringValue($country['name']['common'] ?? null);
    }

    private function resolvePhoneCode(mixed $idd): ?string
    {
        if (! is_array($idd)) {
            return null;
        }

        $root = $this->stringValue($idd['root'] ?? null);
        $suffix = '';

        if (isset($idd['suffixes'][0])) {
            $suffix = $this->stringValue($idd['suffixes'][0]);
        }

        $value = $root . $suffix;

        return $value === '' ? null : $value;
    }

    private function stringValue(mixed $value): string
    {
        if (! is_string($value)) {
            return '';
        }

        return trim($value);
    }
}
