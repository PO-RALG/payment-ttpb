<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_rules', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100)->unique();
            $table->string('module', 100);
            $table->string('sub_module', 150)->nullable();
            $table->string('trigger_action', 255)->nullable();
            $table->string('trigger_condition', 255)->nullable();
            $table->string('payment_type', 150);
            $table->decimal('amount', 14, 2)->nullable();
            $table->string('currency', 3)->default('TZS');
            $table->boolean('active')->default(true);
            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();
            $table->string('frequency', 50)->default('ONE_OFF');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_rules');
    }
};
