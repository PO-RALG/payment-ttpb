<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Names
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);

            // Date type
            $table->date('date_of_birth')->nullable();

            // FK later
            $table->unsignedBigInteger('gender_id')->nullable();
            $table->unsignedBigInteger('admin_hierarchy_id'); // required? keep as required, or nullable if you want

            // Contacts
            $table->string('email', 150)->unique();
            $table->string('phone', 30)->unique();

            // Address
            $table->string('post_code', 20)->nullable();
            $table->string('physical_address', 255)->nullable();

            // Auth
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->rememberToken();
            $table->timestamps();

            // Helpful indexes
            $table->index(['last_name', 'first_name']);
            $table->index('gender_id');
            $table->index('admin_hierarchy_id');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email', 150)->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();

            $table->foreignId('user_id')
                ->nullable()
                ->index()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};