<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pharmacy_id')->constrained('pharmacies')->onDelete('cascade');
            $table->string('name', 255);
            $table->string('generic_name', 255)->nullable();
            $table->string('category', 100)->nullable();
            $table->string('manufacturer', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('dosage', 100)->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock_quantity')->default(0);
            $table->enum('stock_status', ['in_stock', 'low_stock', 'out_of_stock'])->default('in_stock');
            $table->boolean('requires_prescription')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('pharmacy_id');
            $table->index('category');
            $table->index('stock_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};
