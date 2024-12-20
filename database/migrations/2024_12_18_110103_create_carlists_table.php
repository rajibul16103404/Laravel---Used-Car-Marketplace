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
            $table->id();
            $table->integer('seller_id');
            $table->string('title');
            $table->integer('make_id');
            $table->integer('model_id');
            $table->integer('body_type_id');
            $table->integer('drive_type_id');
            $table->integer('transmission_id');
            $table->integer('condition_id');
            $table->integer('year');
            $table->integer('fuel_type_id');
            $table->integer('engine');
            $table->integer('door_id');
            $table->integer('cylinder_id');
            $table->integer('color_id');
            $table->text('description');
            $table->float('price');
            $table->string('safety_features');
            $table->string('key_features');
            $table->integer('category_id');
            $table->string('imageURL');
            $table->string('videoURL');
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
