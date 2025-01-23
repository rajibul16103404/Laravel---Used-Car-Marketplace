<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkouts', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->default(null);
            $table->enum('order_from', ['whatsapp','app'])->default('whatsapp');
            $table->string('car_id')->default(null);
            $table->string('amount')->default(null);
            $table->integer('user_id')->default(null);
            $table->string('full_name')->default(null);
            $table->string('phone')->default(null);
            $table->string('street')->default(null);
            $table->string('city')->default(null);
            $table->string('state')->default(null);
            $table->string('zip')->default(null);
            $table->string('country_code')->default(null);
            $table->string('port_code')->default(null);
            $table->string('shipping_fee')->default(0);
            $table->string('platform_fee')->default(0);
            $table->string('tax')->default(0);
            $table->string('order_status')->default('pending')->default(null);
            $table->string('payment_status')->default('pending')->default(null);
            $table->string('delivery_status')->default('pending')->default(null);
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
        Schema::dropIfExists('checkouts');
    }
};
