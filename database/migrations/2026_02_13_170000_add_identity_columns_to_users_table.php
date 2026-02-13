<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('nationality_id')
                ->nullable()
                ->after('gender_id')
                ->constrained('nationalities')
                ->nullOnDelete();

            $table->string('nin', 50)
                ->nullable()
                ->after('nationality_id')
                ->unique();

            $table->timestamp('phone_verified_at')
                ->nullable()
                ->after('email_verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone_verified_at');

            $table->dropUnique('users_nin_unique');
            $table->dropColumn('nin');

            $table->dropConstrainedForeignId('nationality_id');
        });
    }
};
