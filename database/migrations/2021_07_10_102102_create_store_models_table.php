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
                ['store_code'=>'312','store_name'=>'KFC - City Centre -II'],
                ['store_code'=>'304','store_name'=>'KFC - Park Street'],
                ['store_code'=>'325','store_name'=>'KFC - RB Connector Kolkatta'],
                ['store_code'=>'332','store_name'=>'KFC - DNC Mall Kolkata'],
                ['store_code'=>'345','store_name'=>'KFC- Shree Tower VIP Road'],
                ['store_code'=>'377','store_name'=>'KFC - Nicco Park, Kolkata'],
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
