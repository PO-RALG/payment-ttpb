<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('admin_hierarchy_id');
            $table->timestamp('first_login_at')->nullable()->after('remember_token');
            $table->boolean('must_change_password')->default(true)->after('first_login_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'first_login_at', 'must_change_password']);
        });
    }
};
