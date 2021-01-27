<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id')->unique();
            $table->date('default_date')->nullable();
            $table->tinyInteger('driver_auto_selection')->nullable();
            $table->tinyInteger('contractor_auto_selection')->nullable();
            $table->tinyInteger('measurements_auto_selection')->nullable();
            $table->tinyInteger('purchase_auto_selection')->nullable();
            $table->tinyInteger('sale_auto_selection')->nullable();
            $table->double('second_driver_wage_ratio')->nullable();
            $table->tinyInteger('status');
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
        Schema::dropIfExists('company_settings');
    }
}
