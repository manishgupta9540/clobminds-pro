<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDigitalEmploymentCheckMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('digital_employment_check_masters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->unsigned()->index()->nullable();
            $table->bigInteger('business_id')->unsigned()->index()->nullable();
            $table->string('username',200)->nullable();
            $table->string('password',200)->nullable();
            $table->string('client_id',200)->nullable();
            $table->longtext('profile_details')->nullable();
            $table->string('uan_number',255)->nullable();
            $table->longtext('uan_details')->nullable();
            $table->longtext('as26_details')->nullable();
            $table->enum('is_api_verified',['1','0'])->nullable();
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
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
        Schema::dropIfExists('digital_employment_check_masters');
    }
}
