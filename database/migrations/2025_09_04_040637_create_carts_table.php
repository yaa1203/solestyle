<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->nullable(); // Untuk guest user
            $table->unsignedBigInteger('user_id')->nullable(); // Untuk logged user
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->integer('size')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Kombinasi unik untuk mencegah duplicate
            $table->unique(['session_id', 'product_id', 'size']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
};