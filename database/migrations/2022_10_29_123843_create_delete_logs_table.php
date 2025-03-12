<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeleteLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delete_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('business_id')->unsigned()->index();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->tinyInteger('is_temp')->unsigned()->default('1');
            $table->enum('user_type',['user','customer','client','candidate','guest'])->nullable();
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
        Schema::dropIfExists('delete_logs');
    }
}
