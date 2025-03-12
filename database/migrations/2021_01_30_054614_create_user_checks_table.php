<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_checks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('business_id')->unsigned()->index();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->string('checks',200)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_checks');
    }
}
