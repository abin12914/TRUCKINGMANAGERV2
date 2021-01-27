<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transportations', function (Blueprint $table) {
            $table->increments('id');
            $table->date('transportation_date')->nullable();
            $table->unsignedInteger('transaction_id');
            $table->unsignedInteger('truck_id');
            $table->unsignedInteger('source_id');
            $table->unsignedInteger('destination_id');
            $table->unsignedInteger('material_id');
            $table->unsignedTinyInteger('rent_type');
            $table->double('measurement')->unsigned();
            $table->double('rent_rate')->unsigned();
            $table->double('trip_rent')->unsigned();
            $table->unsignedInteger('no_of_trip');
            $table->double('total_rent')->unsigned();
            $table->unsignedTinyInteger('status');
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
        Schema::dropIfExists('transportations');
    }
}
