<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number', 50)->unique();
            $table->foreignId('payer_id')->constrained('users')->onDelete('cascade');
            $table->enum('payee_type', ['hospital', 'doctor', 'laboratory', 'pharmacy', 'medical_centre']);
            $table->unsignedBigInteger('payee_id');
            $table->enum('related_type', ['appointment', 'lab_order', 'prescription_order']);
            $table->unsignedBigInteger('related_id');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'card', 'online', 'bank_transfer']);
            $table->enum('payment_status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->string('transaction_id', 255)->nullable();
            $table->timestamp('payment_date')->useCurrent();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('payer_id');
            $table->index(['payee_type', 'payee_id']);
            $table->index(['related_type', 'related_id']);
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
