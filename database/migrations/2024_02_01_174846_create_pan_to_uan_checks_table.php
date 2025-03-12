<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePanToUanChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pan_to_uan_checks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->unsigned()->index()->nullable();
            $table->bigInteger('business_id')->unsigned()->index()->nullable();
            $table->bigInteger('service_id')->unsigned()->index()->nullable();
            $table->enum('source_type',['API','Manual','SystemDB'])->nullable();
            $table->string('pan_number',255)->nullable();
            $table->string('uan_number',255)->nullable();
            $table->enum('is_verified',['1','0'])->nullable();
            $table->string('price',255)->nullable();
            $table->string('user_type',255)->nullable();
            $table->bigInteger('user_id')->nullable();
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
        Schema::dropIfExists('pan_to_uan_checks');
    }
}
