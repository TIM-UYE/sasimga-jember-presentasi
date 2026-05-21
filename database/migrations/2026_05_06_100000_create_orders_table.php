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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('kode_order')->unique();
            $table->string('nama_pelanggan');
            $table->string('nomor_hp');
            $table->text('alamat')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('metode_pengiriman', ['pickup', 'delivery']);
            $table->enum('metode_pembayaran', ['cash', 'qris']);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('total_bayar', 15, 2)->default(0);
            $table->enum('status', [
                'pending',
                'diproses',
                'siap_diambil',
                'diantar',
                'selesai',
                'dibatalkan'
            ])->default('pending');
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->timestamps();

            $table->index('kode_order');
            $table->index('status');
            $table->index('payment_status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
