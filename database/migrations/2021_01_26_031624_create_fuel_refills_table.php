<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuelRefillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuel_refills', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('truck_id');
            $table->integer('expense_id')->unique('expense_id');
            $table->dateTime('refill_date');
            $table->double('odometer_reading')->unsigned();
            $table->float('fuel_quantity', 10, 0);
            $table->float('total_fuel_price', 10, 0);
            $table->integer('status');
            $table->integer('company_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fuel_refills');
    }
}
