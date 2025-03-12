<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_prices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->default(2)->nullable();
            $table->bigInteger('business_id')->nullable();
            // $table->bigInteger('candidate_id')->nullable();
            $table->bigInteger('service_id')->nullable();
            // $table->enum('source_type',['SystemDB','API'])->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->decimal('price',10,2)->nullable();
            $table->enum('used_by',['user','customer','superadmin'])->default('customer')->nullable();
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
        Schema::dropIfExists('check_prices');
    }
}
