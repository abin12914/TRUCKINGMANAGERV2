<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_name', 100);
            $table->string('description')->nullable();
            $table->unsignedTinyInteger('type');
            $table->tinyInteger('relation');
            $table->tinyInteger('financial_status');
            $table->double('opening_balance');
            $table->string('name', 100);
            $table->string('phone', 13);
            $table->string('address')->nullable();
            $table->tinyInteger('status');
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
        Schema::dropIfExists('accounts');
    }
}
