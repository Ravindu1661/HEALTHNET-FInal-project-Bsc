<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->enum('publisher_type', ['hospital', 'laboratory', 'pharmacy', 'medical_centre', 'admin']);
            $table->unsignedBigInteger('publisher_id');
            $table->string('title', 255);
            $table->text('content');
            $table->enum('announcement_type', ['health_camp', 'special_offer', 'new_service', 'emergency', 'general']);
            $table->string('image_path', 255)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['publisher_type', 'publisher_id']);
            $table->index('announcement_type');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
