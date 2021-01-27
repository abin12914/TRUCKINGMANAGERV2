<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeWagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_wages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('transaction_id')->unique('transaction_id');
            $table->unsignedInteger('employee_id');
            $table->unsignedTinyInteger('wage_type');
            $table->date('from_date')->nullable();
            $table->date('to_date');
            $table->unsignedInteger('transportation_id')->nullable();
            $table->double('wage_amount')->unsigned();
            $table->unsignedInteger('no_of_trip');
            $table->double('total_wage_amount')->unsigned();
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
        Schema::dropIfExists('employee_wages');
    }
}
