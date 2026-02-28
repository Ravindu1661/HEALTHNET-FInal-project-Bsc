<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_health_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');

            // ══ Body Measurements ══
            $table->decimal('weight', 5, 2)->nullable();        // kg
            $table->decimal('height', 5, 2)->nullable();        // cm
            $table->decimal('waist', 5, 2)->nullable();         // cm
            $table->decimal('hip', 5, 2)->nullable();           // cm

            // ══ Vitals ══
            $table->integer('blood_pressure_systolic')->nullable();
            $table->integer('blood_pressure_diastolic')->nullable();
            $table->integer('heart_rate')->nullable();           // bpm
            $table->decimal('temperature', 4, 2)->nullable();   // °C
            $table->decimal('blood_sugar', 5, 2)->nullable();   // mg/dL (fasting)
            $table->decimal('blood_sugar_pp', 5, 2)->nullable();// mg/dL (post-meal)
            $table->decimal('cholesterol_total', 5, 2)->nullable(); // mg/dL
            $table->decimal('cholesterol_hdl', 5, 2)->nullable();
            $table->decimal('cholesterol_ldl', 5, 2)->nullable();
            $table->integer('oxygen_saturation')->nullable();   // SpO2 %

            // ══ Lifestyle ══
            $table->enum('smoking_status', ['never','former','current'])->nullable();
            $table->enum('alcohol_consumption', ['none','occasional','moderate','heavy'])->nullable();
            $table->enum('exercise_frequency', ['none','1-2/week','3-4/week','5+/week'])->nullable();
            $table->enum('diet_type', ['omnivore','vegetarian','vegan','other'])->nullable();
            $table->integer('sleep_hours')->nullable();         // hrs/night
            $table->enum('stress_level', ['low','moderate','high','very_high'])->nullable();

            // ══ Medical Background ══
            $table->boolean('has_diabetes')->default(false);
            $table->boolean('has_hypertension')->default(false);
            $table->boolean('has_heart_disease')->default(false);
            $table->boolean('has_asthma')->default(false);
            $table->boolean('has_kidney_disease')->default(false);
            $table->boolean('has_thyroid')->default(false);
            $table->text('other_conditions')->nullable();
            $table->text('current_medications')->nullable();
            $table->text('allergies')->nullable();

            // ══ Family History ══
            $table->boolean('family_diabetes')->default(false);
            $table->boolean('family_heart_disease')->default(false);
            $table->boolean('family_hypertension')->default(false);
            $table->boolean('family_cancer')->default(false);

            // ══ Meta ══
            $table->date('recorded_date')->default(DB::raw('CURDATE()'));
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['patient_id', 'recorded_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_health_data');
    }
};
