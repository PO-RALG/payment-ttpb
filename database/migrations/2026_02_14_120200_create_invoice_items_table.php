<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('fee_rule_id')->nullable()->constrained('fee_rules')->nullOnDelete();
            $table->string('item_code', 100)->nullable();
            $table->string('description', 255);
            $table->decimal('quantity', 10, 2)->default(1);
            $table->decimal('unit_amount', 14, 2);
            $table->decimal('line_total', 14, 2);
            $table->json('meta')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
