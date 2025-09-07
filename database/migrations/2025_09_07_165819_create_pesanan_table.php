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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pesanan')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            
            // Customer Information
            $table->string('nama_pelanggan');
            $table->string('email_pelanggan');
            $table->string('nomor_telepon');
            
            // Shipping Address
            $table->text('alamat_lengkap');
            $table->string('kota');
            $table->string('provinsi');
            $table->string('kode_pos', 10);
            
            // Order Information
            $table->enum('status', [
                'pending_payment',
                'payment_verification', 
                'paid',
                'processing',
                'shipped',
                'delivered',
                'cancelled'
            ])->default('pending_payment');
            
            $table->enum('metode_pembayaran', [
                'bank_transfer',
                'credit_card',
                'e_wallet',
                'cod'
            ]);
            
            // Pricing
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('biaya_pengiriman', 10, 2)->default(0);
            $table->decimal('total_harga', 12, 2)->default(0);
            
            // Timestamps
            $table->timestamp('tanggal_pesanan')->useCurrent();
            $table->timestamp('tanggal_pembayaran')->nullable();
            $table->timestamp('tanggal_pengiriman')->nullable();
            $table->timestamp('tanggal_diterima')->nullable();
            
            // Shipping & Payment Information
            $table->string('nomor_resi')->nullable();
            $table->string('bukti_pembayaran')->nullable(); // file path
            $table->text('catatan_pembayaran')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->text('catatan_pelanggan')->nullable();
            
            // Additional fields
            $table->string('kurir')->nullable(); // shipping courier
            $table->string('layanan_kurir')->nullable(); // courier service type
            $table->decimal('berat_total', 8, 2)->nullable(); // total weight in kg
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('status');
            $table->index('tanggal_pesanan');
            $table->index('user_id');
            $table->index(['status', 'tanggal_pesanan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};