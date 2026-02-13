<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_otps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('email', 150)->nullable();
            $table->string('code', 10);
            $table->timestamp('expires_at');
            $table->timestamp('verified_at')->nullable();
            $table->string('sent_via', 50)->default('mail');
            $table->timestamps();

            $table->index(['user_id', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_otps');
    }
};
