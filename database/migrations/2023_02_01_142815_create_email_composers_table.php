<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailComposersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_composers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('candidate_id')->unsigned()->index()->nullable();
            $table->string('email',200)->nullable();
            $table->string('email_subject',255)->nullable();
            $table->text('email_body')->nullable();
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
        Schema::dropIfExists('email_composers');
    }
}
