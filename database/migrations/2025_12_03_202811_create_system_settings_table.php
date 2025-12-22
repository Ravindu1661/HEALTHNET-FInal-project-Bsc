<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key', 100)->unique();
            $table->text('setting_value')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        // Insert default system settings
        DB::table('system_settings')->insert([
            ['setting_key' => 'site_name', 'setting_value' => 'HEALTHNET', 'description' => 'Website Name'],
            ['setting_key' => 'site_email', 'setting_value' => 'info@healthnet.lk', 'description' => 'Contact Email'],
            ['setting_key' => 'currency', 'setting_value' => 'LKR', 'description' => 'Currency Code'],
            ['setting_key' => 'appointment_advance_payment_percentage', 'setting_value' => '50', 'description' => 'Default advance payment percentage for appointments'],
            ['setting_key' => 'max_file_upload_size', 'setting_value' => '10240', 'description' => 'Maximum file upload size in KB'],
            ['setting_key' => 'enable_chatbot', 'setting_value' => '1', 'description' => 'Enable AI Chatbot'],
            ['setting_key' => 'enable_notifications', 'setting_value' => '1', 'description' => 'Enable Push Notifications'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
