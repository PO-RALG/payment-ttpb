<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('invoice_number', 60)->unique();
            $table->string('trigger_code', 100)->nullable();
            $table->string('trigger_reference', 150)->nullable();
            $table->string('module', 100);
            $table->string('sub_module', 150)->nullable();
            $table->string('payer_name', 255);
            $table->string('payer_phone', 30)->nullable();
            $table->string('payer_email', 150)->nullable();
            $table->decimal('amount_total', 14, 2);
            $table->string('currency', 3)->default('TZS');
            $table->string('status', 40)->default('DRAFT');
            $table->timestamp('expires_at')->nullable();
            $table->json('meta')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status']);
            $table->index(['trigger_code', 'trigger_reference']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
