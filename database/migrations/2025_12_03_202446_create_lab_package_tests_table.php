<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lab_package_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('lab_packages')->onDelete('cascade');
            $table->foreignId('test_id')->constrained('lab_tests')->onDelete('cascade');

            $table->unique(['package_id', 'test_id'], 'unique_package_test');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_package_tests');
    }
};
