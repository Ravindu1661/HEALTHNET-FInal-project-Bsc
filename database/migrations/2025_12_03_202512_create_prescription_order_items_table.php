<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescription_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('prescription_orders')->onDelete('cascade');
            $table->foreignId('medication_id')->nullable()->constrained('medications')->onDelete('set null');
            $table->string('medication_name', 255);
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamp('created_at')->useCurrent();

            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescription_order_items');
    }
};
