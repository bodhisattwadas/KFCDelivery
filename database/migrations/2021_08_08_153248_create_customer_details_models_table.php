<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerDetailsModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_details_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_number');
            $table->string('address_line_1');
            $table->string('address_line_2');
            $table->string('city');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('pin');
            $table->timestamps();
        });
    }
    // 'name','contact_number','address_line_1','address_line_2','city',
    // 'latitude','longitude','pin'
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_details_models');
    }
}
