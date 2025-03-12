<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsuffNotificationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insuff_notification_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('business_id')->unsigned()->index()->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->text('file_name')->nullable();
            $table->enum('record_status',['found','not_found','disable'])->nullable();
            $table->tinyInteger('days')->nullable();
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
        Schema::dropIfExists('insuff_notification_logs');
    }
}
