<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidateAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_accesses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('business_id')->unsigned()->index();
            $table->bigInteger('candidate_id')->unsigned()->index();
            $table->bigInteger('access_id')->unsigned()->index();
            $table->string('user_type')->nullable();
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
        Schema::dropIfExists('candidate_accesses');
    }
}
