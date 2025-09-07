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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            
            // Product snapshot data (in case product is deleted)
            $table->string('product_name');
            $table->foreignId('size_id')->nullable()->constrained('product_sizes')->onDelete('set null');
            $table->string('size')->nullable(); // Snapshot of size name
            
            // Order details
            $table->integer('quantity');
            $table->integer('price'); // Price at time of order
            $table->integer('subtotal'); // price * quantity
            
            $table->timestamps();
            
            // Indexes
            $table->index(['order_id', 'product_id']);
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};