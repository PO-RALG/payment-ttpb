<?php

namespace Database\Seeders;

use App\Models\Payment\FeeRule;
use Illuminate\Database\Seeder;

class FeeRulesSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            ['code' => 'FULL_REG_APP', 'module' => 'REGISTRATION', 'sub_module' => 'Full Registration', 'payment_type' => 'Full Registration Application Fee', 'amount' => 15000, 'frequency' => 'ONE_OFF'],
            ['code' => 'FULL_REG_ANNUAL', 'module' => 'REGISTRATION', 'sub_module' => 'Full Registration', 'payment_type' => 'Full Registration Annual Membership Fee', 'amount' => 20000, 'frequency' => 'ANNUAL'],
            ['code' => 'PROV_REG_APP', 'module' => 'REGISTRATION', 'sub_module' => 'Provisional Registration', 'payment_type' => 'Provisional Registration Application Fee', 'amount' => 15000, 'frequency' => 'ONE_OFF'],
            ['code' => 'PROV_REG_ANNUAL', 'module' => 'REGISTRATION', 'sub_module' => 'Provisional Registration', 'payment_type' => 'Provisional Registration Annual Membership Fee', 'amount' => 20000, 'frequency' => 'ANNUAL'],
            ['code' => 'TEMP_REG_APP', 'module' => 'REGISTRATION', 'sub_module' => 'Temporary Registration', 'payment_type' => 'Temporary Registration Fee', 'amount' => 50000, 'frequency' => 'ONE_OFF'],
            ['code' => 'TEMP_REG_ANNUAL', 'module' => 'REGISTRATION', 'sub_module' => 'Temporary Registration', 'payment_type' => 'Temporary Registration Membership Fee', 'amount' => 50000, 'frequency' => 'ANNUAL'],
            ['code' => 'LICENSE_APP', 'module' => 'PRACTICING_LICENSE', 'sub_module' => 'License Application', 'payment_type' => 'License Application Fee', 'amount' => 0, 'frequency' => 'ONE_OFF'],
            ['code' => 'LICENSE_RENEWAL', 'module' => 'PRACTICING_LICENSE', 'sub_module' => 'License Renewal', 'payment_type' => 'License Renewal Fee', 'amount' => 15000, 'frequency' => 'RENEWAL'],
            ['code' => 'CPD_PROVIDER_ACCREDITATION', 'module' => 'CPD_MANAGEMENT', 'sub_module' => 'CPD Provider Registration/Accreditation', 'payment_type' => 'CPD Provider Accreditation Fee', 'amount' => 1000000, 'frequency' => 'ONE_OFF'],
            ['code' => 'CERT_DUPLICATION', 'module' => 'CERTIFICATE', 'sub_module' => 'Replacement or Duplication', 'payment_type' => 'Certificate Duplication Fee', 'amount' => 100000, 'frequency' => 'ONE_OFF'],
            ['code' => 'COURSE_ADVERTISEMENT', 'module' => 'VERIFICATION_SERVICES', 'sub_module' => 'Course Advertisement', 'payment_type' => 'Course Advertisement Fee', 'amount' => null, 'frequency' => 'ONE_OFF'],
            ['code' => 'SYSTEM_VERIFICATION', 'module' => 'VERIFICATION_SERVICES', 'sub_module' => 'System Verification Request', 'payment_type' => 'System Verification Fee', 'amount' => null, 'frequency' => 'ONE_OFF'],
            ['code' => 'VERIFICATION_PRINTING', 'module' => 'VERIFICATION_SERVICES', 'sub_module' => 'Print/Export verified details', 'payment_type' => 'Verification Printing Fee', 'amount' => null, 'frequency' => 'ONE_OFF'],
        ];

        foreach ($rules as $rule) {
            FeeRule::query()->updateOrCreate(
                ['code' => $rule['code']],
                array_merge($rule, [
                    'currency' => 'TZS',
                    'active' => true,
                    'created_by' => 'system',
                    'updated_by' => 'system',
                ])
            );
        }
    }
}
