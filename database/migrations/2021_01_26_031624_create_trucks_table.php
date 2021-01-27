<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrucksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trucks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reg_number', 13);
            $table->string('description', 100)->nullable();
            $table->unsignedTinyInteger('truck_type_id');
            $table->unsignedInteger('volume');
            $table->unsignedTinyInteger('body_type');
            $table->unsignedTinyInteger('ownership_status');
            $table->date('insurance_upto')->nullable();
            $table->date('tax_upto')->nullable();
            $table->date('fitness_upto')->nullable();
            $table->date('permit_upto')->nullable();
            $table->date('pollution_upto')->nullable();
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
        Schema::dropIfExists('trucks');
    }
}
