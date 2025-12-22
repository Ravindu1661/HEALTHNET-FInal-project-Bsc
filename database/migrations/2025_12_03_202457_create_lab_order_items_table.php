<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lab_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('lab_orders')->onDelete('cascade');
            $table->foreignId('test_id')->nullable()->constrained('lab_tests')->onDelete('set null');
            $table->foreignId('package_id')->nullable()->constrained('lab_packages')->onDelete('set null');
            $table->string('item_name', 255);
            $table->decimal('price', 10, 2);
            $table->timestamp('created_at')->useCurrent();

            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_order_items');
    }
};
