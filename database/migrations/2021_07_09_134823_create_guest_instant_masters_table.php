<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestInstantMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_instant_masters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->unsigned()->index()->nullable();
            $table->bigInteger('business_id')->unsigned()->index()->nullable();
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->string('name',200)->nullable();
            $table->string('email',200)->nullable();
            $table->string('contactNumber',200)->nullable();
            $table->string('transaction_id',50)->nullable();
            $table->string('razorpay_id',50)->nullable();
            $table->string('payment_id',50)->nullable();
            $table->bigInteger('promo_code_id')->nullable();
            $table->string('promo_code_title',100)->nullable();
            $table->string('currency',50)->default('INR')->nullable();
            $table->tinyInteger('is_payment_done')->default(0)->nullable();
            $table->enum('status',['success','failed'])->nullable();
            $table->decimal('sub_total',10,2)->nullable();
            $table->decimal('total_price',10,2)->nullable();
            $table->text('zip_name')->nullable();
            // $table->tinyInteger('is_deleted')->default(0)->nullable();
            // $table->bigInteger('deleted_by')->unsigned()->index()->nullable();
            // $table->dateTime('deleted_at')->nullable();
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
        Schema::dropIfExists('guest_instant_masters');
    }
}
