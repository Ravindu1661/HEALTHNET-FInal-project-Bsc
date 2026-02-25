<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up(): void
{
    // ✅ Table already exists නම් skip
    if (Schema::hasTable('payments')) {
        return;
    }

    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('appointment_id');
        $table->unsignedBigInteger('patient_id');
        $table->decimal('amount', 10, 2);
        $table->enum('payment_status', ['pending', 'completed', 'failed', 'refunded'])
              ->default('pending');
        $table->enum('payment_method', ['cash', 'card', 'online', 'insurance'])
              ->default('card');
        $table->string('transaction_id')->nullable();
        $table->string('cardholder_name')->nullable();
        $table->string('currency', 10)->default('LKR');
        $table->text('notes')->nullable();
        $table->timestamps();

        $table->foreign('appointment_id')
              ->references('id')->on('appointments')
              ->onDelete('cascade');
        $table->foreign('patient_id')
              ->references('id')->on('patients')
              ->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
