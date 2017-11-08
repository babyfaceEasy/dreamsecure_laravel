<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('last_name', 100);
            $table->string('other_names');
            $table->char('gender');
            $table->string('email');
            $table->string('phone');
            $table->string('password');
            //these section recieves the the phone numbers
            $table->string('ice_1');
            $table->string('ice_2');
            $table->string('ice_3');

            //this section recieves the emails
            $table->string('rec_email_1');
            $table->string('rec_email_2');
            $table->string('rec_email_3');

            $table->string('code')->unique()->nullable();
            $table->boolean('activated')->default(false);
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
        Schema::dropIfExists('clients');
    }
}
