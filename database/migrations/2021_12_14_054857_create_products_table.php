<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sku')->index();
            $table->string('name',255)->nullable();
            $table->bigInteger('price')->nullable();
            $table->integer('status')->index();
            $table->integer('group_id')->index();
            $table->text('description')->nullable();
            $table->text('options')->nullable();
            $table->integer('type')->index();
            $table->string('note',255)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
