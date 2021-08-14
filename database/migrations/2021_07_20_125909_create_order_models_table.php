<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_models', function (Blueprint $table) {
            $table->id();
            //$table->string('order_id');
            
            $table->string('picup_contact_number');
            $table->string('store_code');
            //$table->integer('order_details');
            //OrderDetails
            
            $table->string('scheduled_time');
            $table->string('order_value')->nullable();
            $table->enum('paid',['true','false']);
            $table->string('client_order_id');
            $table->string('drop_instruction_text')->nullable();
           // $table->integer('take_drop_off_picture')->nullable()->default(0);
           // $table->integer('drop_off_picture_mandatory')->nullable()->default(0);
            
            //CustomerDetails
            //$table->integer('customer_details');
            $table->string('name');
            $table->string('contact_number');
            $table->string('address_line_1');
            $table->string('address_line_2');
            $table->string('city');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('pin');
            

            //MiscDetails
            //$table->integer('misc');
            $table->enum('type',['slotted','express']);
            $table->integer('pickup_otp')->nullable();
            $table->string('rider_code');

            //$table->integer('product_details')->nullable();
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
        Schema::dropIfExists('order_models');
    }
}
