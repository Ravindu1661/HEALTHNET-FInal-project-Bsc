<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescription_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('pharmacy_id')->constrained('pharmacies')->onDelete('cascade');
            $table->string('prescription_file', 255);
            $table->timestamp('order_date')->useCurrent();
            $table->enum('status', ['pending', 'verified', 'processing', 'ready', 'dispatched', 'delivered', 'cancelled'])->default('pending');
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->decimal('delivery_fee', 10, 2)->default(0.00);
            $table->enum('payment_method', ['cash_on_delivery', 'online'])->default('cash_on_delivery');
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->text('delivery_address');
            $table->enum('delivery_method', ['uber', 'pickme', 'own_delivery'])->nullable();
            $table->string('tracking_number', 100)->nullable();
            $table->text('pharmacist_notes')->nullable();
            $table->text('cancelled_reason')->nullable();
            $table->timestamps();

            $table->index('patient_id');
            $table->index('pharmacy_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescription_orders');
    }
};
