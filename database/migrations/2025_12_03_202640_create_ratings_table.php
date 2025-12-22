<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->enum('ratable_type', ['doctor', 'hospital', 'laboratory', 'pharmacy', 'medical_centre']);
            $table->unsignedBigInteger('ratable_id');
            $table->integer('rating')->unsigned();
            $table->text('review')->nullable();
            $table->enum('related_type', ['appointment', 'lab_order', 'prescription_order'])->nullable();
            $table->unsignedBigInteger('related_id')->nullable();
            $table->timestamps();

            $table->unique(['patient_id', 'ratable_type', 'ratable_id', 'related_type', 'related_id'], 'unique_rating');
            $table->index(['ratable_type', 'ratable_id']);
            $table->index('patient_id');
        });

        DB::statement('ALTER TABLE ratings ADD CONSTRAINT chk_rating CHECK (rating >= 1 AND rating <= 5)');
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
