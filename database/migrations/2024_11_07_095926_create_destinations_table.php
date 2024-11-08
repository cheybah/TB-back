<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            // $table->string('image'); // Store image filename or path
            $table->string('image')->nullable(); // Store image as a URL or file path
            $table->string('discount');
            $table->string('name');
            $table->string('location');
            $table->string('price');
            $table->string('original_price');
            $table->integer('rating');
            $table->string('date');
            $table->integer('trip_advisor');
            $table->string('address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
