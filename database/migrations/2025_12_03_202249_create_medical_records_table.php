<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->enum('record_type', ['clinic_visit', 'xray', 'scan', 'prescription', 'lab_report', 'other']);
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->date('record_date')->nullable();
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->onDelete('set null');
            $table->foreignId('hospital_id')->nullable()->constrained('hospitals')->onDelete('set null');
            $table->string('file_path', 255)->nullable();
            $table->string('file_type', 50)->nullable();
            $table->timestamps();

            $table->index('patient_id');
            $table->index('record_type');
            $table->index('record_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
