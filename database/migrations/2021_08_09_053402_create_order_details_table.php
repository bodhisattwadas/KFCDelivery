<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // 'scheduled_time','order_value','paid','client_order_id',
    //     'drop_instruction_text','take_drop_off_picture','drop_off_picture_mandatory'
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->datetime('scheduled_time');
            $table->string('order_value')->nullable();
            $table->integer('paid')->default(0);
            $table->string('client_order_id');
            $table->string('drop_instruction_text')->nullable();
            $table->integer('take_drop_off_picture')->default(0);
            $table->integer('drop_off_picture_mandatory')->default(0);
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
        Schema::dropIfExists('order_details');
    }
}
