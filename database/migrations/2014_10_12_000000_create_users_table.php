<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('verified')->nullable()->default('no');
            $table->string('role')->default('rider');

            $table->string('phone_number1')->nullable();
            $table->string('phone_number2')->nullable();
            $table->string('aadhar_number')->nullable();
            $table->string('dl_number')->nullable();
            $table->string('location')->nullable();
            $table->string('aadhar_picture')->nullable();
            $table->string('dl_picture')->nullable();
            

            $table->rememberToken();
            $table->timestamps();
        });
        DB::table('users')->insert(
            array(
                ['name'=>'admin','email'=>'admin@kfc.com','verified'=>'yes','password' => Hash::make('irishhngf'),'role' => 'admin']
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
        Schema::dropIfExists('users');
    }
}
