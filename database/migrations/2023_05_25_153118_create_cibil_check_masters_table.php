<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCibilCheckMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cibil_check_masters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->unsigned()->index()->nullable();
            $table->bigInteger('business_id')->unsigned()->index()->nullable();
            $table->string('client_id',255)->nullable();
            $table->string('pan_number',255)->nullable();
            $table->string('mobile_number',255)->nullable();
            $table->string('name',255)->nullable();
            $table->longtext('credit_report_link')->nullable();
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
        Schema::dropIfExists('cibil_check_masters');
    }
}
