<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_models', function (Blueprint $table) {
            $table->id();
            $table->string('product_id')->nullable();
            $table->string('product_weight')->nullable();
            $table->string('product_quantity')->nullable();
            $table->string('product_name')->nullable();
            $table->string('product_category')->nullable();
            $table->string('product_price')->nullable();
            $table->timestamps();
        });
    }
    // ['product_id','product_weight','product_quantity',
    // 'product_name','product_price']
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_models');
    }
}
