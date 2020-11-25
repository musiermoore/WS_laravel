<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('flight_code', 10);

            $table->bigInteger('from_id')->unsigned();
            $table->bigInteger('to_id')->unsigned();

            $table->time('time_from');
            $table->time('time_to');

            $table->integer('cost');

            $table->timestamps();

            $table->foreign('from_id')->references('id')->on('airports');
            $table->foreign('to_id')->references('id')->on('airports');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flights');
    }
}
