<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('organization_types');
    }

    public function down(): void
    {
        // Intentionally left empty; organization_types domain removed.
    }
};
