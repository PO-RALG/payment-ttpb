<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')
            && Schema::hasColumn('users', 'admin_hierarchy_id')
            && ! Schema::hasColumn('users', 'admin_area_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('admin_area_id')->nullable()->after('gender_id');
            });
        }

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'admin_hierarchy_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('admin_hierarchy_id');
            });
        }

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'admin_area_id')) {
            if (DB::getDriverName() === 'pgsql') {
                DB::statement('CREATE INDEX IF NOT EXISTS users_admin_area_id_index ON users (admin_area_id)');
                DB::statement(<<<'SQL'
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM pg_constraint
        WHERE conname = 'users_admin_area_id_foreign'
    ) THEN
        ALTER TABLE users
        ADD CONSTRAINT users_admin_area_id_foreign
        FOREIGN KEY (admin_area_id) REFERENCES admin_areas(id) ON DELETE SET NULL;
    END IF;
END $$;
SQL);
            } else {
                Schema::table('users', function (Blueprint $table) {
                    $table->foreign('admin_area_id')
                        ->references('id')
                        ->on('admin_areas')
                        ->nullOnDelete();
                });
            }
        }

        Schema::dropIfExists('admin_hierarchies');
    }

    public function down(): void
    {
        if (Schema::hasTable('users')
            && Schema::hasColumn('users', 'admin_area_id')
            && ! Schema::hasColumn('users', 'admin_hierarchy_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('admin_hierarchy_id')->nullable()->after('gender_id');
            });
        }
    }
};
