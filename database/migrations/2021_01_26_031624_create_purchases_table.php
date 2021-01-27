<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->date('purchase_date')->nullable();
            $table->unsignedInteger('transaction_id');
            $table->unsignedInteger('transportation_id');
            $table->unsignedTinyInteger('measure_type');
            $table->double('quantity');
            $table->double('rate');
            $table->double('discount')->nullable();
            $table->double('purchase_trip_bill');
            $table->unsignedInteger('no_of_trip');
            $table->double('total_amount')->unsigned();
            $table->tinyInteger('status');
            $table->unsignedInteger('company_id');
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
        Schema::dropIfExists('purchases');
    }
}
