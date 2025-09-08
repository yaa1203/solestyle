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
        Schema::table('orders', function (Blueprint $table) {
            // Tambahkan kolom yang mungkin hilang
            $table->text('admin_notes')->nullable()->after('tracking_number');
            $table->string('payment_proof')->nullable()->after('admin_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_date',
                'processing_date',
                'shipped_date',
                'delivered_date',
                'tracking_number',
                'admin_notes',
                'payment_proof'
            ]);
        });
    }
};