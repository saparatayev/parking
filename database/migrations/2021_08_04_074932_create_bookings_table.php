<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('car_model');
            
            // foreign key
            $table->unsignedBigInteger('parking_place_id');
            $table->foreign('parking_place_id')->references('id')->on('parking_places');

            $table->date('date_in');
            $table->date('date_out');

            // foreign key
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
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
