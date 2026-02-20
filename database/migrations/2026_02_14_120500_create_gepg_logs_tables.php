<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gepg_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->foreignId('bill_id')->nullable()->constrained('bills')->nullOnDelete();
            $table->string('request_type', 60);
            $table->string('request_id', 120)->nullable();
            $table->text('payload')->nullable();
            $table->text('signature')->nullable();
            $table->string('status', 40)->default('PENDING');
            $table->text('response_payload')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['request_type', 'request_id']);
        });

        Schema::create('gepg_callbacks', function (Blueprint $table) {
            $table->id();
            $table->string('callback_type', 60);
            $table->string('external_request_id', 120)->nullable();
            $table->string('transaction_id', 120)->nullable();
            $table->string('bill_reference', 120)->nullable();
            $table->string('control_number', 120)->nullable();
            $table->text('signature')->nullable();
            $table->longText('payload');
            $table->boolean('verified')->default(false);
            $table->boolean('processed')->default(false);
            $table->timestamp('processed_at')->nullable();
            $table->string('status_code', 40)->nullable();
            $table->string('status_message', 255)->nullable();
            $table->timestamps();

            $table->unique(['callback_type', 'transaction_id', 'external_request_id'], 'gepg_callbacks_idempotency_uq');
            $table->index(['bill_reference', 'control_number']);
        });

        Schema::create('payment_reconciliations', function (Blueprint $table) {
            $table->id();
            $table->date('reconciliation_date');
            $table->string('reference', 120)->nullable();
            $table->integer('total_records')->default(0);
            $table->integer('matched_records')->default(0);
            $table->integer('unmatched_records')->default(0);
            $table->text('details')->nullable();
            $table->string('status', 40)->default('PENDING');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_reconciliations');
        Schema::dropIfExists('gepg_callbacks');
        Schema::dropIfExists('gepg_requests');
    }
};
