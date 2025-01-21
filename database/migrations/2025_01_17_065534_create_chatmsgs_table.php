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
        Schema::create('chatmsgs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userId'); // Foreign key to users table
            $table->string('sessionId');
            $table->enum('userType', ['user', 'bot']);
            $table->text('content');
            $table->timestamps();
        
            // Define foreign key constraint
            $table->foreign('userId')
                  ->references('id')
                  ->on('auths')
                  ->onDelete('cascade'); // Deletes chat messages if the user is deleted
        });
        
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chatmsgs');
    }
};
