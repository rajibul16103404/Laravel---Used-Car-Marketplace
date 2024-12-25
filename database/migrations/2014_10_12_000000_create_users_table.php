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
        Schema::create('auths', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('otp')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('phone')->unique();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->integer('zip')->nullable();
            $table->string('country')->nullable();
            $table->string('company_name')->nullable();
            $table->text('company_address')->nullable();
            $table->string('company_email')->nullable();
            $table->integer('company_phone')->nullable();
            $table->string('imageURL')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('auths');
    }
};
