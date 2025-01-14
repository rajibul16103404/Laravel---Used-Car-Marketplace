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
        Schema::create('privet_cars', function (Blueprint $table) {
            $table->id();
            $table->string('vin');
            $table->string('heading');
            $table->integer('price');
            $table->integer('miles');
            $table->integer('msrp');
            $table->string('data_source');
            $table->string('vdp_url');
            $table->boolean('carfax_1_owner');
            $table->boolean('carfax_clean_title');
            $table->string('exterior_color');
            $table->string('interior_color');
            $table->string('base_int_color');
            $table->string('base_ext_color');
            $table->integer('dom');
            $table->integer('dom_180');
            $table->integer('dom_active');
            $table->integer('dos_active');
            $table->string('seller_type');
            $table->string('inventory_type');
            $table->string('stock_no');
            $table->timestamp('last_seen_at');
            $table->timestamp('scraped_at');
            $table->timestamp('first_seen_at');
            $table->decimal('price_change_percent', 5, 2);
            $table->integer('ref_price');
            $table->integer('ref_miles');
            $table->string('source');
            $table->boolean('in_transit');
            $table->json('media');
            $table->json('dealer');
            $table->json('build');
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
        Schema::dropIfExists('privet_cars');
    }
};
