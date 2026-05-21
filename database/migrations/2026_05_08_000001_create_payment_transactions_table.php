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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('transaction_id')->unique()->comment('Midtrans transaction ID');
            $table->string('order_id_midtrans')->nullable()->comment('Midtrans order ID');
            $table->decimal('gross_amount', 15, 2)->default(0);
            $table->string('payment_type')->nullable();
            $table->string('transaction_status')->default('pending')->comment('pending, settlement, capture, expire, cancel, deny, challenge');
            $table->string('fraud_status')->nullable();
            $table->string('qr_string')->nullable();
            $table->text('qr_image_url')->nullable()->comment('URL QRIS image from Midtrans');
            $table->text('actions')->nullable()->comment('JSON of payment actions');
            $table->string('va_number')->nullable();
            $table->string('bank')->nullable();
            $table->timestamp('expiry_time')->nullable();
            $table->timestamp('settlement_time')->nullable();
            $table->text('raw_response')->nullable()->comment('Full Midtrans response JSON');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->index('transaction_id');
            $table->index('transaction_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
