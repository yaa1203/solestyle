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
        Schema::create('pesanan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('size_id')->nullable()->constrained('product_sizes')->nullOnDelete();
            
            // Product snapshot data (preserved even if product is deleted)
            $table->string('nama_produk');
            $table->text('deskripsi_produk')->nullable();
            $table->string('sku_produk')->nullable();
            $table->string('size_display')->default('N/A');
            
            // Quantity and pricing
            $table->integer('kuantitas')->default(1);
            $table->decimal('harga_satuan', 10, 2);
            $table->decimal('subtotal', 12, 2);
            
            // Additional information
            $table->string('image_url')->nullable();
            $table->text('catatan_item')->nullable(); // Special notes for this item
            
            // Product specifications snapshot
            $table->json('specifications')->nullable(); // Store product specs as JSON
            $table->decimal('berat_satuan', 8, 2)->nullable(); // weight per unit in kg
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('pesanan_id');
            $table->index('product_id');
            $table->index(['pesanan_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_items');
    }
};