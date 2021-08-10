<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_models', function (Blueprint $table) {
            $table->id();
            $table->string('store_code');
            $table->string('store_name');
            $table->string('store_location')->nullable();
            $table->timestamps();
        });
        DB::table('store_models')->insert(
            array(
                ['store_code'=>'k101','store_name'=>'test location']
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_models');
    }
}
