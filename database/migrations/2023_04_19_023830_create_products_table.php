<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            // $table->bigIncrements('id');
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->integer('status')->default(0);
            $table->string('title');
            $table->string('image_src');
            $table->datetime('date_start')->nullable();
            $table->datetime('date_end')->nullable();
            $table->string('vendor')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
