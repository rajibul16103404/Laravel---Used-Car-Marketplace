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
        Schema::create('v_i_n_s', function (Blueprint $table) {
            $table->id("vin_id");
            $table->string("id");
            $table->string("price");
            $table->string("miles");
            $table->string("data_source");
            $table->string("vdp_url");
            $table->string("seller_type");
            $table->string("inventory_type");
            $table->string("last_seen_at");
            $table->string("last_seen_at_date");
            $table->string("scraped_at");
            $table->string("scraped_at_date");
            $table->string("first_seen_at");
            $table->string("first_seen_at_date");
            $table->string("source");
            $table->string("seller_name");
            $table->string("city");
            $table->string("state");
            $table->string("zip");
            $table->string("status_date");
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
        Schema::dropIfExists('v_i_n_s');
    }
};
