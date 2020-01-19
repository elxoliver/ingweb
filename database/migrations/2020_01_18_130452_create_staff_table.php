<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->bigIncrements('staff_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->bigInteger('address_id');
            $table->string('image')->nullable();
            $table->bigInteger('store_id');
            $table->boolean('active');
            $table->string('username')->unique();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('store_id')->references('store_id')->on('store');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store', function(Blueprint $table) {
            $table->dropForeign('store_manager_staff_foreign');
        });
        Schema::table('staff', function(Blueprint $table) {
            $table->dropForeign('staff_store_id_foreign');
        });
        Schema::dropIfExists('staff');
    }
}
