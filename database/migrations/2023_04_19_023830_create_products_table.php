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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->unique();
            $table->unsignedBigInteger('user_id');
            $table->integer('status')->default(0);
            $table->string('title');
            $table->string('image_src');
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->string('vendor')->nullable();
            $table->integer('stock')->default(0);
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
