<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigIncrements('flight_from')->unsigned();
            $table->bigIncrements('flight_back')->unsigned()->nullable();

            $table->date('date_from');
            $table->date('date_back')->nullable();

            $table->string('code', 5);

            $table->timestamps();

            $table->foreign('flight_from')->references('id')->on('flights');
            $table->foreign('flight_back')->references('id')->on('flights');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
