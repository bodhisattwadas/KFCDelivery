<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiderDeliveryStatusModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    //'email','order_id','order_status','latitude','longitude'
    public function up()
    {
        Schema::create('rider_delivery_status_models', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('order_id');
            $table->enum('order_status',
                array('allocated','arrived','dispatched','arrived_customer_doorstep','delivered','cancelled','cancelled_by_customer','returned_to_seller'));
            $table->string('latitude');
            $table->string('longitude');
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
        Schema::dropIfExists('rider_delivery_status_models');
    }
}
