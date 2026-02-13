<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('institution_code');
            $table->string('registration_no');
            $table->string('tin');
            $table->string('institution_type');
            $table->string('region');
            $table->string('district');
            $table->string('ward');
            $table->string('address');
            $table->string('website');
            $table->bigInteger('created_by_user_id');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('institutions');
    }
};
