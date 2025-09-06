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
        Schema::table('carts', function (Blueprint $table) {
            // Tambah kolom size_id untuk relasi ke product_sizes
            $table->unsignedBigInteger('size_id')->nullable()->after('product_id');
            
            // Index untuk performa
            $table->index(['user_id', 'product_id', 'size_id'], 'cart_user_product_size_idx');
            $table->index(['session_id', 'product_id', 'size_id'], 'cart_session_product_size_idx');
            
            // Foreign key constraint
            $table->foreign('size_id')->references('id')->on('product_sizes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['size_id']);
            $table->dropIndex('cart_user_product_size_idx');
            $table->dropIndex('cart_session_product_size_idx');
            $table->dropColumn('size_id');
        });
    }
};