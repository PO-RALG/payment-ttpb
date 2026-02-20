<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gepg_control_number_inbox', function (Blueprint $table) {
            $table->id();
            $table->string('external_request_id', 120)->nullable();
            $table->string('bill_id', 120)->nullable();
            $table->string('grp_bill_id', 120)->nullable();
            $table->string('control_number', 120)->nullable();
            $table->string('status_code', 40)->nullable();
            $table->text('signature')->nullable();
            $table->longText('payload');
            $table->boolean('verified')->default(false);
            $table->boolean('processed')->default(false);
            $table->unsignedInteger('attempt_count')->default(0);
            $table->timestamp('received_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->text('last_error')->nullable();
            $table->timestamps();

            $table->unique(['external_request_id', 'bill_id', 'grp_bill_id'], 'gepg_control_inbox_uq');
            $table->index(['control_number', 'processed']);
        });

        Schema::create('gepg_payment_inbox', function (Blueprint $table) {
            $table->id();
            $table->string('external_request_id', 120)->nullable();
            $table->string('transaction_id', 120)->nullable();
            $table->string('bill_id', 120)->nullable();
            $table->string('grp_bill_id', 120)->nullable();
            $table->string('control_number', 120)->nullable();
            $table->text('signature')->nullable();
            $table->longText('payload');
            $table->boolean('verified')->default(false);
            $table->boolean('processed')->default(false);
            $table->unsignedInteger('attempt_count')->default(0);
            $table->timestamp('received_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->text('last_error')->nullable();
            $table->timestamps();

            $table->unique(['transaction_id'], 'gepg_payment_inbox_trx_uq');
            $table->index(['control_number', 'processed']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gepg_payment_inbox');
        Schema::dropIfExists('gepg_control_number_inbox');
    }
};
