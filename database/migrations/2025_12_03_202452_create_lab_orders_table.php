<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lab_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->string('reference_number', 50)->unique();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('laboratory_id')->constrained('laboratories')->onDelete('cascade');
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->onDelete('set null');
            $table->string('prescription_file', 255)->nullable();
            $table->timestamp('order_date')->useCurrent();
            $table->enum('status', ['pending', 'sample_collected', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->enum('payment_method', ['cash', 'online', 'card'])->nullable();
            $table->boolean('home_collection')->default(false);
            $table->text('collection_address')->nullable();
            $table->date('collection_date')->nullable();
            $table->time('collection_time')->nullable();
            $table->string('report_file', 255)->nullable();
            $table->timestamp('report_uploaded_at')->nullable();
            $table->timestamps();

            $table->index('patient_id');
            $table->index('laboratory_id');
            $table->index('reference_number');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_orders');
    }
};
