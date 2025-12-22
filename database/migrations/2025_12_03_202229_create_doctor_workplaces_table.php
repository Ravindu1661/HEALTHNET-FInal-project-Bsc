<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_workplaces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->enum('workplace_type', ['hospital', 'medical_centre']);
            $table->unsignedBigInteger('workplace_id');
            $table->enum('employment_type', ['permanent', 'temporary', 'visiting']);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->unique(['doctor_id', 'workplace_type', 'workplace_id'], 'unique_workplace');
            $table->index(['workplace_type', 'workplace_id']);
            $table->index('doctor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_workplaces');
    }
};
