<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPropertiesToTableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('facebook_id')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('address',250)->nullable();
            // $table->integer('district', 10)->nullable();
            // $table->integer('city', 10)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->string('avatar')->nullable();
            $table->tinyInteger('config')->nullable();
            $table->string('code',50)->nullable();
            $table->string('birth')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['facebook_id','phone','address','district','city','status','avatar','config','code','birth']);
        });
    }
}
