<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete()->unique();
            $table->string('bill_reference', 80)->unique();
            $table->string('req_id', 80)->nullable()->index();
            $table->string('grp_bill_id', 80)->nullable()->index();
            $table->string('control_number', 80)->nullable()->unique();
            $table->decimal('amount', 14, 2);
            $table->string('currency', 3)->default('TZS');
            $table->string('status', 40)->default('PENDING_SUBMISSION');
            $table->timestamp('expires_at')->nullable();
            $table->text('submit_payload')->nullable();
            $table->text('submit_response')->nullable();
            $table->json('meta')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
