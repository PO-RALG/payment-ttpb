<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('bill_id')->constrained('bills')->cascadeOnDelete();
            $table->string('transaction_id', 120);
            $table->string('pay_ref_id', 120)->nullable();
            $table->decimal('billed_amount', 14, 2);
            $table->decimal('paid_amount', 14, 2);
            $table->string('currency', 3)->default('TZS');
            $table->string('status', 40)->default('RECEIVED');
            $table->string('payer_name', 255)->nullable();
            $table->string('payer_phone', 30)->nullable();
            $table->string('payer_email', 150)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('raw_payload')->nullable();
            $table->string('failure_reason')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['transaction_id']);
            $table->index(['invoice_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
