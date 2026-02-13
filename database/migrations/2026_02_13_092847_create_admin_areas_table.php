<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin_areas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->unsignedBigInteger('parent_area_id')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('boundary_id')->nullable();
            $table->timestampTz('valid_from')->nullable();
            $table->timestampTz('valid_until')->nullable();
            $table->unsignedBigInteger('area_type_id');
            $table->timestampTz('created_on')->useCurrent();
            $table->unsignedBigInteger('created_by_user_id')->nullable();
            $table->timestampTz('updated_on')->nullable();
            $table->unsignedBigInteger('updated_by_user_id')->nullable();
            $table->unsignedBigInteger('boundary_status_id')->nullable();
            $table->boolean('retired')->default(false);
            $table->string('label', 255)->nullable();
            $table->string('area_short_name', 5)->nullable();
            $table->unsignedBigInteger('area_hq_id')->nullable();
            $table->string('area_code', 8)->nullable();
            $table->boolean('establishment_date_approximated')->default(false);
            $table->string('mof_code', 8)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->text('ares_code')->nullable();

            $table->foreign('area_type_id')
                ->references('id')
                ->on('admin_area_levels')
                ->cascadeOnUpdate();

            $table->foreign('parent_area_id')
                ->references('id')
                ->on('admin_areas')
                ->nullOnDelete();

            $table->foreign('area_hq_id')
                ->references('id')
                ->on('admin_areas')
                ->nullOnDelete();

            $table->index(['area_type_id']);
            $table->index(['parent_area_id']);
            $table->index(['area_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_areas');
    }
};
