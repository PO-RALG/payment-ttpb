<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            Schema::table('users', function (Blueprint $table) {
                try {
                    $table->index('admin_area_id');
                } catch (\Throwable $e) {
                    // Index may already exist.
                }

                try {
                    $table->foreign('admin_area_id')
                        ->references('id')
                        ->on('admin_areas')
                        ->nullOnDelete();
                } catch (\Throwable $e) {
                    // Foreign key may already exist.
                }
            });
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
