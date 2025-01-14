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
        Schema::create('carlists', function (Blueprint $table) {
            $table->id('id');
            $table->string('car_id');
            $table->string('vin');
            $table->string('heading');
            $table->string('price');
            $table->string('miles');
            $table->string('msrp');
            $table->string('data_source');
            $table->string('vdp_url');
            $table->string('carfax_1_owner');
            $table->string('carfax_clean_title');
            $table->string('exterior_color');
            $table->string('interior_color');
            $table->string('base_int_color');
            $table->string('base_ext_color');
            $table->string('dom');
            $table->string('dom_180');
            $table->string('dom_active');
            $table->string('dos_active');
            $table->string('seller_type');
            $table->string('inventory_type');
            $table->string('stock_no');
            $table->string('last_seen_at');
            $table->string('last_seen_at_date');
            $table->string('scraped_at');
            $table->string('scraped_at_date');
            $table->string('first_seen_at');
            $table->string('first_seen_at_date');
            $table->string('first_seen_at_source');
            $table->string('first_seen_at_source_date');
            $table->string('first_seen_at_mc');
            $table->string('first_seen_at_mc_date');
            $table->string('ref_price');
            $table->string('price_change_percent');
            $table->string('ref_price_dt');
            $table->string('ref_miles');
            $table->string('ref_miles_dt');
            $table->string('source');
            $table->string('model_code');
            $table->string('in_transit');
            $table->string('photo_links');
            $table->string('dealer_id');
            $table->string('year');
            $table->string('make');
            $table->string('model');
            $table->string('trim');
            $table->string('version');
            $table->string('body_type');
            $table->string('body_subtype');
            $table->string('vehicle_type');
            $table->string('transmission');
            $table->string('drivetrain');
            $table->string('fuel_type');
            $table->string('engine');
            $table->string('engine_size');
            $table->string('engine_block');
            $table->string('doors');
            $table->string('cylinders');
            $table->string('made_in');
            $table->string('overall_height');
            $table->string('overall_length');
            $table->string('overall_width');
            $table->string('std_seating');
            $table->string('highway_mpg');
            $table->string('city_mpg');
            $table->string('powertrain_type');
            $table->string('status')->default(null);
            $table->integer('featured')->default(0);
            $table->integer('spotlight')->default(0);
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
        Schema::dropIfExists('carlists');
    }
};
