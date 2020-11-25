<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePassangersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passangers', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigIncrements('booking_id')->unsigned();

            $table->string('first_name');
            $table->string('last_name');

            $table->date('birth_date');

            $table->string('document_number', 10);

            $table->string('place_from', 3)->nullable();
            $table->string('place_back', 3)->nullable();

            $table->timestamps();

            $table->foreign('booking_id')->references('id')->on('bookings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('passangers');
    }
}
