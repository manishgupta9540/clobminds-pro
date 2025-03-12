<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportApprovalStatusLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_approval_status_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->unsigned()->index()->nullable();
            $table->bigInteger('business_id')->unsigned()->index()->nullable();
            $table->bigInteger('candidate_id')->unsigned()->index()->nullable();
            $table->tinyInteger('status')->default('1')->comment('1 => send, 2 => cancel, 3 => approve');
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('report_approval_status_logs');
    }
}
